<?php

if(!function_exists('hasAccess'))
{
    function hasAccess($role, $data = array())
    {
        $ci =& get_instance();
        return $ci->access->has_permission($role, $data);
    }
}

if(!function_exists('role'))
{
    function role()
    {
        $ci =& get_instance();
        return $ci->access->role();
    }
}
