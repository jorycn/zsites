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
				<h1>系统升级-<?php echo $version;?></h1>
					
                    <table cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="18%" class="icon">升级</td>
                            <td width="82%"><?php echo $version?></td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon" valign="top">升级说明</td>
                            <td width="82%"><textarea name="intro" cols="50" rows="10"><?php echo $intro;?></textarea></td>
                          </tr>
						   <tr>
                            <td width="18%" class="icon" valign="top">状态</td>
                            <td width="82%"><?php 
							if ($upgrade==0){
								echo "<a href='?_a=doupgrade&version=$version'>执行升级</a>";
							}else{
								echo "已升级,重复升级请删除upgrade.lock文件";
							}
							?></td>
                          </tr>					   
                        </table>
               	
            	</div>    
          </div>    
        </div>
		
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
