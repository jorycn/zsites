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
				<h1>网站设置-<?php echo $siteid;?></h1>
					
                    <table cellspacing="0" cellpadding="0">
						 <form name="myform" action="index.php" method="post">
						   <tr>
                            <td width="18%" class="icon">管理账号</td>
                            <td width="82%">
                              <input type="text" name="admin"  value="<?php echo $admin;?>" />
                            </td>
                          </tr>
						   <tr>
                            <td width="18%" class="icon">管理密码</td>
                            <td width="82%">
                              <input type="text" name="password"  value="<?php echo $password;?>" />
                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">网站名称</td>
                            <td width="82%">
                              <input type="text" name="sitename"  value="<?php echo $sitename;?>" />

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">主域名</td>
                            <td width="82%">
                              <input type="text" name="sitedomain"  value="<?php echo $sitedomain;?>" />不能重复,不要带http://,也不要带/

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">所属城市</td>
                            <td width="82%">
                              <input type="text" name="city"  value="<?php echo $city;?>" />

                            </td>
                          </tr>
						   <tr>
                            <td width="18%" class="icon" valign="top">全局关键词</td>
                            <td width="82%">
                              <textarea name="sitekey" cols="30" rows="5" /><?php echo $sitekey;?></textarea><br>1:输入关键词即可<br>2:一行一个<br>3:配合域名主关键词可以启动超级站群功能
                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">开始日期</td>
                            <td width="82%">
                              <input type="text" name="begindate"  value="<?php echo $begindate;?>" />

                            </td>
                          </tr>
						   <tr>
                            <td width="18%" class="icon">到期日期</td>
                            <td width="82%">
                              <input type="text" name="enddate"  value="<?php echo $enddate;?>" />

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">状态</td>
                            <td width="82%">
                               <input type="radio" name="status"  value="0"<?php echo fc_compare($status,0," checked","")?>/>试用<input type="radio" name="status"  value="1"<?php echo fc_compare($status,1," checked","")?>/>运行<input type="radio" name="status"  value="2"<?php echo fc_compare($status,2," checked","")?>/>停止

                            </td>
                          </tr>
						   <tr>
                            <td width="18%" class="icon">验证码开关</td>
                            <td width="82%">
                               <input type="radio" name="showcheckcode"  value="0"<?php echo fc_compare($showcheckcode,0," checked","")?>/>关闭<input type="radio" name="showcheckcode"  value="1"<?php echo fc_compare($showcheckcode,1," checked","")?>/>开启

                            </td>
                          </tr>
						   <tr>
                            <td width="18%" class="icon">伪静态开关</td>
                            <td width="82%">
                               <input type="radio" name="rewrite"  value="1"<?php echo fc_compare($rewrite,1," checked","")?>/>关闭<input type="radio" name="rewrite"  value="2"<?php echo fc_compare($rewrite,2," checked","")?>/>开启

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">版权信息开关</td>
                            <td width="82%">
                               <input type="radio" name="closeversion"  value="0"<?php echo fc_compare($closeversion,0," checked","")?>/>开启<input type="radio" name="closeversion"  value="1"<?php echo fc_compare($closeversion,1," checked","")?>/>关闭

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon"></td>
                            <td width="82%">
							  <input type="hidden" name="comeurl" value="<?php echo $comeurl;?>">
							  <input type="hidden" name="siteid" value="<?php echo $siteid;?>">
							  <input type="hidden" name="_a" value="savesite">
                              <input type="submit" name="submit"  value="保存" />
                            </td>
                          </tr>
						  </form>
                        </table>
               	
            	</div>    
          </div>    
        </div>
		<?php
		$autoheight=100;
		echo cssheight($autoheight);?>
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
