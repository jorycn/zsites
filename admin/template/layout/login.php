<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php _e('charset'); ?>" />
        <title><?php echo $page_title; ?> -- <?php if((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)){ echo '企业网站后台管理系统';}else{ echo $_SITE->site_name;} ?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo P_TPL_WEB; ?>/css/all.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo P_TPL_WEB; ?>/css/login.css" />
        <script type="text/javascript" language="javascript" src="<?php echo P_SCP; ?>/jquery.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo P_SCP; ?>/helper.js"></script>
		<script type="text/javascript"> 
			$(document).ready(function(){
				var h = $(window).height();
				$('#centerlogin').css({height:h});
			});
			var resizeTimer = null;
		 
			function sizewindow(){
				var h = $(window).height();
				$('#centerlogin').css({height:h});
			}
		 
			window.onresize=sizewindow;
			var adminuser_login_login_error="请输入用户名和密码";
			var adminuser_login_incorrect="用户名或者密码输入错误";
			var adminuser_login_seccode_error="验证码输入错误";
			var ie_cookie_err="浏览器禁用COOKIE";
		</script>
		<!--[if IE 6]> 
		<style type="text/css" media="screen"> 
		body {behavior:url("<?php echo P_TPL_WEB; ?>/css/csshover.htc"); } 
		</style>
		<![endif]-->
    </head>
    <body>
    	    	
<script type="text/javascript" language="javascript"> 
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("loginform_stat");
    if (o_result.result == "ERROR") {
        document.forms["loginform"].reset();
        reload_captcha();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        stat.innerHTML = "OK，正在重定向......";
        
        window.location.href = o_result.forward;
    } else {
        return on_failure(response);
    }
}
 
function on_failure(response) {
    document.forms["loginform"].reset();
    reload_captcha();
    
    document.getElementById("loginform_stat").innerHTML = "请求失败！";
    return false;
}
 
function reload_captcha() {
    var captcha = document.getElementById("login_captcha");
    if (captcha) {
        captcha.src = "../captcha.php?s=" + random_str(6);
    }
}
 
$(document).ready(function() {
    $("#login_user").focus();
});
//-->
</script>
 
<div class="login-div">
 <table border="0" cellpadding="0" style="border-collapse: collapse" width="100%" id="centerlogin">
    <tr>
            <td valign="middle" align="center" style="width:564px;">
            <div class="login-form">
	        <table border="0" cellpadding="0" style="border-collapse: collapse" width="564">
			    <tr>
			            <td class="login-top"></td>
			    </tr>
			    <tr>
			            <td class="login-bg">
			            <table style="width:100%">
							<tr>
								<td class="login"><?php if (1==2){?>
								<img src="<?php echo P_TPL_WEB; ?>/images/login/login_logo.jpg" title="admin"/>
								<?php
								}
								?>
								</td>
							</tr>
							<tr>
								<td>
								<table style="width:100%">
									<tr>
										<td class="login-title">
										<table style="width:100%" >
											<tr>
												<td><img src="<?php echo P_TPL_WEB; ?>/images/login/login_title.png" title="admin"/></td>
											</tr>
											<tr>
												<td class="loigntitle">营销型网站系统具有SEO功能，主动营销.加您的管理和推广,必能带来客户和订单！</td>
											</tr>
										</table>
										</td>
										<td class="loign-line"><img alt="line" src="<?php echo P_TPL_WEB; ?>/images/login/login_line.png" /></td>
										<td class="login-form-center">
										<!--from-->
									            <form name="loginform" id="loginform" onsubmit="javascript:return check_login_info(this);" action="index.php" method="post">
<input type="hidden" name="_m" id="_m" value="frontpage"  /><input type="hidden" name="_a" id="_a" value="dologin"  /><input type="hidden" name="_r" id="_r" value="_ajax"  />
									            <table border="0" cellpadding="0">
									            		<tr>
									            			<td class="logintd"></td>
									            			<td class="logintd2"><div class="loginwanntile"><ul><li><span id="loginform_stat">请输入用户名和密码</span></li></ul></div></td>
									            		</tr>
									                    <tr>
									                            <td class="logintd">用户名</td>
									                            <td class="logintd2"><input type="text" class="infoInput" name="login_user" id="login_user" value="" style="width:175px;" /></td>
									                    </tr>
									                    <tr>
									                            <td class="logintd">密　码</td>
									                            <td class="logintd2"><input type="password" class="infoInput" name="login_pwd" id="login_pwd" value="" style="width:175px;" /></td>
									                    </tr>
														<?php
														if (SITE_LOGIN_VCODE==1){
														?>
									                    <tr>
									                            <td class="logintd">安全问题</td>
									                            <td class="logintd2"><img style="position:relative;top:3px;margin-right:4px;" id="login_captcha" src="../captcha.php" class="captchaimg" border="0" onclick="this.src='../captcha.php?secode=ecisp_seccode&' + Math.random()" /><input type="text" class="infoInput" name="rand_rs" id="rand_rs" value="" style="width:52px;" /></td>
									                    </tr>
														<?php
														}else{
															echo "<input type=\"hidden\" class=\"infoInput\" name=\"rand_rs\" id=\"rand_rs\" value=\"\" style=\"width:52px;\" />";
														}
														?>
									                    <tr>
									                            <td class="logintd"></td>
									                            <td class="logintd2"><input type="submit" class="buttonface" name="button" value="登陆管理平台"/></td>
									                    </tr>
									            </table>
									            </form>
										<!--form_end-->
										</td>
									</tr>
 
								</table>
								</td>
							</tr>
						</table>
			            </td>
			    </tr>
			    <tr>
			      <td class="login-down"></td>
			    </tr>
			</table>
		</div>
    </tr>
</table>
</div>
 
<script type="text/javascript" language="javascript"> 
<!--
function check_login_info(thisForm)
{
if (/^\s*$/.test(thisForm.elements["login_user"].value))
{
	alert("请输入用户名！");
	thisForm.elements["login_user"].focus();
	return false;
}
if (/^\s*$/.test(thisForm.elements["login_pwd"].value))
{
	alert("请输入密码！");
	thisForm.elements["login_pwd"].focus();
	return false;
}
<?php
if (SITE_LOGIN_VCODE==1){
?>
if (/^\s*$/.test(thisForm.elements["rand_rs"].value))
{
	alert("请输入答案！");
	thisForm.elements["rand_rs"].focus();
	return false;
}
<?php
}
?>
$("#loginform_stat").css({"display":"block"});
$("#loginform_stat").html("正在检查用户......");
_ajax_submit(thisForm, on_success, on_failure);
return false;
return true;
}
-->
</script>
<div id="footer"></div> <!--likai--> 
    </body>
</html>
