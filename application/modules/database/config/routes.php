<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['database'] = 'database';
$route['database/addDatabase'] = 'database/addDatabase';
$route['database/saveDatabase'] = 'database/save_database';
$route['database/deleteDatabase'] = 'database/deleteDatabase';
$route['database/getDatabases'] = 'database/get_databases';

$route['database/user'] = 'database/user';
$route['database/saveUser'] = 'database/save_user';
$route['database/getUser'] = 'database/get_user';
$route['database/deleteUser'] = 'database/deleteUser';
$route['database/getUsers'] = 'database/get_users';
