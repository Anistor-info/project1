<?php 

/**
 * 24 June 2012   Author Ravi Sharma (ravi.sharma@softobiz.com)
 * Api for internal use 
 */

# CONSTANTS 
define(DB_HOST,'localhost');
define(DB_USER,'root');
define(DB_PASSWORD,'');
define(DATABASE,'outbackmediaserver');
define(DB_TYPE,'mysql');
define(URL,'http://localhost/outbackmedia_streaming/');
define(USER_UPLOAD_DIR,'app/webroot/files/');

# INCLUDE LIBRARY FILES
require('library/adodb/adodb.inc.php');
require('library/nusoap/lib/nusoap.php');

# DATABASE SETTINGS
$driver = DB_TYPE.'://'.DB_USER.':'.DB_PASSWORD.'@'.DB_HOST.'/'.DATABASE; 
$db = ADONewConnection($driver); # eg. 'mysql' or 'oci8' 
$db->debug = false;
$db->Connect();