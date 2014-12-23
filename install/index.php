<?php
/**
* 自助建站安装程序
* @date 2010-1-12
*/

define('IN_CONTEXT', 1);
@session_start();
if (!defined('IN_CONTEXT')) die('access violation error!');

error_reporting(E_ALL^E_NOTICE);
define('DS', DIRECTORY_SEPARATOR);

define('ROOT', realpath(dirname(__FILE__).'/..'));
define('INSTALL_ROOT', dirname(__FILE__));
define('P_LIB', ROOT.'/library');
define('P_TPL', INSTALL_ROOT.'/template');

header("Content-type: text/html; charset=utf-8");
include_once P_LIB."/param.php";
require '../config/conn.php';
require '../library/mysqli.php';
include_once ROOT."/include/fun_install.php";
include_once ROOT."/include/http.class.php";

$_a = ParamHolder::get("_a","");
$_m = ParamHolder::get("_m","frontpage");
$db_host1 = ParamHolder::get("db_host","");
$db_user = ParamHolder::get("db_user","");
$db_pwd = ParamHolder::get("db_pwd","");
$db_name = ParamHolder::get("db_name","");
$db_prefix = ParamHolder::get("db_prefix","");
$db_port = ParamHolder::get("db_port","");
$admin_name = ParamHolder::get("admin_name","");
$admin_pwd = ParamHolder::get("admin_pwd","");
$demo = ParamHolder::get("demo","");
$sitename = ParamHolder::get("sitename","");
$province = ParamHolder::get("b_province","");
$city = ParamHolder::get("b_city","");
$mobile = ParamHolder::get("mobile","");
$qq = ParamHolder::get("qq","");
$db_host = $db_host1.":".$db_port;
$lockfile = ROOT.'/data/install.lock';
if(file_exists($lockfile)) {
	$msg="已经安装过,重复安装请删除".$lockfile."!";
	exit($msg);
}
if($_a=='template'){
	include P_TPL."/template.php";
}else if($_a=='check'){
	include P_TPL."/check.php";
}else if($_a=='setting'){
	$db_host = Config::$db_host;
	$db_user = Config::$db_user;
	$db_pwd = Config::$db_pass;
	$db_name = Config::$db_name;
	$default_tpl = ParamHolder::get("default_tpl","itkj-110505-a23");
	$_SESSION['default_tpl'] = $default_tpl;

	include P_TPL."/setting.php";
}else if($_a=='result'){
	create_file();
	include P_TPL."/result.php";
}else if($_a=='checkconnection'){
	$link = @mysql_connect($db_host,$db_user,$db_pwd);
	if (!$link) {
		echo '1001';
		exit;
	}
	$r = mysql_select_db($db_name,$link);
	if(!$r){
		if (!mysql_query("CREATE DATABASE ".$db_name."",$link))
		{
			echo '1002';
			exit;
		}
	}
}else if($_a=="create"){
	$link = mysql_connect($db_host,$db_user,$db_pwd);
	mysql_select_db($db_name,$link);
	
	//$sql="DROP TABLE IF EXISTS `ms_domain`";
	//mysql_query($sql);
	$sql="CREATE TABLE `ms_domain` (
  `domainid` int(10) NOT NULL auto_increment,
  `siteid` int(11) default '0',
  `domain` varchar(255) default NULL,
  `mainkey` varchar(255) default NULL,
  `domainkey` longtext,
  `domaintemplate` varchar(255) default NULL,
  `status` int(1) default '0',
  `orderid` int(11) default '0',
  `createtime` datetime default NULL,
  `updatetime` datetime default NULL,
  PRIMARY KEY  (`domainid`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8";
	mysql_query($sql);
	//$sql="DROP TABLE IF EXISTS `ms_site`";
	//mysql_query($sql);
	$sql="CREATE TABLE `ms_site` (
	  `siteid` int(10) NOT NULL,
	  `sitename` varchar(255) default NULL,
	  `sitedomain` varchar(255) default NULL,
	  `username` varchar(255) default NULL,
	  `status` int(11) default '0',
	  `city` varchar(255) default NULL,
	  `citylist` longtext,
	  `sitekey` longtext,
	  `begindate` date default NULL,
	  `enddate` date default NULL,
	  `createtime` datetime default NULL,
	  `updatetime` datetime default NULL,
	  PRIMARY KEY  (`siteid`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	mysql_query($sql);
	//$sql="DROP TABLE IF EXISTS `ms_log`";
	//mysql_query($sql);
	$sql="CREATE TABLE `ms_log` (
	  `logid` int(11) NOT NULL auto_increment,
	  `siteid` int(11) default NULL,
	  `logtype` int(11) default '0',
	  `level` int(11) default '0',
	  `username` varchar(255) default NULL,
	  `userip` varchar(255) default NULL,
	  `logcontent` longtext,
	  `scriptname` longtext,
	  `poststring` longtext,
	  `logtime` datetime default NULL,
	  PRIMARY KEY  (`logid`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	mysql_query($sql);
	$result=createsite(0,0,$sitename,$province."_".$city."_".$mobile."_".$qq."_".$email,$status,$_SERVER['HTTP_HOST'],$admin_name,$admin_pwd,$tpldir,$begindate,$enddate);
	
	create_config($db_host1,$db_user,$db_pwd,$db_name,$db_prefix,$db_port);
	if (substr($result,0,3)=="100"){
		echo '1003';
	}else{
		echo '1005';
	}	
	
}else if($_a=="createadmin"){
	
	echo '1004';
	
}else{
	include P_TPL."/index.php";
}
?>