<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Domain extends MX_Controller {

	private $data;
	private $customer_id;

	function __construct()
    {
        parent::__construct();

		$this->lang->load("module");
		$this->data['jsLang'] = writeJsLang(dirname ( __FILE__ ));
		$this->customer_id = $this->session->userdata('customer_id');
    }

	/* Domain requests */

	public function index()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->listDomains();
	}

	public function addDomain()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->addDomain();
	}

	public function editDomain($id, $domain)
	{
		$this->load->library('Domains', $this->data);
		$this->domains->editDomain($id, $domain);
	}

	public function saveDomain()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->saveDomain();
	}

	public function getDomains()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->getDomains();
    }

	public function suspendDomain()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->suspendDomain();
	}

	public function deleteDomain()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->deleteDomain();
	}

	/* Caching */
	public function saveCache()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->saveCache();
	}

	public function savePageSpeed()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->savePageSpeed();
	}

	/* Forward requests */
	public function forwards()
	{
		$this->load->library('Forward', $this->data);
		$this->forward->forwards();
	}

	public function getForwards()
	{
		$this->load->library('Forward', $this->data);
		$this->forward->getForwards();
    }

	public function addForward()
	{
		$this->load->library('Forward', $this->data);
		$this->forward->addForward();
	}

	public function saveForward()
	{
		$this->load->library('Forward', $this->data);
		$this->forward->saveForward();
	}

	public function editForward($id, $domain)
 	{
		$this->load->library('Forward', $this->data);
		$this->forward->editForward($id, $domain);
	}

	public function deleteForward()
 	{
		$this->load->library('Forward', $this->data);
		$this->forward->deleteForward();
	}

	/* Subdomain requests */
	public function subdomains()
	{
		$this->load->library('Subdomain', $this->data);
		$this->subdomain->subdomains();
	}

	public function getSubdomains()
	{
		$this->load->library('Subdomain', $this->data);
		$this->subdomain->getSubdomains();
    }

	public function addSubdomain()
	{
		$this->load->library('Subdomain', $this->data);
		$this->subdomain->addSubdomain();
	}

	public function saveSubdomain()
	{
		$this->load->library('Subdomain', $this->data);
		$this->subdomain->saveSubdomain();
	}

	public function editSubdomain($id, $domain)
	{
		$this->load->library('Subdomain', $this->data);
		$this->subdomain->editSubdomain($id, $domain);
	}

	public function deleteSubdomain()
	{
		$this->load->library('Subdomain', $this->data);
		$this->subdomain->deleteSubdomain();
	}

	/* SSL requests */
	public function ssl()
	{
		$this->load->library('Ssl', $this->data);
		$this->ssl->ssl();
	}

	public function getAllDomains()
	{
		$this->load->library('Ssl', $this->data);
		$this->ssl->getAllDomains();
    }

	public function createCertificate()
	{
		$this->load->library('Ssl', $this->data);
		$this->ssl->createCertificate();
	}

	public function revokeCertificate()
	{
		$this->load->library('Ssl', $this->data);
		$this->ssl->revokeCertificate();
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
	public function checkAliasNames()
	{
		$this->load->library('Domains', $this->data);
		$this->domains->checkAliasNames();
	}

	/* Global calls */

	public function DomainAvailable()
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

		if(DomainAvailable($domain)){
			$return = array('status' => 200, 'domain' => $domain, 'available' => 1, 'epp' => $isEpp);
		}else{
			$return = array('status' => 400, 'domain' => $domain, 'available' => 0, 'epp' => $isEpp);
		}
		return sendOutput($return);
	}

	public function directoryListing()
	{
		$homeFolder = getSetting('client_path')."/c".$this->customer_id;

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

		return sendOutput($paths);
	}

	public function openStats()
	{
		$this->load->model('DomainModel');
		$piwik = $this->DomainModel->getPiwikUser();
		if(isset($piwik->username)) {
			redirect("https://spider.cconnect.es/index.php?module=Login&action=logme&login=".$piwik->username."&password=".MD5($piwik->password));
		}else{

			$error['title'] = lang('System Error');
			$error['error'] = lang('no piwik user');
			renderPage('errors/html/error_system', $error, true);
		}
	}
}
