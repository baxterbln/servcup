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
        if(has_access(array('manage_ssl')))
		{
			$this->data['site'] = 'ssl';
			$this->data['title'] = lang('Manage SSL');
			$this->data['jsFiles'] = array('ssl.js');

			render_page(role().'/ssl', $this->data, true);
        } else {
            no_access();
        }
	}

	public function get_all_domains()
	{
        if(has_access(array('manage_ssl'))) {

			$domains['total'] = $this->_CI->DomainModel->get_all_ssl_domains(TRUE);
			$domains['rows'] = $this->_CI->DomainModel->get_all_ssl_domains();

            foreach ($domains['rows'] AS $key=>$value) {
                $domains['rows'][$key]->domains = array($value->domain);
                foreach($this->_CI->DomainModel->get_alias_domains($value->id) AS $domain=>$alias) {
                    array_push($domains['rows'][$key]->domains, $alias->alias);
                }
            }
			return send_output($domains);
        } else {
            no_access();
        }
    }

	public function create_certificate()
	{
        $domain = $this->_CI->input->post('domain');
		$domain_id = $this->_CI->input->post('domain_id');

        if(has_access(array('manage_ssl'))) {
            if($this->_CI->DomainModel->check_domain_owner($domain_id, $domain) > 0) {

                $domainData = $this->_CI->DomainModel->get_domain($domain_id, $domain);

                $params = array('countryCode' => 'DE', 'state' => 'Germany', 'mailto' => 'info@cconnect.es', 'logger' => true);
                $this->_CI->load->library('Letsencrypt', $params);
                $this->_CI->letsencrypt->initAccount();

                try {
                    $this->_CI->letsencrypt->signDomains(array($domainData->domain));
                    $data = $this->get_certificate_data($domain);
                    $this->_CI->DomainModel->update_certificate_domain($data, $domain_id);
                }  catch (Exception $e) {
                    return send_output(array('status' => 500, 'error' =>  $e->getMessage()));
                }

                if($domainData->type == 'domain') {
                    foreach($this->_CI->DomainModel->get_alias_domains($domain_id) AS $key=>$value) {

                        try {
                            $this->_CI->letsencrypt->signDomains(array($value->alias));
                            unset($data);
                            $data = $this->get_certificate_data($value->alias);
                            $this->_CI->DomainModel->update_certificate_alias($data, $value->alias, $value->id);
                        } catch (Exception $e) {
                            return send_output(array('status' => 500, 'error' =>  $e->getMessage()));
                        }
                    }
                }
                add_task('update_domain', $domain_id);
                return send_output(array('status' => 200));
            }
        } else {
            no_access();
        }
	}


    public function revoke_certificate()
	{
        $domain = $this->_CI->input->post('domain');
		$domain_id = $this->_CI->input->post('domain_id');

        $data['SSLCertificateType'] = '';
        $data['SSLCertificateFile'] = '';
        $data['SSLCertificateChainFile'] = '';
        $data['SSLCertificateKeyFile'] = '';
        $data['SSLCertificateCreated'] = null;
        $data['SSLCertificateExpire'] = null;

        if(has_access(array('manage_ssl'))) {
            if($this->_CI->DomainModel->check_domain_owner($domain_id, $domain) > 0) {

                $domainData = $this->_CI->DomainModel->get_domain($domain_id, $domain);

                $params = array('logger' => true);
                $this->_CI->load->library('Letsencrypt', $params);

                try {
                    $this->_CI->letsencrypt->revoke_certificate($domain);
                    $this->_CI->DomainModel->update_certificate_domain($data, $domain_id);
                } catch (Exception $e) {
                    return send_output(array('status' => 500, 'error' =>  $e->getMessage()));
                }

                if($domainData->type == 'domain') {
                    foreach($this->_CI->DomainModel->get_alias_domains($domain_id) AS $key=>$value) {

                        try {
                            $this->_CI->letsencrypt->revoke_certificate($value->alias);
                            $this->_CI->DomainModel->update_certificate_alias($data, $value->alias, $value->id);
                        } catch (Exception $e) {
                            return send_output(array('status' => 500, 'error' =>  $e->getMessage()));
                        }
                    }
                }
                add_task('update_domain', $domain_id);
                return send_output(array('status' => 200));
            }
        } else {
            no_access();
        }
	}

    private function get_certificate_data($domain)
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
