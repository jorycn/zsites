<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sitebaohe站群管理平台</title>
<link href="../install/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../script/jquery.min.js"></script>
<script>
jQuery(function($){
	$("#js-agree").click(function(){
		if($("#js-agree").attr("checked") == true){
			$("#js-submit").attr("disabled",false);
		}else{
			$("#js-submit").attr("disabled",true);
		}
	});
	$("#js-submit").click(function(){
		if($("#js-agree").attr("checked") == false){
			return false;
		}else{
			this.href="index.php?_m=frontpage&_a=check";
		}
	});
});
</script>
</head>

<body>
    <div id="in">
    	<?php include_once('head.php');?>
        <div id="banner"></div>
        <div id="center">
        
        	<div id="left">
            	<?php include_once('left.php');?>
            </div>
            
            <div id="right">
				<!---->
				
				<div id="right_bor">
				<h1>系统升级</h1>
						<table cellSpacing=1 cellPadding=6 width="100%" bgColor=#dddddd border=0>
						<tbody>
						<tr bgColor=#f5f5f5 align=center>
						<td width="*">版本升级</td>
						<td width="100">状态</td>
						<td width="60">管理</td>
						</tr>
						<?php
						foreach(scandir('../upgrade/')as$single){
							if (is_dir("../upgrade/".$single)&&$single!="."&&$single!=".."){						
								echo "<tr bgColor=#ffffff align=center>";
								echo "<td>".$single."</td>";
								echo "<td></td>";
								echo "<td><a href=\"?_a=version&version=".$single."\">升级</a></td>";
								echo "</tr>";
							}
						}	
						if ($j>2){
							$autoheight=($j-3)*46;
							$autoheight=$autoheight+10;
						}
						?>
						
						</tbody>
						</table>
			
            	</div> 
				<!---->    
            </div>
            <?php echo cssheight($autoheight);?>
        </div>
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
