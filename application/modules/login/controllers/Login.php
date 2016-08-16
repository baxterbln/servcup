<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {

	function __construct()
    {
        parent::__construct();
		$this->output->enable_profiler(TRUE);

        $this->load->helper('url_helper');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

	public function index()
	{
		$data['site'] = 'Login';
        $uid = $this->session->userdata('uid');
		$user_roles = $this->session->userdata('roles');

		/* Shouldn't happen but if we stick to belt and braces we should be OK */
		if ( ! $uid OR ! $user_roles)
		{
            $this->load->view('login', $data, false);
		}else{
            redirect(base_url('dashboard'));
        }
	}

    public function validate()
    {
        $this->load->library('user_login');
        $this->user_login->validate();
    }

    public function logout()
    {
		$this->load->library('user_login');
        $this->user_login->logout();
    }

    public function display_hashed_password($password = NULL)
    {
         // Delete or comment out this "if (statement)" if you don't want it
         if (ENVIRONMENT == 'production')
         {
             die();
         }
         if($password)
         {
             echo $this->login->hash_password($password);
         }
    }
}
