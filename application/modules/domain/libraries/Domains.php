<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Domains {

    private $data;
    private $update;
    private $server_ip;
    private $customer_id;

    function __construct($data)
    {
        $this->data = $data;
        $this->_CI = & get_instance();
        $this->_CI->load->model('DomainModel');
        //$this->_CI->lang->load("module");
        $this->customer_id = $this->_CI->session->userdata('customer_id');
    }

    /**
     * listDomains
     * List Domains assigned by user
     *
     * @access  public
     */
    public function listDomains()
	{
		if(hasAccess(array('manage_domain')))
		{
			$this->data['site'] = 'domains';
			$this->data['title'] = lang('Manage domains');
			$this->data['jsFiles'] = array('domain.js');

			renderPage(role().'/domain', $this->data, true);
		} else {
            NoAccess();
        }

	}

    /**
     * addDomain
     * open form for new domain
     *
     * @access  public
     */
    public function addDomain()
	{
		if(hasAccess(array('manage_domain')))
		{
			$this->data['site'] = 'domains';
			$this->data['title'] = lang('Add new domain');
			$this->data['jsFiles'] = array('domainForm.js');

			renderPage(role().'/domain_form', $this->data, true);
        } else {
            NoAccess();
        }

	}
    /**
	 * editDomain
	 * open form for new domain
	 *
	 * @param   int  	$id   	Domain ID
     * @param   string  $domain Name of the domain
	 * @access  public
	 */
    public function editDomain($id, $domain)
	{
		if(hasAccess(array('manage_domain')))
		{
			if($this->_CI->DomainModel->checkDomainOwner($id, $domain) > 0) {

				// Load Domaindata
				$this->data['domain'] = $this->_CI->DomainModel->getDomain($id, $domain);
				$this->data['jsFiles'] = array('domainForm.js');
				$this->data['site'] = 'domains';
				$this->data['title'] = lang('Edit domain').$this->data['domain']->domain;

				$aliasDomains = $this->_CI->DomainModel->getAliasDomains($this->data['domain']->id);
				$aliases = "";
				foreach ($aliasDomains as $key => $value) {
					$aliases .= $value->alias."\n";
				}
				$this->data['domain']->alias = preg_replace("/\n$/", "", $aliases);
                $this->data['cache'] =  $this->_CI->DomainModel->getCacheSettings($this->data['domain']->id);
                $this->data['excludeCacheFiles'] = explode("|", $this->data['cache']->excludeCacheFiles);
                $this->data['ps'] =  $this->_CI->DomainModel->getPageSpeedSettings($this->data['domain']->id);
                $this->data['PsExcludeDir'] = explode("|", $this->data['ps']->excludeDir);


                $excludeCacheDirs = '';
                if(isset($this->data['cache']->excludeDirs)) {
                    foreach (explode("|", $this->data['cache']->excludeDirs) as $key => $value) {
                        if($value != "") {
                            $vid = preg_replace("/[^a-z0-9\s]/i", "", preg_replace("/[_\s]/", "", $value));
                            $excludeCacheDirs .= '<li id="ex_'.$vid.'">'.$value.' <i class="glyphicon glyphicon-minus" onclick="removeExcludeDir(\'ex_'.$vid.'\')" style="cursor: pointer; top: 2px; color: red"></i></li>';
                        }
                    }
                }
                $this->data['excludeCacheDirs'] = $excludeCacheDirs;

                $excludePsDirs = '';
                if(isset($this->data['ps']->excludeDir)) {
                    foreach (explode("|", $this->data['ps']->excludeDir) as $key => $value) {
                        if($value != "") {
                            $vid = preg_replace("/[^a-z0-9\s]/i", "", preg_replace("/[_\s]/", "", $value));
                            $excludePsDirs .= '<li id="px_'.$vid.'">'.$value.' <i class="glyphicon glyphicon-minus" onclick="removeExcludeDir(\'px_'.$vid.'\')" style="cursor: pointer; top: 2px; color: red"></i></li>';
                        }
                    }
                }
                $this->data['excludePsDirs'] = $excludePsDirs;


				renderPage(role().'/domain_form', $this->data, true);
			} else {
				NoAccess();
			}
        } else {
            NoAccess();
        }
	}

    public function getDomains()
	{
		if(hasAccess(array('manage_domain'))) {

			$domains['total'] = $this->_CI->DomainModel->getDomains(TRUE);
			$domains['rows'] = $this->_CI->DomainModel->getDomains();

			return sendOutput($domains);
        } else {
            NoAccess();
        }
    }

    public function suspendDomain()
	{
		if(hasAccess(array('manage_domain')))
		{
			$domain = $this->_CI->input->post('domain');
			$domain_id = $this->_CI->input->post('domain_id');
			$status = $this->_CI->input->post('status');

			if($this->_CI->DomainModel->checkDomainOwner($domain_id, $domain) > 0) {
				$this->_CI->DomainModel->suspendDomain($domain_id, $status);

				if($status == 0) {
					addTask('suspendDomain', $domain_id);
				}else{
					addTask('activateDomain', $domain_id);
				}
				return sendOutput(array('status' => 200));
			} else {
				return sendOutput(array('status' => 403));
			}
        } else {
            NoAccess();
        }
	}

    public function deleteDomain()
	{
        $this->_CI->load->model('DnsModel');

		if(hasAccess(array('manage_domain')))
		{
			$domain = $this->_CI->input->post('domain');
			$domain_id = $this->_CI->input->post('domain_id');
			$dns_id = $this->_CI->DnsModel->getDnsDomainId($domain_id);

			if($this->_CI->DomainModel->checkDomainOwner($domain_id, $domain) > 0) {

				// Delete DNS records
				$this->_CI->DnsModel->DeleteDNSDomain($domain_id);
				$this->_CI->DnsModel->deleteDNSRecords($dns_id);

				// Delete Aliase
				$this->_CI->DomainModel->removeAliase($domain_id);

				// Delete domain from piwik
				if(moduleActive('piwik', false)) {
					$this->_CI->load->library('piwik');
					$this->_CI->piwik->delete_site($domain);
				}

				// Delete email records if module used
				if(moduleActive('mail', false)) {
					$this->_CI->DomainModel->deleteMailAlias($domain);
					$this->_CI->DomainModel->deleteMailCatchAll($domain);
					$this->_CI->DomainModel->deleteMailUser($domain);
				}

				$this->_CI->DomainModel->DeleteDomain($domain_id);
				addTask('deleteDomain', $domain_id);

				return sendOutput(array('status' => 200));
			} else {
				return sendOutput(array('status' => 403));
			}
        } else {
            NoAccess();
        }
	}

    /**
	 * saveDomain
	 * create or update domain
	 *
	 * @param   int  	$_POST['domain_id']			optional, only if update
     * @param   string  $_POST['domain']			domainname
	 * @param   string  $_POST['active']			domain status, 0 or 1
	 * @param   string  $_POST['cache']				cache setting, 0 or 1
	 * @param   string  $_POST['cgi']				set cgi, 0 or 1
	 * @param   string  $_POST['ssi']				set ssi, 0 or 1
	 * @param   string  $_POST['ruby']				set ruby, 0 or 1
	 * @param   string  $_POST['python']			set pyhthon, 0 or 1
	 * @param   string  $_POST['domain_redirect']	enable redirect type
	 * @param   string  $_POST['alias']				list of aliases
	 * @param   string  $_POST['destination']		destination if domain_redirect enable
	 * @param   string  $_POST['path']				path of the domain
	 * @param   string  $_POST['seo_redirect']		enable seo redirection
	 * @param   string  $_POST['php']				set php version
	 * @return 	string								Json status
	 * @access  public
	 */
	public function saveDomain()
	{
        $this->_CI->load->model('DnsModel');

		if(hasAccess(array('manage_domain'), true))
		{
			$validate = $this->validate();
			if($validate != 1){
				return sendOutput($validate);
			}
			$domain = $this->_CI->input->post('domain');
			$active = ($this->_CI->input->post('active') == 1) ? 1 : 0;
			$cache = ($this->_CI->input->post('cache') == 1) ? 1 : 0;
			$cgi = ($this->_CI->input->post('cgi') == 1) ? 1 : 0;
			$ssi = ($this->_CI->input->post('ssi') == 1) ? 1 : 0;
			$ruby = ($this->_CI->input->post('ruby') == 1) ? 1 : 0;
			$python = ($this->_CI->input->post('python') == 1) ? 1 : 0;
            $pagespeed = ($this->_CI->input->post('pagespeed') == 1) ? 1 : 0;
			$redirect = ($this->_CI->input->post('domain_redirect') != '') ? $this->_CI->input->post('domain_redirect') : '';
			$aliases = preg_split('/[\n\r]+/', trim($this->_CI->input->post('alias')));
			$redirect_destination = ($redirect != '') ? $this->_CI->input->post('destination') : '';

			$this->server_ip = $this->_CI->DomainModel->getServerIP();

            if($cache == 0){
                $this->resetCacheSettings($this->_CI->input->post('domain_id'));
            }

			if($this->_CI->input->post('domain_id') == "") {

				$this->update = false;

				if($this->_CI->DomainModel->checkDomain($domain) > 0) {
					$errors = array(
						'domain' => $domain.": ".lang('The domain is already exist'),
						'field' => 'domain',
						'status' => 501
					);
					return sendOutput($errors);
				}

				foreach( $aliases as $index => $line )
				{
					if($this->_CI->DomainModel->checkAlias(trim($line)) > 0) {
						$errors = array(
							'domain' => $line.": ".lang('The alias is already exist'),
							'field' => 'alias',
							'status' => 501
						);
						return sendOutput($errors);
					}
				}
			}
			else { // Check owner by update
				$this->update = true;
			}

			$data = array(
				'customer_id' => $this->customer_id,
				'server_id' => $this->_CI->DomainModel->getServerID(),
				'active' => $active,
				'php_version' => $this->_CI->input->post('php'),
				'domain' => $domain,
				'path' => $this->_CI->input->post('path'),
				'seo' => $this->_CI->input->post('seo_redirect'),
				'redirect' => $redirect,
				'redirect_destination' => $redirect_destination,
				'cache' => $cache,
				'cgi' => $cgi,
				'ssi' => $ssi,
				'ruby' => $ruby,
				'python' => $python,
                'pagespeed' => $pagespeed
			);

			if($this->update) {

				$domain_id = $this->_CI->input->post('domain_id');

				// Check owner

				unset($data['server_id']);
				unset($data['domain']);

				$this->_CI->DomainModel->updateDomain($data, $domain_id);

				$dns_id = $this->_CI->DnsModel->getDnsDomainId($domain_id);

				// Remove alias DNS Records
				if(moduleActive('dns') && $dns_id != 0) {
					foreach ($this->_CI->DomainModel->getAliasDomains($domain_id) as $key => $value) {
						$this->_CI->DnsModel->removeAliaseRecords($value->alias, $dns_id);
					}
				}

				// Remove Aliase and save new
				$this->_CI->DomainModel->removeAliase($domain_id);

				foreach( $aliases as $index => $line )
				{
					$addAlias = array('domain_id' => $domain_id, 'alias' => trim($line), 'customer_id' => $this->customer_id);
					$this->_CI->DomainModel->addAlias($addAlias);
				}
				if(moduleActive('dns') && $dns_id != 0) {
					$this->addAliasRecords($aliases, $dns_id);
				}

				addTask('updateDomain', $domain_id);

				$return = array('message' => lang('Domain has been successfully changed'), 'status' => '200');
				return sendOutput($return);

			}
			else{
				$domain_id = $this->_CI->DomainModel->addDomain($data);
				if($domain_id != false) {
					// Add alias if domain saved
					foreach( $aliases as $index => $line )
					{
						$addAlias = array('domain_id' => $domain_id, 'alias' => trim($line), 'customer_id' => $this->customer_id);
						$this->_CI->DomainModel->addAlias($addAlias);
					}

					$return = array('domain_id' => $domain_id, 'status' => '200');

					// Add records for dns
					if(moduleActive('dns')) {
						$this->addDNS($domain_id, $domain, $aliases);
					}
                    if(moduleActive('piwik', false)) {
                        $this->addPiwikDomain($domain);
                    }

					addTask('addDomain', $domain_id);

					return sendOutput($return);
				}
			}
		} else{
			$return = array('status' => 503);
			return sendOutput($return);
		}
	}

    private function addDNS($domain_id, $domain, $alias)
	{
        $this->_CI->load->model('DnsModel');

		$addDomain = array('domain_id' => $domain_id, 'name' => $domain, 'type' => 'NATIVE', 'server_id' => $this->_CI->DomainModel->getServerID(), 'customer_id' => $this->customer_id);
		$dns_id = $this->_CI->DnsModel->addDNSDomain($addDomain);
		if($dns_id > 0) {

			$records[] = array('domain_id' => $dns_id, 'name' => $domain, 'content' => getSetting('primary_dns').' abuse@cconnect.es 1', 'type' => 'SOA', 'ttl' => '86400', 'prio' => NULL);
			$records[] = array('domain_id' => $dns_id, 'name' => $domain, 'content' => getSetting('primary_dns'), 'type' => 'NS', 'ttl' => '86400', 'prio' => NULL);
			$records[] = array('domain_id' => $dns_id, 'name' => $domain, 'content' => getSetting('secundary_dns'), 'type' => 'NS', 'ttl' => '86400', 'prio' => NULL);
			$records[] = array('domain_id' => $dns_id, 'name' => $domain, 'content' => $this->server_ip, 'type' => 'A', 'ttl' => '120', 'prio' => NULL);
			$records[] = array('domain_id' => $dns_id, 'name' => '*.'.$domain, 'content' => $this->server_ip, 'type' => 'A', 'ttl' => '120', 'prio' => NULL);
			$records[] = array('domain_id' => $dns_id, 'name' => 'mail'.$domain, 'content' => $this->server_ip, 'type' => 'A', 'ttl' => '120', 'prio' => NULL);
			$records[] = array('domain_id' => $dns_id, 'name' => $domain, 'content' => 'mail.'.$domain, 'type' => 'MX', 'ttl' => '120', 'prio' => '25');

			$this->_CI->DnsModel->addDNSRecords($records);
			$this->addAliasRecords($alias, $dns_id);
		}
	}

	private function addAliasRecords($alias, $dns_id)
	{
        $this->_CI->load->model('DnsModel');
		$records = array();

		if(count($alias) > 0) {
			foreach( $alias as $index => $line )
			{
				$records[] = array('domain_id' => $dns_id, 'name' => trim($line), 'content' => $this->server_ip, 'type' => 'A', 'ttl' => '120', 'prio' => NULL);
			}
		}
		$this->_CI->DnsModel->addDNSRecords($records);
	}

    private function addPiwikDomain($domain)
	{
		if(moduleActive('piwik', false)) {
			$this->_CI->load->library('piwik');

			$piwik = $this->_CI->DomainModel->getPiwikUser();

			if(!isset($piwik->username)) {
				$piwik = new stdClass;
				$piwik->username = $this->customer_id;
				$piwik->password = randomPassword();
			}

			if(!$this->_CI->piwik->user_exist($piwik->username)) {
				if($this->_CI->piwik->user_add($piwik->username, $piwik->password)) {

					// Save in database
					$newUser = array('customer_id' => $this->customer_id, 'username' => $piwik->username, 'password' => $piwik->password);
					$this->_CI->DomainModel->addPiwikUser($newUser);
				} else {
					//send message
				}
			}

            $siteID = $this->_CI->piwik->add_site($domain);

		    // Set Access for Site
		    $this->_CI->piwik->setSiteAccess($siteID, $piwik->username);
		}
	}

    private function validate()
	{
        $pathError = "";

		$this->_CI->load->library('form_validation');
		$this->_CI->form_validation->set_error_delimiters('', '');

		if($this->_CI->input->post('domain_id') == "") {
			$this->_CI->form_validation->set_rules('domain', 'Domain', 'trim|xss_clean|required|callback_check_fqdn');
		}

        if (!checkPath(trim($this->_CI->input->post('path')))) {
            $pathError = lang('The Pathname is not valid');
        }
		$this->_CI->form_validation->set_rules('path', lang('Path'), 'trim|xss_clean|required|callback_check_path');

		if ($this->_CI->input->post('authcode') != "") {
			$this->_CI->form_validation->set_rules('authcode', lang('Authcode'), 'trim|xss_clean|required|min_length[5]');
		}

		if ($this->_CI->form_validation->run($this->_CI) == FALSE || $pathError != "") {
			$errors = array(
				'domain' => form_error('domain'),
				'path' => $pathError,
				'authcode' => form_error('authcode'),
				'status' => 501
			);
			return $errors;
		}else{
			return 1;
		}
	}

    public function checkAliasNames()
	{
		$error = array();

		$aliases = preg_split('/[\n\r]+/', trim($this->_CI->input->post('alias')));
		foreach( $aliases as $index => $line )
		{
			unset($errors);

			if($this->_CI->DomainModel->checkAlias(trim($line)) > 0) {
				$errors = array(
					'alias' => $line.": ".lang('The alias is already exist'),
					'status' => 501
				);
				return sendOutput($errors);
			}

			if(!preg_match( "/^((?=[a-z0-9-]{1,63}\.)(xn--)?[a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{1,63}$/mi", trim($line), $match )) {
				$errors = array(
					'alias' => $line.": ".lang('The alias is invalid'),
					'status' => 501
				);
				return sendOutput($errors);
			}
		}

	}

	public function check_fqdn($str)
	{
		$this->_CI->form_validation->set_message( 'check_fqdn', lang('The domainname is not valid') );
		return checkFqdn($str);
    }

    public function resetCacheSettings($domain_id)
    {
        $data = array(
            'excludeDirs' => "",
            'excludeCacheFiles' => "",
            'DurantionDefault' => "30",
            'CacheDurantion' => "d",
            'Durantion200' => "30",
            'CacheDurantion200' => "d",
            'Durantion301' => "30",
            'CacheDurantion301' => "m",
            'Durantion302' => "30",
            'CacheDurantion302' => "m",
            'Durantion404' => "30",
            'CacheDurantion404' => "m",
        );

        $this->_CI->DomainModel->updateCache($domain_id);
    }

    public function saveCache()
    {
        if(hasAccess(array('manage_domain'), true))
		{
            $cacheSettings = json_decode($this->_CI->input->post('cacheSettings'));
            $excludeDirs = json_decode($this->_CI->input->post('excludeDirs'));

            $exclude = "";
            $count = 0;
            foreach($excludeDirs AS $key => $value)
            {
                if(count((array)$excludeDirs)-1 > $count){
                    $exclude .= $value."|";
                }else{
                    $exclude .= $value;
                }
                $count++;
            }
            $exclude = preg_replace("/\s+/", "", $exclude);

            $excludeFiles = preg_replace("/^\|/", "",
                                preg_replace("/\|+/", "|",
                                    preg_replace("/(\s+|\,|\.)/", "|", $cacheSettings->excludeCacheFiles)
                                )
                            );
            $data = array(
                'excludeDirs' => $exclude,
                'excludeCacheFiles' => $excludeFiles,
                'DurantionDefault' => $cacheSettings->DurantionDefault,
                'CacheDurantion' => $cacheSettings->CacheDurantion,
                'Durantion200' => $cacheSettings->Durantion200,
                'CacheDurantion200' => $cacheSettings->CacheDurantion200,
                'Durantion301' => $cacheSettings->Durantion301,
                'CacheDurantion301' => $cacheSettings->CacheDurantion301,
                'Durantion302' => $cacheSettings->Durantion302,
                'CacheDurantion302' => $cacheSettings->CacheDurantion302,
                'Durantion404' => $cacheSettings->Durantion404,
                'CacheDurantion404' => $cacheSettings->CacheDurantion404,
            );

            $this->_CI->DomainModel->updateCache($data, $this->_CI->input->post('domain_id'));

            addTask('updateDomain', $this->_CI->input->post('domain_id'));

            $return = array('message' => lang('Domain has been successfully changed'), 'status' => '200');
		    return sendOutput($return);
        } else {
            NoAccess();
        }
    }

    public function savePageSpeed()
    {
        if(hasAccess(array('manage_domain'), true))
		{
            $psSettings = json_decode($this->_CI->input->post('psSettings'));
            $excludeDirs = json_decode($this->_CI->input->post('excludeDirs'));

            $exclude = "";
            $count = 0;
            foreach($excludeDirs AS $key => $value)
            {
                if(count((array)$excludeDirs)-1 > $count){
                    $exclude .= $value."|";
                }else{
                    $exclude .= $value;
                }
                $count++;
            }
            $exclude = preg_replace("/\s+/", "", $exclude);

            $data = array(
                "excludeDir" => $exclude,
                "UseAnalyticsJs" => $this->setPsOnOff($psSettings->UseAnalyticsJs),
                "AnalyticsID" => $psSettings->AnalyticsID,
                "ModifyCachingHeaders" => $this->setPsOnOff($psSettings->ModifyCachingHeaders),
                "XHeaderValue" => $psSettings->XHeaderValue,
                "RunExperiment" => $this->setPsOnOff($psSettings->RunExperiment),
                "DisableRewriteOnNoTransform" => $this->setPsOnOff($psSettings->DisableRewriteOnNoTransform),
                "LowercaseHtmlNames" => $this->setPsOnOff($psSettings->LowercaseHtmlNames),
                "PreserveUrlRelativity" => $this->setPsOnOff($psSettings->PreserveUrlRelativity),
                "add_head" => $this->setPsVar($psSettings->add_head),
                "combine_css" => $this->setPsVar($psSettings->combine_css),
                "combine_javascript" => $this->setPsVar($psSettings->combine_javascript),
                "convert_meta_tags" => $this->setPsVar($psSettings->convert_meta_tags),
                "extend_cache" => $this->setPsVar($psSettings->extend_cache),
                "fallback_rewrite_css_urls" => $this->setPsVar($psSettings->fallback_rewrite_css_urls),
                "flatten_css_imports" => $this->setPsVar($psSettings->flatten_css_imports),
                "inline_css" => $this->setPsVar($psSettings->inline_css),
                "inline_import_to_link" => $this->setPsVar($psSettings->inline_import_to_link),
                "inline_javascript" => $this->setPsVar($psSettings->inline_javascript),
                "rewrite_css" => $this->setPsVar($psSettings->rewrite_css),
                "rewrite_images" => $this->setPsVar($psSettings->rewrite_images),
                "rewrite_javascript" => $this->setPsVar($psSettings->rewrite_javascript),
                "rewrite_style_attributes_with_url" => $this->setPsVar($psSettings->rewrite_style_attributes_with_url)
            );

            $this->_CI->DomainModel->updatePagespeed($data, $this->_CI->input->post('domain_id'));

            addTask('updateDomain', $this->_CI->input->post('domain_id'));

            $return = array('message' => lang('Domain has been successfully changed'), 'status' => '200');
    		return sendOutput($return);
        } else {
            NoAccess();
        }
    }

    private function setPsVar($key)
    {
        return ($key == 1) ? 1 : 0;
    }

    private function setPsOnOff($key)
    {
        return ($key == 1) ? 'on' : 'off';
    }
}
