<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Hostadm extends CI_Model
{
    public function get_setting($key)
    {
        $this->db->select('value');
        $this->db->from('settings');
        $this->db->where(array('key' => $key));

        return $this->db->get()->row();
    }

    public function add_task($task, $object)
    {
        $this->db->insert('tasks', array('task' => $task, 'object' => $object));
    }

    public function get_server($function, $group)
    {
        //SELECT server.ip FROM `server`, `server_groups` WHERE server.group_id = server_groups.id AND mail = 1
        $this->db->select('server.name AS name, server.ip as ip, server_groups.id AS id');
        $this->db->from('server');
        $this->db->join('server_groups', 'server.group_id = server_groups.id');
        $this->db->where(array($function => 1, 'server_groups.id' => $group));

        return $this->db->get()->row();
    }

    public function getUsedServer($customer_id)
    {
        $this->db->select('server_id');
        $this->db->from('user');
        $this->db->where(array('customer_id' => $customer_id));

        return $this->db->get()->row()->server_id;
    }
}
