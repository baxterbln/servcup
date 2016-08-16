<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends MX_Controller {

    private $data;

	function __construct()
    {
        parent::__construct();
        $this->lang->load("module");
        $this->load->model('CustomerModel');
        $this->data['jsLang'] = writeJsLang(dirname ( __FILE__ ));
    }

    public function index()
	{
		if(hasAccess(array('manage_groups')))
		{
			$this->data['site'] = 'group';
            $this->data['jsFiles'] = array('group.js');

        	renderPage('group', $this->data);
		}
    }

    public function getGroups()
	{
        if(hasAccess(array('manage_groups')))
		{
            $groups['total'] = $this->CustomerModel->getGroups(TRUE);
			$groups['rows'] = $this->CustomerModel->getGroups();

            return sendOutput($groups);
        }
    }

	public function addGroup()
	{
        if(hasAccess(array('manage_groups', 'add_groups')))
		{
            $this->data['site'] = 'groupForm';
            $this->data['jsFiles'] = array('groupForm.js');
            $this->data['title'] = lang('add group');

            renderPage('group_form', $this->data);
        }
    }


	public function editGroup($id)
	{
        if(hasAccess(array('manage_groups','edit_groups')))
		{
            $this->data['group'] = $this->CustomerModel->getGroup($id);
            $this->data['site'] = 'groupForm';
            $this->data['jsFiles'] = array('groupForm.js');
            $this->data['title'] = str_replace('#NAME', $this->data['group']->name, lang('edit group'));

            renderPage('group_form', $this->data);
        }
	}

    public function saveGroup()
	{
        if(hasAccess(array('manage_groups','edit_groups', 'add_groups')) || hasAccess('reseller', array('manage_groups', 'edit_groups', 'add_groups')))
		{
            $validate = $this->validate();
			if($validate != 1){
				return sendOutput($validate);
			}

            $data = $this->input->post();

            if ($this->input->post('group_id') != "") { #update

                unset($data['group_id']);
                unset($data['name']);

                $this->CustomerModel->updateGroup(array('name' => $this->input->post('name')), $this->input->post('group_id'));
                $this->CustomerModel->updatePermissions($data, $this->input->post('group_id'));

                $return = array('group_id' => $this->input->post('group_id'), 'status' => '200');


            }else{
                $group_id = $this->CustomerModel->addGroup(array('name' => $this->input->post('name'), 'customer_id' => $this->session->userdata('customer_id')));

                if($group_id > 0) {
                    unset($data['group_id']);
                    unset($data['name']);

                    $this->CustomerModel->updatePermissions($data, $group_id);
                }

                $return = array('group_id' => $group_id, 'status' => '200');
            }

            sendOutput($return);
        }
	}

    public function deleteGroup($group_id)
	{
        if(hasAccess(array('manage_groups', 'delete_groups')))
		{
            $this->CustomerModel->deleteGroup($group_id);
        }
	}

    private function validate()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('name', lang('Groupname'), 'required|min_length[3]');

		if ($this->form_validation->run() == FALSE) {
			$errors = array(
				'name' => form_error('name'),
				'status' => 501
			);
			return $errors;
		}else{
			return 1;
		}
	}

}
