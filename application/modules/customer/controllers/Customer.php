<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends MX_Controller {

	private $data;

	function __construct()
    {
        parent::__construct();
		$this->lang->load("module");
        $this->load->model('CustomerModel');
		$this->data['jsLang'] = write_js_lang(dirname ( __FILE__ ));
	}

	public function index()
	{
		if(has_access(array('manage_customer')))
		{
			$this->data['site'] = 'customer';
			$this->data['jsFiles'] = array('customer.js');
        	render_page('customer', $this->data);
		}
    }

	public function addCustomer()
	{
		if(has_access(array('manage_customer', 'add_customer')))
		{
			$this->data['site'] = 'customerForm';
			$this->data['groups'] = $this->CustomerModel->getGroups();
			$this->data['jsFiles'] = array('StrongPass.js', 'customerForm.js');
			$this->data['title'] = 'Neuen Kunden anlegen';

			render_page('customer_form.php', $this->data);
		}
	}

	public function editCustomer($id)
	{
		if(array('manage_customer', 'edit_customer'))
		{
			$this->data['site'] = 'customerForm';
			$this->data['user'] = $this->CustomerModel->getCustomer($id);
			$this->data['user']->active = $this->CustomerModel->MainUserStatus($this->data['user']->customer_id)->active;
			$this->data['user']->group = $this->CustomerModel->MainUserGroup($this->data['user']->customer_id)->group_id;

			$this->data['groups'] = $this->CustomerModel->getGroups();
			$this->data['jsFiles'] = array('StrongPass.js', 'customerForm.js');

			$this->data['title'] = 'Kunden ändern (Kunden-Nummer: '.$this->data['user']->customer_id.')';

			render_page('customer_form.php', $this->data);
		}
	}

	public function saveCustomer()
	{
		if(has_access(array('manage_customer'))) {

			$this->update = false;

			// Validate input
			$validate = $this->validate();
			if($validate != 1){
				return send_output($validate);
			}

			if ($this->input->post('customer_id') != "") {
				$customer_id = $this->input->post('customer_id');

				if($this->CustomerModel->CheckWritePermission($customer_id) == 0) {
					return send_output(array('status' => '400'));
				}
				$this->update = true;
			}
			else {
				// get new customer id
				$customer_id = $this->CustomerModel->getLastCustomerid();

				// no customer exist, create first one
				if ($customer_id == 0) {
					$customer_id = get_setting('customer_id') + 1;
				}else{
					$customer_id = intval($customer_id) + 1;
				}
			}

			$customerData = array(
				'name' => $this->input->post('lastname'),
				'firstname' => $this->input->post('firstname'),
				'company' => $this->input->post('company'),
				'street' => $this->input->post('street'),
				'zipcode' => $this->input->post('zipcode'),
				'city' => $this->input->post('city'),
				'phone' => $this->input->post('phone'),
				'mobile' => $this->input->post('mobile'),
				'email' => $this->input->post('email'),
				'fax' => $this->input->post('fax'),
				'comment' => $this->input->post('comments'),
			);

			if ($this->update) {
				if(has_access(array('manage_customer', 'edit_customer'))) {

					$this->CustomerModel->updateCustomer($customerData, $customer_id);

					$userdata = $this->security->xss_clean(
										array('group_id' => $this->input->post('group'),
											  'active' => $this->input->post('activeUser'))
										 );

					if ($this->input->post('customerPassword') != "") {
						$password = array('password' => do_hash($this->input->post('customerPassword')));
						$userdata = array_merge($userdata, $password);
					}

					$this->CustomerModel->updateUser($userdata, $customer_id);

					$return = array('customer_id' => $customer_id, 'status' => '200');
				}

			}
			else {

				if(has_access(array('manage_customer', 'add_customer'))) {

					$base = array('user_id' => $this->session->userdata('uid'), 'customer_id' => $customer_id);
					$customerData = array_merge($customerData, $base);

					// Check if customer exist with this data
					if($this->CustomerModel->CheckExistCustomer($this->security->xss_clean($customerData)) > 0){
						$return = array('status' => '401');
					}else{
						if($this->CustomerModel->addCustomer($this->security->xss_clean($customerData))){

							// Insert new User
							$userdata = $this->security->xss_clean(
											array('customer_id' => $customer_id,
												  'group_id' => $this->input->post('group'),
												  'username' => $customer_id,
												  'added_by' => $this->session->userdata('customer_id'),
												  'password' => do_hash($this->session->userdata('customerPassword')),
												  'active' => $this->input->post('activeUser'))
										);
							$this->CustomerModel->addUser($userdata);

							$return = array('customer_id' => $customer_id, 'status' => '200');
						}else{
							$return = array('status' => '400');
						}
					}
				}
			}

			return send_output($return);
		}else{
			return send_output(array('status' => '500'));
		}
	}

	public function saveAdditional()
	{
		if(has_access(array('manage_customer'))) {

			$customer_id = $this->input->post('customer_id');

			$customerData = array(
				'province' => $this->input->post('province'),
				'country' => $this->input->post('country'),
				'website' => $this->input->post('website'),
				'currency' => $this->input->post('currency'),
				'taxrate' => $this->input->post('taxrate'),
				'taxid' => $this->input->post('taxid'),
				'accountholder' => $this->input->post('accountholder'),
				'iban' => $this->input->post('iban'),
				'bic' => $this->input->post('bic'),
				'bankname' => $this->input->post('bankname'),
			);

			if($this->CustomerModel->updateAdditional($this->security->xss_clean($customerData), $customer_id)){

				$return = array('status' => '200');
				return send_output($return);
			}else{
				$return = array('status' => '500');
				return send_output($return);
			}
		}
	}

    public function getCustomers()
	{
		if(has_access(array('manage_customer'))) {

			$customers['total'] = $this->CustomerModel->getCustomers(TRUE);
			$customers['rows'] = $this->CustomerModel->getCustomers();

			return send_output($customers);
		}
    }

	private function validate()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('lastname', 'Nachname', 'required|min_length[3]');
		$this->form_validation->set_rules('firstname', 'Vorname', 'required|min_length[3]');
		$this->form_validation->set_rules('street', 'Strasse', 'required|min_length[3]');
		$this->form_validation->set_rules('zipcode', 'PLZ', 'required|min_length[3]');
		$this->form_validation->set_rules('city', 'Ort', 'required|min_length[3]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		if ($this->input->post('customerPassword') != "" || $this->update = false) {
			$this->form_validation->set_rules('customerPassword', 'Passwort', 'required|min_length[6]');
		}
		if ($this->form_validation->run() == FALSE) {
			$errors = array(
				'lastname' => form_error('lastname'),
				'firstname' => form_error('firstname'),
				'street' => form_error('street'),
				'zipcode' => form_error('zipcode'),
				'city' => form_error('city'),
				'email' => form_error('email'),
				'customerPassword' => form_error('customerPassword'),
				'status' => 501
			);
			return $errors;
		}else{
			return 1;
		}
	}

}
