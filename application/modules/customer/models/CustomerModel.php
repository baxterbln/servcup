<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getCustomers($count = FALSE)
    {
        $uids = $this->getUids();

        $this->db->select('*');
        $this->db->from('customer');
        if(count($uids) > 1) {
            $this->db->group_start();

            foreach ($uids as $key => $value) {
                $this->db->or_where('user_id', $value->id);
            }
            $this->db->group_end();
        }else{
            $this->db->where(array('user_id' => $this->session->userdata('uid')));
        }

        if($this->input->get('search') != "") {
            $this->db->group_start();
            $this->db->like('firstname', $this->input->get('search'));
            $this->db->or_like('name', $this->input->get('search'));
            $this->db->or_like('company', $this->input->get('search'));
            $this->db->or_like('city', $this->input->get('search'));
            $this->db->or_like('customer_id', $this->input->get('search'));
            $this->db->group_end();
        }

        if($count) {
            return $this->db->get()->num_rows();
        }else{
            $this->db->limit($this->input->get('limit'), $this->input->get('offset'));

            if(!$this->input->get('sort')) {
                    $this->db->order_by('customer_id', 'DESC');
            }else{
                $this->db->order_by($this->input->get('sort'), $this->input->get('order'));
            }

            $result = $this->db->get();
            //print $this->db->last_query();
            return $result->result();
        }
    }

    public function getCustomer($id)
    {
        $uids = $this->getUids();

        $this->db->select('*');
        $this->db->from('customer');
        $this->db->where(array('id' => $id));

        if(count($uids) > 1) {
            $this->db->group_start();

            foreach ($uids as $key => $value) {
                $this->db->or_where('user_id', $value->id);
            }
            $this->db->group_end();
        }else{
            $this->db->where(array('user_id' => $this->session->userdata('uid')));
        }

        return $this->db->get()->row();
    }

    public function CheckWritePermission($customer)
    {
        $uids = $this->getUids();

        $this->db->select('id');
        $this->db->from('customer');
        $this->db->where(array('customer_id' => $customer));

        if(count($uids) > 1) {
            $this->db->group_start();

            foreach ($uids as $key => $value) {
                $this->db->or_where('user_id', $value->id);
            }
            $this->db->group_end();
        }else{
            $this->db->where(array('user_id' => $this->session->userdata('uid')));
        }

        return $this->db->get()->num_rows();
    }

    public function MainUserStatus($customer)
    {
        $this->db->select('active');
        $this->db->from('user');
        $this->db->where(array('added_by' => $this->session->userdata('customer_id'), 'username' => $customer));

        return $this->db->get()->row();
    }

    public function MainUserGroup($customer)
    {
        $this->db->select('group_id');
        $this->db->from('user');
        $this->db->where(array('added_by' => $this->session->userdata('customer_id'), 'username' => $customer));

        return $this->db->get()->row();
    }

    public function getUids()
    {
        $this->db->select('id');
        $this->db->from('user');
        $this->db->where(array('customer_id' => $this->session->userdata('customer_id')));

        return $this->db->get()->result();
    }

    public function CheckExistCustomer($data)
    {
        $data = (object) $data;
        $this->db->select('id');
        $this->db->from('customer');
        $this->db->where(
            array(
                'name' => $data->name,
                'firstname' => $data->firstname,
                'company' => $data->company,
                'street' => $data->street,
                'zipcode' => $data->zipcode,
                'city' => $data->city
               )
        );

        return $this->db->get()->num_rows();
    }

    public function addCustomer($data)
    {
        $this->db->insert('customer', $data);
        if($this->db->affected_rows() > 0)
        {
            return true;
        }else{
            return false;
        }
    }

    public function updateCustomer($data, $customer_id)
    {
        $uids = $this->getUids();

        $this->db->where(array('customer_id' => $customer_id));
        if(count($uids) > 1) {
            $this->db->group_start();

            foreach ($uids as $key => $value) {
                $this->db->or_where('user_id', $value->id);
            }
            $this->db->group_end();
        }else{
            $this->db->where(array('user_id' => $this->session->userdata('uid')));
        }
        $this->db->update('customer', $data);
    }

    public function updateUser($data, $customer_id)
    {
        $uids = $this->getUids();

        $this->db->where(array('username' => $customer_id));
        if(count($uids) > 1) {
            $this->db->group_start();

            foreach ($uids as $key => $value) {
                $this->db->or_where('added_by', $value->id);
            }
            $this->db->group_end();
        }else{
            $this->db->where(array('added_by' => $this->session->userdata('uid')));
        }
        $this->db->update('user', $data);
    }

    public function addUser($data)
    {
        $this->db->insert('user', $data);
    }


    public function updateAdditional($data, $customer_id)
    {
        $this->db->where(array('customer_id' => $customer_id, 'user_id' => $this->session->userdata('uid')));
        $this->db->update('customer', $data);

        if($this->db->affected_rows() > 0)
        {
            return true;
        }else{
            return false;
        }
    }

    public function getLastCustomerid()
    {
        $this->db->select('MAX(customer_id) AS customer_id');
        $this->db->from('customer');
        $this->db->where(array('user_id' => $this->session->userdata('uid')));

        $result = $this->db->get();

        return $result->row()->customer_id;
    }

    public function getGroups($count = false)
    {
        $this->db->select('id, name');
        $this->db->from('groups');
        $this->db->where(array('customer_id' => $this->session->userdata('customer_id')));

        if($this->input->get('search') != "") {
            $this->db->group_start();
            $this->db->like('id', $this->input->get('search'));
            $this->db->or_like('name', $this->input->get('search'));
            $this->db->group_end();
        }

        if($count) {
            return $this->db->get()->num_rows();
        }else{
            if($this->input->get('limit') != "") {
                $this->db->limit($this->input->get('limit'), $this->input->get('offset'));
            }

            if(!$this->input->get('sort')) {
                    $this->db->order_by('id', 'ASC');
            }else{
                $this->db->order_by($this->input->get('sort'), $this->input->get('order'));
            }

            $result = $this->db->get();
            return $result->result();
        }

        return $this->db->get()->result();
    }

    public function getGroupName()
    {
        $this->db->select('name');
        $this->db->from('groups');
        $this->db->where(array('id' => $group, 'customer_id' => $this->session->userdata('customer_id')));

        return $this->db->get()->row();
    }

    public function getGroup($group)
    {
        $this->db->select('*');
        $this->db->from('groups');
        $this->db->join('permissions', 'groups.id = permissions.group_id');
        $this->db->where(array('groups.id' => $group, 'groups.customer_id' => $this->session->userdata('customer_id')));

        return $this->db->get()->row();
    }

    public function addGroup($data)
    {
        $this->db->insert('groups', $data);
        return $this->db->insert_id();
    }

    public function updateGroup($data, $group_id)
    {
        $this->db->where(array('id' => $group_id, 'customer_id' => $this->session->userdata('customer_id')));
        $this->db->update('groups', $data);
    }

    public function updatePermissions($data, $group_id)
    {
        $this->db->where(array('group_id' => $group_id));
        $this->db->update('permissions', $data);
    }

    public function deleteGroup($group_id)
    {
        $this->db->where(array('id' => $group_id, 'customer_id' => $this->session->userdata('customer_id')));
        $this->db->delete('groups');
    }
}
