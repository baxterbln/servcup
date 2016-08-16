<?php

if(!function_exists('renderPage'))
{
    function renderPage($template, $data, $sub = false)
    {
        $ci =& get_instance();
        $ci->load->view('widgets/header', $data);
        $ci->load->view('widgets/'.role().'/navigation_side', $data);
		$ci->load->view('widgets/'.role().'/navigation_top', $data);
		$ci->load->view($template, $data);
		$ci->load->view('widgets/footer', $data);
    }

    function readMenuEntrys()
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
        return buildMenu();
    }

    function buildMenu() {
        $ci =& get_instance();

        $myMenu = "";
        //print_r($ci->config->item(role().'_menu'));
        if(is_array($ci->config->item(role().'_menu'))) {
    		foreach($ci->config->item(role().'_menu') as $arr){
      			$myMenu .= createMenu($arr);
    		}
        }

        return $myMenu;
    }

    function createMenu($arr)
    {
        $ci =& get_instance();
        $str = '';
        $uri = $ci->uri->segment(1);
        if(is_array($arr)){

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
                if (strpos($arr['url'], $uri) !== false || (strpos($arr['url'], $uri) !== false && $ci->uri->segment(2))) {
                    $str .= '<ul class="collapse in">';
                }else{
                    $str .= "<ul>";
                }
				foreach($arr['children'] as $subarr){
        			$str .= createMenu($subarr,$str);
				}
				$str .="</ul>";
         	}
			$str .= "</li>";
      }
      return $str;
    }
}

if(!function_exists('NoAccess'))
{
    function NoAccess() {
        $error['title'] = lang('System Error');
		$error['error'] = lang('no access');
		renderPage('errors/html/error_system', $error, true);
    }
}
