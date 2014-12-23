<?php

if (!defined('IN_CONTEXT')) die('access violation error!');
class Toolkit {
public static function transformClassName($class_name) {
$t_class_name = '';
$up_case_idx =&self::_getUpCaseIdx($class_name);
if (sizeof($up_case_idx) == 0) {
$t_class_name = $class_name;
}else {
$start_idx = 0;
for($i = 0;$i <sizeof($up_case_idx);$i++) {
$t_class_name .= '_'.substr($class_name,
$start_idx,$up_case_idx[$i] -$start_idx);
$start_idx = $up_case_idx[$i];
}
$t_class_name .= '_'.substr($class_name,
$start_idx);
$t_class_name = substr($t_class_name,1);
}
return strtolower($t_class_name);
}
private static function &_getUpCaseIdx($class_name) {
$up_case_idx = array();
for ($i = 1;$i <strlen($class_name);$i++) {
if (ord($class_name[$i]) >= 65 &&
ord($class_name[$i]) <= 90) {
$up_case_idx[] = $i;
}
}
return $up_case_idx;
}
public static function pluralize($noun) {
if (preg_match('/(s|sh|ch|x)$/i',$noun)) {
$pl_noun = $noun.'es';
}else if (preg_match('/y$/i',$noun)) {
if (preg_match('/([aeiou]y)$/i',$noun)) {
$pl_noun = $noun.'s';
}else {
$pl_noun = substr($noun,0,strlen($noun) -1).'ies';
}
}else {
$pl_noun = $noun.'s';
}
return $pl_noun;
}
public static function switchText($value,$arr_text) {
if(empty($value)){
$value=0;
}
return $arr_text[$value];
}
public static function &toSelectArray(&$record_array,$value_field,
$text_field,$first_option = array(),$translate = false) {
$select_array = array();
if (sizeof($record_array) >0) {
if (sizeof($first_option) == 2) {
$select_array[$first_option[0]] = $first_option[1];
}
foreach ($record_array as $record) {
$select_array[$record->$value_field] = 
$translate?__($record->$text_field):$record->$text_field;
}
}
return $select_array;
}
public static function &toSwitchArray(&$record_array,$value_field,
$text_field) {
$switch_array = array();
if (sizeof($record_array) >0) {
foreach($record_array as $record) {
$switch_array[$record->$value_field] = $record->$text_field;
}
}
return $switch_array;
}
public static function &loadAllLangs() {
$o_language = new Language();
$all_langs =&$o_language->findAll();
return $all_langs;
}
public static function &loadAllRoles($ignore = array()) {
$where = false;
$params = false;
if (sizeof($ignore) >0) {
$ign_str = '';
$params = array();
foreach ($ignore as $ign_role) {
$ign_str .= ", ?";
$params[] = $ign_role;
}
$where = "name NOT IN (".substr($ign_str,2).")";
}
$o_role = new Role();
$all_roles =&$o_role->findAll($where,$params);
return $all_roles;
}
public static function &reformPositions() {
$positions = array();
foreach (TplInfo::$positions as $position) {
$positions[$position] = __($position);
}
return $positions;
}
function wrap_MB($str,$slen,$break) {
mb_internal_encoding(__('charset'));
$start = 0;
$length = mb_strlen($str);
$lines = array();
while ($start <= $length) {
$lines[] = mb_substr($str,$start,$slen);
$start += $slen;
}
return implode($break,$lines);
}
public static function substr_MB($str,$start,$length,$wrap = false,$len = 75,$break = "<br />\n") {
if(function_exists("mb_substr")){
mb_internal_encoding(__('charset'));
if (!$wrap) {
return mb_substr($str,$start,$length);
}else {
return self::wrap_MB(mb_substr($str,$start,$length),$len,$break);
}
}else {
$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
$re['gbk']          = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
$re['big5']          = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
preg_match_all($re["utf-8"],$str,$match);
if(count($match[0]) <= $length) return $str;
$slice = join("",array_slice($match[0],$start,$length));
return $slice;
}
}
public static function strlen_MB($str,$charset = 'UTF-8') {
if(function_exists("mb_substr")){
return mb_strlen($str,$charset);
}else {
$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
$re['gbk']          = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
$re['big5']          = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
preg_match_all($re["utf-8"],$str,$match);
return sizeof($match[0]);
}
}
public static function jsonOK($params = array()) {
$result = array();
$result['result'] = 'OK';
if (sizeof($params) >0) {
$result = array_merge($result,$params);
}
return json_encode($result);
}
public static function jsonERR($errmsg,$params = array()) {
$result = array();
$result['result'] = 'ERROR';
$result['errmsg'] = $errmsg;
if (sizeof($params) >0) {
$result = array_merge($result,$params);
}
return json_encode($result);
}
public static function randomStr($len = 6,$alphanum = true) {
$chars = 'abcdefghijklmnopqrstuvwxyz'
.'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
.'1234567890';
if (!$alphanum) {
$chars .= '~!@#$%^&*()_-`[]{}|";:,.<>/?';
}
$randstr = '';
if (!is_integer($len) ||$len <6) {
$len = 6;
}
for ($i = 0;$i <$len;$i++) {
$idx = mt_rand(0,strlen($chars) -1);
$randstr .= substr($chars,$idx,1);
}
return $randstr;
}
public static function mkdir_locale($locale) {
$dir_locale = P_LOCALE.'/'.$locale.'/LC_MESSAGES';
if (mkdir($dir_locale,0777,true)) {
return $dir_locale;
}else {
return false;
}
}
public static function mkdir_tpl($tpl) {
$dir_tpl = ROOT.'/template/'.$tpl;
if (mkdir($dir_tpl,0777,true)) {
return $dir_tpl;
}else {
return false;
}
}
public static function rmdir_locale($locale,$flag = '') {
$dir_locale = P_LOCALE.'/'.$locale;
if ($flag == 'front') $dir_locale = str_replace('admin/','',P_LOCALE).'/'.$locale;
return self::_rmdir_r($dir_locale);
}
public static function rmdir_template($template) {
$dir_template = ROOT.'/template/'.$template;
return self::_rmdir_r($dir_template);
}
public static function get_filename($name,$ex='.zip') {
return substr($name,0,-strlen($ex));
}
private static function _rmdir_r($root_dir) {
$files = scandir($root_dir);
foreach ($files as $file) {
if ($file == '.'||$file == '..') {
continue;
}
$f_path = $root_dir.'/'.$file;
if (is_dir($f_path)) {
if (!self::_rmdir_r($f_path)) {
return false;
}
}else {
if (!unlink($f_path)) {
return false;
}
}
}
if (!rmdir($root_dir)) {
return false;
}
return true;
}
public static function getDir($now_dir) {
$files = scandir($now_dir);
$ret_arr = array();
foreach ($files as $file) {
if ($file == '.'||$file == '..'||$file == '.svn') {
continue;
}
if(is_dir(ROOT.'/template/'.$file)) {
array_push($ret_arr,$file);
}else {
continue;
}
}
return $ret_arr;
}
public static function editMode() {
return SessionHolder::get('page/status','view') == 'edit';
}
public static function calcMQHash($query_str) {
if (empty($query_str)) {
$sort_query = '_m='.DEFAULT_MODULE.'&_a='.DEFAULT_ACTION;
return sha1($sort_query);
}
$q_params = array();
parse_str($query_str,$q_params);
if (!isset($q_params['_m']) ||strlen(trim($q_params['_m'])) == 0) {
$q_params['_m'] = DEFAULT_MODULE;
}
if (!isset($q_params['_a']) ||strlen(trim($q_params['_a'])) == 0) {
$q_params['_a'] = DEFAULT_ACTION;
}
$mqstr = '';
if($q_params['_m'] != 'mod_content')
{
include(P_INC.'/menus.php');
foreach ($menus as $key =>$menu) {
if (isset($menu['mod_addr']['mod_name']) ||isset($menu['mod_addr']['addr'])) {
$m_module = isset($menu['mod_addr']['mod_name'])?$menu['mod_addr']['mod_name']:DEFAULT_MODULE;
$m_action = isset($menu['mod_addr']['addr'])?$menu['mod_addr']['addr']:DEFAULT_ACTION;
if ($m_module == $q_params['_m'] &&
$m_action == $q_params['_a']) {
$mqstr = '_m='.$m_module.'&_a='.$m_action;
if ($menu['is_id'] &&isset($q_params[$menu['id_category']])) {
$mqstr .= '&'.$menu['id_category'].'='.$q_params[$menu['id_category']];
}
break;
}
}
}
}
else
{
return sha1('mod_content');
}
if (empty($mqstr)) {
return '_ALL';
}else {
return sha1($mqstr);
}
}
public static function fixpic($picture) {
$missing_img = 'images/missing_img.gif';
if (!file_exists(realpath($picture))) {
return $missing_img;
}else {
return $picture;
}
}
public static function &initSoapClient() {
$client = new SoapClient(APIURL.'/template.wsdl',
array(
'soap_version'=>SOAP_1_2,
'encoding'=>'UTF-8',
'cache_wsdl'=>WSDL_CACHE_NONE
));
return $client;
}
public static function check_referer() {
if (isset($_SERVER['HTTP_REFERER'])) {
$arr_referer = array();
preg_match('/^http:\/\/([^\/]+)/',$_SERVER['HTTP_REFERER'],$arr_referer);
if (isset($arr_referer[1])) {
if (trim($arr_referer[1]) == $_SERVER['HTTP_HOST']) {
return true;
}else {
return false;
}
}else {
return false;
}
}else {
return false;
}
}
public static function getNavDir($now_dir) {
$files = scandir($now_dir);
$ret_arr = array();
foreach ($files as $file) {
if ($file == '.'||$file == '..'||$file == '.svn') {
continue;
}
if(is_dir(ROOT.'/navigation/'.$file)) {
array_push($ret_arr,$file);
}else {
continue;
}
}
return $ret_arr;
}
public static function getDSName($ds_code) {
global $china_ds_data;
return $china_ds_data[$ds_code][0];
}
public static function removeDir($dirName)
{
if(!is_dir($dirName))
{
@unlink($dirName);
return false;
}
$handle = @opendir($dirName);
while(($file = @readdir($handle)) !== false)
{
if($file != '.'&&$file != '..')
{
$dir = $dirName .'/'.$file;
is_dir($dir) ?self::removeDir($dir) : @unlink($dir);
}
}
closedir($handle);
return @rmdir($dirName);
}
public static function validateYesOrNo($boolean = true,$id,$url,$needchange=true)
{
$clickstr="";
$id_str = substr($url,strrpos($url,"=")+1);
if($boolean)
{
if($needchange)  $clickstr='changePic(1,\''.$id_str.$id.'\',\''.$url.'\');';
return '<img id='.$id_str.$id.' src="template/images/yes.gif" alt="Yes" onclick="'.$clickstr.'"/>';
}
else
{
if($needchange)  $clickstr='changePic(0,\''.$id_str.$id.'\',\''.$url.'\');';
return '<img id='.$id_str.$id.' src="template/images/no.gif" alt="No" onclick="'.$clickstr.'"/>';
}
}
public static function getSubDomain($domain) {
if(strpos($domain,"www.") === false) {
return $domain;
}else{
return substr($domain,4);
}
}
public static function getCorp() {
return false;
}
public static function getAuthTpl() {
$http_host = self::getSubDomain($_SERVER['HTTP_HOST']);
if($http_host=='localhost'||$http_host=='127.0.0.1'){
return true;
}
$free_tpl = array("airconditioning_electrical_jackie_1216_2",'attachment_car_jackie_1214_2','bluemountain_common_jackie_0110_2','furniture_jackie_0909_2','health_jackie_1005_2','importfoods_food_jackie_1130_2','monitor_it_jackie_1125_2','niceshoot_photograph_jackie_1209_2','telecom_2','warmhome_furniture_jackie_1127_2','bigtruck_transport_jackie_1228_2','blackorwhite_furniture_jackie_1202_2','bluediamond_jewel_jackie_1123_2','booksell_book_jackie_1103_2','carservice_car_jackie_1218_2','chinastyle_jackie_0922_2','coffee_2','decorative_2','digit_it_jackie_1129_2','digit_jackie_0911_2','fitness_sports_jackie_1204_2','greengrape_wine_jackie_1213_2','greenpower_health_jackie_0129_2','happytime_sweet_jackie_1229_2','higheriq_toy_jackie_1223_2','hotred_food_jackie_0201_2','jackie_swim_2','jackie_wine_0722_bate_2','material_construction_jackie_1215_2','mystyle_computer_jackie_0108_2','mytravel_bag_jackie_1231_2','newintel_computer_jackie_0119_2','orangegray_2','pinklady_jewel_jackie_1208_2','printer_2','redstyle_mobile_jackie_1203_2','solar_jackie_1007_2','takeabath_baby_jackie_1211_2','travel_2','yourbag_cloth_jackie_1221_2');
if(in_array(DEFAULT_TPL,$free_tpl)) {
return true;
}
else 
{
if(file_exists(ROOT.'/licence.dat')) {
return true;
}
}
return false;
}
public static function getAgent() {
$key_value = ParamHolder::get('key');
if(empty($key_value) &&!isset($_SESSION['agent_key']) &&!file_exists(ROOT.'/licence.dat')) 
{
return true;
}
else if((isset($_SESSION['agent_key']) &&$_SESSION['agent_key']=="agent") ||$key_value=="agent")
{
return 'agent';
$_SESSION['agent_key'] = 'agent';
}
else 
{
if(file_exists(ROOT.'/licence.dat')) {
include(ROOT.'/licence.dat');
if(isset($l7) &&$l7 == "agent"){
return false;
}
}
}
return true;
}
public static function issitebaoheAuthorized() {
return !self::getCorp();
}
public static function md5Filter()           
{
$legal_key = array("954b955d2c6221e12654c7f0400004aa","71073490f9235baee7ef9401c618d2dc","c7696a53a40d4cb2ae99de6bf473591f","375f8d1a9faf388317a435780af8ad8d","dbba1cfbb7913daace64419e9d304332","ed42dde3818e9c7a0a5f3191fbcedd70",'e1e39e47918d138562bd45188f06c972','425f833158ce7c0eef482b09e128ef7f','36cf9684fdba5a4717cc347c7ab07ef2');
if(file_exists(ROOT.'/view/common/footer.php'))
{
$key = md5_file(ROOT.'/view/common/footer.php');
if(!in_array($key,$legal_key)) return true;
}
else
{
return false;
}
return true;
}
public static function checkLicenceTimely()
{
$notCheck = false;
$time = strtotime(date("Ymd"));
$key = '34f4dd3s4t5y@#r3343erf!';
$licence_check_sum = md5($time.$key);
$l8 = LICENCE_TIME;
$l9 = LICENCE_CHECK_SUM;
$o_param = new Parameter();
if(empty($l8))
{
$s_param1 = $o_param->find("`key`='LICENCE_TIME'");
$s_param1->val = $time;
$s_param1->save();
$s_param2 = $o_param->find("`key`='LICENCE_CHECK_SUM'");
$s_param2->val = $licence_check_sum;
$s_param2->save();
}
else
{
if(md5($l8.$key) != $l9)
{
ModuleBlock::getMyTemplate();
$s_param1 = $o_param->find("`key`='ERR_LOG'");
$s_param1->val = $s_param1->val.'|||tag=shangmian,l8='.$l8.',l9='.$l9.',nowtime='.date("Y-m-d H:i:s").'.';
$s_param1->save();
file_put_contents(ROOT.'/licence.dat','');
$notCheck = true;
}
}
if(file_exists(ROOT.'/licence.dat') &&!$notCheck)
{
include(ROOT.'/licence.dat');
$pattern = "^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)";
if(!preg_match("/$pattern/i",$l0))
{
$http_host = self::getSubDomain($_SERVER['HTTP_HOST']);
if(!empty($l0))
{
if($l0 == $http_host)
{
$isping = file_get_contents(APIURL.'1/baohe/isping/');
if($isping == 'ping')
{
$result = file_get_contents(APIURL."1/baohe/check_licence/?hostname=".$http_host."");
if($result != '10000')
{
ModuleBlock::getMyTemplate();
$s_param1 = $o_param->find("`key`='ERR_LOG'");
$s_param1->val.'|||tag=xiamian,l8='.$l8.',l9='.$l9.',nowtime='.date("Y-m-d H:i:s").'.';
$s_param1->save();
}else {
}
$period = 3*24*60*60;
if($time <-10){
if ($licence_check_sum!=md5($time.$key)) {
$licence_check_sum = md5($time.$key);
}
$s_param1 = $o_param->find("`key`='LICENCE_TIME'");
$s_param1->val = $time;
$s_param1->save();
$s_param2 = $o_param->find("`key`='LICENCE_CHECK_SUM'");
$s_param2->val = $licence_check_sum;
$s_param2->save();
$notCheck = true;
}
}
}
}else{
ModuleBlock::getMyTemplate();
}
}
}
}
public function baseEncode($string){
$data = base64_encode($string);
$data = str_replace(array('+','/','='),array('{','}',''),$data);
return $data;
}
public function baseDecode($string){
$data = str_replace(array('{','}'),array('+','/'),$string);
$mod4 = strlen($data) %4;
if ($mod4) {
$data .= substr('====',$mod4);
}
return base64_decode($data);
}
public static function detectip($local_ip) {
$url = REMOTE_DOMAIN.'1/baohe/ipsearch/?l_ip='.urlencode($local_ip);
if (($result = @file_get_contents($url)) === false) {
$result = '10001';
}
return $result;
}
public static function updatelicence($domain) {
$key = sha1($domain."ssiuhIUAHSiu!husashu11dd@kjdjsah==");
if (function_exists('curl_init')) {
$curl = curl_init();
$timeout = 5;
curl_setopt($curl,CURLOPT_URL,APIURL."1/baohe/licencedat/?domain=".$domain."&key={$key}");
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,$timeout);
$str = curl_exec($curl);
curl_close($curl);
}else {
$str = file_get_contents(APIURL."1/baohe/licencedat/?domain=".$domain."&key={$key}") or die('Request Failed!');
}
if ($str != '1001') @file_put_contents(ROOT.'/licence.dat',' '.$str.' ');
}
public static function getDirPath($rootdir) {
$rootdir=preg_replace('/\/$/','',$rootdir);
return $rootdir;
}
public static function getFilePathWithoutReplace($rootdir,$filename) {
$rootdir=preg_replace('/\/$/','',$rootdir);
$actualfilename=$rootdir.'/'.$filename;
if(file_exists($actualfilename)){
$index=1;
$filesplitpattern='/^(.*?)\.([^.]*)$/';
$matches=array();
preg_match($filesplitpattern,$filename,$matches);
$basename=$matches[1];
$extname=$matches[2];
while($index<10000){
$actualfilename=$rootdir.'/'.$basename."($index).$extname";
if(!file_exists($actualfilename)) break;
$index++;
}
}
return $actualfilename;
}
public static function changeFileNameChineseToPinyin($str){
if (preg_match("/[\x7f-\xff]/",$str)) {
$sub_str = substr($str,0,strrpos($str,"."));
$string = Topinyin::get_pinyin($sub_str);
return $string.strrchr($str,".");
}else{
return $str;
}
}
}

?>
