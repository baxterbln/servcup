<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User
{
    private $data;
    private $update;
    private $customer_id;

    public function __construct($data)
    {
        $this->data = $data;
        $this->_CI = &get_instance();
        $this->_CI->load->model('DatabaseModel');
        $this->customer_id = $this->_CI->session->userdata('customer_id');
    }

    /**
     * user
     * administrate user.
     *
     *
     * @return html
     * @return json on error
     */
    public function user()
    {
        if (has_access(array('manage_database'))) {
            $this->data['site'] = 'database';
            $this->data['title'] = lang('Manage database user');
            $this->data['jsFiles'] = array('user.js');

            render_page(role().'/user', $this->data, true);
        } else {
            return send_output(array('status' => 500));
        }
    }

    /**
     * get_users
     * get database user list.
     *
     *
     * @return json
     */
    public function get_users()
    {
        if (has_access(array('manage_database'))) {
            $users['total'] = $this->_CI->DatabaseModel->get_users(true);
            $users['rows'] = $this->_CI->DatabaseModel->get_users();

            return send_output($users);
        } else {
            return send_output(array('status' => 500));
        }
    }

    /**
     * get_user
     * get database user data.
     *
     * @param string $_POST['username'] Name of the database user
     * @param string $_POST['id']       Id of the database user
     *
     * @return json
     */
    public function get_user()
    {
        if (has_access(array('manage_database')) && $this->_CI->DatabaseModel->check_owner('username', $this->_CI->input->post('username'), 'sql_user')) {
            $data = $this->_CI->DatabaseModel->get_user($this->_CI->input->post('username'), $this->_CI->input->post('id'));

            return send_output($data);
        } else {
            return send_output(array('status' => 500));
        }
    }

    /**
     * save_user
     * create or update database user.
     *
     * @param string $_POST['username']        Username
     * @param string $_POST['password']        password
     * @param string $_POST['password_repeat'] password repeat
     * @param int    $_POST['remote']          enable remote access (0/1)
     *
     * @return json
     */
    public function save_user()
    {
        if (has_access(array('manage_database'))) {
            $update = false;
            if ($this->_CI->input->post('user_id')) {
                $update = true;
            }

            $validate = $this->validate($update);
            if ($validate != 1) {
                return send_output($validate);
            } else {
                $dbuser = $this->_CI->input->post('username').'_'.$this->customer_id;
                $dbpass = $this->_CI->input->post('password');
                $dbpass_r = $this->_CI->input->post('password_repeat');
                $remote = ($this->_CI->input->post('remote') == 1) ? '%' : 'localhost';

                $data = array(
                    'server_id' => get_server('mysql')->id,
                    'customer_id' => $this->customer_id,
                    'username' => $dbuser,
                    'password' => $dbpass,
                    'remote' => $remote,
                );

                if ($update) {
                    unset($data['server_id']);
                    unset($data['customer_id']);
                    unset($data['username']);

                    $this->_CI->DatabaseModel->update_user($data, $this->_CI->input->post('user_id'));
                } else {
                    $this->_CI->DatabaseModel->add_user($data);
                }

                return send_output($data);
            }
        } else {
            return send_output(array('status' => 500));
        }
    }

    /**
     * delete_user
     * delete database user.
     *
     * @param string $_POST['username'] Database username
     * @param int    $_POST['user_id']  Id of database user
     *
     * @return json
     */
    public function delete_user()
    {
        if (has_access(array('manage_database')) && $this->_CI->DatabaseModel->check_owner('username', $this->_CI->input->post('username'), 'sql_user')) {
            if ($this->_CI->DatabaseModel->check_assign_user($this->_CI->input->post('username'))) { // User is assigned to database, error
                return send_output(array('status' => 501));
            } else {
                $this->_CI->DatabaseModel->delete_user($this->_CI->input->post('user_id'), $this->_CI->input->post('username'));

                return send_output(array('status' => 200));
            }
        } else {
            return send_output(array('status' => 500));
        }
    }

    /**
     * validate
     * validation.
     *
     * @param bool $update if update (true) or not (false, default)
     *
     * @return array
     */
    private function validate($update = false)
    {
        $this->_CI->load->library('form_validation');
        $this->_CI->form_validation->set_error_delimiters('', '');

        $formcheck = false;

        if (!$update) {
            $this->_CI->form_validation->set_rules('username', 'Username', 'trim|xss_clean|required');
            $formcheck = true;
        }

        if (!$update || ($update && $this->_CI->input->post('password') != '')) {
            $this->_CI->form_validation->set_rules('password', 'Password', 'trim|xss_clean|required|min_length[6]');
            $this->_CI->form_validation->set_rules('password_repeat', 'Password repeat', 'trim|xss_clean|required|min_length[6]|matches[password]');
            $formcheck = true;
        }

        if ($formcheck && $this->_CI->form_validation->run($this->_CI) == false) {
            $errors = array(
                'username' => form_error('username'),
                'password' => form_error('password'),
                'password_repeat' => form_error('password_repeat'),
                'status' => 501,
            );

            return $errors;
        } elseif (!$this->check_alphanumeric($this->_CI->input->post('username')) && !$update) {
            return array('username' => lang('username character'), 'status' => 501);
        } elseif (!$this->check_existUser($this->_CI->input->post('username')) && !$update) {
            return array('username' => lang('user exist'), 'status' => 501);
        } else {
            return 1;
        }
    }

    /**
     * check_alphanumeric
     * Check if string alpha-numeric.
     *
     * @param string $str checked string
     *
     * @return bool
     */
    public function check_alphanumeric($str)
    {
        $this->_CI->form_validation->set_message('alphaNumeric', 'Is not alpha numeric');

        return (!preg_match('/^([a-zA-Z0-9_])+$/i', $str)) ? false : true;
    }

    /**
     * check_existUser
     * Check is user already exist.
     *
     * @param string $user username
     *
     * @return bool
     */
    public function check_existUser($user)
    {
        $this->_CI->form_validation->set_message('UserExist', 'user ist already exist');

        return $this->_CI->DatabaseModel->check_exist_user($user.'_'.$this->customer_id);
    }
}
