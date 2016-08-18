<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User {

    private $data;
    private $update;
    private $customer_id;

    function __construct($data)
    {
        $this->data = $data;
        $this->_CI = & get_instance();
        $this->_CI->load->model('DatabaseModel');
        //$this->_CI->lang->load("module");
        $this->customer_id = $this->_CI->session->userdata('customer_id');
    }

    public function user()
    {
        if(hasAccess(array('manage_database')))
		{
			$this->data['site'] = 'database';
			$this->data['title'] = lang('Manage database user');
			$this->data['jsFiles'] = array('user.js');

			renderPage(role().'/user', $this->data, true);
		} else {
            NoAccess();
        }
    }

    public function getUsers()
    {
        if(hasAccess(array('manage_database'))) {

			$users['total'] = $this->_CI->DatabaseModel->getUsers(TRUE);
			$users['rows'] = $this->_CI->DatabaseModel->getUsers();

			return sendOutput($users);
        } else {
            NoAccess();
        }
    }

    public function addUser()
    {
        if(hasAccess(array('manage_database'))) {

            $validate = $this->validate();
			if($validate != 1){
				return sendOutput($validate);
			}else{
                $dbuser = $this->_CI->input->post('username').'_'.$this->customer_id;
                $dbpass = $this->_CI->input->post('password');
                $dbpass_r = $this->_CI->input->post('password_repeat');
                $remote = ($this->_CI->input->post('remote') == 1) ? '%' : 'localhost';

                $data = array(
                    'server_id' => getServer('mysql')->id,
                    'customer_id' => $this->customer_id,
                    'username' => $dbuser,
                    'password' => $dbpass,
                    'remote' => $remote
                );

                $this->_CI->DatabaseModel->addUser($data);

    			return sendOutput($data);
            }
        } else {
            NoAccess();
        }
    }

    public function editUser($userid, $username)
    {

    }

    public function saveUser()
    {

    }


    public function deleteUser()
    {

    }

    private function validate($update = false)
    {
        $this->_CI->load->library('form_validation');
		$this->_CI->form_validation->set_error_delimiters('', '');

		if(!$update) {
			$this->_CI->form_validation->set_rules('username', 'Username', 'trim|xss_clean|required');
		}

        $this->_CI->form_validation->set_rules('password', 'Password', 'trim|xss_clean|required|min_length[6]');
        $this->_CI->form_validation->set_rules('password_repeat', 'Password repeat', 'trim|xss_clean|required|min_length[6]|matches[password]');


		if ($this->_CI->form_validation->run($this->_CI) == FALSE ) {
			$errors = array(
				'username' => form_error('username'),
				'password' => form_error('password'),
				'password_repeat' => form_error('password_repeat'),
				'status' => 501
			);
			return $errors;
		}
        elseif( !$this->check_alphaNumeric($this->_CI->input->post('username')) && !$update ) {
            return array('username' => lang('username character'),'status' => 501);
        }
        elseif( !$this->check_existUser($this->_CI->input->post('username')) && !$update ) {
            return array('username' => lang('user exist'), 'status' => 501);
        }
        else{
			return 1;
		}

    }

    public function check_alphaNumeric($str)
	{
        $this->_CI->form_validation->set_message('alphaNumeric','Is not alpha numeric');
		return ( ! preg_match("/^([a-z_])+$/i", $str)) ? FALSE : TRUE;
    }

    public function check_existUser($user)
    {
        $this->_CI->form_validation->set_message('UserExist','user ist already exist');
        return $this->_CI->DatabaseModel->checkExistUser($user.'_'.$this->customer_id);
    }
}
