<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database extends MX_Controller {

	private $data;
	private $customer_id;

	function __construct()
    {
        parent::__construct();

		$this->lang->load("module");
		$this->data['jsLang'] = writeJsLang(dirname ( __FILE__ ));
		$this->customer_id = $this->session->userdata('customer_id');
    }

    public function index()
    {
        $this->load->library('Databases', $this->data);
		$this->databases->listDatabases();
    }

    public function user()
    {
        $this->load->library('User', $this->data);
		$this->user->user();
    }

}
