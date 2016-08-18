<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DatabaseModel extends CI_Model
{
    private $customer_id;

    function __construct()
    {
        parent::__construct();
        $this->customer_id = $this->session->userdata('customer_id');
    }

    public function getUsers($count = FALSE)
    {
        $this->db->select('*');
        $this->db->from('sql_user');
        $this->db->where(array('customer_id' => $this->customer_id));

        if($this->input->get('search') != "") {
            $this->db->group_start();
            $this->db->like('username', $this->input->get('search'));
            $this->db->group_end();
        }

        if($count) {
            return $this->db->get()->num_rows();
        }else{
            $this->db->limit($this->input->get('limit'), $this->input->get('offset'));

            if(!$this->input->get('sort')) {
                    $this->db->order_by('username', 'DESC');
            }else{
                $this->db->order_by($this->input->get('sort'), $this->input->get('order'));
            }

            $result = $this->db->get();
            //print $this->db->last_query();
            return $result->result();
        }
    }

    public function getDatabases($count = FALSE)
    {
        $this->db->select('*');
        $this->db->from('sql_databases');
        $this->db->where(array('customer_id' => $this->customer_id));

        if($this->input->get('search') != "") {
            $this->db->group_start();
            $this->db->like('db_name', $this->input->get('search'));
            $this->db->or_like('db_user', $this->input->get('search'));
            $this->db->group_end();
        }

        if($count) {
            return $this->db->get()->num_rows();
        }else{
            $this->db->limit($this->input->get('limit'), $this->input->get('offset'));

            if(!$this->input->get('sort')) {
                    $this->db->order_by('db_name', 'DESC');
            }else{
                $this->db->order_by($this->input->get('sort'), $this->input->get('order'));
            }

            $result = $this->db->get();
            //print $this->db->last_query();
            return $result->result();
        }
    }

    /* Check if user exist */
    public function checkExistUser($username)
    {
        $this->db->where('username', $username);
        $result = $this->db->get('sql_user');
        $this->db->last_query();
        if($result->num_rows() > 0)
        {
            return false;
        }
        return true;
    }

    /* Save new user */
    public function addUser($data)
    {
        $this->db->insert('sql_user', $data);
        $this->createUser($data['username'], $data['password'], $data['remote']);
    }

    /* Create MySQL User */
    public function createUser($user, $password, $host)
    {
        $dbm = $this->load->database(getServer('mysql')->name, true);
        $dbm->query("CREATE USER '". $user ."'@'".$host."' IDENTIFIED BY '". $password ."';");
        $dbm->query("FLUSH PRIVILEGES;");

    }

    /* Grant MySQL Privileges */
    private function grantPrivileges($user, $database, $host)
    {
        $dbm = $this->load->database(getServer('mysql')->name, true);
        $dbm->query("GRANT CREATE, ALTER, DELETE, INSERT, SELECT, DROP, UPDATE  ON * . * TO '". $user ."'@'".$host."';");
        $dbm->query("FLUSH PRIVILEGES;");
    }

    /* Create MySQL Database */
    public function createDatabase($database)
    {
        $dbm = $this->load->database(getServer('mysql')->name, true);
        $dbm->dbforge->create_database( $database );
    }


}
