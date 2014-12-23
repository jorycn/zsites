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
                	<li><a href="../">返回首页</a></li>
                </ul>
            </div>
           
            <div id="right">
            	<div id="right_bor">
				<h1>请先登录</h1>
					
                    <table cellspacing="0" cellpadding="0">
						 <form name="myform" action="index.php" method="post">
                          <tr>
                            <td width="18%" class="icon">用户名</td>
                            <td width="82%">
							<?php
							if (ADMIN!=""){
								echo ADMIN."(如要更更改请修改../config/config.php)";
							}else{
							?>
                              <input type="text" name="user"  value="" />输入登录帐号
							<?php }?> 
                            </td>
                          </tr>
						   <tr>
                            <td width="18%" class="icon">登录密码</td>
                            <td width="82%">
                              <input type="password" name="password"  value="" /><a href="?_a=resetpassword"><font color="#FF0000">重设密码</font></a>


                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon"></td>
                            <td width="82%">
							  <input type="hidden" name="_a" value="check">
                              <input type="submit" name="submit"  value="登录" />

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
