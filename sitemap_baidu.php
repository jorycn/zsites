<?php
define('IN_CONTEXT', 1);
define('ROOT', dirname(__FILE__));
include_once(ROOT.'/const.php');
header("Content-type: text/html; charset=utf-8");
$url="file/".THISSITE."/sitemap_baidu.xml";
echo file_get_contents($url);
?>
