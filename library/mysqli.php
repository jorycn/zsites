<?php

if (!defined('IN_CONTEXT')) die('access violation error!');
define('RSTYPE_ASSOC',MYSQLI_ASSOC);
define('RSTYPE_NUM',MYSQLI_NUM);
define('RSTYPE_BOTH',MYSQLI_BOTH);
$GLOBALS['_DB_CONNECTION'] = null;
class MysqlConnection {
private $_db_ref;
public $debug;
public function __construct($db_host,$db_user,$db_pwd,$db_name) {
$this->_db_ref = @mysqli_connect($db_host,$db_user,$db_pwd,$db_name,Config::$port);
if (!$this->_db_ref) {
die('database server '.$db_host.' connect error!<br />'
.mysqli_error());
}
@mysqli_set_charset($this->_db_ref,Config::$mysqli_charset);
$select_db_rs = @mysqli_select_db($this->_db_ref,$db_name);
if (!$select_db_rs) {
$error = 'error '.mysqli_errno($this->_db_ref).': '
.mysqli_error($this->_db_ref);
die($error);
}
$this->debug = false;
global $_DB_CONNECTION;
$_DB_CONNECTION = $this;
}
public static function &get() {
global $_DB_CONNECTION;
if ($_DB_CONNECTION == null) {
die('record database connection not set!');
}
return $_DB_CONNECTION;
}
private function _getParamHolder() {
return '?';
}
private function _rebuildSql($sql,&$params) {
if (!$params) {
return $sql;
}else {
$sql_part = explode($this->_getParamHolder(),$sql);
$sql = $sql_part[0];
for ($i = 1;$i <sizeof($sql_part);$i++) {
$sql .= "'"
.mysqli_real_escape_string($this->_db_ref,$params[$i -1])
."'".$sql_part[$i];
}
return $sql;
}
}
public function &query($sql,$params = false)
{
$error = '';
$sql = $this->_rebuildSql($sql,$params);
if ($this->debug === true) {
echo $sql."\n========\n";
}
if(Memorycache::$clear_flag != true) Memorycache::UpdateMemTable($sql);
mysqli_ping($this->_db_ref);
$rs = @mysqli_query($this->_db_ref,$sql);
if (!$rs) {
if ($this->debug === true) {
$error = 'error '.mysqli_errno($this->_db_ref).': '
.mysqli_error($this->_db_ref)."\n";
}
$error .= 'sql execution failed!'."\n";
throw new MysqlException($error);
}
if (is_object($rs)) {
$mysql_rs = new MysqlRecordset($rs);
Memorycache::$clear_flag = false;
return $mysql_rs;
}else {
Memorycache::$clear_flag = false;
return $rs;
}
}
public function getInsertId() {
return @mysqli_insert_id($this->_db_ref);
}
public function close() {
@mysqli_close($this->_db_ref);
}
}
class MysqlRecordset {
private $_rs;
public function __construct(&$rs) {
$this->_rs = $rs;
}
public function &fetchRows($rs_type = RSTYPE_ASSOC) {
$rows = array();
while ($row = @mysqli_fetch_array($this->_rs,$rs_type)) {
$rows[] = $row;
}
return $rows;
}
public function &fetchRow($rs_type = RSTYPE_ASSOC) {
$row = @mysqli_fetch_array($this->_rs,$rs_type);
return $row;
}
public function &fetchObjects($class_name = false,$params = false) {
$objects = array();
if (!$class_name) {
while ($object = @mysqli_fetch_object($this->_rs)) {
$objects[] = $object;
}
}else {
if (!$params) {
while ($object = @mysqli_fetch_object($this->_rs,
$class_name)) {
$objects[] = $object;
}
}else {
while ($object = @mysqli_fetch_object($this->_rs,
$class_name,$params)) {
$objects[] = $object;
}
}
}
return $objects;
}
public function &fetchObject($class_name = false,$params = false) {
if (!$class_name) {
$object = @mysqli_fetch_object($this->_rs);
}else {
if (!$params) {
$object = @mysqli_fetch_object($this->_rs,$class_name);
}else {
$object = @mysqli_fetch_object($this->_rs,
$class_name,$params);
}
}
return $object;
}
public function getRecordNum() {
return @mysqli_num_rows($this->_rs);
}
public function free() {
@mysqli_free_result($this->_rs);
}
public function reset() {
@mysqli_data_seek($this->_rs,0);
}
}
class MysqlException extends Exception {
}