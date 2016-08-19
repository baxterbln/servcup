<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['domain'] = 'domain';
$route['domain/getDomains'] = 'domain/get_domains';
$route['domain/add'] = 'domain/add_domain';
$route['domain/edit/(:num)/(:any)'] = 'domain/edit_domain/$1/$2';
$route['domain/DomainAvailable'] = 'domain/domain_available';
$route['domain/saveDomain'] = 'domain/save_domain';
$route['domain/suspendDomain'] = 'domain/suspend_domain';
$route['domain/deleteDomain'] = 'domain/delete_domain';
$route['domain/checkAliasNames'] = 'domain/check_alias_names';

$route['domain/forwards'] = 'domain/forwards';
$route['domain/getForwards'] = 'domain/get_forwards';
$route['domain/addForward'] = 'domain/add_forward';
$route['domain/saveForward'] = 'domain/save_forward';
$route['domain/forward/edit/(:num)/(:any)'] = 'domain/edit_forward/$1/$2';
$route['domain/deleteForward'] = 'domain/delete_forward';

$route['domain/subdomains'] = 'domain/subdomains';
$route['domain/getSubdomains'] = 'domain/get_subdomains';
$route['domain/addSubdomain'] = 'domain/add_subdomain';
$route['domain/saveSubdomain'] = 'domain/save_subdomain';
$route['domain/subdomain/edit/(:num)/(:any)'] = 'domain/edit_subdomain/$1/$2';
$route['domain/deleteSubdomain'] = 'domain/delete_subdomain';

$route['domain/ssl'] = 'domain/ssl';
$route['domain/getAllDomains'] = 'domain/get_all_domains';
$route['domain/createCertificate'] = 'domain/create_certificate';
$route['domain/revokeCertificate'] = 'domain/revoke_certificate';

$route['domain/saveCache'] = 'domain/save_cache';
$route['domain/savePageSpeed'] = 'domain/save_pagespeed';

$route['domain/directorys'] = 'domain/directory_listing';
$route['domain/stats'] = 'domain/open_stats';
