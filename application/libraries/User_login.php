<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_login {

    private $_CI;

    function __construct()
    {
        $this->_CI =& get_instance();
        $this->_CI->load->library('form_validation');
        $this->_CI->load->helper(array('url'));
        $this->_CI->load->model("Acl");
    }

    function validate($redirect = NULL)
    {
        $username = $this->_CI->input->post('username');
        $password = $this->_CI->input->post('password');

        $this->_CI->form_validation->set_rules('username', 'Username', 'required|strip_tags');
        $this->_CI->form_validation->set_rules('password', 'Password', 'required|strip_tags');
        if ($this->_CI->form_validation->run() === FALSE)
        {
            $msg = validation_errors('<p>', '</p>');
            $this->_CI->session->set_flashdata('message', array('class' => 'error', 'msg' => $msg));
            print "validate errors";
            //redirect(base_url('login'));
        }
        else
        {
            $loginUser = $this->_CI->Acl->checkUser($username, do_hash($password));
            print_r($loginUser);
            if (isset($loginUser->id))
            {
                $user = $loginUser;

                // User has been authenticated lets log them in
                $msg = 'Success! You have been logged in.'; # $this->_CI->config->item('login_message');
                $this->_CI->session->set_flashdata('message', array('class' => 'success', 'msg' => $msg));

                $data = array(
                        'uid' => $loginUser->id,
                        'customer_id' => $loginUser->customer_id,
                        'group_id' => $loginUser->group_id,
                        'roles' => $this->_CI->Acl->getGroupName($loginUser->group_id)->role,
                        'name' => $this->_CI->Acl->getGroupName($loginUser->group_id)->name,
                        'username' => $username,
                );

                $this->_CI->session->set_userdata($data);
                $page = isset($redirect) ? $redirect : 'dashboard'; #$this->_CI->config->item('login_landing_page');
                redirect(base_url($page));
            }
            else
            {   $msg = "no user found";
                $this->_CI->session->set_flashdata('message', array('class' => 'error', 'msg' => $msg));
                //redirect(base_url('login'));
            }


            $this->_CI->session->set_flashdata('message', array('class' => 'error', 'msg' => $msg));
            //redirect(base_url('login'));
        }
    }

    /**
     * Function to log user out and redirect them to
     * logged out landing page
     *
     * @param string $redirect Optional
     */
    public function logout($redirect = NULL)
    {
        $array_items = array('uid' => '', 'customer_id' => '', 'roles' => '', 'username' => '');
        $this->_CI->session->unset_userdata($array_items);
        $this->_CI->session->sess_destroy();

        $page = isset($redirect) ? $redirect : 'login'; # $this->_CI->config->item('logout_landing_page');

        $logout_message = 'Success! You have been logged out.';# $this->_CI->config->item('logout_message');
        if($logout_message)
        {
            $this->_CI->session->set_flashdata('message', array('class' => 'success', 'msg' => $logout_message));
        }
        redirect(base_url($page));
    }
}
