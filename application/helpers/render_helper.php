<?php

if(!function_exists('render_page'))
{
    function render_page($template, $data, $sub = false)
    {
        $ci =& get_instance();
        $ci->load->view('widgets/header', $data);
        $ci->load->view('widgets/'.role().'/navigation_side', $data);
		$ci->load->view('widgets/'.role().'/navigation_top', $data);
		$ci->load->view($template, $data);
		$ci->load->view('widgets/footer', $data);
    }
}

if(!function_exists('read_menu_entrys'))
{
    function read_menu_entrys()
    {
        $ci =& get_instance();
        $menuentry = array();
        $myMenu = '';

        if ($handle = opendir(APPPATH.'/modules')) {
            while (false !== ($module = readdir($handle))) {
                if ($module != "." && $module != "..") {
                    $menufile = APPPATH.'/modules/'.$module.'/menu/menu.php';
                    if(file_exists($menufile)) {
                        include($menufile);
                        if(isset($position[role()])) {
                            $pos = $position[role()];
                            if(is_numeric($pos) && isset($menu[role()])) {
                                $menuentry[$pos] = $menu[role()];
                            }
                            unset($menufile);
                            unset($menu);
                            unset($pos);
                        }
                    }
                }
            }
            closedir($handle);
            ksort($menuentry);

            $ci->config->set_item(role().'_menu', $menuentry);
        }
        return build_menu();
    }
}

if(!function_exists('build_menu'))
{
    function build_menu() {
        $ci =& get_instance();

        $myMenu = "";
        //print_r($ci->config->item(role().'_menu'));
        if(is_array($ci->config->item(role().'_menu'))) {
    		foreach($ci->config->item(role().'_menu') as $arr){
      			$myMenu .= create_menu($arr);
    		}
        }

        return $myMenu;
    }
}

if(!function_exists('create_menu'))
{
    function create_menu($arr)
    {
        $ci =& get_instance();
        $str = '';
        $uri = $ci->uri->segment(1);
        if(is_array($arr)){
            if(has_access(array($arr['perms']))) {

                $str .= '<li><a href="'.$arr['url'].'">';
    			if(!empty($arr['icon'])){
    				$str .= '<i class="fa fa-'.$arr['icon'].'"></i>';
    			}
    			$str .= $arr['title'];
    			if(!empty($arr['children'])){
    				$str .= '<i class="fa arrow"></i>';
    			}
    			$str .= '</a>';
             	if(!empty($arr['children'])){
                    if(has_access(array($arr['perms']))) {
                        if (strpos($arr['url'], $uri) !== false || (strpos($arr['url'], $uri) !== false && $ci->uri->segment(2))) {
                            $str .= '<ul class="collapse in">';
                        }else{
                            $str .= "<ul>";
                        }
        				foreach($arr['children'] as $subarr){
                			$str .= create_menu($subarr,$str);
        				}
        				$str .="</ul>";
                 	}
                }
    			$str .= "</li>";
            }
        }
        return $str;
    }
}

if(!function_exists('no_access'))
{
    function no_access() {
        $error['title'] = lang('System Error');
		$error['error'] = lang('no access');
		render_page('errors/html/error_system', $error, true);
    }
}
