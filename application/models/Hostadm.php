<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Hostadm extends CI_Model
{
    public function getSetting($key)
    {
        $this->db->select('value');
        $this->db->from('settings');
        $this->db->where(array('key' => $key));

        return $this->db->get()->row();
    }

    public function addTask($task, $object)
    {
        $this->db->insert('tasks', array('task' => $task, 'object' => $object));
    }
}
