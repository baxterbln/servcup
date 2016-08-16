<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Simple config file based ACL
 *
 * @author Kevin Phillips <kevin@kevinphillips.co.nz>
 */
class Access {

	private $_CI;
	private $acl;

	function __construct()
	{
		$this->_CI = & get_instance();
		$this->_CI->load->library('session');
		//$this->_CI->load->config('acl', TRUE);
	}

	/**
	 * function that checks that the user has the required permissions
	 *
	 * @param array $required_permissions
	 * @param integer $author_uid
	 * @return boolean
	 */
	public function has_permission($required_permissions = array('delete all'), $json = false)
	{
		$this->acl = $this->_CI->config->item('permission', 'acl');
		$this->_CI->load->model("Acl");

		/* make sure that the required permissions is an array */
		if ( ! is_array($required_permissions))
		{
			$required_permissions = explode( ',', $required_permissions );
		}

		/* Get the vars from ci_session */
		$uid = $this->_CI->session->userdata('uid');
		$user_roles = $this->_CI->session->userdata('roles');

		/* Shouldn't happen but if we stick to belt and braces we should be OK */
		if ( ! $uid OR ! $user_roles)
		{
			if($json) {
				return false;
			} else {
            	redirect(base_url('login'));
			}
		}

		/* set empty array */
		$permissions = array();

		/* Load the permissions */
		$permissions = (array) $this->_CI->Acl->getPermissions($this->_CI->session->userdata('group_id')); //$this->_CI->config->item('permission');

		foreach ($permissions as $action => $role)
		{
			if (in_array($action, $required_permissions))
			{
				if($role == 1){
					log_message('debug', "user has access");
					return TRUE;
				}else{
					log_message('debug', "user has no access");
					return FALSE;
				}
			}
		}
	}

	public function role()
	{
		/* Get the vars from ci_session */
		$uid = $this->_CI->session->userdata('uid');
		$user_roles = $this->_CI->session->userdata('roles');

		/* Shouldn't happen but if we stick to belt and braces we should be OK */
		if ( ! $uid OR ! $user_roles)
		{
            redirect(base_url('login'));
		}

		return $user_roles;
	}

	/**
	 * Function to see if a user is logged in
	 */
	public function is_logged_in()
	{
		$uid = $this->_CI->session->userdata('uid');
		if ($uid)
		{
			return TRUE;
		}
	}

}
