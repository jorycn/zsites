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
				<h1>站群管理</h1>
				<table cellspacing="0" cellpadding="0">
							
                          <tr>
                            <td width="18%" class="icon">网站ID</td>
                            <td width="82%"><?php echo $siteid;?></td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">所属会员</td>
                            <td width="82%"><?php echo $admin;?></td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">有效期</td>
                            <td width="82%"><?php echo $begindate;?>-<?php echo $enddate;?></td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">网站名称</td>
                            <td width="82%"><?php echo $sitename;?></td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">返回站点</td>
                            <td width="82%"><?php echo "<a href=\"?_a=manage&siteid=".$siteid."\">点击返回</a>";?></td>
                          </tr>
						   <tr>
                            <td width="18%" class="icon">增加管理员</td>
                            <form action=""><td width="82%"><input type="text" name="admin"  value="" />
							  <input type="hidden" name="siteid" value="<?php echo $siteid;?>">
							  <input type="hidden" name="_a" value="addadmin">
                              <input type="submit" name="submit"  value="增加" /></td></form>
                          </tr>
						  </table>
						<table cellSpacing=1 cellPadding=6 width="100%" bgColor=#dddddd border=0>
						<tbody>
						<tr bgColor=#f5f5f5 align=center>
						<td width="40">编号</td>
						<td width="*">会员名</td>
						<td width="60">改密</td>
						<td width="60">删除</td>
						</tr>
						<?php
						$field=trim($_REQUEST["field"]);
						$keyword=trim($_REQUEST["keyword"]);
						$strfilename=$_SERVER['SCRIPT_NAME']."?field=".$field."&keyword=".$keyword."";
						$maxperpage=(int)$_GET["maxperpage"];
						if ($maxperpage<1){
							$maxperpage=6;
						}
						$page=(int)$_GET["page"];					
						if ($field!=""&&$keyword!=""){
							$sqlwhere=$sqlwhere." and ".$field." like '%".$keyword."%'";
						}
						$totalnumber=getfieldvalue("select count(*) from ".$siteid."_users where s_role='{admin}'".$sqlwhere);
						If (fmod($totalnumber,$maxperpage)==0){
							$totalpage = (int)$totalnumber / $maxperpage;
						}else{
							$totalpage = (int)($totalnumber / $maxperpage) + 1;
						}
						If ($page > $totalpage){
							$page = $totalpage;
						}
						if ($page<1){
							$page=1;
						}
						//$sql="show tables like '%_siteinfos%'" 显示全部表
						$sql="select * from ".$siteid."_users where s_role='{admin}'".$sqlwhere;
						$sql = $sql." order by id desc limit ".($page-1)*$maxperpage.",".$maxperpage."";
						$ques = mysql_query($sql);
						if ($ques){
							while($rows = mysql_fetch_array($ques)){
							echo "<tr bgColor=#ffffff align=center>";
							echo "<td><a href=\"#\">".$rows["id"]."</a></td>";
							echo "<td>".$rows["login"]."</td>";
							echo "<td><a href=\"?_a=adminpwd&siteid=$siteid&id=".$rows["id"]."\">重置密码</a></td>";
							echo "<td><a href=\"?_a=deladmin&siteid=$siteid&id=".$rows["id"]."\">删除</a></td>";
							echo "</tr>";
	
							}
						}
						?>
						
						</tbody>
						</table>
						<div class="page">
						<?php
						if ($totalnumber>0){
							echo "共".$totalnumber."条记录,<a href=".$strfilename."&maxperpage=".$maxperpage."&page=1>首页</a>&nbsp;";
							if ($page<2){
								echo "上一页";
							}else{
								echo "<a href=".$strfilename."&maxperpage=".$maxperpage."&page=".($page-1).">上一页</a>";
							}
							echo "&nbsp;";
							if ($page>=$totalpage){
								echo "下一页";
							}else{
								echo "<a href=".$strfilename."&maxperpage=".$maxperpage."&page=".($page+1).">下一页</a>";
							}
							echo "&nbsp;".$page."/".$totalpage."页";
							echo "<Input type='text' name='maxperpage' size='3' maxlength='4' value='$maxperpage' onKeyPress=\"if (event.keyCode==13) window.location='".$strfilename."&page=".$page."&maxperpage='+this.value;\">条记录/页&nbsp;&nbsp;转到第<Input type='text' name='page' size='3' maxlength='5' value='$page' onKeyPress=\"if (event.keyCode==13) window.location='".$strfilename."&maxperpage=".$maxperpage."&page='+this.value;\">页";
						}	
						?>
						</div>

               			<form method='Get' name='sForm' action=''><table width='100%' border='0' cellpadding='0' cellspacing='0' class='border'>  <tr class='tdbg'>   <td width='80' align='right'><strong>记录搜索：</strong></td><td><?php
						echo "<select name='field' size='1'>><option value='login'".fc_compare($field,"login"," selected","").">用户名</option></select><input type='text' name='keyword'  size='20' value='$keyword' maxlength='50' onFocus='this.select();'><input type='submit' value='搜索'>";						
						?><td></td></tr></table></form>
            	</div> 
				<!---->    
            </div>
            
        </div>
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
