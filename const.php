<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
ini_set("display_errors","off");
date_default_timezone_set("Asia/ShangHai");
header("Content-type: text/html; charset=utf-8");
include_once('config/conn.php');
include_once('config/config.php');

if(defined('UC_API')) {
	include_once('uc_client/client.php');
}
include_once('include/fun_install.php');
define('APIURL', 'http://siteapi.jzbao.net:9090/');//修改会出错
$db_host = (Config::$db_host).":".(Config::$port);
$db_user = Config::$db_user;
$db_pwd = Config::$db_pass;
$db_name = Config::$db_name;
$domain=strtolower($_SERVER['SERVER_NAME']);
$link = @mysql_connect($db_host,$db_user,$db_pwd);
mysql_query("set names utf8");
$r = mysql_select_db($db_name,$link);
if (strstr($domain,DEFAULTDOMAIN)){
	$siteid=explode(".",$domain);
	$siteid=$siteid[0];	
}else{
	$siteid=0;
}
$dokey=0;
if ($siteid>0){
	$sql="select * from ms_site d where siteid=$siteid";
}else{
	$sql="select s.*,d.mainkey,d.domainkey,d.domaintemplate from ms_site s,ms_domain d where s.siteid=d.siteid and d.domain='".$domain."'";
	$dokey=1;
}
$a=mysql_query($sql);
$row=mysql_fetch_array($a);
if ($row){
	$siteid=(int)$row['siteid'];
	if ($dokey=1){
		$sitekey=$row['sitekey'];
		$mainkey=$row['mainkey'];
		$domaintemplate=$row['domaintemplate'];
		$domainkey=$row['domainkey'];
	}
}else{
	$dokey=0;
	$siteid=0;
}
if ($siteid<1){
	$siteid=(int)DEFAULTSITEID;
	$siteid=(int)getfieldvalue("select siteid from ms_site where siteid=".$siteid."");
	if ($siteid<1){
		$siteid=(int)getfieldvalue("select siteid from ms_site order by siteid asc");
		create_set(UC_OPEN,ADMIN,ADMINPWD,APIURL,DEFAULTDOMAIN,$siteid,"1683309111",APIURL,APIKEY,SHOWRIGHT,APIUSERID,APIPASSWORD);
	}
}
$action=isset($_REQUEST["action"])?$_REQUEST["action"]:'';
if ($action=="addcopy"){
	$key=trim($_REQUEST["skey"]);
	if (checkpurview($key,trim($_REQUEST["siteid"]))==1){
		addcopy(1,$siteid."_");
	}
	break;
}
if ($action=="delcopy"){
	$key=trim($_REQUEST["skey"]);
	if (checkpurview($key,trim($_REQUEST["siteid"]))==1){
		delcopy(1,$siteid."_");
	}
	break;	
}
if ($action=="ping"){
	die(md5($_SERVER['HTTP_HOST']));
}
define('THISSITE', $siteid);
define('SITEPREFIX', $siteid.'_');
define('THISDOMAIN', $domain);
define('IMG_URL_BD',DEFAULTPICURL);
define('SITEKEY', $sitekey?$sitekey:'ZSites');
define('MAINKEY', $mainkey?$mainkey:'ZSites');
define('DOMAINKEY', $domainkey?$domainkey:'ZSites');
