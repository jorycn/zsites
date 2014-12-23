<?php

if (!defined('IN_CONTEXT')) die('access violation error!');
if (!defined('CACHE_DIR')) {
$cache_path = dirname('/home/www1/record.php').'/cache';
if (!file_exists($cache_path) &&!@mkdir($cache_path,0777)) {
trigger_error('record table cache dir cannot be created. '
.'thus record table cache will not be available!',
E_USER_WARNING);
}
define('CACHE_DIR',$cache_path);
}
if (!defined('REL_BOTH')) define ('REL_BOTH',610);
if (!defined('REL_CHILDREN')) define('REL_CHILDREN',611);
if (!defined('REL_PARENT')) define('REL_PARENT',612);
class RecordTable {
public $fields = array();
public $pkeys = array();
public $aikey;
public function __construct($table_name) {
$db =&MysqlConnection::get();
try {
$rs =&$db->query("DESCRIBE `$table_name`");
while ($field =&$rs->fetchObject()) {
$this->fields[] = $field;
if (strpos(strtolower($field->Key),'pri') !== false) {
$this->pkeys[] = $field->Field;
}
if (strpos(strtolower($field->Extra),'auto_increment') !== false) {
$this->aikey = $field->Field;
}
}
$rs->free();
$this->_cacheRecordTable($table_name);
}catch (MysqlException $ex) {
throw new RecordException($ex->getMessage());
}
}
private function _cacheRecordTable($table_name) {
$record_table_cache_file = CACHE_DIR.'/'.$table_name.'.cache';
$fp = @fopen($record_table_cache_file,'w');
@fwrite($fp,serialize($this));
@fclose($fp);
}
}
class RecordObject {
protected $_class_name;
protected $_table_name;
protected $_table;
protected $_stat_new;
public $has_one = array();
public $has_many = array();
public $belong_to = array();
public $belong_to_many = array();
public $masters = array();
public $slaves = array();
protected $yes_validate = array();
protected $no_validate = array();
public function __construct($aikey_val = false,$_stat_new = true) {
$this->_class_name = get_class($this);
if ($this->_table_name == null) {
$this->_table_name = SITEPREFIX.Toolkit::pluralize(
Toolkit::transformClassName($this->_class_name));
}
try {
$this->_table =&$this->_getRecordTable($this->_table_name);
$this->_stat_new = $_stat_new;
if ($aikey_val !== false) {
if (empty($this->_table->aikey)) {
throw new RecordException('No auto_increment key defined in table!'."\n");
}else {
$sql = "SELECT * FROM `{$this->_table_name}` "
."WHERE `{$this->_table->aikey}`=?";
$db =&MysqlConnection::get();
$rs =&$db->query($sql,array($aikey_val));
$row =&$rs->fetchRow();
if ($row !== false) {
$this->set($row);
$this->_stat_new = false;
}
$rs->free();
}
}
}catch (RecordException $ex) {
throw new RecordException($ex->getMessage());
}catch (MysqlException $ex) {
throw new RecordException('Failed loading record!'."\n"
.$ex->getMessage());
}
}
protected function &_getRecordTable($table_name) {
$record_table_cache_file = CACHE_DIR.'/'.$table_name.'.cache';
$record_table = false;
try {
if (file_exists($record_table_cache_file)) {
$fp = @fopen($record_table_cache_file,'r');
$record_table_str = @fread($fp,filesize($record_table_cache_file));
@fclose($fp);
$record_table = unserialize($record_table_str);
}else {
$record_table = new RecordTable($table_name);
}
return $record_table;
}catch (RecordException $ex) {
throw new RecordException('Failed getting record table!'."\n"
.$ex->getMessage());
}
}
public function &find($where = false,$params = false,$more_sql = false) {
$sql = "SELECT * FROM `{$this->_table_name}`";
if ($where !== false) {
$sql .= " WHERE $where";
}
if ($more_sql !== false) {
$sql .= " $more_sql";
}
$db =&MysqlConnection::get();
try {
$rs =&$db->query($sql,$params);
$object =&$rs->fetchObject($this->_class_name,
array(false,false));
$rs->free();
return $object;
}catch (MysqlException $ex) {
throw new RecordException('Failed loading records!'."\n"
.$ex->getMessage());
}
}
public function &findAll($where = false,$params = false,$more_sql = false) {
$sql = "SELECT DISTINCT * FROM `{$this->_table_name}`";
if ($where !== false) {
$sql .= " WHERE $where";
}
if ($more_sql !== false) {
$sql .= " $more_sql";
}
$result = $this->cacheStrategy($sql,$params);
if(!empty($result))
{
if($result == 'empty')
{
return null;
}
if($result != 'notmatch')
{
return $result;
}
}
$db =&MysqlConnection::get();
try {
$rs =&$db->query($sql,$params);
$objects =&$rs->fetchObjects($this->_class_name,
array(false,false));
$rs->free();
return $objects;
}catch (MysqlException $ex) {
throw new RecordException('Failed loading records!'."\n"
.$ex->getMessage());
}
}
public function &findBy($mix_field,$mix_value,$more_sql = false) {
if (is_array($mix_field) &&!is_array($mix_value)) {
throw new RecordException('Parameter type not match!'."\n");
}
$where = "";
$params = array();
if (is_array($mix_field)) {
for ($i = 0;$i <sizeof($mix_field);$i++) {
$where .= " AND {$mix_field[$i]}=?";
}
$where = substr($where,4);
$params = $mix_value;
}else {
$where = " $mix_field=?";
$params = array($mix_value);
}
try {
$objects =&$this->findAll($where,$params,$more_sql);
return $objects;
}catch (RecordException $ex) {
throw new RecordException($ex->getMessage());
}
}
public function count($where = false,$params = false) {
$sql = "SELECT COUNT(*) FROM `{$this->_table_name}`";
if ($where !== false) {
$sql .= " WHERE $where";
}
$result = $this->cacheStrategy($sql,$params);
if(!empty($result))
{
if($result == 'empty')
{
return null;
}
if($result != 'notmatch')
{
return $result;
}
}
$db =&MysqlConnection::get();
try {
$rs =&$db->query($sql,$params);
$row =&$rs->fetchRow(RSTYPE_NUM);
$rs->free();
return $row[0];
}catch (MysqlException $ex) {
throw new RecordException('Failed loading data!'."\n"
.$ex->getMessage());
}
}
protected function _insert($get_insert_id = '') {
$fields = "";
$value_place_holders = "";
$values = array();
foreach ($this->_table->fields as $field) {
if ($field->Field != $this->_table->aikey) {
$attr_name = $field->Field;
$fields .= ", `$attr_name`";
$value_place_holders .= ", ?";
$values[] = $this->$attr_name;
}
}
$fields = substr($fields,2);
$value_place_holders = substr($value_place_holders,2);
$sql = "INSERT INTO `{$this->_table_name}` ($fields) VALUES "
."($value_place_holders)";
$db =&MysqlConnection::get();
try {
$rs = $db->query($sql,$values);
if ($rs) {
if (!empty($this->_table->aikey)) {
$insert_id = $db->getInsertId();
$ai_attr_name = $this->_table->aikey;
$this->$ai_attr_name = $insert_id;
}
$this->_stat_new = false;
}
return !empty($get_insert_id) ?$this->$ai_attr_name : $rs;
}catch (MysqlException $ex) {
throw new RecordException('Failed executing(insert) update!'.$sql."\n"
.$ex->getMessage());
}
}
protected function _update() {
$rs = false;
if (empty($this->_table->aikey)) {
throw new RecordException('AUTO_INCREMENT key not defined in table. '
.'No record updated!'."\n");
}else {
$set_fields = "";
$values = array();
foreach ($this->_table->fields as $field) {
if ($field->Field != $this->_table->aikey) {
$attr_name = $field->Field;
$set_fields .= ", `$attr_name`=?";
$values[] = $this->$attr_name;
}
}
$set_fields = substr($set_fields,2);
$ai_attr_name = $this->_table->aikey;
$where = "`$ai_attr_name`=?";
$values[] = $this->$ai_attr_name;
$sql = "UPDATE `{$this->_table_name}` SET $set_fields "
."WHERE $where";
$db =&MysqlConnection::get();
try {
$rs = $db->query($sql,$values);
return $rs;
}catch (MysqlException $ex) {
throw new RecordException('Failed executing(update) update2!'."\n"
.$ex->getMessage());
}
}
}
public function save($get_insert_id = '') {
$error_msg = '';
if (sizeof($this->yes_validate) >= 1) {
foreach ($this->yes_validate as $validate =>$attr_array) {
if (sizeof($attr_array) == 0) {
continue;
}
if ($validate == '_regexp_') {
foreach ($attr_array as $attr) {
$attr_name = $attr[1];
if (!DataValidator::customMatch($attr[0],$this->$attr_name)) {
$error_msg .= $attr[2]."\n";
}
}
}else {
foreach ($attr_array as $attr) {
$attr_name = $attr[0];
if (!DataValidator::$validate($this->$attr_name)) {
$error_msg .= $attr[1]."\n";
}
}
}
}
}
if (sizeof($this->no_validate) >= 1) {
foreach ($this->no_validate as $validate =>$attr_array) {
if (sizeof($attr_array) == 0) {
continue;
}
if ($validate == '_regexp_') {
foreach ($attr_array as $attr) {
$attr_name = $attr[1];
if (DataValidator::customMatch($attr[0],$this->$attr_name)) {
$error_msg .= $attr[2]."\n";
}
}
}else {
foreach ($attr_array as $attr) {
$attr_name = $attr[0];
if (DataValidator::$validate($this->$attr_name)) {
$error_msg .= __($attr[1])."\n";
}
}
}
}
}
if (!empty($error_msg)) {
throw new RecordException($error_msg);
}
try {
if ($this->_stat_new) {
return $this->_insert($get_insert_id);
}else {
return $this->_update();
}
}catch (RecordException $ex) {
throw new RecordException($ex->getMessage());
}
}
public function delete() {
$pkey_size = sizeof($this->_table->pkeys);
$rs = false;
if (empty($this->_table->aikey) &&$pkey_size == 0) {
throw new RecordException('Neither auto_increment key nor '
.'primary key defined in table. '
.'No record deleted!'."\n");
}else {
if (!$this->_stat_new) {
$where = "";
$params = array();
if (!empty($this->_table->aikey)) {
$attr_name = $this->_table->aikey;
$where .= " AND `$attr_name`=?";
$params[] = $this->$attr_name;
}
if ($pkey_size >= 1) {
for ($i = 0;$i <$pkey_size;$i++) {
$attr_name = $this->_table->pkeys[$i];
$where .= " AND `$attr_name`=?";
$params[] = $this->$attr_name;
}
}
$where = substr($where,4);
$sql = "DELETE FROM `{$this->_table_name}` WHERE $where";
$db =&MysqlConnection::get();
try {
$rs = $db->query($sql,$params);
return $rs;
}catch (MysqlException $ex) {
throw new RecordException('Failed executing(delete) update3!'."\n"
.$ex->getMessage());
}
}
}
}
public function set($params) {
if ($this->_stat_new) {
foreach ($this->_table->fields as $field) {
$attr_name = $field->Field;
$this->$attr_name = isset($params[$attr_name])?$params[$attr_name]:'';
}
}else {
foreach ($params as $attr_name =>$value) {
$this->$attr_name = $value;
}
}
}
public function loadRelatedObjects($direct = REL_BOTH,$target_objs = array()) {
if ($direct != REL_BOTH &&$direct != REL_PARENT &&
$direct != REL_CHILDREN) {
$direct = REL_BOTH;
}
$db =&MysqlConnection::get();
try {
if ($direct == REL_BOTH ||$direct == REL_CHILDREN) {
if (sizeof($this->has_one) >= 1) {
foreach ($this->has_one as $class_name) {
if (!empty($target_objs) &&
!in_array($class_name,$target_objs)) {
continue;
}
$object = new $class_name();
if (in_array($this->_class_name,$object->belong_to)) {
$t_class_name =
Toolkit::transformClassName($this->_class_name);
$ai_attr_name = $this->_table->aikey;
$this->slaves[$class_name] =&
$object->find("{$t_class_name}_id=?",
array($this->$ai_attr_name));
}else {
throw new RecordException('Broken relation: '.$this->_class_name
.' has_one '.$class_name.'!'."\n");
}
unset($object);
}
}
if (sizeof($this->has_many) >= 1) {
foreach ($this->has_many as $class_name) {
if (!empty($target_objs) &&
!in_array($class_name,$target_objs)) {
continue;
}
$object = new $class_name();
if (in_array($this->_class_name,$object->belong_to)) {
$t_class_name =
Toolkit::transformClassName($this->_class_name);
$ai_attr_name = $this->_table->aikey;
$this->slaves[$class_name] =&
$object->findAll("{$t_class_name}_id=?",
array($this->$ai_attr_name));
}else if (in_array($this->_class_name,
$object->belong_to_many)) {
$t_class_name =
Toolkit::transformClassName($this->_class_name);
$t_class_name_s =
Toolkit::transformClassName($class_name);
$ai_attr_name = $this->_table->aikey;
$ai_attr_name_s = $object->_table->aikey;
$table_name_s = $object->_table_name;
$table_name_r = $t_class_name.'_'.$t_class_name_s;
$id_r = $t_class_name.'_id';
$id_r_s = $t_class_name_s.'_id';
$sql = "SELECT `$table_name_s`.* FROM `$table_name_s`, "
."`$table_name_r` WHERE "
."`$table_name_s`.`$ai_attr_name_s`=`$table_name_r`.`$id_r_s` "
."AND `$table_name_r`.`$id_r`=?";
$rs =&$db->query($sql,array($this->$ai_attr_name));
$this->slaves[$class_name] =&
$rs->fetchObjects($class_name,
array(false,false));
$rs->free();
unset($rs);
}else {
throw new RecordException('Broken relation: '.$this->_class_name
.' has_many '.$class_name.'!'."\n");
}
unset($object);
}
}
}
if ($direct == REL_BOTH ||$direct == REL_PARENT) {
if (sizeof($this->belong_to) >= 1) {
foreach ($this->belong_to as $class_name) {
if (!empty($target_objs) &&
!in_array($class_name,$target_objs)) {
continue;
}
$object = new $class_name();
if (in_array($this->_class_name,$object->has_one) ||
in_array($this->_class_name,$object->has_many)) {
$t_class_name_m =
Toolkit::transformClassName($class_name);
$id_r_m = $t_class_name_m.'_id';
$ai_attr_name_m = $object->_table->aikey;
$this->masters[$class_name] =&
$object->find("$ai_attr_name_m=?",
array($this->$id_r_m));
}else {
throw new RecordException('Broken relation: '.$this->_class_name
.' belong_to '.$class_name.'!'."\n");
}
unset($object);
}
}
if (sizeof($this->belong_to_many) >= 1) {
foreach ($this->belong_to_many as $class_name) {
if (!empty($target_objs) &&
!in_array($class_name,$target_objs)) {
continue;
}
$object = new $class_name();
if (in_array($this->_class_name,$object->has_many)) {
$t_class_name =
Toolkit::transformClassName($this->_class_name);
$t_class_name_m =
Toolkit::transformClassName($class_name);
$ai_attr_name = $this->_table->aikey;
$ai_attr_name_m = $object->_table->aikey;
$table_name_m = $object->_table_name;
$table_name_r = $t_class_name_m.'_'.$t_class_name;
$id_r = $t_class_name.'_id';
$id_r_m = $t_class_name_m.'_id';
$sql = "SELECT `$table_name_m`.* FROM `$table_name_m`, "
."`$table_name_r` WHERE "
."`$table_name_m`.`$ai_attr_name_m`=`$table_name_r`.`$id_r_m` "
."AND `$table_name_r`.`$id_r`=?";
$rs =&$db->query($sql,array($this->$ai_attr_name));
$this->masters[$class_name] =&
$rs->fetchObjects($class_name,
array(false,false));
$rs->free();
unset($rs);
}else {
throw new RecordException('Broken relation: '.$this->_class_name
.' belong_to_many '.$class_name.'!'."\n");
}
unset($object);
}
}
}
}catch (RecordException $ex) {
throw new RecordException($ex->getMessage());
}catch (MysqlException $ex) {
throw new RecordException('Failed loading records!'."\n"
.$ex->getMessage());
}
}
public function hasChildren($object_name,$where = false,$params = false) {
}
public function deleteChildren($object_name,$where = false,$params = false) {
}
public function deleteAllChildren() {
}
public static function cacheStrategy($param1,$param2) {
}
}
class RecordException extends Exception {
}