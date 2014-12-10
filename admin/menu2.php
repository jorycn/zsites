<?php
error_reporting(0);
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
echo "<link href=\"images/master/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
echo "<script type=\"text/javascript\" src=\"images/master/jquery-1.3.2.min.js\"></script>\n";
echo "<script type=\"text/javascript\" src=\"images/master/sidebar.js\"></script>\n";
echo "</head>\n";
echo "<body id=\"sidebar_page\">\n";
echo "<div class=\"wrap\">\n";
echo "    <div class=\"cotainer\">\n";
echo "        <div id=\"sidebar\">\n";
echo "        <div class=\"con\">\n";
$action=$_REQUEST["action"];
if ($action==""){
echo "        <h2>快捷导航</h2>\n";
echo "        <ul>\n";
echo "          <li><a href='main.php' target='main' >欢迎首页</a></li>\n";
echo "        </ul>\n";
}
if ($action=="effect"){
echo "        <h2>效果查看</h2>\n";
echo "        <ul>\n";
echo "          <li><a href='index.php?_m=mod_order&_a=admin_list' target='main' >订单查看</a></li>\n";
echo "		  \n";
echo "          <li><a href='index.php?_m=mod_message&_a=admin_list' target='main' >留言查看</a></li>\n";
echo "		  \n";
echo "          <li><a href='index.php?_m=mod_user&_a=admin_list' target='main' >会员查看</a></li>\n";
echo "        </ul>\n";
}
if ($action=="content"){
echo "        <h2>内容管理</h2>\n";
echo "        <ul>\n";
echo "          <li><a href='index.php?_m=mod_product&_a=admin_list' target='main' >产品管理</a></li>\n";
echo "		  \n";
echo "          <li><a href='index.php?_m=mod_article&_a=admin_list' target='main' >文章管理</a></li>\n";
echo "		  \n";
echo "          <li><a href='index.php?_m=mod_download&_a=admin_list' target='main' >下载管理</a></li>\n";
echo "		  \n";
echo "          <li><a href='index.php?_m=mod_bulletin&_a=admin_list' target='main' >公告管理</a></li>\n";
echo "        </ul>\n";
}
if ($action=="system"){
echo "        <h2>系统设置</h2>\n";
echo "        <ul>\n";
echo "          <li><a href='index.php?_m=mod_site&_a=admin_list&rurl=1' target='main' >基本设置</a></li>\n";
echo "          <li><a href='index.php?_m=mod_site&_a=admin_seo&rurl=1' target='main' >SEO设置</a></li>\n";
echo "          <li><a href='index.php?_m=mod_lang&_a=admin_list' target='main' >多语言站点</a></li>\n";
echo "          <li><a href='index.php?_m=mod_payaccount&_a=admin_list' target='main' >在线支付</a></li>\n";
echo "          <li><a href='index.php?_m=mod_statistics&_a=admin_list' target='main' >网站统计</a></li>\n";
echo "          <li><a href='index.php?_m=mod_backup&_a=admin_list' target='main' >数据备份</a></li>\n";
echo "        </ul>\n";
}
if ($action=="help"){
echo "        <h2>帮助中心</h2>\n";
echo "        <ul>\n";
echo "          <li><a href='index.php?_m=mod_article&_a=admin_list' target='main' >在线教程</a></li>\n";
echo "		  \n";
echo "          <li><a href='main.php' target='main' >联系我们</a></li>\n";
echo "        </ul>\n";
}
echo "        </div><!--/ .con-->\n";
echo "        </div><!--/ sidebar-->\n";
echo "    </div>\n";
echo "</div> \n";
echo "<script type=\"text/javascript\"> \n";
echo "$(document).ready(function(){\n";
echo "    var aArr = $(\".con\").find(\"li:first a\");\n";
echo "  if (aArr && aArr.html() == \"\") or (aarr && aarr.html()==\"\")\n";
echo "    {\n";
echo "        aArr.addClass(\"active\");\n";
echo "        $('#main', window.parent.document).attr('src', aArr.attr('href'));\n";
echo "    }\n";
echo "})\n";
echo "</script>\n";
echo "</body>\n";
echo "</html>\n";
echo "\n";
?>