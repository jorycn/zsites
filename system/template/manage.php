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
				<h1>网站管理-<?php echo $sitename;?></h1>
					<?php
					if ($admin==""){
						$adminid=(int)getfieldvalue("select id from ".$siteid."_users where s_role='{admin}' order by id asc");
						if ($adminid>0){
							$admin=getfieldvalue("select login from ".$siteid."_users where s_role='{admin}' order by id asc");	
							if ($admin==""){
								$admin="admin";
								$sql="update ".$siteid."_users set login='$admin' where id=".$adminid;
								$result=mysql_query($sql);
							}
						}else{
						}
					}
					?>
                    <table cellspacing="0" cellpadding="0">
						  <tr>
                            <td width="18%" class="icon">返回上级页面</td>
                            <td width="82%">
                             <a href="<?php echo $comeurl;?>">返回上级</a>
                            </td>
                          </tr>
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
                            <td width="18%" class="icon">验证码开关</td>
                            <td width="82%"><?php echo fc_compare($showcheckcode,1,"开","关");?></td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">伪静态</td>
                            <td width="82%"><?php echo fc_compare($rewrite,2,"开","关");?></td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">点击访问</td>
                            <td width="82%"><?php echo "<a href=\"".gettrueurl(0,$siteurl)."\" target='_blank'>".$siteurl."</a>";?></td>
                          </tr>				  
						   <tr>
                            <td class="icon">修改信息</td>
                            <td><a href="?_a=modify&siteid=<?php echo $siteid;?>">点击修改</a></td>
                          </tr>
						  <tr>
                            <td class="icon">管理员列表</td>
                            <td><a href="?_a=adminlist&siteid=<?php echo $siteid;?>">点击管理</a></td>
                          </tr>
						  <tr>
                            <td class="icon">分离网站</td>
                            <td><a href="?_a=out&siteid=<?php echo $siteid;?>">查看操作步骤</a></td>
                          </tr>
						   <tr><td colspan="2"><h1>超级站群设置</h1></td></tr>
						   <tr>
                            <td width="18%" class="icon" valign="top">导入域名</td>
                            <td width="82%"><a href="?_a=addprovince&siteid=<?php echo $siteid;?>">导入省超级站群</a></td>
                          </tr>
						   <form action="">
						   <tr>
                            <td width="18%" class="icon" valign="top">全局关键词</td>
                            <td width="82%"> <textarea name="sitekey" cols="30" rows="5" /><?php echo $sitekey;?></textarea><a href="?_a=getsitekey&siteid=<?php echo $siteid;?>">获得网站名称、关键词、描述和产品名称</a></td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon" valign="top">增加域名</td>
                            <td width="82%"> <textarea name="domain" cols="30" rows="5" /></textarea><br>格式如下<br>1:域名或者域名|主关键<br>2:一行一个
							  <input type="hidden" name="siteid" value="<?php echo $siteid;?>">
							  <input type="hidden" name="_a" value="adddomain">
                              <input type="submit" name="submit"  value="保存" /></td>
                          </tr>
						  </form>
						   <tr>
                            <td class="icon" valign="top">域名列表</td>
                            <td>
								<!--info-->
								<table cellSpacing=1 cellPadding=6 width="98%" bgColor=#dddddd border=0>
								<tbody>
								<tr bgColor=#f5f5f5 align=center>
								<td width="*">域名(<a href="?_a=delalldomain&siteid=<?php echo $siteid;?>">清空全部域名</a>)</td>
								<td width="120">模板</td>
								<td width="80">主关键词</td>
								<td width="24">状态</td>
								<td width="24">修改</td>
								<td width="24">检测</td>
								<td width="24">删除</td>
								</tr>
								<?php
								$field=trim($_REQUEST["field"]);
								$keyword=trim($_REQUEST["keyword"]);
								$strfilename=$_SERVER['SCRIPT_NAME']."?_a=$_a&siteid=$siteid&field=".$field."&keyword=".$keyword."";
								$maxperpage=(int)$_GET["maxperpage"];
								if ($maxperpage<1){
									$maxperpage=6;
								}
								$page=(int)$_GET["page"];
								
								if ($field!=""&&$keyword!=""){
									$sqlwhere=$sqlwhere." and ".$field." like '%".$keyword."%'";
								}
								$totalnumber=getfieldvalue("select count(*) from ms_domain where siteid=$siteid".$sqlwhere);
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
								$sql="select * from ms_domain where siteid=$siteid".$sqlwhere;
								$sql = $sql." order by orderid asc limit ".($page-1)*$maxperpage.",".$maxperpage."";
								$ques = mysql_query($sql);
								$j=0;
								if ($ques){
									while($rows = mysql_fetch_array($ques)){
									$j=$j+1;
									$domainid=$rows["domainid"];
									$siteurl=$rows["domain"];
									if ($rows["domaintemplate"]){
										$template=$rows["domaintemplate"];
									}else{
										$template=$defaulttpl;
									}
									echo "<tr bgColor=#ffffff align=center>";
									echo "<td><a href=\"".gettrueurl(0,$siteurl)."\" target='_blank'>".$siteurl."</a></td>";
									echo "<td>".$template."</td>";
									echo "<td>".$rows["mainkey"]."</td>";
									echo "<td>".getdomainstatus($rows["status"])."</td>";
									echo "<td><a href=\"?_a=domainmodify&domainid=".$rows["domainid"]."\">修改</a></td>";
									echo "<td><a href=\"?_a=checkdomain&siteid=$siteid&domain=$siteurl&domainid=".$rows["domainid"]."\">检测</a></td>";
									echo "<td><a href=\"?_a=deldomain&siteid=$siteid&domainid=".$rows["domainid"]."\">删除</a></td>";
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
								echo "<select name='field' size='1'><option value='domain'".fc_compare($field,"domain"," selected","").">域名</option><option value='mainkey'".fc_compare($field,"mainkey"," selected","").">主关键词</option><option value='domain'".fc_compare($field,"domain"," selected","").">替换关键词</select><input type='text' name='keyword'  size='20' value='$keyword' maxlength='50' onFocus='this.select();'><input type='hidden' name='_a'  value='$_a'><input type='hidden' name='siteid'  value='$siteid'><input type='submit'  value='搜索'>";
								$autoheight=320;
								if ($j>0){
									$autoheight=$autoheight+$j*46;
									
								}					
								?><td></td></tr></table></form>
								<!--info-->
							</td>
                          </tr>
                          <tr>
                          	<td colspan="2">
                          		<h1>删除该站点&nbsp;<button onclick="location.href='?_a=delete&siteid=<?php echo $siteid;?>'">确认</button></h1>
                          		<p class="red">&nbsp;删除之后将无法恢复，数据将会全部丢失！</p>
                          	</td>
                          </tr>
                        </table>

               	
            	</div>    
          </div>    
        </div>
		<?php echo cssheight($autoheight);?>
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
