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
		if(has_access(array('manage_alias')))
		{
			$this->data['site'] = 'domains';
			$this->data['title'] = lang('Manage forwards');
			$this->data['jsFiles'] = array('forwards.js');

			render_page(role().'/forwards', $this->data, true);
        } else {
            no_access();
        }
	}

	public function get_forwards()
	{
		if(has_access(array('manage_alias'))) {

			$domains['total'] = $this->_CI->DomainModel->get_forwards(TRUE);
			$domains['rows'] = $this->_CI->DomainModel->get_forwards();

			return send_output($domains);
        } else {
            no_access();
        }
    }

	public function add_forward()
	{
		if(has_access(array('manage_alias')))
		{
			$this->data['site'] = 'domains';
			$this->data['title'] = lang('Add forward');
			$this->data['jsFiles'] = array('forwardForm.js');
			$this->data['domains'] = $this->_CI->DomainModel->list_domains();

			render_page(role().'/forward_form', $this->data, true);
        } else {
            no_access();
        }
	}

	public function save_forward()
	{
		if(has_access(array('manage_alias'), true))
		{
			// Validation
			$validate = $this->validate();
			if($validate != 1){
				return send_output($validate);
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
			$domain = $this->_CI->DomainModel->get_domain_name($domain_id);
			$forwardDomain = trim($sub).'.'.$domain;
			if($domain == "") {
				//error
				print "error 0";
			}else{

				$data = array(
					'customer_id' => $this->customer_id,
                    'server_id' => get_server('mail')->id,
                    'server_ip' => get_server('mail')->ip,
					'active' => 1,
					'parent_id' => $domain_id,
					'domain' => $forwardDomain,
					'redirect' => $redirectType,
					'redirect_destination' => $destination,
					'type' => 'forward'
				);


				if($this->_CI->DomainModel->check_domain_owner($domain_id, $domain) > 0) {

					if($alias_id != "" && $domain_id != "") { // Update

						unset($data['customer_id']);
						unset($data['server_id']);
						unset($data['active']);
						unset($data['parent_id']);
						unset($data['domain']);
						unset($data['type']);

						$this->_CI->DomainModel->update_forward($data, $alias_id);
						add_task('update_domain', $domain_id);

						return send_output(array('status' => '200'));

					} else { // Insert

						// Check if exist
						if ($this->_CI->DomainModel->check_domain($forwardDomain) > 0) {
							return send_output(array('domain' => lang('exist in domain'), 'status' => 501));
						}

						// Check if in alias exists
						if ($this->_CI->DomainModel->check_alias($forwardDomain) > 0) {
							return send_output(array('domain' => lang('exist in alias'), 'status' => 501));
						}

						// Insert Domain
						$alias_id = $this->_CI->DomainModel->add_domain($data);
						if($alias_id != false) {

							$return = array('domain_id' => $domain_id, 'alias_id' => $alias_id, 'status' => '200');
							add_task('add_domain', $domain_id);
							return send_output($return);
						}
					}
				} else {
					return send_output(array('domain' => lang('access denied'), 'status' => 501));
				}
			}
		} else{
			$return = array('status' => 503);
			return send_output($return);
		}
	}

	public function edit_forward($id, $domain)
 	{
		if(has_access(array('manage_alias')))
		{
			if($this->_CI->DomainModel->check_domain_owner($id, $domain) > 0) {

				// Load Domaindata
				$domainData = $this->_CI->DomainModel->get_domain($id, $domain);
				$mainDomain = $this->_CI->DomainModel->get_parent_domain($domainData->parent_id);

				$this->data['sub'] = str_replace(".$mainDomain", "", $domainData->domain);
				$this->data['domain'] = $this->_CI->DomainModel->get_domain($id, $domain);
				$this->data['jsFiles'] = array('forwardForm.js');
				$this->data['site'] = 'forwards';
				$this->data['title'] = lang('Edit forward').': '.$this->data['domain']->domain;
				$this->data['domains'] = $this->_CI->DomainModel->list_domains();

				render_page(role().'/forward_form', $this->data, true);
			}else{
				print "tztztz";
			}
		} else {
            no_access();
        }
	}

	public function delete_forward()
 	{
		$domain = $this->_CI->input->post('domain');
		$domain_id = $this->_CI->input->post('domain_id');

        $this->_CI->load->model('DnsModel');
		$dns_id = $this->_CI->DnsModel->get_dns_domain_id($domain_id);

		if($this->_CI->DomainModel->check_domain_owner($domain_id, $domain) > 0) {

			$this->_CI->DomainModel->delete_domain($domain_id);
			add_task('delete_domain', $domain_id);

			return send_output(array('status' => 200));
		} else {
			return send_output(array('status' => 403));
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
