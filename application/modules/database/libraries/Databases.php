<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Databases {

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

    public function list_databases()
    {
        if(has_access(array('manage_database')))
		{
			$this->data['site'] = 'database';
			$this->data['title'] = lang('Manage databases');
			$this->data['jsFiles'] = array('database.js');
            $this->data['users'] = $this->_CI->DatabaseModel->listingUser();

			render_page(role().'/database', $this->data, TRUE);
		} else {
            no_access();
        }
    }

    public function get_databases()
    {
        if (has_access(array('manage_database'))) {
            $users['total'] = $this->_CI->DatabaseModel->get_databases(TRUE);
            $users['rows'] = $this->_CI->DatabaseModel->get_databases();

            return send_output($users);
        } else {
            return send_output(array('status' => 500));
        }
    }

    public function save_database()
    {
        if (has_access(array('manage_database'))) {

            $update = FALSE;
            if($this->_CI->input->post('db_id') != "")
            {
                $update = TRUE;
            }
            $remote = ($this->_CI->input->post('remote') == 1) ? '%' : 'localhost';

            $data = array(
                'server_id' => get_server('mysql')->id,
                'customer_id' => $this->customer_id,
                'db_name' => trim($this->_CI->input->post('dbname').'_'.$this->customer_id),
                'db_user' => $this->_CI->input->post('username'),
                'db_type' => 'MySQL',
                'remote' => $remote,
            );

            if(!$update) {
                if(!preg_match('/^([a-zA-Z0-9_])+$/i', trim($this->_CI->input->post('dbname')))){
                    return send_output(array('dbname' => lang('username character'), 'status' => 501));
                }
                else if($this->_CI->input->post('username') == "") {
                    return send_output(array('username' => lang('No user exist'), 'status' => 501));
                }
                else if(!$this->_CI->DatabaseModel->checkExistDB($data['db_name'])){
                    return send_output(array('dbname' => lang('db exist'), 'status' => 501));
                }
                else{
                    $this->_CI->DatabaseModel->createDatabase($data);
                    return send_output(array('status' => 200));
                }
            }else{

            }
        } else {
            return send_output(array('status' => 500));
        }
    }





}
