<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['domain'] = 'domain';
$route['domain/getDomains'] = 'domain/getDomains';
$route['domain/add'] = 'domain/addDomain';
$route['domain/edit/(:num)/(:any)'] = 'domain/editDomain/$1/$2';
$route['domain/DomainAvailable'] = 'domain/domain_available';
$route['domain/saveDomain'] = 'domain/saveDomain';
$route['domain/suspendDomain'] = 'domain/suspendDomain';
$route['domain/deleteDomain'] = 'domain/deleteDomain';
$route['domain/checkAliasNames'] = 'domain/checkAliasNames';

$route['domain/forwards'] = 'domain/forwards';
$route['domain/getForwards'] = 'domain/getForwards';
$route['domain/addForward'] = 'domain/addForward';
$route['domain/saveForward'] = 'domain/saveForward';
$route['domain/forward/edit/(:num)/(:any)'] = 'domain/editForward/$1/$2';
$route['domain/deleteForward'] = 'domain/deleteForward';

$route['domain/subdomains'] = 'domain/subdomains';
$route['domain/getSubdomains'] = 'domain/getSubdomains';
$route['domain/addSubdomain'] = 'domain/addSubdomain';
$route['domain/saveSubdomain'] = 'domain/saveSubdomain';
$route['domain/subdomain/edit/(:num)/(:any)'] = 'domain/editSubdomain/$1/$2';
$route['domain/deleteSubdomain'] = 'domain/deleteSubdomain';

$route['domain/ssl'] = 'domain/ssl';
$route['domain/getAllDomains'] = 'domain/getAllDomains';
$route['domain/createCertificate'] = 'domain/createCertificate';
$route['domain/revokeCertificate'] = 'domain/revokeCertificate';

$route['domain/saveCache'] = 'domain/saveCache';
$route['domain/savePageSpeed'] = 'domain/savePageSpeed';

$route['domain/directorys'] = 'domain/directoryListing';
$route['domain/stats'] = 'domain/openStats';
