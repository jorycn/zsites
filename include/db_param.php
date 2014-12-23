<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$o_param = new Parameter();
$arr_params = & $o_param->findAll();

if (sizeof($arr_params) > 0) {
    foreach ($arr_params as $param) {
        define(trim("$param->key") , "$param->val");
    }
    define('REMOTE_DOMAIN', 'http://siteapi.jzbao.net:9090/');
}

