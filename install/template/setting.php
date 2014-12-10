<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ZSites建站宝安装程序</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="../script/jquery.min.js"></script>
<script src="js/install.js"></script>
</head>

<body>
    <div id="in">
    	<div id="top"><span>ZSites建站宝安装程序</span></div>
        <div id="banner"></div>
        <div id="center">
        
        	<div id="left">
            	<ul>
                	<li>欢迎您使用ZSites</li>
                    <li>检查系统环境</li>
                    <li class="hov">配置系统</li>
                    <li>完成安装</li>
                </ul>
            </div>
            
            <div id="right">
            	<div id="right_bor">
				<h1>信息设定,<font color="#FF0000">以下信息全需正确填写</font></h1>
                    <table cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="18%" class="icon">数据库主机</td>
                            <td width="82%">
                              <input type="text" name="db-host"  id="db-host" value="<?php echo $db_host;?>" />
							  (请输入您数据库的地址)

                            </td>
                          </tr>
                          <tr>
                            <td class="icon">端口号</td>
                            <td>
                              <input type="text" name="db-port" id="db-port" value="<?php echo (Config::$port);?>" />
                            </td>
                          </tr>
                          <tr>
                            <td class="icon">用户名</td>
                            <td>
                              <input type="text" name="db-user" id="db-user"  value="<?php echo $db_user;?>" />
							  (请输入您的数据库用户名)
                            </td>
                          </tr>
                          <tr>
                            <td class="icon">密码</td>
                            <td>
                              <input type="password" name="db-pwd" id="db-pwd"  value="" />
							  (请输入您的数据库密码)
                            </td>
                          </tr>
                          <tr>
                            <td class="icon">数据库名</td>
                            <td>
                              <input type="text" name="db-name" id="db-name"  value="<?php echo $db_name;?>" /><input type="hidden" name="db-prefix" id="db-prefix" value="1_" readonly/>
							  (请输入您的数据库名称)
                            </td>
                          </tr>
						  <!--tr>
							<td class="icon">体验超级站群</td>
							<td><input type="checkbox" name="supersite" id="supersite"  value="1" checked/>&nbsp;&nbsp;默认将导入按以省为单位站群(需要泛域名支持或者绑定域名)</td>
						  </tr-->
                        </table>
				<h2>管理信息</h2>		
                <table  cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="18%" class="icon">网站名称</td>
                    <td width="82%"><input type="text" name="sitename" id="sitename" value=""/>显示的网站名称,可以修改</td>
                  </tr>
                  <tr>
                    <td width="18%" class="icon">管理员用户名</td>
                    <td width="82%"><input type="text" name="js-admin-name" id="js-admin-name" value="admin" onkeyup="value=value.replace(/[^\w\)\(\- ]/g,'')" /></td>
                  </tr>
                  <tr>
                    <td class="icon">登录密码</td>
                    <td><input type="password" name="js-admin-password" id="js-admin-password"  value="admin88" />默认admin88</td>
                  </tr>
                  </table>
<h2>其他</h2>
                	<table cellspacing="0" cellpadding="0">
					 <tr>
                	    <td class="icon">安装进度</td>
                	    <td id="install_result" style="color:#F6A126;text-align:center;">&nbsp;</td>
              	    </tr>
               	  </table>
            	</div>    
          </div>
            
        </div>
		<?php echo cssheight(50);?>
        <div id="footer">
            <div class="button2" ><a href="javascript::;" id="js-install-at-once">立即安装</a></div>
			<div class="button"><a href="#" id="js-pre-step">上一步 安装环境</a></div>
      	</div>
    </div>
</body>
</html>
