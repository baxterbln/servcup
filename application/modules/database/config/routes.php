<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['database'] = 'database';
$route['database/addDatabase'] = 'database/addDatabase';
$route['database/editDatabase/(:num)/(:any)'] = 'database/editDatabase/$1/$2';
$route['database/saveDatabase'] = 'database/saveDatabase';
$route['database/deleteDatabase'] = 'database/deleteDatabase';
$route['database/getDatabases'] = 'database/getDatabases';

$route['database/user'] = 'database/user';
$route['database/saveUser'] = 'database/saveUser';
$route['database/addUser'] = 'database/addUser';
$route['database/editUser/(:num)/(:any)'] = 'database/editUser/$1/$2';
$route['database/deleteUser'] = 'database/deleteUser';
$route['database/getUsers'] = 'database/getUsers';
