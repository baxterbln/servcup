<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ssl {

    private $data;
    private $update;
    private $server_ip;
    private $customer_id;

    function __construct($data)
    {
        $this->data = $data;
        $this->_CI = & get_instance();
        $this->_CI->load->model('DomainModel');
        $this->customer_id = $this->_CI->session->userdata('customer_id');
    }

    public function ssl()
	{
        if(hasAccess(array('manage_ssl')))
		{
			$this->data['site'] = 'ssl';
			$this->data['title'] = lang('Manage SSL');
			$this->data['jsFiles'] = array('ssl.js');

			renderPage(role().'/ssl', $this->data, true);
        } else {
            NoAccess();
        }
	}

	public function getAllDomains()
	{
        if(hasAccess(array('manage_ssl'))) {

			$domains['total'] = $this->_CI->DomainModel->getAllSSLDomains(TRUE);
			$domains['rows'] = $this->_CI->DomainModel->getAllSSLDomains();

            foreach ($domains['rows'] AS $key=>$value) {
                $domains['rows'][$key]->domains = array($value->domain);
                foreach($this->_CI->DomainModel->getAliasDomains($value->id) AS $domain=>$alias) {
                    array_push($domains['rows'][$key]->domains, $alias->alias);
                }
            }
			return sendOutput($domains);
        } else {
            NoAccess();
        }
    }

	public function createCertificate()
	{
        $domain = $this->_CI->input->post('domain');
		$domain_id = $this->_CI->input->post('domain_id');

        if(hasAccess(array('manage_ssl'))) {
            if($this->_CI->DomainModel->checkDomainOwner($domain_id, $domain) > 0) {

                $domainData = $this->_CI->DomainModel->getDomain($domain_id, $domain);

                $params = array('countryCode' => 'DE', 'state' => 'Germany', 'mailto' => 'info@cconnect.es', 'logger' => true);
                $this->_CI->load->library('Letsencrypt', $params);
                $this->_CI->letsencrypt->initAccount();

                try {
                    $this->_CI->letsencrypt->signDomains(array($domainData->domain));
                    $data = $this->getCertificateData($domain);
                    $this->_CI->DomainModel->updateCertificateDomain($data, $domain_id);
                }  catch (Exception $e) {
                    return sendOutput(array('status' => 500, 'error' =>  $e->getMessage()));
                }

                if($domainData->type == 'domain') {
                    foreach($this->_CI->DomainModel->getAliasDomains($domain_id) AS $key=>$value) {

                        try {
                            $this->_CI->letsencrypt->signDomains(array($value->alias));
                            unset($data);
                            $data = $this->getCertificateData($value->alias);
                            $this->_CI->DomainModel->updateCertificateAlias($data, $value->alias, $value->id);
                        } catch (Exception $e) {
                            return sendOutput(array('status' => 500, 'error' =>  $e->getMessage()));
                        }
                    }
                }
                addTask('updateDomain', $domain_id);
                return sendOutput(array('status' => 200));
            }
        } else {
            NoAccess();
        }
	}


    public function revokeCertificate()
	{
        $domain = $this->_CI->input->post('domain');
		$domain_id = $this->_CI->input->post('domain_id');

        $data['SSLCertificateType'] = '';
        $data['SSLCertificateFile'] = '';
        $data['SSLCertificateChainFile'] = '';
        $data['SSLCertificateKeyFile'] = '';
        $data['SSLCertificateCreated'] = null;
        $data['SSLCertificateExpire'] = null;

        if(hasAccess(array('manage_ssl'))) {
            if($this->_CI->DomainModel->checkDomainOwner($domain_id, $domain) > 0) {

                $domainData = $this->_CI->DomainModel->getDomain($domain_id, $domain);

                $params = array('logger' => true);
                $this->_CI->load->library('Letsencrypt', $params);

                try {
                    $this->_CI->letsencrypt->revokeCertificate($domain);
                    $this->_CI->DomainModel->updateCertificateDomain($data, $domain_id);
                } catch (Exception $e) {
                    return sendOutput(array('status' => 500, 'error' =>  $e->getMessage()));
                }

                if($domainData->type == 'domain') {
                    foreach($this->_CI->DomainModel->getAliasDomains($domain_id) AS $key=>$value) {

                        try {
                            $this->_CI->letsencrypt->revokeCertificate($value->alias);
                            $this->_CI->DomainModel->updateCertificateAlias($data, $value->alias, $value->id);
                        } catch (Exception $e) {
                            return sendOutput(array('status' => 500, 'error' =>  $e->getMessage()));
                        }
                    }
                }
                addTask('updateDomain', $domain_id);
                return sendOutput(array('status' => 200));
            }
        } else {
            NoAccess();
        }
	}

    private function getCertificateData($domain)
    {
        $this->_CI->config->load('letsencrypt');

        $certificate['SSLCertificateType'] = 'Let’s Encrypt';
        $certificate['SSLCertificateFile'] = file_get_contents($this->_CI->config->item('certificate_path') . '/_domains/'.$domain . '/cert.pem');
        $certificate['SSLCertificateChainFile'] = file_get_contents($this->_CI->config->item('certificate_path') . '/_domains/'.$domain . '/chain.pem');
        $certificate['SSLCertificateKeyFile'] = file_get_contents($this->_CI->config->item('certificate_path') . '/_domains/'.$domain . '/private.pem');

        $certificate['SSLCertificateCreated'] = date('Y-m-d H:i:s', time());
        $certificate['SSLCertificateExpire'] = date('Y-m-d H:i:s', strtotime('+90 days', time()));

        return $certificate;
    }
}
