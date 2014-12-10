<?php
/**
* 安装函数
* @copyright www.jzbao.net
* @date 2013-10-15
*/
//if (!defined('IN_CONTEXT')) die('access violation error!');
function defaultversion(){
	return "jzbao_v1.4";
}
function create_table($tpldir,$DBname, $DBPrefix, $sqlfile, $numOfInstall="" ){
	//break;
	if(file_exists(ROOT.'/config/conn.php')){
		include_once ROOT.'/config/conn.php';
		if(Config::$mysql_ext=='mysqli'){
			$link = mysql_connect(Config::$db_host,Config::$db_user,Config::$db_pass);
		}
	}
	if(!file_exists($sqlfile)){
		$sqlfile=ROOT.'/install/itkj-110505-a23_2_sample.sql';
	}
	@mysql_select_db($DBname);
	$mqr = @get_magic_quotes_runtime();
	@set_magic_quotes_runtime(0);
	$query = fread(fopen($sqlfile, "r"), filesize($sqlfile)); 
	@set_magic_quotes_runtime($mqr);
	$pieces  = split_sql($query);
	for ($i=0; $i<count($pieces); $i++){
		$pieces[$i] = trim($pieces[$i]);
		if(!empty($numOfInstall)){
			if(strpos($pieces[$i],'payment_accounts') || strpos($pieces[$i],'payment_providers')){
				continue;
			}
		}
		if(!empty($pieces[$i]) && $pieces[$i] != "ss_"){
			$pieces[$i] = str_replace( "ss_", $DBPrefix, $pieces[$i]); 
			$pieces[$i] = str_replace( "/admin/fckeditor/upload/../../../","", $pieces[$i]); 
			$pieces[$i] = str_replace( "../../../http://", "http://", $pieces[$i]); 
			$pieces[$i] = str_replace( "sitebaohe_v2.3", defaultversion(), $pieces[$i]);
			
			if (!$result = mysql_query($pieces[$i])) {
				file_put_contents("../cache/".$tpldir."_".$i.".txt",$pieces[$i]);
				//$updatesql=true;
				//return $errors[] = array (mysql_error(), $pieces[$i] );				
			}
		}
	}
	if (substr($sqlfile,-9)=="basic.sql"){
		//$updatesql=true;
	}
	if ($updatesql){
		$sql="ALTER TABLE `".$DBPrefix."friendlinks` ADD `i_order` INT( 11 ) NULL DEFAULT '0' AFTER `for_roles`";
		mysql_query($sql);
		$sql="ALTER TABLE `".$DBPrefix."menu_items` ADD `url` VARCHAR(255) NULL AFTER `title`, ADD `content_id` INT(11) NULL DEFAULT '0' AFTER `url`";
		mysql_query($sql);
		$sql="ALTER TABLE `".$DBPrefix."online_qqs` ADD `i_order` INT( 11 ) NULL DEFAULT '0' AFTER `category`";
		mysql_query($sql);
		$sql="ALTER TABLE `".$DBPrefix."static_contents` ADD `flag` VARCHAR(255) NULL AFTER `for_roles`";
		mysql_query($sql);
		$sql="ALTER TABLE `".$DBPrefix."marquees` ADD `i_order` INT( 11 ) NULL DEFAULT '0' AFTER `link`";
		mysql_query($sql);
	}
	updateblocks($DBPrefix);
	$param='a:4:{s:7:"img_src";s:20:"images/site_logo.png";s:8:"img_desc";s:0:"";s:9:"img_width";s:0:"";s:10:"img_height";s:0:"";}';	
	$aupsql = "update ".$DBPrefix."module_blocks set s_param='".$param."' where s_pos='logo'";
	$aupque = mysql_query($aupsql);
	if (DEFAULTQQ){
		$defaultqq="1683309111";
	}else{
		$defaultqq=DEFAULTQQ;
	}
	/*  修改网站客服  */
	$sqlo = "select * from ".$DBPrefix."online_qqs where account like '%800015083%'";
	$queo = mysql_query($sqlo);
	while($rowo = mysql_fetch_array($queo)){
			$id=$rowo["id"];
			$account=$defaultqq;
			$sqllo = "update ".$DBPrefix."online_qqs set account='".$account."' where id='".$id."'";
			$fuplo = mysql_query($sqllo);
	}
	
	/*  修改友情连接  */
	$sqlo = "select * from ".$DBPrefix."friendlinks where fl_addr like '%JZBAO%' or fl_addr like '%dns%'";
	$queo = mysql_query($sqlo);
	while($rowo = mysql_fetch_array($queo)){
			$id=$rowo["id"];
			$fl_name=$rowo["fl_name"];
			if ($fl_name=="建站宝"){
				$fl_name="百度";
				$fl_img="baidu.gif";
				$fl_addr="http://www.baidu.com";
			}else{
				$fl_name="谷歌";
				$fl_img="google.gif";
				$fl_addr="http://www.google.com";
			}
			$sqllo = "update ".$DBPrefix."friendlinks set fl_name='".$fl_name."', fl_img='".$fl_img."', fl_addr='".$fl_addr."' where id='".$id."'";
			$fuplo = mysql_query($sqllo);
	}
}
function drop_site_table($siteid){
	//break;
	if(file_exists(ROOT.'/config/conn.php')){
		include_once ROOT.'/config/conn.php';
		if(Config::$mysql_ext=='mysqli'){
			$link = mysql_connect(Config::$db_host,Config::$db_user,Config::$db_pass);
		}
	}
	$sqlfile=ROOT.'/install/dropSite.sql';
	@mysql_select_db($DBname);
	$mqr = @get_magic_quotes_runtime();
	@set_magic_quotes_runtime(0);
	$query = fread(fopen($sqlfile, "r"), filesize($sqlfile)); 
	@set_magic_quotes_runtime($mqr);
	$pieces  = split_sql($query);

	$DBPrefix = $siteid.'_';
	foreach ($pieces as $v) {
		$v = str_replace( "ss_", $DBPrefix, trim($v));
		mysql_query($v);
	}
}

function updateblocks($DBPrefix){
	$sql = "select * from ".$DBPrefix."module_blocks where s_pos='banner' and s_param like '%upload/%'";
	$que = mysql_query($sql);
	//echo $sql;
	while($row = mysql_fetch_array($que)){
		//239
		$param=$row["s_param"];
		$array=explode(";",$param);
		for ($i=0;$i<sizeof($array);$i++){
			$thisvalue=$array[$i];
			if (strpos($thisvalue,"upload/")){
				$thislen=explode('"',$thisvalue);
				$thislen=$thislen[1];
				$newvalue="s:".strlen($thislen).":".'"'.$thislen.'"';
				$param=str_ireplace($thisvalue,$newvalue,$param);
			}
			
		}
		$aupsql = "update ".$DBPrefix."module_blocks set s_param='".$param."' where id=".$row["id"]."";
		$aupque = mysql_query($aupsql);
	}
}
function replacesitekey($ikey,$ivalue,$dvalue,$avalue){
	$result=$avalue;
	if ($ikey){
		if ($ivalue||$dvalue){
			$ivalue=$ivalue.chr(10).$dvalue;
			$ivalue=$ivalue.chr(10).chr(13);
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
						$thisvalue=$allkey[0];
						$newkey=$allkey[1];
						if (!$newkey){
							$newkey=$ikey.$thisvalue;
						}
					}else{
						$newkey=$ikey.$thisvalue;
					}
					if (!strpos($thisvalue,chr(106)&chr(122)&chr(98)&chr(97)&chr(111))){
						$result=str_replace($thisvalue,$newkey,$result);
					}
				}				
			}
		}	
	}
	return $result;
}
function showmsg($itype,$msg,$url){
	$html="";
	
	if ($itype==1){
		$html="if (confirm('$msg')){window.location.href='$url';}else{return false;};";
	}else{
		if ($msg){
			$html=$html."alert('.$msg.');";
		}
		if ($url){
			$html=$html."window.location.href='$url';";
		}
	}
	return "<script language='javascript'>".$html."</script>";
}
function microupdate($siteid){
	$version=gettrueversion(getfieldvalue("select val from ".$siteid."_parameters where `key`='SYSVER'"));
	if ($version<14){
		$sql="ALTER TABLE `".$siteid."_messages` ADD `published` INT(1) NULL DEFAULT '0' AFTER `message`;";
		mysql_query($sql);
		$sql="ALTER TABLE `".$siteid."_messages` ADD `admin_reply` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `published`;";
		mysql_query($sql);
		$sql="update `".$siteid."_parameters` set `val`='".defaultversion()."' where `key`='SYSVER'";
		mysql_query($sql);
	}
}
function gettrueversion($val){
	$result=explode("_v",$val);
	if (sizeof($result)==2){
		$name=$result[0];
		$version=$result[1];
		$version=(int)str_replace(".","", $version);
		return $version;
	}else{
		return 0;
	}
}
function split_sql($sql){
	$sql = trim($sql);
	$sql = preg_replace("/\n#[^\n]*\n/", "\n", $sql);
	$buffer = array();
	$ret = array();
	$in_string = false;
	for($i=0; $i<strlen($sql)-1; $i++){
		if($sql[$i] == ";" && !$in_string){
			$ret[] = substr($sql, 0, $i);
			$sql = substr($sql, $i + 1);
			$i = 0;
		}
	if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\"){
		$in_string = false;
	}elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")){
		$in_string = $sql[$i];
	}
	if(isset($buffer[1])) {
		$buffer[0] = $buffer[1];
	}
		$buffer[1] = $sql[$i];
	}
	if(!empty($sql)){
		$ret[] = $sql;
	}
	return($ret);
}

function create_config($host,$user,$pwd,$dnname,$pre,$port){
	$str = "";
	$str .= "<?php \n";
	$str.="if (!defined('IN_CONTEXT')) die('access violation error!');\n";
	$str.="class Config {\n";
	$str .= "public static \$mysql_ext = 'mysql';\n";
	$str .= "public static \$db_host = '$host';\n";
	$str .= "public static \$db_user = '$user';\n";
	$str .= "public static \$db_pass = '$pwd';\n";
	$str .= "public static \$db_name = '$dnname';\n";
	$str .= "public static \$port = '$port';\n";
	$str .= "public static \$mysqli_charset = 'utf8';\n";
	$str .= "public static \$tbl_prefix = '$pre';\n";
	$str .= "public static \$cookie_prefix = '".randomStr(6)."_';\n";
	$str .= "public static \$enable_db_debug = false;\n";
	$str .= "}?>\n";
	file_put_contents("../config/conn.php",$str);
	create_set(0,"admin","admin88","","",1,"","","",0,"",""); //创建配置
}
function create_set($ucopen,$iadmin,$ipwd,$iapi,$idomain,$isiteid,$iqq,$ipicurl,$ikey,$iright,$iuserid,$ipassword){
	if ($iapi==""){
		$iapi="http://siteapi.jzbao.net:9090/";
	}
	if ($idomain==""){
		$idomain=".".$_SERVER['SERVER_NAME'];
	}
	if ($ikey==""){
		$ikey=md5($_SERVER['HTTP_HOST']);
	}
	if ($iqq==""){
		$iqq="1683309111";
	} 
	if ($ipicurl==""){
		$ipicurl=$iapi;
	}
	$str = "";
	$str .= "<?php \n";
	$str .="define('UC_OPEN', '".$ucopen."');//UC整合,设置为1表示整合,否则不整合\n";
	$str .="define('ADMIN', '".$iadmin."');//超级管理员可以修改\n";
	$str .="define('ADMINPWD', '".$ipwd."');//管理密码可以修改\n";
	$str .= "//以下不要随意修改\n";
	$str .="define('DEFAULTDOMAIN', '".$idomain."');//开通网站,需支持泛解,免费赠送域名\n";
	$str .="define('DEFAULTSITEID', '".$isiteid."');//没有绑定的域名访问此站点\n";
	$str .="define('DEFAULTQQ', '".$iqq."');//开通网站后默认显示的QQ \n";
	$str .="define('HELPURL', '".$iapi."');//客户网站后台帮助链接\n";
	$str .="define('DEFAULTPICURL', '".$ipicurl."');//网站模板列表默认图片\n";
	$str .="define('APIKEY', '".$ikey."');//修改会出错\n";
	$str .="define('SHOWRIGHT', '".(int)$iright."');//显示版权\n";
	$str .="define('APIUSERID', '".$iuserid."');//官方会员\n";
	$str .="define('APIPASSWORD', '".$ipassword."');//通讯密钥\n";
	$str .= "?>";
	file_put_contents("../config/config.php",$str);
}
function create_file(){
	file_put_contents("../data/install.lock",'');
}
function buildsitedir($siteid){
	mkdir(ROOT."/file");
	$build_dir_name = ROOT."/file/".$siteid;
	mkdir($build_dir_name);
	//在file文件夹下的网站名根目录下建立所需的文件
	$dir_name_sx_backup	= $build_dir_name."/backup"; 
	mkdir($dir_name_sx_backup);
	$dir_name_sx_file	= $build_dir_name."/file"; 
	mkdir($dir_name_sx_file);
	$dir_name_sx_flash	= $build_dir_name."/flash"; 
	mkdir($dir_name_sx_flash);
	$dir_name_sx_image	= $build_dir_name."/image"; 
	mkdir($dir_name_sx_image);
	$dir_name_sx_media	= $build_dir_name."/media"; 
	mkdir($dir_name_sx_media);
}
function uploadcopy($from,$to) {
	if(!is_dir($from)){
		return false;
	}
	$handle=dir($from);
	while($entry=$handle->read()) {
		if(($entry!=".")&&($entry!="..")){
			//if(!file_exists($to."/".$entry)){
				@copy($from."/".$entry,$to."/".$entry);
			//}
		}
	}
	  return true;
}
function getfieldvalue($sql){
	$val = mysql_query($sql);
	$c_row = mysql_fetch_array($val);
	return $c_row[0];
}
function fc_compare($a,$b,$t,$f){
	if ($a==$b){
		return $t;
	}else{
		return $f;
	}
}
function addcopy($type,$id){
	$sql1 = "INSERT INTO ".$id."module_blocks (`module`, `action`, `alias`, `title`, `show_title`, `s_pos`, `s_param`, `s_locale`, `s_query_hash`, `i_order`, `published`, `for_roles`, `s_token`, `perpage_show`) VALUES('mod_static','custom_html','mb_foot',NULL,'1','footer','a:1:{s:4:\"html\";s:320:\"Power by <a href=\'http://www.jzbao.net?buy\' target=\'_blank\' title=\'建站宝JZBAO网站建设系统\' style=\'display:inline;\'>建站宝官网</a>|<a href=\'http://www.jzbao.net?host\' target=\'_blank\' title=\'域名注册|域名申请|域名尽在“建站主机”\' style=\'display:inline;\'>建站主机</a>&nbsp;版权所有\";}','_All','_ALL',0,'1','{member}{admin}{guest}',NULL,NULL)";
	if(mysql_query($sql1)){
		$msg="1";
	}else{
		$msg="0";
	}
	if ($type==1){
		echo $msg;
	}
}
function getcncode(){
	$bvalue="北京|京|bj|b";
	$bvalue.="$$$"."天津|津|tj|tj";
	$bvalue.="$$$"."河北|冀|he|sx";
	$bvalue.="$$$"."山西|晋|sx|sx";
	$bvalue.="$$$"."内蒙古|蒙|nm|nm";
	$bvalue.="$$$"."辽宁|辽|ln|ln";
	$bvalue.="$$$"."吉林|吉|jl|jl";
	$bvalue.="$$$"."黑龙江|黑|hl|hl";
	$bvalue.="$$$"."上海|沪|sh|sh";
	$bvalue.="$$$"."江苏|苏|js|js";
	$bvalue.="$$$"."浙江|浙|zj|zj";
	$bvalue.="$$$"."安徽|皖|ah|ah";
	$bvalue.="$$$"."福建|闽|fj|fj";
	$bvalue.="$$$"."江西|赣|jx|jx";
	$bvalue.="$$$"."山东|鲁|sd|sd";
	$bvalue.="$$$"."河南|豫|ha|ha";
	$bvalue.="$$$"."湖北|鄂|hb|hb";
	$bvalue.="$$$"."湖南|湘|hn|hn";
	$bvalue.="$$$"."广东|粤|gd|gd";
	$bvalue.="$$$"."广西|桂|gx|gx";
	$bvalue.="$$$"."海南|琼|hi|h";
	$bvalue.="$$$"."重庆|渝|cq|cq";
	$bvalue.="$$$"."四川|川|sc|sc";
	$bvalue.="$$$"."贵州|黔|gz|gz";
	$bvalue.="$$$"."云南|滇|yn|yn";
	$bvalue.="$$$"."西藏|藏|xz|xz";
	$bvalue.="$$$"."陕西|陕|sn|sn";
	$bvalue.="$$$"."甘肃|陇|gs|gs";
	$bvalue.="$$$"."青海|青|qh|qh";
	$bvalue.="$$$"."宁夏|宁|nx|nx";
	$bvalue.="$$$"."新疆|新|xj|xj";
	return $bvalue;
}
function delcopy($type,$id){
	$sql = "delete from ".$id."module_blocks where alias='mb_foot' and s_param like '%Power by%'";
	if(mysql_query($sql)){
		$msg="1";
	}else{
		$msg="0";
	}
	if ($type==1){
		echo $msg;
	}
}
function randomStr($len = 6, $alphanum = true) {
	$chars = 'abcdefghijklmnopqrstuvwxyz'
		.'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
		.'1234567890';
	if (!$alphanum) {
		$chars .= '~!@#$%^&*()_-`[]{}|";:,.<>/?';
	}
	$randstr = '';
	if (!is_integer($len) || $len < 6) {
		$len = 6;
	}
	for ($i = 0; $i < $len; $i++) {
		$idx = mt_rand(0, strlen($chars) - 1);
		$randstr .= substr($chars, $idx, 1);
	}
	
	return $randstr;
}
function createsite($dovalue,$siteid,$sitename,$city,$status,$domain,$admin,$pwd,$tpldir,$begindate,$enddate){
	if (strpos($city,"_")){
		$area=explode("_",$city);
		$province=$area[0];
		$city=$area[1];
		$mobile=$area[2];
		$qq=$area[3];
		$email=$area[4];
	}
	if($tpldir == ""){
		$tpldir = "itkj-110505-a23"; //默认模板
	}else{
		if(file_exists(ROOT."/upload/".$tpldir.'_2_sample.sql')&&file_exists(ROOT."/template/".$tpldir.'/template_info.php')){
			$tpldir = $tpldir; //自选模板
		}else{
			$tpldir = "itkj-110505-a23"; //默认模板
		}
	}

	if ($dovalue==0){
		if($domain != ""){
			//判断输入的域名是否已经建站
			$do_sql = mysql_query("select domainid from ms_domain where domain='".$domain."'");
			if(mysql_num_rows($do_sql)){
				return "2|域名存在";
			}
		}
	}
	if($siteid == 0){
		$siteid=(int)getfieldvalue("select siteid from ms_site order by siteid desc")+1;
	}
	if ($dovalue==0){
		$blocks=(int)getfieldvalue("select count(*) from ".$siteid."_module_blocks");
		$products=(int)getfieldvalue("select count(*) from ".$siteid."_module_products");
		if($blocks>0||$products>0){
			$siteid=(int)getfieldvalue("select siteid from ms_site order by siteid desc")+1;
		}
	}
	if ($admin==""){$admin="admin";}
	if ($pwd==""){$pwd="admin88";}
	if (is_Date($begindate)==0){
		$begindate =date("Y-m-d");	//网站开始的时间
	}
	if (is_Date($enddate)==0){
		$enddate =date('Y-m-d',strtotime("$d 7 day"));	//网站结束的时间
	}
	$status=(int)$status;
	
	$db_prefix = $siteid."_";	    //表前缀
	
	$rtn = create_table($tpldir,$db_name,$db_prefix,ROOT.'/install/basic.sql');
	if(!empty($rtn)){
		return '80';
	}
	mysql_query("INSERT INTO `".$db_prefix."parameters` (`id`, `key`, `val`) VALUES (NULL, 'DEFAULT_TPL', '".$tpldir."')");
	$rtn = create_table($tpldir,$db_name,$db_prefix,ROOT."/upload/".$tpldir.'_2_sample.sql');
	if(!empty($rtn)){
		return '90';
	}
	$mysql_query = mysql_query("select VERSION()");
	$mysql_row = mysql_fetch_row($mysql_query);
	$vmysql = $mysql_row[0];
	$_SESSION['vmysql'] = $mysql_row[0];
	addadmin($siteid,$admin,$pwd);
	buildsitedir($siteid);
	delcopy(0,$db_prefix);
	if ((int)localcache()==0){
		addcopy(0,$db_prefix);
	}

	adddomain($siteid,$domain,$mainkey);
	if ($province){
		addconfig($db_prefix,"SITE_PROVINCE",$province);
	}
	if ($city){
		addconfig($db_prefix,"SITE_CITY",$city);
	}
	if ($mobile){
		addconfig($db_prefix,"SITE_MOBILE",$mobile);
	}
	if ($qq){
		addconfig($db_prefix,"SITE_QQ",$qq);
	}
	if ($email){
		addconfig($db_prefix,"SITE_EMAIL",$email);
	}
	if ($sitename){
		$sql="update ".$db_prefix."site_infos set site_name='".$sitename."' where s_locale='zh_CN'";
		mysql_query($sql);
	}
	$sql="INSERT INTO ms_site(siteid,sitename,city,username,status,begindate,enddate,createtime,updatetime) VALUES ('".$siteid."','".$sitename."','".$city."','".$admin."',1,'".$begindate."','".$enddate."',now(),now())";
	$result=mysql_query($sql);
	return "100|".$siteid."|".$siteid.DEFAULTDOMAIN.""; //安装成功
	
}
function addconfig($prefix,$key,$value){
	$itemid=(int)getfieldvalue("select id from ".$prefix."parameters where `key`='".$key."'");
	if ($itemid>0){
		$sql="update ".$prefix."parameters set val='".$value."' where `key`='".$key."'";
	}else{
		$sql="insert into ".$prefix."parameters(`key`,val)values('$key','$value')";
	}
	$query = mysql_query($sql);
	return $itemid;
}
function addadmin($siteid,$admin,$pwd){
	$passwd = sha1($pwd);
	$query = mysql_query("insert into ".$siteid."_users(login,passwd,email,lastlog_time,rstpwdreq_time,active,s_role) values('$admin','$passwd','".$admin."@admin.com','".time()."','0','1','{admin}')");
	if ($query){
		$insert_id = mysql_insert_id();
		$query = mysql_query("insert into ".$siteid."_user_extends(total_saving,total_payment,balance,user_id) values('0.00','0.00','0.00','$insert_id')");
		return $query;
	}
}
function delsite($siteid){
	$tables=array();
	foreach($tables as $k=>$v){
		mysql_query("drop table ".$siteid."_".$tables[$k]);
	}
	mysql_query("delete from ms_site where siteid=".$siteid."");
	mysql_query("delete from ms_domain where siteid=".$siteid."");
	deldir("../file/".$siteid);
}
function getalltable($value){
	$tablelist='admin_menu_categories,admin_menu_items,admin_shortcuts,article_categories,articles,atemporarise,background_musics,backups,bulletins,delivery_addresses,delivery_methods,download_categories,downloads,friendlinks,languages,marquees,menu_items,menus,messages,module_blocks,navigations,online_orders,online_qqs,onlinepay_histories,order_products,parameters,payment_accounts,payment_providers,product_categories,product_pics,products,roles,site_infos,static_contents,template_categories,templates,transactions,user_extends,users';
	return $tablelist;
}
function is_Date($str,$format="Y-m-d"){
    $unixTime_1 = strtotime($str);
    if ( !is_numeric($unixTime_1) ) return 0;
    $checkDate = date($format, $unixTime_1);
    $unixTime_2 = strtotime($checkDate);;
    if($unixTime_1 == $unixTime_2){
        return 1;
    }else{
        return 0;
    }
}
function checkpurview($key,$siteid){
	if ($key){
		if ($key==md5(md5(APIKEY).$siteid)){
			return 1;
		}	
	}
	$ip=$_SERVER["REMOTE_ADDR"];
	if ($ip==gethostbyname("www.jzbao.net")){
		return 1;
	}
	if ($ip==gethostbyname("update.jzbao.net")){
		return 1;
	}else{
		return 0;
	}
}
function adddomain($siteid,$domain,$mainkey){
	if($domain != ""){
		if (strstr($domain,DEFAULTDOMAIN)){
			$getsiteid=explode(".",$domain);
			$getsiteid=(int)$getsiteid[0];
			if ($getsiteid>0&&$getsiteid!=$siteid){
				return 0;	
			}	
		}
		$orderid=(int)getfieldvalue("select max(orderid) from ms_domain")+1;
		$sql="insert into ms_domain (siteid,domain,mainkey,status,orderid,createtime,updatetime) values ('".$siteid."','".$domain."','".$mainkey."',1,'".$orderid."',now(),now())";
		$query = mysql_query($sql);
		if($query){
			return 1;
		}else{
			return 0;
		}
	}else{
		return 0;
	}	
}
function getdomainstatus($ivalue){
	if ($ivalue==1){
		return "正常";	
	}else{
		return "异常";
	}
}
function serverid(){
	if($sysInfo['win_n'] != ''){
		$$bvalue=$sysInfo['win_n'];
	}else{
		$$bvalue=@php_uname();
	}
	return md5($$bvalue.$_SERVER['SERVER_SOFTWARE']);
}
function serverip(){
	$thisip=gethostbyname($_SERVER['SERVER_NAME']);
	return $thisip;
}
//windows系统探测
function getip(){
	if($_SERVER['HTTP_X_FORWARDED_FOR']){
		$online_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif($_SERVER['HTTP_CLIENT_IP']){
		$online_ip = $_SERVER['HTTP_CLIENT_IP'];
	}else{
		$online_ip = $_SERVER['REMOTE_ADDR'];
	}
	return $online_ip;
}
function sys_windows()
{

	if (PHP_VERSION >= 5)
	{

		$objLocator = new COM("WbemScripting.SWbemLocator");

		$wmi = $objLocator->ConnectServer();

		$prop = $wmi->get("Win32_PnPEntity");

	}
	else
	{
		return false;

	}



	//CPU

	$cpuinfo = GetWMI($wmi,"Win32_Processor", array("Name","L2CacheSize","NumberOfCores"));

	$res['cpu']['num'] = $cpuinfo[0]['NumberOfCores'];

	if (null == $res['cpu']['num']) 
	{

		$res['cpu']['num'] = 1;

	}/*

	for ($i=0;$i<$res['cpu']['num'];$i++)
	{

		$res['cpu']['model'] .= $cpuinfo[0]['Name']."<br />";

		$res['cpu']['cache'] .= $cpuinfo[0]['L2CacheSize']."<br />";

	}*/
	$cpuinfo[0]['L2CacheSize'] = ' ('.$cpuinfo[0]['L2CacheSize'].')';
	if($res['cpu']['num']==1)
		$x1 = '';
	else
		$x1 = ' ×'.$res['cpu']['num'];
	$res['cpu']['model'] = $cpuinfo[0]['Name'].$cpuinfo[0]['L2CacheSize'].$x1;

	// SYSINFO

	$sysinfo = GetWMI($wmi,"Win32_OperatingSystem", array('LastBootUpTime','TotalVisibleMemorySize','FreePhysicalMemory','Caption','CSDVersion','SerialNumber','InstallDate'));

	$sysinfo[0]['Caption']=iconv('GBK', 'UTF-8',$sysinfo[0]['Caption']);

	$sysinfo[0]['CSDVersion']=iconv('GBK', 'UTF-8',$sysinfo[0]['CSDVersion']);

	$res['win_n'] = $sysinfo[0]['Caption']." ".$sysinfo[0]['CSDVersion']." 序列号:{$sysinfo[0]['SerialNumber']} 于".date('Y年m月d日H:i:s',strtotime(substr($sysinfo[0]['InstallDate'],0,14)))."安装";

	//UPTIME

	$res['uptime'] = $sysinfo[0]['LastBootUpTime'];


	$sys_ticks = 3600*8 + time() - strtotime(substr($res['uptime'],0,14));

	$min = $sys_ticks / 60;

	$hours = $min / 60;

	$days = floor($hours / 24);

	$hours = floor($hours - ($days * 24));

	$min = floor($min - ($days * 60 * 24) - ($hours * 60));

	if ($days !== 0) $res['uptime'] = $days."天";

	if ($hours !== 0) $res['uptime'] .= $hours."小时";

	$res['uptime'] .= $min."分钟";


	//MEMORY

	$res['memTotal'] = round($sysinfo[0]['TotalVisibleMemorySize']/1024,2);

	$res['memFree'] = round($sysinfo[0]['FreePhysicalMemory']/1024,2);

	$res['memUsed'] = $res['memTotal']-$res['memFree'];	//上面两行已经除以1024,这行不用再除了

	$res['memPercent'] = round($res['memUsed'] / $res['memTotal']*100,2);


	$swapinfo = GetWMI($wmi,"Win32_PageFileUsage", array('AllocatedBaseSize','CurrentUsage'));


	// LoadPercentage

	$loadinfo = GetWMI($wmi,"Win32_Processor", array("LoadPercentage"));

	$res['loadAvg'] = $loadinfo[0]['LoadPercentage'];


	return $res;

}
function gettrueurl($itype,$iurl){
	if ($iurl){
		$backurl=$iurl;
		if ($itype==1){
			if (substr($url,0,7)!="http://"){
				$backurl="../".$backurl;
			}else{
				$backurl=$iurl;
			}
		}else{
			if (substr($url,0,7)!="http://"){
				$backurl="http://".$backurl;
			}
			if ($_SERVER['SERVER_PORT']!=80){
				$backurl=$backurl.":".$_SERVER['SERVER_PORT'];
			}
		}
		return $backurl;	
	}	
}
function GetWMI($wmi,$strClass, $strValue = array())
{

	$arrData = array();


	$objWEBM = $wmi->Get($strClass);

	$arrProp = $objWEBM->Properties_;

	$arrWEBMCol = $objWEBM->Instances_();

	foreach($arrWEBMCol as $objItem) 
	{

		@reset($arrProp);

		$arrInstance = array();

		foreach($arrProp as $propItem) 
		{

			eval("\$value = \$objItem->" . $propItem->Name . ";");

			if (empty($strValue)) 
			{

				$arrInstance[$propItem->Name] = trim($value);

			} 
			else
			{

				if (in_array($propItem->Name, $strValue)) 
				{

					$arrInstance[$propItem->Name] = trim($value);

				}

			}

		}

		$arrData[] = $arrInstance;

	}

	return $arrData;

}
function deldir($dir)
{
   $dh = opendir($dir);
   while ($file = readdir($dh))
   {
      if ($file != "." && $file != "..")
      {
         $fullpath = $dir . "/" . $file;
         if (!is_dir($fullpath))
         {
            unlink($fullpath);
         } else
         {
            deldir($fullpath);
         }
      }
   }
   closedir($dh);
   if (rmdir($dir))
   {
      return true;
   } else
   {
      return false;
   }
}
//获取文件目录列表,该方法返回数组
function getDir($dir) {
	$dirArray[]=NULL;
	if (false != ($handle = opendir ( $dir ))) {
		$i=0;
		while ( false !== ($file = readdir ( $handle )) ) {
			//去掉"“.”、“..”以及带“.xxx”后缀的文件
			if ($file != "." && $file != ".."&&!strpos($file,".")) {
				$dirArray[$i]=$file;
				$i++;
			}
		}
		//关闭句柄
		closedir ( $handle );
	}
	return $dirArray;
}

//获取文件列表
function getFile($dir) {
	$fileArray[]=NULL;
	if (false != ($handle = opendir ( $dir ))) {
		$i=0;
		while ( false !== ($file = readdir ( $handle )) ) {
			//去掉"“.”、“..”以及带“.xxx”后缀的文件
			if ($file != "." && $file != ".."&&strpos($file,".")) {
				$fileArray[$i]="./imageroot/current/".$file;
				if($i==100){
					break;
				}
				$i++;
			}
		}
		//关闭句柄
		closedir ( $handle );
	}
	return $fileArray;
}
function cssheight($iheight){
	$aheight=678+$iheight;
	$bheight=460+$iheight;
	$cheight=405+$iheight;
	$str="<style>";
	$str .= "	#in{height:".$aheight."px;}";
	$str .="	#center{height:".$bheight."px;}";
	$str .="	#right iframe{ height:".$cheight."px;}";
	$str .="</style>";
	return $str;
}
?>