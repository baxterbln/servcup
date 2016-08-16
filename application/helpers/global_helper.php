<?php

if(!function_exists('getSetting'))
{
    function getSetting($key)
    {
        $ci =& get_instance();
        $ci->load->model('Hostadm');

        return $ci->Hostadm->getSetting($key)->value;
    }
}

if(!function_exists('addTask'))
{
    function addTask($task, $object)
    {
        $ci =& get_instance();
        $ci->load->model('Hostadm');

        return $ci->Hostadm->addTask($task, $object);
    }
}


if(!function_exists('sendOutput'))
{
    function sendOutput($data)
    {
        $ci =& get_instance();
        return $ci->output
        	->set_content_type('application/json')
        	->set_status_header(200)
        	->set_output(json_encode($data));
    }
}


if(!function_exists('writeJsLang'))
{
    function writeJsLang($path)
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

if(!function_exists('moduleActive'))
{
    function moduleActive($name, $checkPath = true)
    {
        $ci =& get_instance();
        $modulePath = $ci->config->item('modules_path').$name;
        $moduleName = $name.'_module';
        if ($checkPath) {
            if(!is_dir($modulePath)) {
                return false;
            }
        }
        if(getSetting($moduleName) == 1) {
            return true;
        }else{
            return false;
        }
    }
}
if(!function_exists('randomPassword'))
{
    function randomPassword() {
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
