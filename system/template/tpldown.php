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
				<h1>模板库通讯</h1>
				<div id="footer">
					<div class="button2"><a href="?_a=upgrade">系统升级</a></div>
					<div class="button2"><a href="?_a=tpldown">模板库</a></div>
					<div class="button2"><a href="?_a=domain">泛域名设置</a></div>
					<div class="button2"><a href="?_a=setting">基本设置</a></div>
				</div>	
                    <table cellspacing="0" cellpadding="0">
						 <form name="myform" action="index.php" method="post">
						  <tr>
                            <td width="18%" class="icon" valign="top">官网帐号</td>
                            <td width="82%">
                              <input type="text" name="apiuserid"  value="<?php echo APIUSERID;?>" /><a href="http://www.jzbao.net/member/register" target="_blank"><font color="#FF0000">免费注册</font></a>
                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon" valign="top">通讯密钥</td>
                            <td width="82%">
                              <input type="text" name="apipassword"  value="<?php echo APIPASSWORD;?>" />
                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon"></td>
                            <td width="82%">
							  <input type="hidden" name="_a" value="savetpldown">
                              <input type="submit" name="submit"  value="保存" />
                            </td>
                          </tr>
						  </form>
                        </table>
               	
            	</div>    
          </div>    
        </div>
		<?php echo cssheight(50);?>
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
