<?php

if (!defined('IN_CONTEXT')) die('access violation error!');
class Mailer {
public static function send($recipient,$template,
$params = array(),$recipient_name = '') {
try {
$template_info =&self::_loadTemplate($template);
if (!isset($template_info['type']) ||
!isset($template_info['subject']) ||
!isset($template_info['sender_name']) ||
!isset($template_info['body'])) {
$error = 'Missing mail template parameters!'."\n";
throw new MailerException($error);
}
if (sizeof($params) >0) {
$repl_entity = array();
$repl_value = array();
foreach ($params as $key =>$value) {
$repl_entity[] = '%%'.$key.'%%';
$repl_value[] = $value;
}
$template_info['subject'] = str_replace($repl_entity,$repl_value,
$template_info['subject']);
$template_info['body'] = str_replace($repl_entity,$repl_value,
$template_info['body']);
}
$mail = new PHPMailer();
$mail->SetLanguage('en',P_LIB.'/phpmailer/language/');
if (ENABLE_SMTP_DEBUG) {
$mail->SMTPDebug = true;
}
if (USE_SMTP) {
$mail->IsSMTP();
$mail->Host = SMTP_SERVER;
$mail->SMTPAuth = true;
$mail->Username = SMTP_USER;
$mail->Password = SMTP_PASS;
}else {
$mail->IsMail();
}
$mail->CharSet = MAIL_CHARSET;
$mail->From = SMTP_USER;
$mail->FromName = trim($template_info['sender_name']);
$mail->AddAddress($recipient,$recipient_name);
$mail->WordWrap = 80;
if (trim($template_info['type']) == '1') {
$mail->IsHTML(true);
}
$mail->Subject = $template_info['subject'];
$mail->Body = $template_info['body'];
if (!$mail->Send()) {
$error = $mail->ErrorInfo."\n";
throw new MailerException('Sending mail failed!'."\n"
.$error);
}else {
return true;
}
}catch (MailerException $ex) {
throw new MailerException($ex->getMessage());
}
}
private static function &_loadTemplate($template) {
$template_info = array();
$fp = @fopen(P_MTPL.'/'.$template.'.mtpl','r');
if ($fp) {
$tpl_section = '';
while (!feof($fp)) {
$line = fgets($fp);
switch (trim($line)) {
case '==SENDER_NAME==':
$tpl_section = 'sender_name';
break;
case '==IS_HTML==':
$tpl_section = 'type';
break;
case '==SUBJECT==':
$tpl_section = 'subject';
break;
case '==BODY==':
$tpl_section = 'body';
break;
default:
if (empty($tpl_section)) {
continue;
}
if (!isset($template_info[$tpl_section])) {
$template_info[$tpl_section] = '';
}
$template_info[$tpl_section] .= $line;
}
}
fclose($fp);
if (sizeof($template_info) == 0) {
$error = 'Mail template "'.$template.'" format error!'."\n";
throw new MailerException($error);
}else {
return $template_info;
}
}else {
$error = 'Mail template "'.$template.'" does not exist!'."\n";
throw new MailerException($error);
}
}
}
class MailerException extends Exception {
}