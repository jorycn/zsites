<?php // 
if (!defined('IN_CONTEXT')) die('access violation error!');
$GLOBALS['_NOTICE_HOLDER'] = null;
class Notice {
public static function set($key_path,$value) {
global $_SESSION_HOST_NAME;
ParamParser::assign($_SESSION[$_SESSION_HOST_NAME]['_NOTICE'],
$key_path,$value);
}
public static function dump() {
global $_SESSION_HOST_NAME;
if(isset($_SESSION[$_SESSION_HOST_NAME]['_NOTICE'])){
$GLOBALS['_NOTICE_HOLDER'] =
$_SESSION[$_SESSION_HOST_NAME]['_NOTICE'];
}else{
$GLOBALS['_NOTICE_HOLDER']='';
}
$_SESSION[$_SESSION_HOST_NAME]['_NOTICE'] = null;
}
public static function &get($key_path,$default = false) {
global $_NOTICE_HOLDER;
$rs =&ParamParser::retrive($_NOTICE_HOLDER,
$key_path,$default);
return $rs;
}
}