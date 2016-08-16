<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Acl extends CI_Model
{
    public function getGroups()
    {
        $this->db->select('*');
        $this->db->from('groups');

        return $this->db->get()->result();
    }

    public function getGroupName($group)
    {
        $this->db->select('name, role');
        $this->db->from('groups');
        $this->db->where(array('id' => $group));

        return $this->db->get()->row();
    }

    public function getPermissions($group)
    {
        $this->db->select('*');
        $this->db->from('permissions');
        $this->db->where(array('group_id' => $group));

        return $this->db->get()->row();
    }

    public function checkUser($username, $password)
    {
        $this->db->select('id, customer_id, group_id, customer_id');
        $this->db->from('user');
        $this->db->where(array('username' => $username, 'password' => $password, 'active' => 1));

        $result =  $this->db->get()->row();
        echo $this->db->last_query();
        return $result;
    }
}
