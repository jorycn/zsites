<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
class ACL {
public static function loginUser($username,$password,$tag = '') {
if (strlen(trim($username)) == 0 ||
strlen(trim($password)) == 0) {
return false;
}
$password = sha1($password);
$o_user = new User();
$n_user = $o_user->count("login=? AND passwd=?",
array($username,$password));
if ($n_user == 1) {
$curr_user =&$o_user->find("login=? AND passwd=?",
array($username,$password));
if (ACL::isRoleAdmin($curr_user->s_role) &&!empty($tag) ) {
SessionHolder::set('role',$curr_user->s_role);
}else {
SessionHolder::set('user/id',$curr_user->id);
SessionHolder::set('user/login',$curr_user->login);
SessionHolder::set('user/passwd',$curr_user->passwd);
SessionHolder::set('user/s_role',$curr_user->s_role);
SessionHolder::set('user/email',$curr_user->email);
SessionHolder::set('user/lastlog_time',$curr_user->lastlog_time);
SessionHolder::set('user/lastlog_ip',$curr_user->lastlog_ip);
SessionHolder::set('user/member_verify',$curr_user->member_verify);
SessionHolder::set('user/active',$curr_user->active);
if (ACL::isRoleAdmin($curr_user->s_role)) {
SessionHolder::set('page/status','edit');
}else {
SessionHolder::set('page/status','view');
}
$curr_user->lastlog_time = time();
$curr_user->lastlog_ip = $_SERVER['REMOTE_ADDR'];
@$curr_user->save();
}
return true;
}else {
return false;
}
}
public static function loginGuest() {
if (!SessionHolder::has('user')) {
SessionHolder::set('user/id',1);
SessionHolder::set('user/login','guest');
SessionHolder::set('user/passwd','305e67fb4048f3119c8a9136a14b56ebc51465ff');
SessionHolder::set('user/s_role','{guest}');
SessionHolder::set('user/lastlog_time','0');
SessionHolder::set('user/lastlog_ip','0.0.0.0');
SessionHolder::set('page/status','view');
}
}
public static function checkLogin() {
if (!SessionHolder::has('user')) {
return false;
}
if (SessionHolder::get('user/login','guest') == 'guest'||
SessionHolder::get('user/passwd','tseug') == 'tseug') {
return false;
}
$o_user = new User();
$n_user = $o_user->count("login=? AND passwd=? AND active='1'",
array(SessionHolder::get('user/login'),
SessionHolder::get('user/passwd')));
if ($n_user == 1) {
$curr_user =&$o_user->find("login=? AND passwd=? AND active='1'",
array(SessionHolder::get('user/login'),
SessionHolder::get('user/passwd')));
SessionHolder::set('user/id',$curr_user->id);
SessionHolder::set('user/login',$curr_user->login);
SessionHolder::set('user/passwd',$curr_user->passwd);
SessionHolder::set('user/s_role',$curr_user->s_role);
return true;
}else {
SessionHolder::destroy();
return false;
}
}
public static function requireRoles($arr_roles = array('guest')) {
$user_role = trim(SessionHolder::get('user/s_role','{guest}'));
foreach ($arr_roles as $role) {
if($role=='admin'){
if(self::isRoleAdmin($user_role)) return true;
}
elseif ($user_role == '{'.$role.'}') {
return true;
}
}
return false;
}
public static function isRoleSuperAdmin($rolename=null) {
if(!isset($rolename)){
$rolename=trim(SessionHolder::get('user/s_role','{guest}'));
}
if ($rolename == '{admin}') {
return true;
}
return false;
}
public static function isRoleAdmin($rolename=null) {
if(!isset($rolename)){
$rolename=trim(SessionHolder::get('user/s_role','{guest}'));
}
if ($rolename != '{guest}'&&$rolename != '{member}') {
return true;
}
return false;
}
public static function explainAccess($is_member_only) {
$accessible_roles = '{member}{admin}';
if (!$is_member_only) {
$accessible_roles .= '{guest}';
}
return $accessible_roles;
}
public static function isMemOnly($accessible_roles) {
if (!$accessible_roles) {
return 0;
}else if (strpos($accessible_roles,'{guest}') === false) {
return 1;
}else {
return 0;
}
}
public static function isAdminActionHasPermission($module=R_MOD,$action=R_ACT) {
$user_role = trim(SessionHolder::get('user/s_role','{guest}'));
if($user_role=='{admin}') return true;
if($user_role=='{guest}'||$user_role=='{member}') return false;
$permissions=self::getUserPermission();
return Role::isActionPermission($module,$action,$permissions);
}
public static function getUserPermission() {
if(!isset($GLOBALS['_user_permissions'])){
$user_role = trim(SessionHolder::get('user/s_role','{guest}'));
$permissions=Role::getRolePermission($user_role);
$GLOBALS['_user_permissions']=$permissions;
}
return  $GLOBALS['_user_permissions'];
}
}