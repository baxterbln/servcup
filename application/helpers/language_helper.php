<?php

if(!function_exists('lUrl'))
{
    function lUrl()
    {
        $ci =& get_instance();
        return $ci->session->userdata('short_lang');
    }
}

if(!function_exists('lang'))
{
    function lang($value)
    {
        $ci =& get_instance();
        return $ci->lang->line($value);
    }
}
