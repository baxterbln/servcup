<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database extends MX_Controller
{
    private $data;
    private $customer_id;

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('module');
        $this->data['jsLang'] = write_js_lang(dirname(__FILE__));
        $this->customer_id = $this->session->userdata('customer_id');
    }

    /**
     * @see	libraries/Databases.php/list_databases
     */
    public function index()
    {
        $this->load->library('Databases', $this->data);
        $this->databases->list_databases();
    }

    /**
     * @see	libraries/User.php/user
     */
    public function user()
    {
        $this->load->library('User', $this->data);
        $this->user->user();
    }

    /**
     * @see	libraries/User.php/get_users
     */
    public function get_users()
    {
        $this->load->library('User', $this->data);
        $this->user->get_users();
    }

    /**
     * @see	libraries/User.php/get_user
     */
    public function get_user()
    {
        $this->load->library('User', $this->data);
        $this->user->get_user();
    }

    /**
     * @see	libraries/User.php/save_user
     */
    public function save_user()
    {
        $this->load->library('User', $this->data);
        $this->user->save_user();
    }

    /**
     * @see	libraries/User.php/delete_user
     */
    public function delete_user()
    {
        $this->load->library('User', $this->data);
        $this->user->delete_user();
    }

    /**
     * @see	libraries/Databases.php/get_databases
     */
    public function get_databases()
    {
        $this->load->library('Databases', $this->data);
        $this->databases->get_databases();
    }

	/**
     * @see	libraries/Databases.php/get_database
     */
    public function get_database()
    {
        $this->load->library('Databases', $this->data);
        $this->databases->get_database();
    }

    /**
     * @see	libraries/Databases.php/save_database
     */
    public function save_database()
    {
        $this->load->library('Databases', $this->data);
        $this->databases->save_database();
    }

	/**
     * @see	libraries/Databases.php/delete_database
     */
    public function delete_database()
    {
        $this->load->library('Databases', $this->data);
        $this->databases->delete_database();
    }
}
