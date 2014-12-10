<?php include_once('header.php') ?>

    <div class="container g-wrap">
    	<h1 class="tit">ZSites站群管理平台</h1>
    	
    	<div class="row">
    		<div class="col-lg-3">
    			<?php include_once('left.php');?>
    		</div>
    		<div class="col-lg-9">
    			<div class="row">
    				<div class="col-lg-12">
    					<h1>站群管理&nbsp;&nbsp;<a href="?_a=import" class="btn btn-default">同步站点到站点数据库</a></h1>
						<div class="alert alert-info">
							数据库地址:<?php echo $db_host;?>&nbsp;&nbsp;&nbsp;数据库名:<?php echo $db_name;?>
						</div>
    				</div>
    			</div>
    			<div class="row">
    				<div class="col-lg-12 search">
    					<form method='Get' name='sForm' action=''>
    						<table width='100%' border='0' cellpadding='0' cellspacing='0' class='border'>  
    							<tr class='tdbg'>   
    								<td width='80' align='right'><strong>记录搜索：</strong></td>
    								<td class="form-inline">
    									<?php echo "<select name='field' size='1' class='form-control'><option value='siteid'".fc_compare($field,"siteid"," selected","").">网站ID</option><option value='sitename'".fc_compare($field,"sitename"," selected","").">网站名称<option value='sitekey'".fc_compare($field,"sitekey"," selected","").">全局关键词<option value='domain'".fc_compare($field,"domain"," selected","").">域名<option value='mainkey'".fc_compare($field,"mainkey"," selected","").">主关键词</option><option value='domainkey'".fc_compare($field,"domainkey"," selected","").">替换关键词<option value='username'".fc_compare($field,"username"," selected","").">会员名</option></select>&nbsp;<input type='text' name='keyword'  size='20' value='$keyword' maxlength='50' onFocus='this.select();' class='form-control'>&nbsp;<input type='submit' value='搜索' class='btn btn-primary'>";?>
									<td></td>
								</tr>
							</table>
						</form>
    				</div>
    			</div>
    			<div class="row">
    				<div class="col-lg-12">
    					<table  width="100%"  class="table">
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
    				</div>
    			</div>
				
				<?php if($totalnumber>0):?>
	    			<div class="row">
	    				<div class="col-lg-12">
	    					<ul class="pagination">
	    						<li <?php if($page<2):?>class="disabled"<?php endif;?>><a href='<?php echo $strfilename."&maxperpage=".$maxperpage."&page=".($page-1) ?>'>&laquo;</a></li>
	    						<li <?php if($page>=$totalpage):?>class="disabled"<?php endif;?>><a href='<?php echo $strfilename."&maxperpage=".$maxperpage."&page=".($page+1) ?>'>&raquo;</a></li>
								<li><?php echo "&nbsp;".$page."/".$totalpage."页";?></li>
								<li class="form-inline"><?php echo "<Input type='text' name='maxperpage' size='3' class='form-control' maxlength='4' value='$maxperpage' onKeyPress=\"if (event.keyCode==13) window.location='".$strfilename."&page=".$page."&maxperpage='+this.value;\">条记录/页&nbsp;&nbsp;转到第<Input type='text' name='page' size='3' class='form-control' maxlength='5' value='$page' onKeyPress=\"if (event.keyCode==13) window.location='".$strfilename."&maxperpage=".$maxperpage."&page='+this.value;\">页";?></li>
							</ul>
	    				</div>
	    			</div>
    			<?php endif;?>
            </div>
    	</div>
    </div>

<?php include_once('foot.php');?>
