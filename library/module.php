<?php

if (!defined('IN_CONTEXT')) die('access violation error!');
if (!defined('NO_LAYOUT')) define('NO_LAYOUT',620);
if (!defined('DFT_LAYOUT')) define('DFT_LAYOUT',621);
class Module {
protected $_filters = array();
protected $_view_vars = array();
protected $_layout = DFT_LAYOUT;
protected function _applyFilters($action) {
if (sizeof($this->_filters) >0) {
foreach ($this->_filters as $filter_name =>$exceptions) {
if (strpos($exceptions,'{'.$action.'}') === false) {
if (file_exists(P_FLT.'/'.$filter_name.'.php')) {
include_once(P_FLT.'/'.$filter_name.'.php');
}else {
die("3".PAGE_404);
Content::redirect(PAGE_404);
}
$filter_name_part = explode('_',$filter_name);
$filter_class_name = '';
foreach ($filter_name_part as $name_part) {
$filter_class_name .= ucfirst($name_part);
}
$filter_class = new $filter_class_name();
if (!$filter_class->execute()) {
return false;
}
}
}
}
if(!Toolkit::md5Filter()) {
return false;
}
return true;
}
public function execute($_action,$_use_layout = true) {
if (!$this->_applyFilters($_action) &&R_TPE == '_page') {
exit(1);
}
$_view = $this->$_action();
$o_site =&SessionHolder::get('_SITE',false);
if ($o_site) {
$this->setVar('_SITE',$o_site);
}
if (sizeof($this->_view_vars) >0) {
foreach ($this->_view_vars as $var =>$value) {
$$var = $value;
}
}
$_module_class_name = get_class($this);
$_flat_module_class_name =
Toolkit::transformClassName($_module_class_name);
$_flat_module = strtolower(ParamHolder::get('_m'));
if(strpos($_SERVER['PHP_SELF'],'/admin/') === false)
{
if ($_view) {
$_view_file = P_TPL_VIEW.'/view/'.$_flat_module_class_name.'/'.$_view.'.php';
}else {
$_view_file = P_TPL_VIEW.'/view/'.$_flat_module_class_name.'/'.$_action.'.php';
}
}
else 
{
if ($_view) {
$_view_file = P_TPL.'/view/'.$_flat_module_class_name.'/'.$_view.'.php';
}else {
$_view_file = P_TPL.'/view/'.$_flat_module_class_name.'/'.$_action.'.php';
}
}
if (!file_exists($_view_file)) {
die("4".$_view_file);
Content::redirect(PAGE_404);
}
if (!$_use_layout) {
$this->_layout = NO_LAYOUT;
}
if(check_mod($_flat_module_class_name)) {
if ($this->_layout == NO_LAYOUT) {
include($_view_file);
}else {
if (preg_match('/^admin_/',$_action)) {
if (file_exists(P_TPL.'/layout/admin_'.$_flat_module_class_name.'.php')) {
$_layout_file = P_TPL.'/layout/admin_'.$_flat_module_class_name.'.php';
}else {
$_layout_file = P_TPL.'/layout/'.$this->_layout.'.php';
}
}else {
if(file_exists(P_TPL.'/layout/layout.php')) 
{
if(!(strpos('mod_user|mod_offline|mod_tool|mod_navigation|mod_media',R_MOD) ===  false) &&!in_array(R_ACT,array('reg_form','edit_profile')))
{
$_layout_file = ROOT."/view/layout/only_content.php";
}
else
{
if(!empty($_GET))
{
if(empty($_GET['_v']))
{
$url='';
foreach($_GET as $k =>$v)
{
$url .= $k.'='.$v.'&';
}
$url = substr($url,0,strlen($url)-1);
}
else
{
$url = '_m=frontpage&_a=index';
}
}
else
{
$url = '_m=frontpage&_a=index';
}
$menu_item = new MenuItem();
$result = $menu_item->find("link='$url' AND s_locale = '".trim(SessionHolder::get('_LOCALE'))."'");
include_once(ROOT.'/template/'.DEFAULT_TPL.'/layout/conf.php');
$layout_param = LayouConfig::$layout_param;
if(empty($result) ||$result->layout == 'default'||empty($result->layout))
{
$_layout_file = P_TPL."/layout/{$layout_param['default']['layout_php_file']}";
}
else
{
$_layout_file = P_TPL."/layout/{$layout_param[$result->layout]['layout_php_file']}";
}
}
}
elseif (file_exists(P_TPL.'/layout/'.$_flat_module_class_name.'.php')) 
{
$_layout_file = P_TPL.'/layout/'.$_flat_module_class_name.'.php';
}
else
{
$_layout_file = P_TPL.'/layout/'.$this->_layout.'.php';
}
}
if (!file_exists($_layout_file)) {
die("5".$_layout_file);
Content::redirect(PAGE_404);
}
$_content_ = $_view_file;
include_once($_layout_file);
}
}else {
die('Module not supported!');
}
}
protected function setVar($key,$value) {
$this->_view_vars[$key] = $value;
}
protected function assign($key,$value) {
$this->setVar($key,$value);
}
}