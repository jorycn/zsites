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
				<h1>建站之星迁移</h1>
					 <?php
					   $pre=trim($_REQUEST["pre"]);
					   if ($pre==""){
						   $pre="ss_";
					   }else{
						   $prenum=(int)substr($pre,0,strlen($pre)-1);
					   }
					   echo "<table cellspacing=\"0\" cellpadding=\"0\">";
						echo "<tr>";
						echo "<td width=\"18%\" class=\"icon\" valign=\"top\">第一步</td>";
						echo "<form action=\"\" name='check' method=\"get\"> <td width=\"82%\">检测建站之星的表前缀";
						echo "<input type=\"text\" name=\"pre\"  value=\"$pre\" />";
						echo "<input type=\"hidden\" name=\"_a\" value=\"plugsitestar\">";
						echo "<input type=\"submit\" value=\"检测\" />";
						echo "</td>";
						echo "</form>";
						echo "</tr>";
						if (trim($_REQUEST["pre"])){
							if ($prenum==0){
								$importok=(int)getfieldvalue("select id from ".$pre."site_infos");
								echo "<tr>";
								echo "<td width=\"18%\" class=\"icon\">第二步</td>";
								echo "<td width=\"82%\">登陆建站之星后台,备份建站之星数据库,支持v2.3及以上版本</td>";
								echo "</tr>";
								echo "<tr>";
								echo "<td width=\"18%\" class=\"icon\">第三步</td>";
								echo "<td width=\"82%\">在确认不会与本系统冲突的情况，请将备份通过phpmyadmin或者数据库工具导入本系统</td>";
								echo "</tr>";
								if ($importok>0){
									$sql="select val from ".$pre."parameters where `key`='DEFAULT_TPL'";
									$tpldir=getfieldvalue($sql);
									echo "<tr>";
									echo "<td width=\"18%\" class=\"icon\" valign=\"top\">第四步</td>";
									echo "<td width=\"82%\">请将建站之星文件夹template/".$tpldir."复制到本系统template下<br>将建站之星upload下文件复制到本系统upload下</td>";
									echo "</tr>";
									if (file_exists(ROOT."/template/".$tpldir.'/template_info.php')){
										$siteid=(int)getfieldvalue("select siteid from ms_site order by siteid desc")+1;
										echo "<tr>";
										echo "<td width=\"18%\" class=\"icon\" valign=\"top\">第五步</td>";
										echo "<form action=\"".$_SERVER['SCRIPT_NAME']."\" name='move' method=\"get\"> <td width=\"82%\">";
										echo "<input type=\"hidden\" name=\"pre\"  value=\"$pre\" />";
										echo "<input type=\"hidden\" name=\"_a\" value=\"dositestar\">";
										echo "<input type=\"submit\" value=\"执行迁移,预计新站点为".$siteid."\" />";
										echo "</td>";
										echo "</form>";
										echo "</tr>";
									}
								}
							}else{
								echo "<tr>";
								echo "<td width=\"18%\" class=\"icon\">第二步</td>";
								echo "<td width=\"82%\">建站之星表为数字表前缀，请修改为其他表(非数字前缀)前缀后，再检测表前缀</td>";
								echo "</tr>";
							}
						}
						echo "</table>";
					   ?>
               	
            	</div>    
          </div>    
        </div>
		
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
