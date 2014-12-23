<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
class ModAuth extends Module {
protected $_filters = array(
'check_login'=>'{loginform}{loginregform}{dologin}{dologout}'
);
public function loginform() {
$this->_layout = 'frontpage';
$this->assign('page_title',__('Login'));
if (SessionHolder::get('user/s_role','{guest}') != '{guest}') {
$this->userinfo();
return 'userinfo';
}else {
$forward_url = ParamHolder::get('_f','');
if (strlen(trim($forward_url)) == 0) {
$forward_url = 'index.php';
}
$this->setVar('forward_url',$forward_url);
}
}
public function loginregform() {
$this->_layout = 'frontpage';
$this->assign('page_title',__('Login'));
$forward_url = ParamHolder::get('_f','');
$goto =&SessionHolder::get('goto');
if ((MOD_REWRITE == 2) &&!empty($goto)) {
$forward_url = $goto;
SessionHolder::set('goto','');
}
if (strlen(trim($forward_url)) == 0) {
$forward_url = 'index.php';
}
$this->setVar('forward_url',$forward_url);
}
public function userinfo() {
$curr_user = new User(SessionHolder::get('user/id','0'));
$this->setVar('curr_user',$curr_user);
}
public function dologin() {
$user=ParamHolder::get('login_user','');
$pwd=ParamHolder::get('login_pwd','');
if (UC_OPEN>1){
list($uid,$username,$password,$email) = uc_user_login($user,$pwd);
if($uid >0) {
$sql = "select count(*) from ".THISSITE."_users where login='".$user."'";
$que = mysql_query($sql);
$result = mysql_fetch_array($que);
if($result[0]==0){
$insql = "insert into ".THISSITE."_users (login,passwd,full_name,email,lastlog_time,lastlog_ip,rstpwdreq_time,rstpwdreq_rkey,active,s_role,wizard,mobile,member_verify) values ('".$user."','".sha1($pwd)."','".$user."','".$email."','1345108836','127.0.0.1','0','','1','{member}','0','13700516567','0')";
$inque = mysql_query($insql);
}else{
$insql = "update ".THISSITE."_users set passwd='".sha1($pwd)."' where login='".$user."'";
$inque = mysql_query($insql);
}
}
}
$captcha = ParamHolder::get('rand_rs') ?ParamHolder::get('rand_rs') : ParamHolder::get('rand_rs_reglogn');
if (!RandMath::checkResult($captcha)) {
$this->setVar('json',Toolkit::jsonERR(__('Sorry! Please have another try with the math!')));
return '_result';
}
if (ACL::loginUser($user,$pwd,'client')) {
if (ACL::isRoleAdmin()) {
$this->setVar('json',Toolkit::jsonERR(__('Administrator prohibit login!')));
}else if(MEMBER_VERIFY=='1'&&SessionHolder::get('user/member_verify')!='1'){
SessionHolder::destroy();
$this->setVar('json',Toolkit::jsonERR(__('being reviewed')));
}else if(SessionHolder::get('user/active')!='1'){
SessionHolder::destroy();
$this->setVar('json',Toolkit::jsonERR(__('This account was prohibited from login, please contact the administrator.')));
}else{
$forward_url = ParamHolder::get('_f','');
if (strlen(trim($forward_url)) == 0) {
$forward_url = 'index.php';
}
$this->setVar('json',Toolkit::jsonOK(array('forward'=>$forward_url)));
}
}else {
$this->setVar('json',Toolkit::jsonERR(__('Username and password mismatch!')));
}
return '_result';
}
public function dologout() {
SessionHolder::destroy();
Content::redirect('index.php');
}
}