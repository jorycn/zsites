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
				<h1>站群管理-数据库地址:<?php echo $db_host;?>-数据库名:<?php echo $db_name;?></h1>
						<a href="?_a=import">同步站点到站点数据库</a>
						<div class="form-tips">
							<ol>
								<li>赚钱计划：<a href="http://www.jzbao.net/buy"><font color="#FF0000">限量,超便宜购买建站终身授权包</font></a></li>
								<li>省钱省时：5MB程序,<font color="#FF0000">1</font>个空间,<font color="#FF0000">1</font>个数据库建立<font color="#FF0000">无数</font>个网站</li>
								<li>站群原理：通过域名识别网站数据表和网站模板</li>
								<li>海量模板：2000套模板提供试用</li>
								<li>无版权信息：前后台均无版权信息,方便销售</li>
							</ol>
						</div>
						<table cellSpacing=1 cellPadding=6 width="100%" bgColor=#dddddd border=0>
						<tbody>
						<tr bgColor=#f5f5f5 align=center>
						<td width="40">编号</td>
						<td width="80">会员名</td>
						<td width="200">名称</td>
						<td width="40">城市</td>
						<td width="150">时间</td>
						<td width="24">管理</td>
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
						
						$sqls="select s.* from ms_site s where 1=1";
						$sql="select count(*) from ms_site s where 1=1";		
						if ($field!=""&&$keyword!=""){
							if ($field=="domain"||$field=="mainkey"||$field=="domainkey"){
								$domaintable=1;
								$sql="select count(*) from ms_site s,ms_domain d where s.siteid=d.siteid";
								$sqls="select s.*,d.".$field." from ms_site s,ms_domain d where s.siteid=d.siteid";
								$sqlwhere=$sqlwhere." and d.".$field." like '%".$keyword."%'";
							}else{
								$sqlwhere=$sqlwhere." and s.".$field." like '%".$keyword."%'";
							}	
						}
						$totalnumber=getfieldvalue($sql.$sqlwhere);
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
						$sql=$sqls.$sqlwhere;
						$sql = $sql." order by s.siteid desc limit ".($page-1)*$maxperpage.",".$maxperpage."";
						$ques = mysql_query($sql);
						$j=0;
						if ($ques){
							while($rows = mysql_fetch_array($ques)){
							$j=$j+1;
							$siteid=$rows["siteid"];
							$sitename=$rows["sitename"];
							if ($sitename==""){
								$sitename="站点".$siteid;
							}
							$siteurl=$rows["sitedomain"];
							if ($siteurl==""){
								$siteurl=$siteid.DEFAULTDOMAIN;
							}
							echo "<tr bgColor=#ffffff align=center>";
							echo "<td><a href=\"#\">".$rows["siteid"]."</a></td>";
							echo "<td>".$rows["username"]."</td>";
							echo "<td><a href=\"".gettrueurl(0,$siteurl)."\" target='_blank'>".$sitename."</a>";
							if ($field){
								echo "<br>".$rows[$field]."";
							}
							echo "</td>";
							echo "<td>".$rows["city"]."</td>";
							echo "<td>".$rows["createtime"]."</td>";
							echo "<td><a href=\"?_a=manage&siteid=".$rows["siteid"]."\">管理</a></td>";
							echo "</tr>";
	
							}
						}
						if ($j>2){
							$autoheight=($j-3)*92;
							$autoheight=$autoheight+90;
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
						echo "<select name='field' size='1'><option value='siteid'".fc_compare($field,"siteid"," selected","").">网站ID</option><option value='sitename'".fc_compare($field,"sitename"," selected","").">网站名称<option value='sitekey'".fc_compare($field,"sitekey"," selected","").">全局关键词<option value='domain'".fc_compare($field,"domain"," selected","").">域名<option value='mainkey'".fc_compare($field,"mainkey"," selected","").">主关键词</option><option value='domainkey'".fc_compare($field,"domainkey"," selected","").">替换关键词<option value='username'".fc_compare($field,"username"," selected","").">会员名</option></select><input type='text' name='keyword'  size='20' value='$keyword' maxlength='50' onFocus='this.select();'><input type='submit' value='搜索'>";					
						?><td></td></tr></table></form>
			
            	</div> 
				<!---->    
            </div>
            <?php echo cssheight($autoheight);?>
        </div>
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
