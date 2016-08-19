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
