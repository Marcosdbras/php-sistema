<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');




$active_group = 'default';
$active_record = TRUE;
/*
 * Configuração para rhcloud.com
 * openshift.com
*/


//Executar em rhcloud.com ou openshift
//$db['default']['hostname'] = $_ENV['OPENSHIFT_MYSQL_DB_HOST']; 
//$db['default']['username'] = $_ENV['OPENSHIFT_MYSQL_DB_USERNAME'];
//$db['default']['password'] = $_ENV['OPENSHIFT_MYSQL_DB_PASSWORD'];
//$db['default']['database'] = $_ENV['OPENSHIFT_APP_NAME'];


//Executar no local
$db['default']['hostname'] = '127.0.0.1'; 
$db['default']['username'] = 'root';
$db['default']['password'] = 'UTSETaoiom';
$db['default']['database'] = 'sistema';


//Executar em marcosbras.com
//$db['default']['hostname'] = 'localhost'; 
//$db['default']['username'] = 'marcosbr_admin';
//$db['default']['password'] = 'NnyiTUhdF%#y';
//$db['default']['database'] = 'marcosbr_dberp';



$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
