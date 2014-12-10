<?php
/**
* 自助建站安装程序
* @date 2010-1-12
*/
define('IN_CONTEXT', 1);
session_start();
// store session data

if (!defined('IN_CONTEXT')) die('access violation error!');

error_reporting(0);
define('DS', DIRECTORY_SEPARATOR);

define('ROOT', realpath(dirname(__FILE__).'/..'));
define('INSTALL_ROOT', dirname(__FILE__));
define('P_LIB', ROOT.'/library');
define('P_TPL', INSTALL_ROOT.'/template');

header("Content-type: text/html; charset=utf-8");

include_once ROOT."/const.php";
include_once P_LIB."/param.php";
include_once ROOT."/include/http.class.php";
$_a = ParamHolder::get("_a","");
$comeurl=trim($_REQUEST["comeurl"]);
if ($comeurl==""){
	$comeurl=$_SERVER['HTTP_REFERER'];
}
if($_a=='loginout'){
	unset($_SESSION['admin']);
	echo "<script>alert('退出成功');window.location.href='index.php';</script>";
}else if($_a=='checkapi'){
	$url=APIURL."api/checkuser?thiskey=".APIKEY."&value=".$_SERVER['SCRIPT_NAME']."&user=".urlencode($user)."&key=".$key;
	$apiback=(int)file_get_contents($url);
}else if($_a=='check'){
	checkadmin();
	if (ADMIN!=""){
		$user = trim(ADMIN);
	}else{
		$user = trim(ParamHolder::get("user",""));
	}
	$password = trim(ParamHolder::get("password",""));
	if (ADMIN){
		if (ADMINPWD=="ADMINPWD"){
			$loginok=1;
		}
		if (ADMINPWD==$password||$loginok==1){
			$_SESSION['admin']=$user;
			create_set(UC_OPEN,$user,ADMINPWD,HELPURL,DEFAULTDOMAIN,DEFAULTSITEID,DEFAULTQQ,DEFAULTPICURL,APIKEY,SHOWRIGHT,APIUSERID,APIPASSWORD); //创建配置
		}else{
			echo "<script>alert('登录失败');history.go(-1);</script>";
		}
	}else{
		$_SESSION['admin']=$user;
		define('ADMINPWD', $password);//超级管理员可以修改
		create_set(UC_OPEN,$user,ADMINPWD,HELPURL,DEFAULTDOMAIN,DEFAULTSITEID,DEFAULTQQ,DEFAULTPICURL,APIKEY,SHOWRIGHT,APIUSERID,APIPASSWORD); //创建配置
		echo "<script>window.location.href='index.php';</script>";
	}
}elseif($_a=='saveset'){
	checkadmin();
	$user = trim(ParamHolder::get("user",""));
	$password = trim(ParamHolder::get("password",""));
	$defaultdomain = trim(ParamHolder::get("defaultdomain",""));
	$helpurl = trim(ParamHolder::get("helpurl",""));
	$defaultsiteid=(int)trim(ParamHolder::get("defaultsiteid",""));
	$defaultqq=(int)trim(ParamHolder::get("defaultqq",""));
	$defaultpicurl=trim(ParamHolder::get("defaultpicurl",""));
	$showright=(int)trim(ParamHolder::get("showright",""));
	if ($user==""){
		echo "<script>alert('用户名不能为空');history.go(-1);</script>";
	}
	create_set(UC_OPEN,$user,$password,$helpurl,DEFAULTDOMAIN,$defaultsiteid,$defaultqq,$defaultpicurl,APIKEY,$showright,APIUSERID,APIPASSWORD); //创建配置
	echo "<script>window.location.href='?_a=setting';</script>";
}elseif($_a=='savedomain'){
	checkadmin();
	$defaultdomain = trim(ParamHolder::get("defaultdomain",""));
	if ($defaultdomain==""){
		echo "<script>alert('泛域名不能为空');history.go(-1);</script>";
	}

	create_set(UC_OPEN,ADMIN,ADMINPWD,HELPURL,$defaultdomain,DEFAULTSITEID,DEFAULTQQ,DEFAULTPICURL,APIKEY,SHOWRIGHT,APIUSERID,APIPASSWORD); //创建配置
	echo "<script>window.location.href='".$comeurl."';</script>";	
}elseif($_a=='saveuc'){
	$uc_open = (int)trim(ParamHolder::get("ucopen",""));
	$ucconfig = trim($_REQUEST["ucconfig"]);
	$ucconfig=str_replace(chr(92),"",$ucconfig);
	$ucconfig=str_replace("'mysql'","'null'",$ucconfig);
	
	
	create_set($uc_open,ADMIN,ADMINPWD,HELPURL,DEFAULTDOMAIN,DEFAULTSITEID,DEFAULTQQ,DEFAULTPICURL,APIKEY,SHOWRIGHT,APIUSERID,APIPASSWORD); //创建配置
	$str = "";
	$str .= "<?php\n";
	$str.=$ucconfig;
	file_put_contents("../config/config.inc.php",$str);
	//file_put_contents($file, $content)
	echo "<script>window.location.href='?_a=uc';</script>";	
}elseif($_a=='add'||$_a=='modify'){
	checkadmin();
	$admin="admin";
	$password="admin88";
	
	$siteid=(int)trim(ParamHolder::get("siteid",""));
	$showcheckcode=1;
	if ($siteid>0){
		$sitename=getfieldvalue("select site_name from ".$siteid."_site_infos");
		$sql="select * from ms_site where siteid=".$siteid;
		$result=mysql_query($sql);
		if ($result){
			$rows=mysql_fetch_array($result);
			$sitedomain=$rows["sitedomain"];
			$city=$rows["city"];
			$sitekey=$rows["sitekey"];
			$admin=$rows["username"];
			$begindate=$rows["begindate"];
			$enddate=$rows["enddate"];
			$status=(int)$rows["status"];
			$password="";
		}
		$showcheckcode=(int)getfieldvalue("select val from ".$siteid."_parameters where `key`='SITE_LOGIN_VCODE'");
		$rewrite=(int)getfieldvalue("select val from ".$siteid."_parameters where `key`='MOD_REWRITE'");
		$closeversion=(int)getfieldvalue("select val from ".$siteid."_parameters where `key`='CLOSEVERSION'");
	}
	if (is_Date($begindate)==0){
		$begindate =date("Y-m-d");	//网站开始的时间
	}
	if (is_Date($enddate)==0){
		$enddate =date('Y-m-d',strtotime("$d 7 day"));	//网站结束的时间
	}
	include P_TPL."/site_add.php";
}elseif($_a=='domainmodify'){
	checkadmin();
	$domainid=(int)trim(ParamHolder::get("domainid",""));
	$sql="select * from ms_domain where domainid=".$domainid;
	$result=mysql_query($sql);
	if ($result){
		$rows=mysql_fetch_array($result);
		$domain=$rows["domain"];
		$domaintemplate=$rows["domaintemplate"];
		$mainkey=$rows["mainkey"];
		$domainkey=$rows["domainkey"];
		$status=(int)$rows["status"];
	}else{
		die ("<script>alert('参数丢失');history.go(-1);</script>");	
	}
	include P_TPL."/domainmodify.php";
}elseif($_a=='savemodifydomain'){
	checkadmin();
	$domainid=(int)trim(ParamHolder::get("domainid",""));
	$mainkey=trim(ParamHolder::get("mainkey",""));
	$domaintemplate=trim(ParamHolder::get("domaintemplate",""));
	$domainkey=trim(ParamHolder::get("domainkey",""));
	$status=(int)trim(ParamHolder::get("status",""));
	$sql="update ms_domain set mainkey='".$mainkey."',domainkey='".$domainkey."',domaintemplate='".$domaintemplate."',status='".$status."' where domainid=".$domainid;
	//die($sql);
	$result=mysql_query($sql);
	if ($result){
		$msg="操作成功";	
	}else{
		$msg="操作失败";	
	}
	echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";		
}elseif($_a=='manage'||$_a=='checkdomain'||$_a=='out'||$_a=='adminlist'){
	checkadmin();
	$siteid=(int)trim(ParamHolder::get("siteid",""));
	if ($siteid>0){
		$sitename=getfieldvalue("select site_name from ".$siteid."_site_infos");
		$sql="select * from ms_site where siteid=".$siteid;
		$result=mysql_query($sql);
		if ($result){
			$rows=mysql_fetch_array($result);
			$sitedomain=$rows["sitedomain"];
			$city=$rows["city"];
			$sitekey=$rows["sitekey"];
			$admin=$rows["username"];
			$begindate=$rows["begindate"];
			$enddate=$rows["enddate"];
			$status=(int)$rows["status"];
			$password="";
			$siteurl=$rows["sitedomain"];
			if ($siteurl==""){
				$siteurl=$siteid.DEFAULTDOMAIN;
			}
		}
		
	}
	if ($_a=='checkdomain'){
		$domainid =(int)trim(ParamHolder::get("domainid",""));
		$domain =trim(ParamHolder::get("domain",""));
		include P_TPL."/checkdomain.php";
	}elseif ($_a=='adminlist'){
		include P_TPL."/adminlist.php";	
	}elseif ($_a=='out'){
		include P_TPL."/out.php";	
	}else{
		$showcheckcode=(int)getfieldvalue("select val from ".$siteid."_parameters where `key`='SITE_LOGIN_VCODE'");
		$rewrite=(int)getfieldvalue("select val from ".$siteid."_parameters where `key`='MOD_REWRITE'"); 
		$defaulttpl=getfieldvalue("select val from ".$siteid."_parameters where `key`='DEFAULT_TPL'"); 
		include P_TPL."/manage.php";
	}	
}elseif($_a=='savesite'){
	checkadmin();
	$admin_name = trim(ParamHolder::get("admin",""));
	$admin_pwd = trim(ParamHolder::get("password",""));
	$begindate = trim(ParamHolder::get("begindate",""));
	$enddate = trim(ParamHolder::get("enddate",""));
	$sitename = trim(ParamHolder::get("sitename",""));
	$city = trim(ParamHolder::get("city",""));
	$sitekey = trim(ParamHolder::get("sitekey",""));
	$status = (int)trim(ParamHolder::get("status",""));
	$siteid=(int)trim(ParamHolder::get("siteid",""));
	$sitedomain=trim(ParamHolder::get("sitedomain",""));
	$showcheckcode=(int)trim(ParamHolder::get("showcheckcode",""));
	$rewrite=(int)trim(ParamHolder::get("rewrite",""));
	$closeversion=(int)trim(ParamHolder::get("closeversion",""));

	if(!$admin_name) die ("<script>alert('请输入管理员名称');history.go(-1);</script>");
	if(!$sitename) die ("<script>alert('请输入网站名称');history.go(-1);</script>");

	if (is_Date($begindate)==0){
			$begindate =date("Y-m-d");	//网站开始的时间
	}
	if (is_Date($enddate)==0){
		$enddate =date('Y-m-d',strtotime("$d 7 day"));	//网站结束的时间
	}
	$adddata=0;	
	if ($siteid>0){
		if ($sitedomain){
			$sql="select siteid from ms_domain where domain='$sitedomain'";
			$domainsiteid=(int)getfieldvalue($sql);
			if ($domainsiteid==0){
				$adddata=1;
				adddomain($siteid,$sitedomain,"");
			}else{
				if ($domainsiteid==$siteid){
					$adddata==0;
				}else{
					die ("<script>alert('域名被其他站点绑定');history.go(-1);</script>");	
				}
			}
		}
		if ($sitename){
			$sql="update ".$siteid."_site_infos set site_name='".$sitename."' where s_locale='zh_CN'";
			mysql_query($sql);
		}
		if ($adddata==1){
			$sql="update ms_site set sitename='$sitename',city='$city' where siteid=".$siteid;
			$result=mysql_query($sql);
		}
		$sql="update ".$siteid."_parameters set val='$showcheckcode' where `key`='SITE_LOGIN_VCODE'";
		$result=mysql_query($sql);
		$sql="update ".$siteid."_parameters set val='$rewrite' where `key`='MOD_REWRITE'";
		$result=mysql_query($sql);
		addconfig($siteid."_","CLOSEVERSION",$closeversion);
		$sql="update ms_site set username='$admin_name',status=$status,sitename='$sitename',sitedomain='$sitedomain',city='$city',sitekey='$sitekey',begindate='$begindate',enddate='$enddate' where siteid=".$siteid;
		$result=mysql_query($sql);
		if ($result){
			$msg='修改成功';
		}else{
			$msg='修改失败';
		}	
		echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";
	}else{
		$result=createsite(0,$siteid,$sitename,$city,$status,$sitedomain,$admin_name,$admin_pwd,$tpldir,$begindate,$enddate);

		if (substr($result,0,3)=="100"){
			$msg='增加成功';
		}else{
			$msg='增加失败,错误代码'.$result;
		}
		echo "<script>alert('".$msg."');window.location.href='?';</script>";
	}
}elseif($_a=='getsitekey'){
	checkadmin();
	$siteid =(int)trim(ParamHolder::get("siteid",""));
	$sitekey="";
	$sql="select * from ".$siteid."_site_infos order by id asc";
	$result=mysql_query($sql);
	if ($result){
		$rows=mysql_fetch_array($result);
		$site_name=$rows["site_name"];
		$keywords=$rows["keywords"];
		$description=$rows["description"];
		$sitekey=$sitekey.$site_name.chr(10);
		if ($site_name!=$keywords){
			$sitekey=$sitekey.$keywords.chr(10);
		}
		if ($description!=$keywords){
			$sitekey=$sitekey.$description.chr(10);
		}
	}
	$sql="select * from ".$siteid."_products where published='1' order by name asc";
	$ques = mysql_query($sql);
	$j=0;
	if ($ques){
		while($rows = mysql_fetch_array($ques)){
		$j=$j+1;
		$sitekey=$sitekey.$rows["name"].chr(10);
		}
	}
	if ($sitekey){
		$sql="update ms_site set sitekey='$sitekey' where siteid=".$siteid;
		$result=mysql_query($sql);
	}else{
		$result=0;	
	}
	if ($result){
		$msg='操作成功';
	}else{
		$msg='操作失败';
	}
	echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";	
}elseif($_a=='addprovince'){
	checkadmin();
	$mainkey="";
	$siteid=(int)trim(ParamHolder::get("siteid",""));
	$thisdomain=getcncode();	
	if ($siteid>0&&$thisdomain!=""){
		$ivalue=$thisdomain;
		$split="$$$";
		$array=explode($split,$ivalue);
		for ($i=0;$i<sizeof($array);$i++){
			$thisvalue=$array[$i];
			$thisvalue=str_replace(chr(10),"",$thisvalue);
			$thisvalue=str_replace(chr(13),"",$thisvalue);
			if ($thisvalue){
				
				if (strpos($thisvalue,"|")){
					$allkey=explode("|",$thisvalue);
					$sitedomain=$allkey[2].DEFAULTDOMAIN;
					$mainkey=$allkey[0];
				}else{
					$sitedomain=$thisvalue;
				}
				if (1==1){
					$sql="select siteid from ms_domain where domain='$sitedomain'";
					$domainsiteid=(int)getfieldvalue($sql);
					if ($domainsiteid==0){
						if (adddomain($siteid,$sitedomain,$mainkey)){
							$msg.=$sitedomain.'操作成功,';
						}else{
							$msg.=$sitedomain.'操作失败,';
						}
					}else{
						if ($domainsiteid==$siteid){
							$msg.=$sitedomain."已经绑定,";
						}else{
							$msg.=$sitedomain."被站点".$domainsiteid."绑,";	
						}
					}
				}
			}				
		}	
	}else{
		$msg='参数丢失';
	}
	echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";		
}elseif($_a=='adddomain'){
	checkadmin();
	$mainkey="";
	$siteid=(int)trim(ParamHolder::get("siteid",""));
	$thisdomain=trim(ParamHolder::get("domain",""));
	$sitekey=trim(ParamHolder::get("sitekey",""));
	if ($sitekey){
		$sql="update ms_site set sitekey='$sitekey' where siteid=".$siteid;
		$result=mysql_query($sql);
		$msg="关键词更新成功";
	}else{
		$msg="操作成功,共享站群功能关闭";
	}	
	if ($siteid>0&&$thisdomain!=""){
		$ivalue=$thisdomain.chr(10).chr(13);
		if (strpos($ivalue,chr(10))){
			$split=chr(10);
		}elseif(strpos($ivalue,chr(13))){
			$split=chr(13);
		}
		$array=explode($split,$ivalue);
		for ($i=0;$i<sizeof($array);$i++){
			$thisvalue=$array[$i];
			$thisvalue=str_replace(chr(10),"",$thisvalue);
			$thisvalue=str_replace(chr(13),"",$thisvalue);
			if ($thisvalue){
				
				if (strpos($thisvalue,"|")){
					$allkey=explode("|",$thisvalue);
					$sitedomain=$allkey[0];
					$mainkey=$allkey[1];
				}else{
					$sitedomain=$thisvalue;
				}
				$sql="select siteid from ms_domain where domain='$sitedomain'";
				$domainsiteid=(int)getfieldvalue($sql);
				if ($domainsiteid==0){
					if (adddomain($siteid,$sitedomain,$mainkey)){
						$msg.=$sitedomain.'操作成功,';
					}else{
						$msg.=$sitedomain.'操作失败,';
					}
				}else{
					if ($domainsiteid==$siteid){
						$msg.=$sitedomain."已经绑定,";
					}else{
						$msg.=$sitedomain."被站点".$domainsiteid."绑定,";	
					}
				}
			}				
		}	
	}
	echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";		
}elseif($_a=='deldomain'){
	checkadmin();
	$domainid =(int)trim(ParamHolder::get("domainid",""));
	$sql="delete from ms_domain where domainid=".$domainid;
	$result=mysql_query($sql);
	if ($result){
		$msg='操作成功';
	}else{
		$msg='操作失败';
	}
	echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";	
}elseif($_a=='delalldomain'){
	checkadmin();
	$siteid =(int)trim(ParamHolder::get("siteid",""));
	$sql="delete from ms_domain where siteid=".$siteid;
	$result=mysql_query($sql);
	if ($result){
		$msg='操作成功';
	}else{
		$msg='操作失败';
	}
	echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";		
}elseif($_a=='addadmin'){
	checkadmin();
	$siteid =(int)trim(ParamHolder::get("siteid",""));
	$admin=trim(ParamHolder::get("admin",""));
	$result=addadmin($siteid,$admin,"admin88");
	if ($result){
		$msg='操作成功';
	}else{
		$msg='操作失败';
	}
	echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";	
}elseif($_a=='adminpwd'){
	checkadmin();
	$siteid =(int)trim(ParamHolder::get("siteid",""));
	$id =(int)trim(ParamHolder::get("id",""));
	$sql="update ".$siteid."_users set passwd='".sha1("admin88")."' where id=".$id;
	$result=mysql_query($sql);
	if ($result){
		$msg='密码重置为admin88';
	}else{
		$msg='操作失败';
	}
	echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";	
}elseif($_a=='deladmin'){
	checkadmin();
	$siteid =(int)trim(ParamHolder::get("siteid",""));
	$id =(int)trim(ParamHolder::get("id",""));
	$sql="delete from ".$siteid."_users where id=".$id;
	$result=mysql_query($sql);
	$sql="delete from ".$siteid."_user_extends where user_id=".$id;
	$result=mysql_query($sql);
	if ($result){
		$msg='操作成功';
	}else{
		$msg='操作失败';
	}
	echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";			
}elseif($_a=='import'){
	checkadmin();
	if (is_Date($begindate)==0){
		$begindate =date("Y-m-d");	//网站开始的时间
	}
	if (is_Date($enddate)==0){
		$enddate =date('Y-m-d',strtotime("$d 7 day"));	//网站结束的时间
	}
	$sql="show tables like '%_templates%'";
	$ques = mysql_query($sql);
	if ($ques){
		while($rows = mysql_fetch_array($ques)){
			$siteid=explode(".",$rows[0]);
			$siteid=(int)$siteid[0];
			if ($siteid>0){
				$sql="select siteid from ms_site where siteid='$siteid'";
				$oldsiteid=(int)getfieldvalue($sql);
				if ($oldsiteid==0){
					$sql="INSERT INTO ms_site(siteid,sitename,city,username,status,begindate,enddate,createtime,updatetime) VALUES ('".$siteid."','".$sitename."','".$city."','".$admin."',1,'".$begindate."','".$enddate."',now(),now())";
					mysql_query($sql);
				}
			}	
		}
	}
	echo "<script>alert('同步成功');window.location.href='$comeurl';</script>";		
}elseif($_a=='uc'){
	checkadmin();
	$ucfile = file_get_contents("../config/config.inc.php");
	$ucfile=explode("<?php",$ucfile);
	$ucconfig=$ucfile[1];
	include P_TPL."/uc.php";
}elseif($_a=='savesn'){
	checkadmin();
	$sn =trim(ParamHolder::get("sn",""));
	file_put_contents("../config/sn.txt",$sn);
	$msg="操作成功";
	echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";	
}elseif($_a=='dositestar'){
	checkadmin();
	$pre =trim(ParamHolder::get("pre",""));
	$sqllist = getalltable(0);	
	$array=explode(",",$sqllist);
	$siteid=(int)getfieldvalue("select siteid from ms_site order by siteid desc")+1;
	for ($i=0;$i<sizeof($array);$i++){
		$thisvalue=$array[$i];
		$thisvalue = str_replace(chr(34),"", $thisvalue);
		$sql="ALTER TABLE `".$pre.$thisvalue."` RENAME `".$siteid."_".$thisvalue."`";
		$result=mysql_query($sql);
	}
	if ($result){
		$msg="操作成功";
		if ($admin==""){$admin="admin";}
		if ($pwd==""){$pwd="admin88";}
		if (is_Date($begindate)==0){
			$begindate =date("Y-m-d");	//网站开始的时间
		}
		if (is_Date($enddate)==0){
			$enddate =date('Y-m-d',strtotime("$d 7 day"));	//网站结束的时间
		}
		$status=(int)$status;
		$sql="INSERT INTO ms_site(siteid,sitename,city,username,status,begindate,enddate,createtime,updatetime) VALUES ('".$siteid."','".$sitename."','".$city."','".$admin."',1,'".$begindate."','".$enddate."',now(),now())";
		$result=mysql_query($sql);
	}else{
		$msg="操作失败";
	}	
	echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";	
}elseif($_a=='resetpassword'){
	$lockfile = ROOT.'/data/system.lock';
	if(file_exists($lockfile)) {
		$msg="请删除文件:/data/system.lock";
	}else{
	
		$msg="重设密码为:admin88";
		create_set(UC_OPEN,ADMIN,"admin88",HELPURL,DEFAULTDOMAIN,DEFAULTSITEID,DEFAULTQQ,DEFAULTPICURL,APIKEY,SHOWRIGHT,APIUSERID,APIPASSWORD);
		file_put_contents($lockfile,"");
	}
	echo "<script>alert('".$msg."');window.location.href='$comeurl';</script>";	
}elseif($_a=='delete'){
	checkadmin();
	$siteid =trim(ParamHolder::get("siteid",""));
	//删除站点列表
	$sql = "DELETE FROM `ms_site` WHERE `siteid`=".$siteid;
	mysql_query($sql);
	//删除该站点的域名
	$sql2 = "DELETE FROM `ms_domain` WHERE `siteid`=".$siteid;
	mysql_query($sql);
	//删除该站点的表
	drop_site_table($siteid);
	$msg="站点已删除";
	echo "<script>alert('".$msg."');window.location.go='-1';</script>";	

}elseif($_a!=''){
	checkadmin();
	include P_TPL."/".$_a.".php";	
}else{
	if ($_SESSION['admin']==ADMIN&&$_SESSION['admin']!=""){	
		include P_TPL."/index.php";
	}else{
		include P_TPL."/login.php";
	} 
}

function checkadmin(){
	if ($_SESSION['admin']!=ADMIN||$_SESSION['admin']==""){
		echo "<script>window.location.href='index.php';</script>";
	}
}
