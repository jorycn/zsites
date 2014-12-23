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
				<h1>UC整合设置</h1>
					<div class="form-tips">
						<ol>
							<li>会员管理：整合后，网站前后台的用户名和密码以uc数据为准</li>
							<li>整合效果：所有网站都帮您积累会员基础。您的会员总数=网站数*单个网站会员数
							<li>平台应用：实现旗下所有网站公用会员，打造属于自己的通行证</li>
						</ol>
					</div>
                    <table cellspacing="0" cellpadding="0">
						 <form name="myform" action="index.php" method="post">
                          <tr>
                            <td width="18%" class="icon">整合UC</td>
                            <td width="82%">
                             <input type="radio" name="ucopen"  value="0"<?php echo fc_compare(UC_OPEN,0," checked","")?>/>强制禁止<input type="radio" name="ucopen"  value="1"<?php echo fc_compare(UC_OPEN,1," checked","")?>/>自由决定<input type="radio" name="ucopen"  value="2"<?php echo fc_compare(UC_OPEN,2," checked","")?>/>强制整合<br>整合后,所有网站后台管理员和前台会员都必须在UC有记录,<font color="#FF0000">此举可以大大增加主站会员数</font>

                            </td>
                          </tr>
						   <tr>
                            <td width="18%" class="icon">UC信息</td>
                            <td width="82%">
                              <textarea name="ucconfig" cols="50" rows="10"><?php echo $ucconfig;?></textarea>
							  <br>请直接从UC应用管理中。复制应用的 UCenter 配置信息
                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon"></td>
                            <td width="82%">
							  <input type="hidden" name="_a" value="saveuc">
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
