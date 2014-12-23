<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
function check_mod($module) {
if(EZSITE_LEVEL =='1'&&$module=='mod_cart'){
return false;
}
if (strpos(ALLOWED_MOD,$module.',') !== false)
return true;
else
return false;
}
$_STATIC_SEED = '83d5de05e4d9a6537a97bd6c70768db71a93596dca73880d42552c84b403786d';
$_EZ_SEED = '^MCHL$'.EZSITE_UID.$_STATIC_SEED.EZSITE_LEVEL.'EZLV$';
if(function_exists("hash")){
$_S = hash('sha256',$_EZ_SEED);
}else {
include_once 'hash.php';
$_S = SHA256::hash($_EZ_SEED);
}