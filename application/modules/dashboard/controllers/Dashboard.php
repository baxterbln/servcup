<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller {

	function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
		$data['site'] = 'dashboard';
		//hasAccess('admin', array('manage_domain'));

		renderPage(role().'/dashboard', $data);
	}
}
