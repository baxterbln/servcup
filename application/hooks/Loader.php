<?php
class Loader
{
    public function __construct()
    {
            $this->ci =& get_instance();
    }

    function initialize() {

        log_message('debug', 'Initialize main hooks');
        $this->loadAclPerms();
        $this->setlanguage();
    }

    private function loadAclPerms()
    {
        $this->ci =& get_instance();
        $this->ci->load->model("Acl");

        $roles = array();

        $groups = $this->ci->Acl->getGroups();
        foreach ($groups as $key => $value) {
            $permission[strtolower($value->role)] = (array) $this->ci->Acl->getPermissions($value->id);
            array_push($roles, strtolower($value->role));
        }
        $this->ci->config->set_item('permission', $permission);
        $this->ci->config->set_item('roles', $roles);
    }

    private function setlanguage() {
        $this->ci->load->helper('language');
        $this->ci->load->helper('url');

        if (preg_match("/\w+-\w+/", $this->ci->uri->segment(1)) && $this->ci->uri->segment(1) != $this->ci->session->userdata('short_lang')){
            if ($this->ci->uri->segment(1) == 'de-DE') {
                $this->ci->session->set_userdata('site_lang', 'german');
                $this->ci->session->set_userdata('short_lang', 'de-DE');
                $this->ci->session->set_userdata('header_lang', 'de');
                $this->ci->lang->load('message','german');
            }
            if ($this->ci->uri->segment(1) == 'en-EN') {
                $this->ci->session->set_userdata('site_lang', 'english');
                $this->ci->session->set_userdata('short_lang', 'en-EN');
                $this->ci->session->set_userdata('header_lang', 'en');
                $this->ci->lang->load('message','english');
            }
            if ($this->ci->uri->segment(1) == 'es-ES') {
                $this->ci->session->set_userdata('site_lang', 'spanish');
                $this->ci->session->set_userdata('short_lang', 'es-ES');
                $this->ci->session->set_userdata('header_lang', 'es');
                $this->ci->lang->load('message','english');
            }
        }

        $site_lang = $this->ci->session->userdata('site_lang');
        $short_lang = $this->ci->session->userdata('short_lang');
        $header_lang = $this->ci->session->userdata('header_lang');

        if ($site_lang != "" && $short_lang != "" && $header_lang != "") {
            $this->ci->lang->load('message',$this->ci->session->userdata('site_lang'));
        } else {
            $userLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            switch ($userLang){
                case "de":
                    $this->ci->session->set_userdata('site_lang', 'german');
                    $this->ci->session->set_userdata('short_lang', 'de-DE');
                    $this->ci->session->set_userdata('header_lang', 'de');
                    $this->ci->lang->load('message','german');
                    break;
                case "en":
                    $this->ci->session->set_userdata('site_lang', 'english');
                    $this->ci->session->set_userdata('short_lang', 'en-EN');
                    $this->ci->session->set_userdata('header_lang', 'en');
                    $this->ci->lang->load('message','english');
                    break;
                case "es":
                    $this->ci->session->set_userdata('site_lang', 'spanish');
                    $this->ci->session->set_userdata('short_lang', 'es-ES');
                    $this->ci->session->set_userdata('header_lang', 'es');
                    $this->ci->lang->load('message','spanish');
                    break;
                default:
                    $this->ci->session->set_userdata('site_lang', 'german');
                    $this->ci->session->set_userdata('short_lang', 'de-DE');
                    $this->ci->session->set_userdata('header_lang', 'de');
                    $this->ci->lang->load('message','german');
                    break;
            }
        }

        if ($this->ci->uri->segment(1) != 'json') {
            if (!preg_match("/\w+-\w+/", $this->ci->uri->segment(1)) && $this->ci->uri->segment(1) != $this->ci->session->userdata('short_lang')){
                //redirect($this->ci->config->item('redirect_base')."/".$this->ci->session->userdata('short_lang')."/start");
            }
        }


        /*$appConfigOptions = $this->ci->AppConfigModel->get_configurations();
	    if($appConfigOptions) {
		    foreach($appConfigOptions as $appConfigOption)
		    {
			    $this->ci->config->set_item($appConfigOption->key,$appConfigOption->value);
		    }
	    }*/
    }
}
