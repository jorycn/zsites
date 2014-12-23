<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sitebaohe站群管理平台</title>
<link href="../install/css/style.css" rel="stylesheet" type="text/css" />
<script src="../script/jquery.min.js"></script>
</head>

<body>
    <div id="in">
    	<?php include_once('head.php');?>
        <div id="banner"></div>
        <div id="center">
        
        	<div id="left">
            	<ul>
                	<?php include_once('left.php');?>
                </ul>
            </div>
           
            <div id="right">
            	<div id="right_bor">
				<h1>网站设置</h1>
				<div id="footer">
					<div class="button2"><a href="?_a=upgrade">系统升级</a></div>
					<div class="button2"><a href="?_a=domain">泛域名设置</a></div>
					<div class="button2"><a href="?_a=tpldown">模板库</a></div>
					<div class="button2"><a href="?_a=setting">基本设置</a></div>
				</div>	
                    <table cellspacing="0" cellpadding="0">
						 <form name="myform" action="index.php" method="post">
						   <tr>
                            <td width="18%" class="icon">管理员</td>
                            <td width="82%">
                              <input type="text" name="user"  value="<?php echo ADMIN;?>" />

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">管理密码</td>
                            <td width="82%">
                              <input type="text" name="password"  value="<?php echo ADMINPWD;?>" />

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">缺省站点ID</td>
                            <td width="82%">
                              <input type="text" name="defaultsiteid"  value="<?php echo DEFAULTSITEID;?>" />当域名非正常绑定时,显示缺省站点

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">默认客服QQ</td>
                            <td width="82%">
                              <input type="text" name="defaultqq"  value="<?php echo DEFAULTQQ;?>" />开通网站后默认显示的QQ

                            </td>
                          </tr>
						   <tr>
                            <td width="18%" class="icon">客户帮助链接</td>
                            <td width="82%">
                              <input type="text" name="helpurl"  value="<?php echo HELPURL;?>"   style="width:240px;"/>客户网站后台帮助链接

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">模板图片与演示地址</td>
                            <td width="82%">
                              <input type="text" name="defaultpicurl"  value="<?php echo DEFAULTPICURL;?>"   style="width:240px;"/>网站模板列表默认图片以及演示地址

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">通讯密钥</td>
                            <td width="82%">
                              <input type="text" name="apikey"  value="<?php echo APIKEY;?>" disabled  style="width:240px;">api(一分钟)在线开通网站唯一编码,不建议修改

                            </td>
                          </tr>
						   <tr>
                            <td width="18%" class="icon">显示版权</td>
                            <td width="82%">
                             <input type="radio" name="showright"  value="0"<?php echo fc_compare(SHOWRIGHT,0," checked","")?>/>系统决定<input type="radio" name="showright"  value="1"<?php echo fc_compare(SHOWRIGHT,1," checked","")?>/>强制显示

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon"></td>
                            <td width="82%">
							  <input type="hidden" name="_a" value="saveset">
                              <input type="submit" name="submit"  value="保存" />
                            </td>
                          </tr>
						  </form>
                        </table>
               	
            	</div>    
          </div>    
        </div>
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
