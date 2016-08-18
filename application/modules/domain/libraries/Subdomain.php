<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subdomain {

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

    public function subdomains()
	{
        if(hasAccess(array('manage_alias')))
		{
			$this->data['site'] = 'subdomains';
			$this->data['title'] = lang('Manage subdomains');
			$this->data['jsFiles'] = array('subdomains.js');

			renderPage(role().'/subdomains', $this->data, true);
        } else {
            NoAccess();
        }
	}

    public function getSubdomains()
	{
		if(hasAccess(array('manage_domain'))) {

			$domains['total'] = $this->_CI->DomainModel->getSubdomains(TRUE);
			$domains['rows'] = $this->_CI->DomainModel->getSubdomains();

			return sendOutput($domains);
		} else {
            NoAccess();
        }
    }

    /**
     * addSubdomain
     * open form for new subdomain
     *
     * @access  public
     */
	public function addSubdomain()
	{
		if(hasAccess(array('manage_domain')))
		{
			$this->data['site'] = 'add_subdomain';
			$this->data['title'] = lang('Add new subdomain');
			$this->data['jsFiles'] = array('subdomainForm.js', 'autocomplete.min.js');
			$this->data['cssFiles'] = array('autocomplete.min.css');
			$this->data['domains'] = $this->_CI->DomainModel->listDomains();

			renderPage(role().'/subdomain_form', $this->data, true);
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
    public function editSubdomain($id, $domain)
	{
		if(hasAccess(array('manage_domain')))
		{
			if($this->_CI->DomainModel->checkDomainOwner($id, $domain) > 0) {

				// Load Domaindata
                $domainData = $this->_CI->DomainModel->getDomain($id, $domain);
				$mainDomain = $this->_CI->DomainModel->getParentDomain($domainData->parent_id);

                $this->data['sub'] = str_replace(".$mainDomain", "", $domainData->domain);
				$this->data['domain'] = $this->_CI->DomainModel->getDomain($id, $domain);
				$this->data['jsFiles'] = array('subdomainForm.js', 'autocomplete.min.js');
				$this->data['site'] = 'subdomain';
				$this->data['title'] = lang('Edit subdomain').': '.$this->data['domain']->domain;
				$this->data['domains'] = $this->_CI->DomainModel->listDomains();

				renderPage(role().'/subdomain_form', $this->data, true);
			}else{
				print "tztztz";
			}
        } else {
            NoAccess();
        }
	}

	public function saveSubdomain()
	{
		if(hasAccess(array('manage_domain')))
		{
			$validate = $this->validate();
			if($validate != 1){
				return sendOutput($validate);
			}

            $domain_id = $this->_CI->input->post('domain_id');
			$sub_id = $this->_CI->input->post('sub_id');
			$domain = $this->_CI->input->post('domain');
			$sub = $this->_CI->input->post('sub');
            $active = ($this->_CI->input->post('active') == 1) ? 1 : 0;
            $cache = ($this->_CI->input->post('cache') == 1) ? 1 : 0;
            $cgi = ($this->_CI->input->post('cgi') == 1) ? 1 : 0;
            $ssi = ($this->_CI->input->post('ssi') == 1) ? 1 : 0;
            $ruby = ($this->_CI->input->post('ruby') == 1) ? 1 : 0;
            $python = ($this->_CI->input->post('python') == 1) ? 1 : 0;
            $php_version = $this->_CI->input->post('php');

            if($sub_id == "" && $domain_id == "") {
				$domain_id = $domain;
			}

			// Check if forward or domain alias exist, if yes check owner and make update
			$domain = $this->_CI->DomainModel->getDomainName($domain_id);
			$subdomain = trim($sub).'.'.$domain;

			if($domain == "") {
				//error
				print "error 0";
			}else{
				$data = array(
					'customer_id' => $this->customer_id,
                    'server_id' => getServer('mail')->id,
                    'server_ip' => getServer('mail')->ip,
					'active' => $active,
					'parent_id' => $domain_id,
					'domain' => $subdomain,
					'type' => 'subdomain',
				    'php_version' => $this->_CI->input->post('php'),
				    'path' => $this->_CI->input->post('path'),
				    'cache' => $cache,
				    'cgi' => $cgi,
				    'ssi' => $ssi,
                    'ruby' => $ruby,
                    'python' => $python
				);


				if($this->_CI->DomainModel->checkDomainOwner($domain_id, $domain) > 0) {

					if($sub_id != "" && $domain_id != "") { // Update

						unset($data['customer_id']);
						unset($data['server_id']);
						unset($data['parent_id']);
						unset($data['domain']);
						unset($data['type']);

						$this->_CI->DomainModel->updateSubdomain($data, $sub_id);
						addTask('updateDomain', $domain_id);

						return sendOutput(array('status' => '200'));

					} else { // Insert

						// Check if exist
						if ($this->_CI->DomainModel->checkDomain($subdomain) > 0) {
							return sendOutput(array('domain' => lang('exist in domain'), 'status' => 501));
						}

						// Insert Domain
						$sub_id = $this->_CI->DomainModel->addDomain($data);
						if($sub_id != false) {

							$return = array('domain_id' => $domain_id, 'sub_id' => $sub_id, 'status' => '200');
                            $this->addPiwikDomain($subdomain);
							addTask('addDomain', $domain_id);
							return sendOutput($return);
						}
					}
				} else {
					return sendOutput(array('domain' => lang('access denied'), 'status' => 501));
				}
			}
		} else{
			$return = array('status' => 503);
			return sendOutput($return);
		}
	}

    public function deleteSubdomain()
	{
		if(hasAccess(array('manage_domain')))
		{
			$domain = $this->_CI->input->post('domain');
			$domain_id = $this->_CI->input->post('domain_id');

			if($this->_CI->DomainModel->checkDomainOwner($domain_id, $domain) > 0) {

				// Delete domain from piwik
				if(moduleActive('piwik', false)) {
					$this->_CI->load->library('piwik');
					$this->_CI->piwik->delete_site($domain);
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
		if($this->_CI->input->post('domain_id') == "" &&  $this->_CI->input->post('sub_id') == "") {
			$this->_CI->form_validation->set_rules('domain', 'domain', 'trim|xss_clean|numeric|required');
			$this->_CI->form_validation->set_rules('sub', 'sub', 'trim|xss_clean|required|regex_match[/^[a-zA-Z0-9-.]*$/]');
		}else{
            $this->_CI->form_validation->set_rules('domain_id', 'domain', 'trim|required');
        }
        if (!checkPath(trim($this->_CI->input->post('path')))) {
            $pathError = lang('The Pathname is not valid');
        }

		if ($this->_CI->form_validation->run($this->_CI) == FALSE || $pathError != "") {
			$errors = array(
				'domain' => form_error('domain'),
				'path' => $pathError,
				'status' => 501
			);
			return $errors;
		}else{
			return 1;
		}
	}
}
