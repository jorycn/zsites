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
				<h1>分离站点-<?php echo $sitename;?></h1>
					
                    <table cellspacing="0" cellpadding="0">
						  <tr>
                            <td class="icon">第一步</td>
                            <td>在新的虚拟主机,全新安装本程序</td>
                          </tr>
                          <tr>
                            <td width="18%" class="icon">第二步</td>
                            <td width="82%">登陆要导出的站点<?php echo $siteid?>后台,备份数据库</td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon" valign="top">第三步</td>
                            <td width="82%">将file/<?php echo $siteid?>,打包下载,并且上传到新的虚拟主机中</td>
                          </tr>
						   <tr>
                            <td width="18%" class="icon" valign="top">第四步</td>
                            <td width="82%">将file/<?php echo $siteid?>/backup中的最新数据库文件还原到新的数据库中</td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon" valign="top">第五步</td>
                            <td width="82%">登陆新的站点的站群管理系统,点开站群设置,将缺省站点修改为:<?php echo $siteid?></td>
                          </tr>						   
                        </table>
               	
            	</div>    
          </div>    
        </div>
		
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
