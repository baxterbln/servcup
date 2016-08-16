<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DomainModel extends CI_Model
{
    private $customer_id;

    function __construct()
    {
        parent::__construct();
        $this->customer_id = $this->session->userdata('customer_id');
    }

    public function getDomains($count = FALSE)
    {
        $this->db->select('*');
        $this->db->from('domain');
        $this->db->where(array('customer_id' => $this->customer_id));
        $this->db->where(array('type' => 'domain'));

        if($this->input->get('search') != "") {
            $this->db->group_start();
            $this->db->like('domain', $this->input->get('search'));
            $this->db->or_like('path', $this->input->get('search'));
            $this->db->or_like('redirect_destination', $this->input->get('search'));
            $this->db->group_end();
        }

        if($count) {
            return $this->db->get()->num_rows();
        }else{
            $this->db->limit($this->input->get('limit'), $this->input->get('offset'));

            if(!$this->input->get('sort')) {
                    $this->db->order_by('domain', 'DESC');
            }else{
                $this->db->order_by($this->input->get('sort'), $this->input->get('order'));
            }

            $result = $this->db->get();
            //print $this->db->last_query();
            return $result->result();
        }
    }

    public function listDomains()
    {
        $this->db->select('id, domain');
        $this->db->from('domain');
        $this->db->where(array('customer_id' => $this->customer_id, 'type' => 'domain'));

        return $this->db->get()->result();
    }

    public function getForwards($count = FALSE)
    {
        $this->db->select('id, domain, redirect, created, redirect_destination, active');
        $this->db->from('domain');
        $this->db->where(array('customer_id' => $this->customer_id));
        $this->db->where(array('type' => 'forward'));

        if($this->input->get('search') != "") {
            $this->db->group_start();
            $this->db->like('domain', $this->input->get('search'));
            $this->db->or_like('redirect_destination', $this->input->get('search'));
            $this->db->group_end();
        }

        if($count) {
            return $this->db->get()->num_rows();
        }else{
            $this->db->limit($this->input->get('limit'), $this->input->get('offset'));

            if(!$this->input->get('sort')) {
                    $this->db->order_by('domain', 'DESC');
            }else{
                $this->db->order_by($this->input->get('sort'), $this->input->get('order'));
            }

            $result = $this->db->get();
            //print $this->db->last_query();
            return $result->result();
        }
    }

    public function getSubdomains($count = FALSE)
    {
        $this->db->select('id, domain, path, created, php_version, active');
        $this->db->from('domain');
        $this->db->where(array('customer_id' => $this->customer_id));
        $this->db->where(array('type' => 'subdomain'));

        if($this->input->get('search') != "") {
            $this->db->group_start();
            $this->db->like('domain', $this->input->get('search'));
            $this->db->or_like('path', $this->input->get('search'));
            $this->db->group_end();
        }

        if($count) {
            return $this->db->get()->num_rows();
        }else{
            $this->db->limit($this->input->get('limit'), $this->input->get('offset'));

            if(!$this->input->get('sort')) {
                    $this->db->order_by('domain', 'DESC');
            }else{
                $this->db->order_by($this->input->get('sort'), $this->input->get('order'));
            }

            $result = $this->db->get();
            //print $this->db->last_query();
            return $result->result();
        }
    }

    public function getAllSSLDomains($count = FALSE)
    {
        $this->db->select('id, domain, SSLCertificateType, SSLCertificateCreated, SSLCertificateExpire');
        $this->db->from('domain');
        $this->db->where(array('domain.customer_id' => $this->customer_id));

        $this->db->group_start();
        $this->db->where('domain.type', 'domain');
        $this->db->or_where('domain.type', 'subdomain');
        $this->db->group_end();

        if($this->input->get('search') != "") {
            $this->db->group_start();
            $this->db->like('domain.domain', $this->input->get('search'));
            $this->db->group_end();
        }
        $this->db->group_by('domain.id');

        if($count) {
            return $this->db->get()->num_rows();
        }else{
            $this->db->limit($this->input->get('limit'), $this->input->get('offset'));

            if(!$this->input->get('sort')) {
                    $this->db->order_by('domain.type', 'ASC');
            }else{
                $this->db->order_by($this->input->get('sort'), $this->input->get('order'));
            }

            $result = $this->db->get();
            //print $this->db->last_query();
            return $result->result();
        }
    }

    public function getDomain($id, $domain)
    {
        $this->db->select('*');
        $this->db->from('domain');
        $this->db->where(array('id' => $id, 'domain' => $domain, 'customer_id' => $this->customer_id));

        return $this->db->get()->row();
    }

    public function getParentDomain($id)
    {
        $this->db->select('domain');
        $this->db->from('domain');
        $this->db->where(array('id' => $id, 'customer_id' => $this->customer_id));

        return $this->db->get()->row()->domain;
    }

    public function getDomainName($id)
    {
        $this->db->select('domain');
        $this->db->from('domain');
        $this->db->where(array('id' => $id, 'customer_id' => $this->customer_id));

        $result = $this->db->get();

        if($result->num_rows()) {
            return $result->row()->domain;
        }
    }

    public function getAliasDomains($domain_id)
    {
        $this->db->select('id, alias');
        $this->db->from('domain_alias');
        $this->db->where(
            array(
                'customer_id' => $this->customer_id,
                'domain_id' => $domain_id
               )
        );

        return $this->db->get()->result();
    }


    public function addDomain($data)
    {
        $this->db->insert('domain', $data);
        if($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        }else{
            return false;
        }
    }

    public function updateDomain($data, $domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $domain_id));
        $this->db->update('domain', $data);
    }

    public function suspendDomain($domain_id, $status)
    {
        $data = array('active' => $status);
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $domain_id));
        $this->db->update('domain', $data);
    }

    public function addAlias($data)
    {
        $this->db->insert('domain_alias', $data);
    }

    public function removeAliase($domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'domain_id' => $domain_id));
        $this->db->delete('domain_alias');
    }

    public function DeleteDomain($domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $domain_id));
        $this->db->delete('domain');
    }

    public function getCacheSettings($domain_id)
    {
        $this->db->select('*');
        $this->db->from('domain_cache');
        $this->db->where(array('domain_id' => $domain_id));

        return $this->db->get()->row();
    }

    public function getPageSpeedSettings($domain_id)
    {
        $this->db->select('*');
        $this->db->from('domain_pagespeed');
        $this->db->where(array('domain_id' => $domain_id));

        return $this->db->get()->row();
    }

    public function updateCache($data, $domain_id)
    {
        $this->db->where(array('domain_id' => $domain_id));
        $this->db->update('domain_cache', $data);
    }

    public function updatePagespeed($data, $domain_id)
    {
        $this->db->where(array('domain_id' => $domain_id));
        $this->db->update('domain_pagespeed', $data);
    }

    // fetch server_id from customer
    public function checkDomain($domain)
    {
        $this->db->select('id');
        $this->db->from('domain');
        $this->db->where(
            array(
                'domain' => $domain
               )
        );
        return $this->db->get()->num_rows();
    }

    // Check if domain exist in db
    public function checkAlias($alias)
    {
        $this->db->select('id');
        $this->db->from('domain_alias');
        $this->db->where(
            array(
                'alias' => $alias
               )
        );
        return $this->db->get()->num_rows();
    }

    public function getServerID()
    {
        $this->db->select('server_id');
        $this->db->from('customer');
        $this->db->where(
            array(
                'customer_id' => $this->customer_id
               )
        );
        $result = $this->db->get();

        if($result->num_rows()) {
            return $result->row()->server_id;
        }
    }

    public function getServerIP()
    {
        $server_ip = $this->getServerID();

        $this->db->select('ip');
        $this->db->from('server');
        $this->db->where(
            array(
                'id' => $server_ip
               )
        );
        $result = $this->db->get();

        if($result->num_rows()) {
            return $result->row()->ip;
        }
    }

    public function checkDomainOwner($id, $domain)
    {
        $this->db->select('id');
        $this->db->from('domain');
        $this->db->where(
            array(
                'id' => $id,
                'domain' => $domain,
                'customer_id' => $this->customer_id
               )
        );
        return $this->db->get()->num_rows();
    }

    public function getPiwikUser()
    {
        $this->db->select('username, password');
        $this->db->from('domain_stats');
        $this->db->where(
            array(
                'customer_id' => $this->customer_id
               )
        );
        $result = $this->db->get();
        if($result->num_rows()) {
            return $result->row();
        }
    }

    public function addPiwikUser($data)
    {
        $this->db->insert('domain_stats', $data);
    }

    public function deleteMailAlias($domain)
    {
        $this->db->where(array('domain' => $domain, 'customer_id' => $this->customer_id));
        $this->db->delete('mail_alias');
    }

    public function deleteMailCatchAll($domain)
    {
        $this->db->where(array('domain' => $domain, 'customer_id' => $this->customer_id));
        $this->db->delete('mail_catchall');
    }

    public function deleteMailUser($domain)
    {
        $this->db->where(array('domain' => $domain, 'customer_id' => $this->customer_id));
        $this->db->delete('mail_user');
    }

    public function addForward($data)
    {
        $this->db->insert('domain', $data);

        if($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        }else{
            return false;
        }
    }

    public function updateForward($data, $domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $domain_id));
        $this->db->update('domain', $data);
    }

    public function updateSubdomain($data, $domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $domain_id));
        $this->db->update('domain', $data);
    }

    public function updateCertificateDomain($data, $domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $domain_id));
        $this->db->update('domain', $data);
    }

    public function updateCertificateAlias($data, $alias, $domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'alias' => $alias, 'domain_id' => $domain_id));
        $this->db->update('domain_alias', $data);
    }

}
