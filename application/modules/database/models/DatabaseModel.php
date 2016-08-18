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

    /**
	 * checkExistUser
	 * Check if user exist
	 *
     * @param   string  $username username
	 * @access  public
     *
     * @return  bool    true|false
	 */
    public function checkExistUser($username)
    {
        $this->db->where('username', $username);
        $result = $this->db->get('sql_user');
        if($result->num_rows() > 0)
        {
            return false;
        }
        return true;
    }

    /**
	 * addUser
	 * Save new user
	 *
     * array['server_id']   int Id of the used mysql server
     * array['customer_id'] int customer id
     * array['username'] string new username
     * array['password'] string password of mysql user
     * array['remote'] string allowed host for mysql connect
     *
     * @param   array  $data (See above)
	 * @access  public
	 */
    public function addUser($data)
    {
        $this->db->insert('sql_user', $data);
        $this->createDBUser($data['username'], $data['password'], $data['remote']);
    }

    /**
	 * updateUser
	 * update mysql user
	 *
     * array['password'] string password of mysql user
     * array['remote'] string allowed host for mysql connect
     *
     * @param   array  $data (See above)
     * @param   int $user_id    Id of user dataset
	 * @access  public
	 */
    public function updateUser($data, $user_id)
    {
        $this->db->where(array('customer_id' => $this->customer_id, 'id' => $user_id));
        $this->db->update('sql_user', $data);
        $this->updateDBUser($user_id, $data['password'], $data['remote']);
    }

    /**
	 * getUser
	 * fetch user data
     *
     * @param   string  $username   MySQL username
     * @param   int     $user_id    Id of user
     *
     * @return  object[]
	 * @access  public
	 */
    public function getUser($username, $id)
    {
        $this->db->select('*');
        $this->db->where('username', $username);
        $this->db->where('id', $id);
        $this->db->where('customer_id', $this->customer_id);
        return $this->db->get('sql_user')->row();
    }

    /**
	 * getUsername
	 * get username, required for update
     *
     * @param   int     $id    Id of user
     *
     * @return  string  username
	 * @access  private
	 */
    private function getUsername($id)
    {
        $this->db->select('username');
        $this->db->where('customer_id', $this->customer_id);
        $this->db->where('id', $id);
        return $this->db->get('sql_user')->row()->username;
    }

    /**
	 * deleteUser
	 * delete sql user
     *
     * @param   int     $id    Id of user
     * @param   string  $username   MySQL username
     *
     * @return  string  username
	 * @access  public
	 */
    public function deleteUser($id, $username)
    {
        $this->db->where( array('id' => $id, 'customer_id' => $this->customer_id, 'username' => $username) );
        $this->db->delete('sql_user');

        $this->deleteDBUser($username);
    }

    /**
	 * createDBUser
	 * Create MySQL User
     *
     * @param   string     $user        MySQL username
     * @param   string     $password    MySQL password
     * @param   string     $host        allowed host for access
     *
	 * @access  public
	 */
    public function createDBUser($user, $password, $host)
    {
        $dbm = $this->load->database(getServer('mysql')->name, true);
        $dbm->query("CREATE USER '". $user ."'@'".$host."' IDENTIFIED BY '". $password ."';");
        $dbm->query("FLUSH PRIVILEGES;");
    }

    /**
	 * deleteDBUser
	 * Delete serverside mysql user
     *
     * @param   string     $user        MySQL username
     *
	 * @access  private
	 */
    private function deleteDBUser($user)
    {
        $lasthost = $this->getDbHost($user);

        $dbm = $this->load->database(getServer('mysql')->name, true);
        $dbm->query("DROP USER '".$user."'@'".$lasthost."';");
        $dbm->query("FLUSH PRIVILEGES;");
    }

    /**
	 * updateDBUser
	 * Update serverside mysql user
     *
     * @param   int        $id          Id of MySQL User
     * @param   string     $password    new MySQL password
     * @param   string     $host        allowed host for access
     *
	 * @access  public
	 */
    public function updateDBUser($id, $password, $host)
    {
        $user = $this->getUsername($id);
        $lasthost = $this->getDbHost($user);

        $dbm = $this->load->database(getServer('mysql')->name, true);
        $dbm->query("ALTER USER '".$user."'@'".$lasthost."' IDENTIFIED BY '". $password ."';");
        $dbm->query("UPDATE user SET Host='". $host ."' WHERE User='". $user ."';");
        $dbm->query("FLUSH PRIVILEGES;");
    }

    /**
	 * grantPrivileges
	 * Grant MySQL Privileges
     *
     * @param   string     $user        MySQL User
     * @param   string     $database    Name of database
     * @param   string     $host        allowed host for access
     *
	 * @access  private
	 */
    private function grantPrivileges($user, $database, $host)
    {
        $dbm = $this->load->database(getServer('mysql')->name, true);
        $dbm->query("GRANT CREATE, ALTER, DELETE, INSERT, SELECT, DROP, UPDATE  ON * . * TO '". $user ."'@'".$host."';");
        $dbm->query("FLUSH PRIVILEGES;");
    }

    /**
	 * createDatabase
	 * create MySQL Database
     *
     * @param   string     $database    Name of database
     *
	 * @access  public
	 */
    public function createDatabase($database)
    {
        $dbm = $this->load->database(getServer('mysql')->name, true);
        $dbm->dbforge->create_database( $database );
    }

    /**
	 * createDatabase
	 * get host for update
     *
     * @param   string     $user   MySQL username
     * @return  string  $hostname  Hostname for user
     *
	 * @access  private
	 */
    private function getDbHost($user)
    {
        $dbm = $this->load->database(getServer('mysql')->name, true);
        $dbm->select('Host');
        $dbm->where('User', $user);
        return $dbm->get('user')->row()->Host;
    }

    /**
     * checkOwner
     * check if customer owner of user
     *
     * @param   string  $field  Table field
     * @param   string  $key    Value for table field
     * @param   string  $table  Table
     * @return  bool    true|false
     * @access  public
     */
    public function checkOwner($field, $key, $table)
    {
        $this->db->where($field, $key);
        $this->db->where('customer_id', $this->customer_id);
        $result = $this->db->get($table);
        if($result->num_rows() > 0)
        {
            return true;
        }
        return false;
    }

    /**
     * checkAssignUser
     * check has user assigned database
     *
     * @param   string     $user   MySQL username
     * @return  bool    true|false
     * @access  public
     */
    public function checkAssignUser($username)
    {
        $this->db->where('db_user', $username);
        $this->db->where('customer_id', $this->customer_id);
        $result = $this->db->get('sql_databases');
        if($result->num_rows() > 0)
        {
            return true;
        }
        return false;
    }
}
