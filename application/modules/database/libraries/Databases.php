<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Databases {

    private $data;
    private $update;
    private $customer_id;

    function __construct($data)
    {
        $this->data = $data;
        $this->_CI = & get_instance();
        //$this->_CI->load->model('DomainModel');
        //$this->_CI->lang->load("module");
        $this->customer_id = $this->_CI->session->userdata('customer_id');
    }

    public function listDatabases()
    {
        if(hasAccess(array('manage_database')))
		{
			$this->data['site'] = 'database';
			$this->data['title'] = lang('Manage databases');
			$this->data['jsFiles'] = array('domain.js');
            echo "bla";

			//renderPage(role().'/domain', $this->data, true);
		} else {
            NoAccess();
        }
    }





}
