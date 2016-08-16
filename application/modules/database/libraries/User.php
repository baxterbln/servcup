<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User {

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

    public function user()
    {
        if(hasAccess(array('manage_database')))
		{
			$this->data['site'] = 'database';
			$this->data['title'] = lang('Manage database user');
			$this->data['jsFiles'] = array('domain.js');

			renderPage(role().'/user', $this->data, true);
		} else {
            NoAccess();
        }
    }

}
