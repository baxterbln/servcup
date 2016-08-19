<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database extends MX_Controller {

	private $data;
	private $customer_id;

	function __construct()
    {
        parent::__construct();

		$this->lang->load("module");
		$this->data['jsLang'] = write_js_lang(dirname ( __FILE__ ));
		$this->customer_id = $this->session->userdata('customer_id');
    }

    public function index()
    {
        $this->load->library('Databases', $this->data);
		$this->databases->list_databases();
    }

    public function user()
    {
        $this->load->library('User', $this->data);
		$this->user->user();
    }

    public function get_users()
    {
        $this->load->library('User', $this->data);
		$this->user->get_users();
    }

	public function get_user()
    {
        $this->load->library('User', $this->data);
		$this->user->get_user();
    }

    public function save_user()
    {
        $this->load->library('User', $this->data);
		$this->user->save_user();
    }

	public function delete_user()
    {
        $this->load->library('User', $this->data);
		$this->user->delete_user();
    }

	public function get_databases()
    {
        $this->load->library('Databases', $this->data);
		$this->databases->get_databases();
    }

	public function save_database()
    {
        $this->load->library('Databases', $this->data);
		$this->databases->save_database();
    }


}
