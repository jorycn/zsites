<?php
/**
* 自助建站安装程序
* @copyright 
* @date 2010-1-12
*/
header("Content-type: text/html; charset=utf-8"); 
error_reporting(0);
define('IN_CONTEXT', 1);
require '../const.php';
require '../library/mysql.php';
@session_start();
if (!defined('IN_CONTEXT')) die('access violation error!');
//echo "aaaaaaaa";
error_reporting(0);
define('DS', DIRECTORY_SEPARATOR);

define('ROOT', realpath(dirname(__FILE__).'/..'));
define('INSTALL_ROOT', dirname(__FILE__));
define('P_LIB', ROOT.'/library');
define('P_TPL', INSTALL_ROOT.'/template/view/frontpage');

include_once P_LIB."/param.php";
include_once INSTALL_ROOT."/include/http.class.php";

$siteid = (int)$_REQUEST["siteid"];
$dotype = (int)$_REQUEST["dotype"]; 
$new_domain = $_REQUEST["newdomain"];
$old_domain = $_REQUEST["olddomain"];
$action = $_REQUEST["action"];
$new_admin = $_REQUEST["newadmin"];
$old_admin  = $_REQUEST["oldadmin"];
$skey     = $_REQUEST["skey"];			//权限密匙
$username = $_REQUEST["username"];		//用户名称
$checkkey=md5(md5(APIKEY).$siteid);
if ($skey != $checkkey){
	echo "1|errkey";
	break;
}
define("ACT_API",$action);
define("NEW_DOMAIN",$new_domain);
define("OLD_DOMAIN",$old_domain);
define("SITE_ID",$siteid);
define("NEW_ADMIN",$new_admin);
define("OLD_ADMIN",$old_admin);
define("DOTYPE",$dotype);

switch($_REQUEST["action"]){
	case "build":
		$domain   = $_REQUEST["domain"]; 		//域名	
		$admin_name = $_REQUEST["admin_name"];	//管理员用户名
		$admin_pwd  = $_REQUEST["admin_pwd"];	//管理员密码
		$tpldir = $_REQUEST["tplname"]; //安装模板
		echo createsite($dotype,$siteid,$sitename,$city,$status,NEW_DOMAIN,$admin_name,$admin_pwd,$tpldir,$begindate,$enddate);
		break;
	case "delsite":
		delsite($siteid);
		echo 1;
		break;
	case "deldomain":
		$sql = "delete from ms_domain where siteid='".SITE_ID."' and domain='".OLD_DOMAIN."'";
		if(mysql_query($sql)){
			echo "169";	//删除域名成功
		}else{
			echo "168";	//删除域名失败
		}
		break;
	case "adddomain":
		$sql="select siteid from ms_domain where domain='$new_domain'";
		$domainsiteid=(int)getfieldvalue($sql);
		if ($domainsiteid==0){
			if (adddomain($siteid,NEW_DOMAIN,"")){
				echo "159";
			}else{
				echo "158";
			}
		}else{
			if ($domainsiteid==$siteid){
				echo "159";
			}else{
				echo "157";	
			}
		}
		break;
	case "addcopy":
		addcopy(1,SITE_ID."_");
		break;
	case "delcopy":
		delcopy(1,SITE_ID."_");
		break;		
	case "ping":
		echo md5($_SERVER['HTTP_HOST']);
		break;
	case "getinfo";
		$sql = "select * from ms_site where siteid='".SITE_ID."'";
		$que = mysql_query($sql);
		$row = mysql_fetch_array($que);
		echo "109$$$".SITE_ID."$$$".$row["username"]."";
		echo "$$$".$row["begindate"]."$$$ ".$row["enddate"]."";
		$sql = "select * from ms_domain where siteid='".SITE_ID."'";
		$sql = $sql." order by siteid asc";
		$ques = mysql_query($sql);
		if ($ques){
			echo "$$$";
			while($rows = mysql_fetch_array($ques)){
				echo "".$rows["siteid"]."###".$rows["domain"]."@@@";
			}
		}
		break;
	case "getall";
		echo "179";
		$sql = "select * from ms_domain where 1=1";
		$sql = $sql." order by siteid asc";
		$ques = mysql_query($sql);
		if ($ques){
			echo "$$$";
			while($rows = mysql_fetch_array($ques)){
				echo "".$rows["siteid"]."###".$rows["domain"]."@@@";
			}
		}
		$sql = "select * from ".SITE_ID."_users where active='1' and s_role='{admin}'";
		$ques = mysql_query($sql);
		if ($ques){
			echo "$$$";
			while($rows = mysql_fetch_array($ques)){
				echo "".$rows["login"]."@@@";
			}
		}
		$sql = "select * from ms_site";
		$sql = $sql." order by siteid asc";
		$ques = mysql_query($sql);
		if ($ques){
			echo "$$$";
			while($rows = mysql_fetch_array($ques)){
				echo "".$rows["siteid"]."@@@";
			}
		}
		break;
	case "editadminuser";
		$sql = "update ".SITE_ID."_users set login=".NEW_ADMIN." where login=".OLD_ADMIN;
		if(mysql_query($sql)){
			$sql = "update ms_site set username=".NEW_ADMIN." where siteid=".SITE_ID." and username=".OLD_ADMIN;
			if(mysql_query($sql)){
				echo 189;
			}else{
				echo 187;	//添加域名失败
			}
		}else{
			echo 188;
		}
	break;
}
?>