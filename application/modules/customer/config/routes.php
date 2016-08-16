<?php

$route['customer'] = 'customer';
$route['customer/add'] = 'customer/addCustomer';
$route['customer/edit/(:num)'] = 'customer/editCustomer/$1';
$route['customer/getCustomers'] = 'customer/getCustomers';

$route['customer/group'] = 'group';
$route['customer/addGroup'] = 'group/addGroup';
$route['customer/editGroup/(:num)'] = 'group/editGroup/$1';
$route['customer/getGroups'] = 'group/getGroups';
$route['customer/saveGroup'] = 'group/saveGroup';
$route['customer/deleteGroup/(:num)'] = 'group/deleteGroup/$1';
