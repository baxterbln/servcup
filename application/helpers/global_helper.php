<?php

if(!function_exists('get_setting'))
{
    function get_setting($key)
    {
        $ci =& get_instance();
        $ci->load->model('Hostadm');

        return $ci->Hostadm->get_setting($key)->value;
    }
}

if(!function_exists('add_task'))
{
    function add_task($task, $object)
    {
        $ci =& get_instance();
        $ci->load->model('Hostadm');

        return $ci->Hostadm->add_task($task, $object);
    }
}


if(!function_exists('send_output'))
{
    function send_output($data)
    {
        $ci =& get_instance();
        return $ci->output
        	->set_content_type('application/json')
        	->set_status_header(200)
        	->set_output(json_encode($data));
    }
}


if(!function_exists('write_js_lang'))
{
    function write_js_lang($path)
    {
        $ci =& get_instance();
        $idiom = $ci->session->get_userdata('language');

        if(isset($idiom['short_lang'])){

    		require(str_replace("controllers", "", $path).'/language/'.$idiom['site_lang'].'/message_js.php');

            $content = "";
    		foreach($js as $key => $value) {
    			$content .=  "var LG_".str_replace(" ", "_", $key)." = '".$value."';\n";
    		}
            file_put_contents(str_replace("system/", "assets/js/locale/modules_".$idiom['short_lang'].".js", BASEPATH), $content);

            return "/assets/js/locale/modules_".$idiom['short_lang'].".js";
        }
    }
}

if(!function_exists('module_active'))
{
    function module_active($name, $check_path = true)
    {
        $ci =& get_instance();
        $modulePath = $ci->config->item('modules_path').$name;
        $moduleName = $name.'_module';
        if ($check_path) {
            if(!is_dir($modulePath)) {
                return false;
            }
        }
        if(get_setting($moduleName) == 1) {
            return true;
        }else{
            return false;
        }
    }
}
if(!function_exists('random_password'))
{
    function random_password() {
    	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-!%@';
    	$pass = array(); //remember to declare $pass as an array
    	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    	for ($i = 0; $i < 9; $i++) {
        	$n = rand(0, $alphaLength);
        	$pass[] = $alphabet[$n];
    	}
    	return implode($pass); //turn the array into a string
	}
}

if(!function_exists('get_server'))
{
    function get_server($function) {
        $ci =& get_instance();
        $ci->load->model('Hostadm');

        $group = $ci->Hostadm->getUsedServer($ci->session->userdata('customer_id'));
        return $ci->Hostadm->get_server($function, $group);
    }
}

if(!function_exists('crypt_password'))
{
    /**
     * crypt the plaintext password.
     *
     * @golbal  string  $cryptscheme
     * @param   string  $clear  the cleartext password
     * @param   string  $salt   optional salt
     * @return  string          the properly crypted password
     */
    function crypt_password($clear, $cryptscheme = 'sha512', $salt = '')
    {
        if($cryptscheme === 'sha') {
            $hash = sha1($clear);
            $cryptedpass = '{SHA}' . base64_encode(pack('H*', $hash));
        } elseif ($cryptscheme === 'CLEAR') {
            $cryptedpass=$clear;
        } else {
            if(empty($salt)) {
                switch($cryptscheme){
                    case 'des':
                        $salt = '';
                    break;
                    case 'md5':
                        $salt='$1$';
                    break;
                    case 'sha512':
                        $salt='$6$';
                    break;
                    case 'bcrypt':
                        $salt='$2a$10$';
                    break;
                    default:
                        if(preg_match('/\$[:digit:][:alnum:]?\$/', $cryptscheme)) {
                            $salt=$cryptscheme;
                        } else {
                            die(_('The value of $cryptscheme is invalid!'));
                        }
                }
                $salt.=get_random_bytes(CRYPT_SALT_LENGTH).'$';
            }

            $cryptedpass = crypt($clear, $salt);
        }
        return $cryptedpass;
    }
}

if(!function_exists('get_random_bytes'))
{
    /**
     * Generate pseudo random bytes
     *
     * @param int $count number of bytes to generate
     * @return string A string with the hexadecimal number
     */
    function get_random_bytes($count)
    {
        $output = base64_encode(openssl_random_pseudo_bytes($count));
        $output = strtr(substr($output, 0, $count), '+', '.'); //base64 is longer, so must truncate the result
        return $output;
    }
}
