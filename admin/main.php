<?php
define('IN_CONTEXT', 1);
define('ROOT', realpath(dirname(__FILE__).'/..'));
include_once('../const.php');
echo " <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \n";
echo "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\"><head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
echo "<link href=\"images/master/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
echo "<script language=\"JavaScript\"> \n";
echo "<!--\n";
echo "if (this.location.href == top.location.href){\n";
echo "    top.location.href = \"\";\n";
echo "}\n";
echo "-->\n";
echo "</script>\n";
echo "  <STYLE TYPE=\"text/css\">\n";
echo "    <!--\n";
echo "    .calCurrentDay  {background-color: #FFCC00; font-size: 12px; color: #000000;}\n";
echo "    .calOtherDay    {background-color: #FFFFFF; font-size: 12px; color: #000000;}\n";
echo "    .calNotDay	    {background-color: #FFFFFF; font-size: 12px; color: #000000;}\n";
echo "    -->\n";
echo "  </STYLE>\n";
echo "</head>\n";
	
echo "<body id=\"main_page\">\n";
echo "<div class=\"wrap\">\n";
echo "    <div class=\"container\">\n";
echo "        <div id=\"main\">\n";
echo "            <div class=\"con\">\n";
echo "                <div class=\"table\">\n";
echo "        <h2 class=\"th\" style=\"padding-left:15px;\">欢迎光临,您当前操作的是站点".THISSITE.",想建立遍布全省或全国的站群,请联系管理员".DEFAULTQQ."</h2>";
echo "                    <table>\n";
if (1==1){
echo "                        <tr>\n";
echo "                        <td width=\"10%\" \n";
echo "style=\"color:#DD0000;font-weight:bold;height:30px;\">待处理信息∶</td>\n";
echo "                        <td width=\"15%\" style=\"text-align:center;\"><a \n";
echo "href=\"index.php?_m=mod_order&_a=admin_list\">未支付订单 <B style=\"color:#ff3300;\">".getfieldvalue("select count(*) from ".THISSITE."_online_orders where order_status<3")."</B> 条</a></td>\n";
echo "                        <td width=\"15%\" style=\"text-align:center;\"><a \n";
echo "href=\"index.php?_m=mod_message&_a=admin_list\">待审核留言 <B style=\"color:#ff3300;\">".getfieldvalue("select count(*) from ".THISSITE."_messages where published=0")."</B> 条</a></td>\n";
echo "                        <td width=\"15%\" style=\"text-align:center;\"><a \n";
echo "href=\"index.php?_m=mod_user&_a=admin_list\">待审核会员数 <B style=\"color:#ff3300;\">".getfieldvalue("select count(*) from ".THISSITE."_users where member_verify=0")."</B> 条</a></td>\n";
echo "                        <td width=\"15%\" style=\"text-align:center;\"><a \n";
echo "href=\"index.php?_m=mod_product&_a=admin_list\">未发布产品数 <B style=\"color:#ff3300;\">".getfieldvalue("select count(*) from ".THISSITE."_products where published='0'")."</B> 条</a></td>\n";
echo "                        <td width=\"15%\" style=\"text-align:center;\"><a \n";
echo "href=\"index.php?_m=mod_article&_a=admin_list\">未发布文章数 <B style=\"color:#ff3300;\">".getfieldvalue("select count(*) from ".THISSITE."_articles where published='0'")."</B> 条</a></td>\n";
echo "                        <td width=\"15%\" style=\"text-align:center;\"><a \n";
echo "href=\"index.php?_m=mod_download&_a=admin_list\">未发布下载数 <B style=\"color:#ff3300;\">".getfieldvalue("select count(*) from ".THISSITE."_downloads where published='0'")."</B> 条</a></td>\n";
echo "                      </tr>\n";
echo "                        <tr>\n";
echo "                        <td width=\"10%\" \n";
echo "style=\"color:#DD0000;font-weight:bold;height:30px;\">信息统计∶</td>\n";
echo "                        <td width=\"15%\" style=\"text-align:center;\"><a \n";
echo "href=\"index.php?_m=mod_order&_a=admin_list\">全部订单 <B style=\"color:#ff3300;\">".getfieldvalue("select count(*) from ".THISSITE."_online_orders")."</B> 条</a></td>\n";
echo "                        <td width=\"15%\" style=\"text-align:center;\"><a \n";
echo "href=\"index.php?_m=mod_message&_a=admin_list\">全部留言 <B style=\"color:#ff3300;\">".getfieldvalue("select count(*) from ".THISSITE."_messages")."</B> 条</a></td>\n";
echo "                        <td width=\"15%\" style=\"text-align:center;\"><a \n";
echo "href=\"index.php?_m=mod_user&_a=admin_list\">全部会员数 <B style=\"color:#ff3300;\">".getfieldvalue("select count(*) from ".THISSITE."_users")."</B> 条</a></td>\n";
echo "                        <td width=\"15%\" style=\"text-align:center;\"><a \n";
echo "href=\"index.php?_m=mod_product&_a=admin_list\">全部产品数 <B style=\"color:#ff3300;\">".getfieldvalue("select count(*) from ".THISSITE."_products")."</B> 条</a></td>\n";
echo "                        <td width=\"15%\" style=\"text-align:center;\"><a \n";
echo "href=\"index.php?_m=mod_article&_a=admin_list\">全部文章数 <B style=\"color:#ff3300;\">".getfieldvalue("select count(*) from ".THISSITE."_articles")."</B> 条</a></td>\n";
echo "                        <td width=\"15%\" style=\"text-align:center;\"><a \n";
echo "href=\"index.php?_m=mod_download&_a=admin_list\">全部下载数 <B style=\"color:#ff3300;\">".getfieldvalue("select count(*) from ".THISSITE."_downloads")."</B> 条</a></td>\n";
echo "                      </tr>\n";
}
echo "                    </table>\n";
echo "                </div>\n";
//
$closeversion=(int)getfieldvalue("select val from ".THISSITE."_parameters where `key`='CLOSEVERSION'");
if ($closeversion==0){
echo "                <div class=\"table\">\n";
echo "        <h2 class=\"th\" style=\"padding-left:15px;\">正版授权信息(关闭授权信息显示请登录<a href='../system' target='_blank'>站群系统/修改站点".THISSITE."信息</a>中进行操作)</h2>";
echo "                    <table>\n";

echo "                        <tr>\n";
echo "                        <td width=\"10%\" \n";
echo "style=\"color:#DD0000;font-weight:bold;height:30px;\">授权信息∶</td>\n";
$domain = $_SERVER['HTTP_HOST'];
if(isset($_SERVER['SERVER_ADDR'])){
	$ip = $_SERVER['SERVER_ADDR'];
}else{
	$ip='127.0.0.1';
}
$system = preg_replace('/\s/','',PHP_OS);
$vphp = PHP_VERSION;
$winuser=@file_get_contents("../config/winuser.txt");
$sitename=getfieldvalue("select site_name from ".THISSITE."_site_infos");
$sitename=iconv("UTF-8", "GB2312", $sitename);
$check="h"."t"."t"."p".":"."/"."/"."u"."pd"."at"."e".".j"."z"."b"."a"."o"."."."n"."e"."t:"."9"."0"."9"."0"."/S"."er"."vi"."ce"."/C"."r"."m/Cr"."m"."Ge"."t/?action=checkgenuine&showtype=1&charset=utf-8&type=write&a=".urlencode($sitename)."&b=$domain&c=JZBAO&key=".APIKEY."&siteid=".THISSITE."&h=".$winuser."&ip=$ip&version=$version&vphp=$vphp&vmysql=$vmysql&tpl_name=$tpl_name&vos=$system&r=".ADMIN."&province=".SITE_PROVINCE."&city=".SITE_CITY."&d=".SITE_EMAIL."&e=".SITE_MOBILE."&q=".SITE_QQ."";
$result=@file_get_contents($check);
$result=iconv('GB2312', 'UTF-8', $result);
echo "                        <td width=\"85%\" style=\"text-align:center;\">".$result."</td>\n";
echo "                      </tr>\n";

echo "                    </table>\n";
echo "                </div>\n";
}
//
echo "           <!--/ con-->\n";
echo "        </div>    \n";
echo "    </div><!--/ container-->\n";
echo "</div><!--/ wrap-->\n";
if (1==2){
echo "<div class=\"wrap\">\n";
echo "  <div class=\"container\">\n";
echo "    <div id=\"main\">\n";
echo "      <div class=\"con\">\n";
echo "        <div class=\"table\">  \n";
echo "          <h2 class=\"th1\">\n";
echo "            <span style=\"float:left;padding-left:15px;color:#555;\">内部公文</span>\n";
echo "          </h2>         \n";
echo "          <h2 class=\"th2\">\n";
echo "            <span style=\"float:left;padding-left:15px;color:#555;\">企业新闻</span>\n";
echo "          </h2>         \n";
echo "          <table class=\"td1\">\n";
echo "            <tr>\n";
echo "              <td valign=\"top\" style=\"text-align:left;padding:10px;height:130px;\">\n";
echo "              </td>\n";
echo "            </tr>\n";
echo "          </table>\n";
echo "          <table class=\"td2\">\n";
echo "            <tr>\n";
echo "              <td valign=\"top\" style=\"text-align:left;padding:10px;height:130px;\">\n";
echo "<span style=\"float:right;\">2011-7-9 14:40:29</span>· <a href=\"../OA/News.asp?\n";
echo "action=view&ONid=3\">测试第三条新闻</a><BR>\n";
echo "<span style=\"float:right;\">2011-7-9 14:40:20</span>· <a href=\"../OA/News.asp?\n";
echo "action=view&ONid=2\">123</a><BR>\n";
echo "<span style=\"float:right;\">2011-7-9 14:40:16</span>· <a href=\"../OA/News.asp?\n";
echo "action=view&ONid=1\">123</a><BR>\n";
echo "              </td>\n";
echo "            </tr>\n";
echo "          </table>\n";
echo "        </div>\n";
echo "        <div style=\"clear:both;height:1px;\"></div>\n";

echo "      </div><!--/ con-->\n";
echo "    </div>    \n";
echo "  </div><!--/ container-->\n";
echo "</div><!--/ wrap-->\n";
}
echo "<BR>\n";
echo "<BR>\n";
microupdate(THISSITE);
buildsitedir(THISSITE);
echo "</body>\n";
echo "</html>\n";
echo "\n";
?>