<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$curr_locale = DEFAULT_LOCALE;
$r_lang = trim(ParamHolder::get('_l',''));
if($r_lang){
SessionHolder::set('SS_LOCALE',$r_lang);
}
if(SessionHolder::get('SS_LOCALE')){
$curr_locale = SessionHolder::get('SS_LOCALE');
}
SessionHolder::set('_LOCALE',$curr_locale);
$lang = include_once P_LOCALE.'/'.$curr_locale.'/lang.php';
function __($msgid){
global $lang;
if(array_key_exists($msgid,$lang)){
return $lang[$msgid];
}else{
return $msgid;
}
}
function _e($msgid){
global $lang;
if(array_key_exists($msgid,$lang)){
echo $lang[$msgid];
}else{
echo $msgid;
}
}