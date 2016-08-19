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
     * list_domains
     * List Domains assigned by user
     *
     * @access  public
     */
    public function list_domains()
	{
		if(has_access(array('manage_domain')))
		{
			$this->data['site'] = 'domains';
			$this->data['title'] = lang('Manage domains');
			$this->data['jsFiles'] = array('domain.js');

			render_page(role().'/domain', $this->data, true);
		} else {
            no_access();
        }

	}

    /**
     * add_domain
     * open form for new domain
     *
     * @access  public
     */
    public function add_domain()
	{
		if(has_access(array('manage_domain')))
		{
			$this->data['site'] = 'domains';
			$this->data['title'] = lang('Add new domain');
			$this->data['jsFiles'] = array('domainForm.js');

			render_page(role().'/domain_form', $this->data, true);
        } else {
            no_access();
        }

	}
    /**
	 * edit_domain
	 * open form for new domain
	 *
	 * @param   int  	$id   	Domain ID
     * @param   string  $domain Name of the domain
	 * @access  public
	 */
    public function edit_domain($id, $domain)
	{
		if(has_access(array('manage_domain')))
		{
			if($this->_CI->DomainModel->check_domain_owner($id, $domain) > 0) {

				// Load Domaindata
				$this->data['domain'] = $this->_CI->DomainModel->get_domain($id, $domain);
				$this->data['jsFiles'] = array('domainForm.js');
				$this->data['site'] = 'domains';
				$this->data['title'] = lang('Edit domain').$this->data['domain']->domain;

				$aliasDomains = $this->_CI->DomainModel->get_alias_domains($this->data['domain']->id);
				$aliases = "";
				foreach ($aliasDomains as $key => $value) {
					$aliases .= $value->alias."\n";
				}
				$this->data['domain']->alias = preg_replace("/\n$/", "", $aliases);
                $this->data['cache'] =  $this->_CI->DomainModel->get_cache_settings($this->data['domain']->id);
                $this->data['excludeCacheFiles'] = explode("|", $this->data['cache']->excludeCacheFiles);
                $this->data['ps'] =  $this->_CI->DomainModel->get_pagespeed_settings($this->data['domain']->id);
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


				render_page(role().'/domain_form', $this->data, true);
			} else {
				no_access();
			}
        } else {
            no_access();
        }
	}

    public function get_domains()
	{
		if(has_access(array('manage_domain'))) {

			$domains['total'] = $this->_CI->DomainModel->get_domains(TRUE);
			$domains['rows'] = $this->_CI->DomainModel->get_domains();

			return send_output($domains);
        } else {
            no_access();
        }
    }

    public function suspend_domain()
	{
		if(has_access(array('manage_domain')))
		{
			$domain = $this->_CI->input->post('domain');
			$domain_id = $this->_CI->input->post('domain_id');
			$status = $this->_CI->input->post('status');

			if($this->_CI->DomainModel->check_domain_owner($domain_id, $domain) > 0) {
				$this->_CI->DomainModel->suspend_domain($domain_id, $status);

				if($status == 0) {
					add_task('suspend_domain', $domain_id);
				}else{
					add_task('activate_domain', $domain_id);
				}
				return send_output(array('status' => 200));
			} else {
				return send_output(array('status' => 403));
			}
        } else {
            no_access();
        }
	}

    public function delete_domain()
	{
        $this->_CI->load->model('DnsModel');

		if(has_access(array('manage_domain')))
		{
			$domain = $this->_CI->input->post('domain');
			$domain_id = $this->_CI->input->post('domain_id');
			$dns_id = $this->_CI->DnsModel->get_dns_domain_id($domain_id);

			if($this->_CI->DomainModel->check_domain_owner($domain_id, $domain) > 0) {

				// Delete DNS records
				$this->_CI->DnsModel->delete_dns_domain($domain_id);
				$this->_CI->DnsModel->delete_dns_records($dns_id);

				// Delete Aliase
				$this->_CI->DomainModel->remove_alias($domain_id);

				// Delete domain from piwik
				if(module_active('piwik', false)) {
					$this->_CI->load->library('piwik');
					$this->_CI->piwik->delete_site($domain);
				}

				// Delete email records if module used
				if(module_active('mail', false)) {
					$this->_CI->DomainModel->delete_mail_alias($domain);
					$this->_CI->DomainModel->delete_mail_catchall($domain);
					$this->_CI->DomainModel->deleteMailUser($domain);
				}

				$this->_CI->DomainModel->delete_domain($domain_id);
				add_task('delete_domain', $domain_id);

				return send_output(array('status' => 200));
			} else {
				return send_output(array('status' => 403));
			}
        } else {
            no_access();
        }
	}

    /**
	 * save_domain
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
	public function save_domain()
	{
        $this->_CI->load->model('DnsModel');

		if(has_access(array('manage_domain'), true))
		{
			$validate = $this->validate();
			if($validate != 1){
				return send_output($validate);
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

			$this->server_ip = get_server('mail')->ip;

            if($cache == 0){
                $this->reset_cache_settings($this->_CI->input->post('domain_id'));
            }

			if($this->_CI->input->post('domain_id') == "") {

				$this->update = false;

				if($this->_CI->DomainModel->check_domain($domain) > 0) {
					$errors = array(
						'domain' => $domain.": ".lang('The domain is already exist'),
						'field' => 'domain',
						'status' => 501
					);
					return send_output($errors);
				}

				foreach( $aliases as $index => $line )
				{
					if($this->_CI->DomainModel->check_alias(trim($line)) > 0) {
						$errors = array(
							'domain' => $line.": ".lang('The alias is already exist'),
							'field' => 'alias',
							'status' => 501
						);
						return send_output($errors);
					}
				}
			}
			else { // Check owner by update
				$this->update = true;
			}

			$data = array(
				'customer_id' => $this->customer_id,
				'server_id' => get_server('mail')->id,
                'server_ip' => $this->server_ip,
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

				$this->_CI->DomainModel->update_domain($data, $domain_id);

				$dns_id = $this->_CI->DnsModel->get_dns_domain_id($domain_id);

				// Remove alias DNS Records
				if(module_active('dns') && $dns_id != 0) {
					foreach ($this->_CI->DomainModel->get_alias_domains($domain_id) as $key => $value) {
						$this->_CI->DnsModel->remove_aliase_records($value->alias, $dns_id);
					}
				}

				// Remove Aliase and save new
				$this->_CI->DomainModel->remove_alias($domain_id);

				foreach( $aliases as $index => $line )
				{
					$add_alias = array('domain_id' => $domain_id, 'alias' => trim($line), 'customer_id' => $this->customer_id);
					$this->_CI->DomainModel->add_alias($add_alias);
				}
				if(module_active('dns') && $dns_id != 0) {
					$this->add_alias_records($aliases, $dns_id);
				}

				add_task('update_domain', $domain_id);

				$return = array('message' => lang('Domain has been successfully changed'), 'status' => '200');
				return send_output($return);

			}
			else{
				$domain_id = $this->_CI->DomainModel->add_domain($data);
				if($domain_id != false) {
					// Add alias if domain saved
					foreach( $aliases as $index => $line )
					{
						$add_alias = array('domain_id' => $domain_id, 'alias' => trim($line), 'customer_id' => $this->customer_id);
						$this->_CI->DomainModel->add_alias($add_alias);
					}

					$return = array('domain_id' => $domain_id, 'status' => '200');

					// Add records for dns
					if(module_active('dns')) {
						$this->add_dns($domain_id, $domain, $aliases);
					}
                    if(module_active('piwik', false)) {
                        $this->add_piwik_domain($domain);
                    }

					add_task('add_domain', $domain_id);

					return send_output($return);
				}
			}
		} else{
			$return = array('status' => 503);
			return send_output($return);
		}
	}

    private function add_dns($domain_id, $domain, $alias)
	{
        $this->_CI->load->model('DnsModel');

		$add_domain = array('domain_id' => $domain_id, 'name' => $domain, 'type' => 'NATIVE', 'server_id' => get_server('mail')->id, 'customer_id' => $this->customer_id);
		$dns_id = $this->_CI->DnsModel->add_dns_domain($add_domain);
		if($dns_id > 0) {

			$records[] = array('domain_id' => $dns_id, 'name' => $domain, 'content' => get_setting('primary_dns').' abuse@cconnect.es 1', 'type' => 'SOA', 'ttl' => '86400', 'prio' => NULL);
			$records[] = array('domain_id' => $dns_id, 'name' => $domain, 'content' => get_setting('primary_dns'), 'type' => 'NS', 'ttl' => '86400', 'prio' => NULL);
			$records[] = array('domain_id' => $dns_id, 'name' => $domain, 'content' => get_setting('secundary_dns'), 'type' => 'NS', 'ttl' => '86400', 'prio' => NULL);
			$records[] = array('domain_id' => $dns_id, 'name' => $domain, 'content' => $this->server_ip, 'type' => 'A', 'ttl' => '120', 'prio' => NULL);
			$records[] = array('domain_id' => $dns_id, 'name' => '*.'.$domain, 'content' => $this->server_ip, 'type' => 'A', 'ttl' => '120', 'prio' => NULL);
			$records[] = array('domain_id' => $dns_id, 'name' => 'mail'.$domain, 'content' => $this->server_ip, 'type' => 'A', 'ttl' => '120', 'prio' => NULL);
			$records[] = array('domain_id' => $dns_id, 'name' => $domain, 'content' => 'mail.'.$domain, 'type' => 'MX', 'ttl' => '120', 'prio' => '25');

			$this->_CI->DnsModel->add_dns_records($records);
			$this->add_alias_records($alias, $dns_id);
		}
	}

	private function add_alias_records($alias, $dns_id)
	{
        $this->_CI->load->model('DnsModel');
		$records = array();

		if(count($alias) > 0) {
			foreach( $alias as $index => $line )
			{
				$records[] = array('domain_id' => $dns_id, 'name' => trim($line), 'content' => $this->server_ip, 'type' => 'A', 'ttl' => '120', 'prio' => NULL);
			}
		}
		$this->_CI->DnsModel->add_dns_records($records);
	}

    private function add_piwik_domain($domain)
	{
		if(module_active('piwik', false)) {
			$this->_CI->load->library('piwik');

			$piwik = $this->_CI->DomainModel->get_piwik_user();

			if(!isset($piwik->username)) {
				$piwik = new stdClass;
				$piwik->username = $this->customer_id;
				$piwik->password = random_password();
			}

			if(!$this->_CI->piwik->user_exist($piwik->username)) {
				if($this->_CI->piwik->user_add($piwik->username, $piwik->password)) {

					// Save in database
					$newUser = array('customer_id' => $this->customer_id, 'username' => $piwik->username, 'password' => $piwik->password);
					$this->_CI->DomainModel->add_piwik_user($newUser);
				} else {
					//send message
				}
			}

            $siteID = $this->_CI->piwik->add_site($domain);

		    // Set Access for Site
		    $this->_CI->piwik->set_site_access($siteID, $piwik->username);
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

        if (!check_path(trim($this->_CI->input->post('path')))) {
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

    public function check_alias_names()
	{
		$error = array();

		$aliases = preg_split('/[\n\r]+/', trim($this->_CI->input->post('alias')));
		foreach( $aliases as $index => $line )
		{
			unset($errors);

			if($this->_CI->DomainModel->check_alias(trim($line)) > 0) {
				$errors = array(
					'alias' => $line.": ".lang('The alias is already exist'),
					'status' => 501
				);
				return send_output($errors);
			}

			if(!preg_match( "/^((?=[a-z0-9-]{1,63}\.)(xn--)?[a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{1,63}$/mi", trim($line), $match )) {
				$errors = array(
					'alias' => $line.": ".lang('The alias is invalid'),
					'status' => 501
				);
				return send_output($errors);
			}
		}

	}

	public function check_fqdn($str)
	{
		$this->_CI->form_validation->set_message( 'check_fqdn', lang('The domainname is not valid') );
		return check_fqdn($str);
    }

    public function reset_cache_settings($domain_id)
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

        $this->_CI->DomainModel->update_cache($domain_id);
    }

    public function save_cache()
    {
        if(has_access(array('manage_domain'), true))
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

            $this->_CI->DomainModel->update_cache($data, $this->_CI->input->post('domain_id'));

            add_task('update_domain', $this->_CI->input->post('domain_id'));

            $return = array('message' => lang('Domain has been successfully changed'), 'status' => '200');
		    return send_output($return);
        } else {
            no_access();
        }
    }

    public function save_pagespeed()
    {
        if(has_access(array('manage_domain'), true))
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
                "UseAnalyticsJs" => $this->set_ps_onoff($psSettings->UseAnalyticsJs),
                "AnalyticsID" => $psSettings->AnalyticsID,
                "ModifyCachingHeaders" => $this->set_ps_onoff($psSettings->ModifyCachingHeaders),
                "XHeaderValue" => $psSettings->XHeaderValue,
                "RunExperiment" => $this->set_ps_onoff($psSettings->RunExperiment),
                "DisableRewriteOnNoTransform" => $this->set_ps_onoff($psSettings->DisableRewriteOnNoTransform),
                "LowercaseHtmlNames" => $this->set_ps_onoff($psSettings->LowercaseHtmlNames),
                "PreserveUrlRelativity" => $this->set_ps_onoff($psSettings->PreserveUrlRelativity),
                "add_head" => $this->set_ps_var($psSettings->add_head),
                "combine_css" => $this->set_ps_var($psSettings->combine_css),
                "combine_javascript" => $this->set_ps_var($psSettings->combine_javascript),
                "convert_meta_tags" => $this->set_ps_var($psSettings->convert_meta_tags),
                "extend_cache" => $this->set_ps_var($psSettings->extend_cache),
                "fallback_rewrite_css_urls" => $this->set_ps_var($psSettings->fallback_rewrite_css_urls),
                "flatten_css_imports" => $this->set_ps_var($psSettings->flatten_css_imports),
                "inline_css" => $this->set_ps_var($psSettings->inline_css),
                "inline_import_to_link" => $this->set_ps_var($psSettings->inline_import_to_link),
                "inline_javascript" => $this->set_ps_var($psSettings->inline_javascript),
                "rewrite_css" => $this->set_ps_var($psSettings->rewrite_css),
                "rewrite_images" => $this->set_ps_var($psSettings->rewrite_images),
                "rewrite_javascript" => $this->set_ps_var($psSettings->rewrite_javascript),
                "rewrite_style_attributes_with_url" => $this->set_ps_var($psSettings->rewrite_style_attributes_with_url)
            );

            $this->_CI->DomainModel->update_pagespeed($data, $this->_CI->input->post('domain_id'));

            add_task('update_domain', $this->_CI->input->post('domain_id'));

            $return = array('message' => lang('Domain has been successfully changed'), 'status' => '200');
    		return send_output($return);
        } else {
            no_access();
        }
    }

    private function set_ps_var($key)
    {
        return ($key == 1) ? 1 : 0;
    }

    private function set_ps_onoff($key)
    {
        return ($key == 1) ? 'on' : 'off';
    }
}
