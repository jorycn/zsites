<?php

if (!defined('IN_CONTEXT')) die('access violation error!');
class DataValidator {
public static function isNumeric($var) {
return is_numeric($var);
}
public static function isEmpty($var) {
$var = strip_tags($var);
$var = str_replace('&nbsp;','',$var);
return self::customMatch('/^\s*$/',$var);
}
public static function isEmail($var) {
$email_parts = explode('@',$var);
if (sizeof($email_parts) != 2) {
return false;
}
if (preg_match('/^\..*$/',trim($email_parts[0])) ||
preg_match('/^.*\.$/',trim($email_parts[0])) ||
preg_match('/^\..*\.$/',trim($email_parts[0]))) {
return false;
}
if (!preg_match('/^[0-9a-zA-Z\!#\$%\*\/\?\|\^\{\}`~&\'\+\-=_\.]+$/',trim($email_parts[0]))) {
return false;
}
if (preg_match('/\.\./',trim($email_parts[1]))) {
return false;
}
$domain_parts = explode('.',$email_parts[1]);
if (sizeof($domain_parts) <2) {
return false;
}
foreach ($domain_parts as $s) {
$s = trim($s);
if (preg_match('/^\-.*$/',$s) ||
preg_match('/^.*\-$/',$s) ||
preg_match('/^\-.*\-$/',$s)) {
return false;
}
if (!preg_match('/^[0-9a-zA-Z\-]+$/',$s)) {
return false;
}
}
return true;
}
public static function customMatch($regexp,$var) {
return preg_match($regexp,$var);
}
}