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

    public function get_domains($count = FALSE)
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

    public function list_domains()
    {
        $this->db->select('id, domain');
        $this->db->from('domain');
        $this->db->where(array('customer_id' => $this->customer_id, 'type' => 'domain'));

        return $this->db->get()->result();
    }

    public function get_forwards($count = FALSE)
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

    public function get_subdomains($count = FALSE)
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

    public function get_all_ssl_domains($count = FALSE)
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

    public function get_domain($id, $domain)
    {
        $this->db->select('*');
        $this->db->from('domain');
        $this->db->where(array('id' => $id, 'domain' => $domain, 'customer_id' => $this->customer_id));

        return $this->db->get()->row();
    }

    public function get_parent_domain($id)
    {
        $this->db->select('domain');
        $this->db->from('domain');
        $this->db->where(array('id' => $id, 'customer_id' => $this->customer_id));

        return $this->db->get()->row()->domain;
    }

    public function get_domain_name($id)
    {
        $this->db->select('domain');
        $this->db->from('domain');
        $this->db->where(array('id' => $id, 'customer_id' => $this->customer_id));

        $result = $this->db->get();

        if($result->num_rows()) {
            return $result->row()->domain;
        }
    }

    public function get_alias_domains($domain_id)
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


    public function add_domain($data)
    {
        $this->db->insert('domain', $data);
        if($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        }else{
            return false;
        }
    }

    public function update_domain($data, $domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $domain_id));
        $this->db->update('domain', $data);
    }

    public function suspend_domain($domain_id, $status)
    {
        $data = array('active' => $status);
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $domain_id));
        $this->db->update('domain', $data);
    }

    public function add_alias($data)
    {
        $this->db->insert('domain_alias', $data);
    }

    public function remove_alias($domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'domain_id' => $domain_id));
        $this->db->delete('domain_alias');
    }

    public function delete_domain($domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $domain_id));
        $this->db->delete('domain');
    }

    public function get_cache_settings($domain_id)
    {
        $this->db->select('*');
        $this->db->from('domain_cache');
        $this->db->where(array('domain_id' => $domain_id));

        return $this->db->get()->row();
    }

    public function get_pagespeed_settings($domain_id)
    {
        $this->db->select('*');
        $this->db->from('domain_pagespeed');
        $this->db->where(array('domain_id' => $domain_id));

        return $this->db->get()->row();
    }

    public function update_cache($data, $domain_id)
    {
        $this->db->where(array('domain_id' => $domain_id));
        $this->db->update('domain_cache', $data);
    }

    public function update_pagespeed($data, $domain_id)
    {
        $this->db->where(array('domain_id' => $domain_id));
        $this->db->update('domain_pagespeed', $data);
    }

    // fetch server_id from customer
    public function check_domain($domain)
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
    public function check_alias($alias)
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

    public function check_domain_owner($id, $domain)
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

    public function get_piwik_user()
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

    public function add_piwik_user($data)
    {
        $this->db->insert('domain_stats', $data);
    }

    public function add_forward($data)
    {
        $this->db->insert('domain', $data);

        if($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        }else{
            return false;
        }
    }

    public function update_forward($data, $domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $domain_id));
        $this->db->update('domain', $data);
    }

    public function update_subdomain($data, $domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $domain_id));
        $this->db->update('domain', $data);
    }

    public function update_certificate_domain($data, $domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $domain_id));
        $this->db->update('domain', $data);
    }

    public function update_certificate_alias($data, $alias, $domain_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'alias' => $alias, 'domain_id' => $domain_id));
        $this->db->update('domain_alias', $data);
    }

    public function add_mail_domain($data)
    {
        $this->db->insert('mail_domains', $data);
        return $this->db->insert_id();
    }

    public function add_mail_user($data)
    {
        $this->db->insert('mail_users', $data);
    }

}
