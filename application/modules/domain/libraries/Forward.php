<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forward {

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

    public function forwards()
	{
		if(hasAccess(array('manage_alias')))
		{
			$this->data['site'] = 'domains';
			$this->data['title'] = lang('Manage forwards');
			$this->data['jsFiles'] = array('forwards.js');

			renderPage(role().'/forwards', $this->data, true);
        } else {
            NoAccess();
        }
	}

	public function getForwards()
	{
		if(hasAccess(array('manage_alias'))) {

			$domains['total'] = $this->_CI->DomainModel->getForwards(TRUE);
			$domains['rows'] = $this->_CI->DomainModel->getForwards();

			return sendOutput($domains);
        } else {
            NoAccess();
        }
    }

	public function addForward()
	{
		if(hasAccess(array('manage_alias')))
		{
			$this->data['site'] = 'domains';
			$this->data['title'] = lang('Add forward');
			$this->data['jsFiles'] = array('forwardForm.js');
			$this->data['domains'] = $this->_CI->DomainModel->listDomains();

			renderPage(role().'/forward_form', $this->data, true);
        } else {
            NoAccess();
        }
	}

	public function saveForward()
	{
		if(hasAccess(array('manage_alias'), true))
		{
			// Validation
			$validate = $this->validate();
			if($validate != 1){
				return sendOutput($validate);
			}

			$domain_id = $this->_CI->input->post('domain_id');
			$alias_id = $this->_CI->input->post('alias_id');
			$domain = $this->_CI->input->post('domain');
			$sub = $this->_CI->input->post('sub');
			$redirectType = $this->_CI->input->post('domain_redirect');
			$destination = $this->_CI->input->post('destination');

			if($alias_id == "" && $domain_id == "") {
				$domain_id = $domain;
			}

			// Check if forward or domain alias exist, if yes check owner and make update
			$domain = $this->_CI->DomainModel->getDomainName($domain_id);
			$forwardDomain = trim($sub).'.'.$domain;
			if($domain == "") {
				//error
				print "error 0";
			}else{

				$data = array(
					'customer_id' => $this->customer_id,
                    'server_id' => getServer('mail')->id,
                    'server_ip' => getServer('mail')->ip,
					'active' => 1,
					'parent_id' => $domain_id,
					'domain' => $forwardDomain,
					'redirect' => $redirectType,
					'redirect_destination' => $destination,
					'type' => 'forward'
				);


				if($this->_CI->DomainModel->checkDomainOwner($domain_id, $domain) > 0) {

					if($alias_id != "" && $domain_id != "") { // Update

						unset($data['customer_id']);
						unset($data['server_id']);
						unset($data['active']);
						unset($data['parent_id']);
						unset($data['domain']);
						unset($data['type']);

						$this->_CI->DomainModel->updateForward($data, $alias_id);
						addTask('updateDomain', $domain_id);

						return sendOutput(array('status' => '200'));

					} else { // Insert

						// Check if exist
						if ($this->_CI->DomainModel->checkDomain($forwardDomain) > 0) {
							return sendOutput(array('domain' => lang('exist in domain'), 'status' => 501));
						}

						// Check if in alias exists
						if ($this->_CI->DomainModel->checkAlias($forwardDomain) > 0) {
							return sendOutput(array('domain' => lang('exist in alias'), 'status' => 501));
						}

						// Insert Domain
						$alias_id = $this->_CI->DomainModel->addDomain($data);
						if($alias_id != false) {

							$return = array('domain_id' => $domain_id, 'alias_id' => $alias_id, 'status' => '200');
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

	public function editForward($id, $domain)
 	{
		if(hasAccess(array('manage_alias')))
		{
			if($this->_CI->DomainModel->checkDomainOwner($id, $domain) > 0) {

				// Load Domaindata
				$domainData = $this->_CI->DomainModel->getDomain($id, $domain);
				$mainDomain = $this->_CI->DomainModel->getParentDomain($domainData->parent_id);

				$this->data['sub'] = str_replace(".$mainDomain", "", $domainData->domain);
				$this->data['domain'] = $this->_CI->DomainModel->getDomain($id, $domain);
				$this->data['jsFiles'] = array('forwardForm.js');
				$this->data['site'] = 'forwards';
				$this->data['title'] = lang('Edit forward').': '.$this->data['domain']->domain;
				$this->data['domains'] = $this->_CI->DomainModel->listDomains();

				renderPage(role().'/forward_form', $this->data, true);
			}else{
				print "tztztz";
			}
		} else {
            NoAccess();
        }
	}

	public function deleteForward()
 	{
		$domain = $this->_CI->input->post('domain');
		$domain_id = $this->_CI->input->post('domain_id');

        $this->_CI->load->model('DnsModel');
		$dns_id = $this->_CI->DnsModel->getDnsDomainId($domain_id);

		if($this->_CI->DomainModel->checkDomainOwner($domain_id, $domain) > 0) {

			$this->_CI->DomainModel->DeleteDomain($domain_id);
			addTask('deleteDomain', $domain_id);

			return sendOutput(array('status' => 200));
		} else {
			return sendOutput(array('status' => 403));
		}
	}

    private function validate()
	{
		$this->_CI->load->library('form_validation');
		$this->_CI->form_validation->set_error_delimiters('', '');

		if($this->_CI->input->post('domain_id') == "" &&  $this->_CI->input->post('alias_id') == "") {
			$this->_CI->form_validation->set_rules('domain', 'domain', 'trim|xss_clean|numeric|required');
			$this->_CI->form_validation->set_rules('sub', 'sub', 'trim|xss_clean|required|regex_match[/^[a-zA-Z0-9-.]*$/]');
		}
		$this->_CI->form_validation->set_rules('domain_redirect', 'domain_redirect', 'trim|xss_clean|required');
		$this->_CI->form_validation->set_rules('destination', 'destination', 'trim|xss_clean|valid_url|required');

		if ($this->_CI->form_validation->run($this->_CI) == FALSE) {
			$errors = array(
				'domain' => form_error('domain'),
				'sub' => form_error('sub'),
				'domain_redirect' => form_error('domain_redirect'),
				'destination' => form_error('domain_redirect'),
				'status' => 501
			);
			return $errors;
		}else{
			return 1;
		}
	}
}
