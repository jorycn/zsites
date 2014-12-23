<?php
define('IN_CONTEXT', 1);
if (!defined('IN_CONTEXT')) die('access violation error!');

date_default_timezone_set("Asia/ShangHai");

define('DS', DIRECTORY_SEPARATOR);
define('IS_INSTALL', 1); // 0:share 1:install

define('ROOT', realpath(dirname(__FILE__).'/..'));
if ( IS_INSTALL ) {
	$lockfile = ROOT.'/data/install.lock';
	if(!file_exists($lockfile)) {
		echo 'please install JZBAO!';
		exit("<script>window.location.href='../install';</script>");
	}
}
define('ADMIN_ROOT', dirname(__FILE__));
define('P_FLT', ADMIN_ROOT.'/filter');
define('P_INC', ROOT.'/include');
define('P_LIB', ROOT.'/library');
define('P_MDL', ROOT.'/model');
define('P_MOD', ADMIN_ROOT.'/module');
define('P_MTPL', ADMIN_ROOT.'/m-template');

include_once(ROOT.'/const.php');
include_once(P_LIB.'/memorycache.php');
include_once(P_LIB.'/to_pinyin.php');
include_once(P_LIB.'/toolkit.php');
include_once(P_INC.'/json_encode.php');
//include_once(P_INC.'/china_ds_data.php');

header("Content-type: text/html; charset=utf-8");

include_once(P_LIB.'/'.Config::$mysql_ext.'.php');
$db = new MysqlConnection(
    Config::$db_host,
    Config::$db_user,
    Config::$db_pass,
    Config::$db_name
);
if (Config::$enable_db_debug === true) {
    $db->debug = true;
}

include_once(P_INC.'/autoload.php');

define('CACHE_DIR', ROOT.'/cache');
include_once(P_LIB.'/record.php');
include_once(P_LIB.'/validator.php');

include_once(P_INC.'/db_param.php');
include_once(P_INC.'/userlevel.php');

if (intval(DB_SESSION) == 1) {
    include_once(P_LIB.'/session_db.php');
}

include_once(P_INC.'/magic_quotes.php');

define('P_TPL', ADMIN_ROOT.'/template');
define('P_SCP', '../script');
define('P_TPL_WEB', 'template');

include_once(P_LIB.'/pager.php');
//include_once(P_LIB.'/rand_math.php');

include_once(P_LIB.'/param.php');
include_once(P_LIB.'/notice.php');
SessionHolder::initialize();
Notice::dump();

/**
 * Edit 02/08/2010
 */
$act =& ParamHolder::get('_m');
switch ($act) {
	case 'mod_order':
		include_once(P_INC.'/china_ds_data.php');
		break;
	case 'frontpage':
		include_once(P_LIB.'/rand_math.php');
		break;
}

define('P_LOCALE', ADMIN_ROOT.'/locale');
//include_once(P_LIB.'/php-gettext/gettext.inc');
include_once(P_INC.'/locale.php');

include_once(P_INC.'/siteinfo.php');

include_once(P_LIB.'/acl.php');
ACL::loginGuest();

include_once(P_LIB.'/module.php');
include_once(P_LIB.'/form.php');

include_once(P_LIB.'/content.php');
include_once(P_INC.'/global_filters.php');

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \n";
echo "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
echo "<head>\n";
echo "<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\" />\n";
echo "<title>admin</title>\n";
echo "<link href=\"template/css/all.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
echo "<link href=\"template/css/baselist.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
echo "</head>\n";
echo "<body>\n";
echo "<div id=\"headbg\">\n";
echo "	<div id=\"head\">\n";
echo "		<div class=\"logo\"><div id=\"logo\"></div></div>\n";
echo "		<div id=\"botton\">\n";
echo "			<ul>\n";
echo "				<li id=\"topleftbg\"></li>\n";
echo "				<li id=\"bottonbg\">";
$o_locale = new Parameter();
	$locale_items = $o_locale->findAll("`key` = 'DEFAULT_LOCALE'");
	$_user = SessionHolder::get('user/login');
	/*if($locale_items[0]->val == 'zh_CN') {
		$showMsg = '您好尊敬的'.$_user.'会员';
	} elseif($locale_items[0]->val == 'en') {
		$showMsg = 'Hello,'.$_user;
	}*/
	echo __('Hello,').$_user;
	echo "&nbsp;";
echo "</li>\n";
if($_user == "admin"){
echo "				<li id=\"bottonbg\"><a class=\"tabclicklink\" \n";
echo "target=\"main\" hidefocus=\"true\" href=\"index.php?_m=mod_user&_a=admin_edit&u_id=1\">修改密码</a></li>\n";
}
echo "				<li id=\"bottonbg\"><a class=\"annex\" hidefocus=\"true\" \n";
echo "href=\"../\" target=\"_blank\">网站预览</a></li>\n";
echo "				<li id=\"bottonbg\"><a class=\"annex\" hidefocus=\"true\" \n";
echo "href=\"".HELPURL."\" target=\"_blank\">使用帮助</a></li>\n";
echo "				<li id=\"bottonbg\"><a class=\"tabclicklink\" \n";
echo "hidefocus=\"true\" href=".Html::uriquery('frontpage', 'dologout')." target=\"_top\">";
echo _e('Logout');
echo "</a></li>\n";
echo "				<li id=\"rightbg\"></li>\n";
echo "			</ul>\n";
echo "		</div>\n";
echo "	</div>\n";
echo "	<div class=\"menubotton\" id=\"menu\">\n";
echo "		<ul class=\"topnav\" id=\"clickmenubotton\">\n";
echo "		<li><a href=\"menu.php\" target=\"menu\"><span class=\"bgmenuhove\" \n";
echo "id=\"item0\" onclick=\"Tabmenu(this,0);\">欢迎页</span></a></li>\n";
echo "		<li><a href=\"menu.php?action=effect\" target=\"menu\"><span id=\"item1\" \n";
echo "onclick=\"Tabmenu(this,1);\">效果</span></a></li>\n";
echo "		<li><a href=\"menu.php?action=content\" target=\"menu\"><span id=\"item2\" \n";
echo "onclick=\"Tabmenu(this,2);\">内容</span></a></li>\n";
echo "		<li><a href=\"menu.php?action=system\" target=\"menu\"><span id=\"item3\" \n";
echo "onclick=\"Tabmenu(this,3);\">系统</span></a></li>\n";
//echo "		<li><a href=\"menu.php?action=help\" target=\"menu\"><span id=\"item4\" \n";
//echo "onclick=\"Tabmenu(this,4);\">帮助</span></a></li>\n";
echo "	</li>\n";
echo "</ul>\n";
echo "	</div>\n";
echo "	<div class=\"menuline\"></div>\n";
echo "	<div class=\"menuline2\"></div>\n";
echo "</div>\n";
echo "<script type=\"text/javascript\"> \n";
echo "function Tabmenu(obj,n){\n";
echo "	var Items = document.getElementById(\"menu\").getElementsByTagName(\"span\");\n";
echo "	for(var i= 0,len = Items.length;i<len;++i){\n";
echo "		if(Items[i].clssName !==\"\"){\n";
echo "			Items[i].className = \"\";\n";
echo "		}\n";
echo "		obj.className = \"bgmenuhove\";\n";
echo "		obj.blur();\n";
echo "		location.hash = n;\n";
echo "	}\n";
echo "};\n";
echo "(function(){\n";
echo "var n = location.hash.replace(\"#\",\"\");\n";
echo "if(!n){ n = 0;}\n";
echo "var curitem = document.getElementById(\"item\"+n);\n";
echo "	Tabmenu(curitem,n);\n";
echo "})();\n";
echo "</script>\n";
echo "</body> \n";
echo "</html>\n";
$db->close();
?>