<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Domain extends MX_Controller {

	private $data;
	private $customer_id;

	function __construct()
    {
        parent::__construct();

		$this->lang->load("module");
		$this->data['jsLang'] = write_js_lang(dirname ( __FILE__ ));
		$this->customer_id = $this->session->userdata('customer_id');
    }

	/* Domain requests */

	public function index()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->list_domains();
	}

	public function add_domain()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->add_domain();
	}

	public function edit_domain($id, $domain)
	{
		$this->load->library('Domains', $this->data);
		$this->domains->edit_domain($id, $domain);
	}

	public function save_domain()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->save_domain();
	}

	public function get_domains()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->get_domains();
    }

	public function suspend_domain()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->suspend_domain();
	}

	public function delete_domain()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->delete_domain();
	}

	/* Caching */
	public function save_cache()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->save_cache();
	}

	public function save_pagespeed()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->save_pagespeed();
	}

	/* Forward requests */
	public function forwards()
	{
		$this->load->library('Forward', $this->data);
		$this->forward->forwards();
	}

	public function get_forwards()
	{
		$this->load->library('Forward', $this->data);
		$this->forward->get_forwards();
    }

	public function add_forward()
	{
		$this->load->library('Forward', $this->data);
		$this->forward->add_forward();
	}

	public function save_forward()
	{
		$this->load->library('Forward', $this->data);
		$this->forward->save_forward();
	}

	public function edit_forward($id, $domain)
 	{
		$this->load->library('Forward', $this->data);
		$this->forward->edit_forward($id, $domain);
	}

	public function delete_forward()
 	{
		$this->load->library('Forward', $this->data);
		$this->forward->delete_forward();
	}

	/* Subdomain requests */
	public function subdomains()
	{
		$this->load->library('Subdomain', $this->data);
		$this->subdomain->subdomains();
	}

	public function get_subdomains()
	{
		$this->load->library('Subdomain', $this->data);
		$this->subdomain->get_subdomains();
    }

	public function add_subdomain()
	{
		$this->load->library('Subdomain', $this->data);
		$this->subdomain->add_subdomain();
	}

	public function save_subdomain()
	{
		$this->load->library('Subdomain', $this->data);
		$this->subdomain->save_subdomain();
	}

	public function edit_subdomain($id, $domain)
	{
		$this->load->library('Subdomain', $this->data);
		$this->subdomain->edit_subdomain($id, $domain);
	}

	public function delete_subdomain()
	{
		$this->load->library('Subdomain', $this->data);
		$this->subdomain->delete_subdomain();
	}

	/* SSL requests */
	public function ssl()
	{
		$this->load->library('Ssl', $this->data);
		$this->ssl->ssl();
	}

	public function get_all_domains()
	{
		$this->load->library('Ssl', $this->data);
		$this->ssl->get_all_domains();
    }

	public function create_certificate()
	{
		$this->load->library('Ssl', $this->data);
		$this->ssl->create_certificate();
	}

	public function revoke_certificate()
	{
		$this->load->library('Ssl', $this->data);
		$this->ssl->revoke_certificate();
	}

	public function getCert()
	{
		$this->load->library('Ssl', $this->data);
		$this->ssl->getCert();
	}

	public function saveCert()
	{
		$this->load->library('Ssl', $this->data);
		$this->ssl->saveCert();
	}

	/* Global calls */
	public function check_alias_names()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->check_alias_names();
	}

	/* Global calls */

	public function domain_available()
	{
		$domain = $this->input->post('domain');

		$epp = array(
			'.com', '.net', '.org', '.info', '.biz', '.us', '.in', '.name', '.bz', '.mn', '.mobi', '.cc', '.tv', '.coop', '.de'
		);
		$isEpp = 0;
		foreach ($epp as $key => $value) {
			if (strpos($domain, $value) !== false) {
				$isEpp = 1;
			}
		}

		if(domain_available($domain)){
			$return = array('status' => 200, 'domain' => $domain, 'available' => 1, 'epp' => $isEpp);
		}else{
			$return = array('status' => 400, 'domain' => $domain, 'available' => 0, 'epp' => $isEpp);
		}
		return send_output($return);
	}

	public function directory_listing()
	{
		$homeFolder = get_setting('client_path')."/c".$this->customer_id;

		$searchFolder = $this->input->get('q');

		$iter = new RecursiveIteratorIterator(
		    new RecursiveDirectoryIterator($homeFolder, RecursiveDirectoryIterator::SKIP_DOTS),
		    RecursiveIteratorIterator::SELF_FIRST,
		    RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
		);

		$paths = array();
		foreach ($iter as $path => $dir) {
		    if ($dir->isDir()){
				$userDir = str_replace($homeFolder, "", $path);
				if(preg_match("!^".$searchFolder."!", $userDir) && !preg_match("!logs|stats!", $userDir)) {
		        	$paths[] = $userDir;
				}
		    }
		}

		return send_output($paths);
	}

	public function open_stats()
	{
		$this->load->model('DomainModel');
		$piwik = $this->DomainModel->get_piwik_user();
		if(isset($piwik->username)) {
			redirect("https://spider.cconnect.es/index.php?module=Login&action=logme&login=".$piwik->username."&password=".MD5($piwik->password));
		}else{

			$error['title'] = lang('System Error');
			$error['error'] = lang('no piwik user');
			render_page('errors/html/error_system', $error, true);
		}
	}
}
