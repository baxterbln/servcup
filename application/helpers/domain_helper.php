<?php

if(!function_exists('DomainAvailable'))
{
    function DomainAvailable($domain)
    {
        include APPPATH . 'third_party/DomainAvailability/AvailabilityService.php';

        $service = new AvailabilityService();
        $available = $service->isAvailable($domain);
        if ($available) {
			return true;
		}
		else {
			return false;
		}
    }
}


if(!function_exists('checkPath'))
{
    function checkPath($str)
    {
        $re = "/^(\/[a-zA-Z0-9\.\_\-\/]*)+\/?$/m";
		if(!preg_match($re, $str, $match  ) ) {
			return FALSE;
        }
		elseif(preg_match("!\.{2,}!", $str, $match  )) {
			return FALSE;
		}
        else{
            return TRUE;
        }
	}
}

if(!function_exists('checkFqdn'))
{
    function checkFqdn($str)
	{
		$tld_list = file(str_replace("controllers", "assets", dirname(__FILE__)).'/tlds-alpha-by-domain.txt');
		$tld_list = array_values(array_diff($tld_list, preg_grep('/^(#|XN--)/', $tld_list)));

        if($c = preg_match( "/^([a-zA-Z0-9-]+)+\.[a-zA-Z]{2,10}?$/i", $str, $match )) {
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
}
