<?php

if (!defined('IN_CONTEXT')) die('access violation error!');
$GLOBALS['_SESS_DB_HOST'] = 'localhost';
$GLOBALS['_SESS_DB_USER'] = 'root';
$GLOBALS['_SESS_DB_PWD'] = '';
$GLOBALS['_SESS_DB_NAME'] = 'test';
$GLOBALS['_SESS_DB'] = null;
$GLOBALS['_SESS_TABLE'] = "`sessions`";
function openSession() {
global $_SESS_DB_HOST;
global $_SESS_DB_USER;
global $_SESS_DB_PWD;
global $_SESS_DB_NAME;
global $_SESS_DB;
$_SESS_DB = mysql_pconnect(
$_SESS_DB_HOST,
$_SESS_DB_USER,
$_SESS_DB_PWD
);
mysql_select_db($_SESS_DB_NAME,$_SESS_DB);
return true;
}
function closeSession() {
global $_SESS_DB;
mysql_close($_SESS_DB);
return true;
}
function readSession($sess_id) {
global $_SESS_DB;
global $_SESS_TABLE;
$rs = mysql_query("SELECT * FROM $_SESS_TABLE WHERE `sess_id`='$sess_id'",
$_SESS_DB);
$row = mysql_fetch_array($rs,MYSQL_ASSOC);
if ($row) {
return $row['sess_data'];
}else {
return '';
}
}
function writeSession($sess_id,$sess_data) {
global $_SESS_DB;
global $_SESS_TABLE;
$sess_data = mysql_real_escape_string($sess_data,$_SESS_DB);
$now = time();
$rs = mysql_query(
"INSERT INTO $_SESS_TABLE VALUES ('$sess_id', '$sess_data', '$now')",
$_SESS_DB);
return $rs;
}
function destroySession($sess_id) {
global $_SESS_DB;
global $_SESS_TABLE;
$rs = mysql_query("DELETE FROM $_SESS_TABLE WHERE `sess_id`='$sess_id'",
$_SESS_DB);
return $rs;
}
function cleanSession($max_life) {
global $_SESS_DB;
global $_SESS_TABLE;
$sess_access = time() -$max_life;
$rs = mysql_query("DELETE FROM $_SESS_TABLE WHERE `sess_access` < '$sess_access'",
$_SESS_DB);
return $rs;
}
session_set_save_handler(
'openSession',
'closeSession',
'readSession',
'writeSession',
'destroySession',
'cleanSession'
);