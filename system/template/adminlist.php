<?php include_once('header.php') ?>

    <div class="container g-wrap">
      <h1 class="tit">ZSites站群管理平台</h1>

      <div class="row">
        <div class="col-lg-3">
            <?php include_once('left.php');?>
        </div>
        <div class="col-lg-9">
            <h2>站群管理</h2>

            <table cellspacing="0" cellpadding="0" class="table">
							
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
                        	<td width="82%">
                        		<form action="" class="form-inline">
                        		<input type="text" name="admin"  value="" class="form-control" />
						  		<input type="hidden" name="siteid" value="<?php echo $siteid;?>">
						  		<input type="hidden" name="_a" value="addadmin">
                          		<input type="submit" name="submit"  class="btn btn-default" value="增加" />
                          		</form>
                      		</td>
                          </tr>
			</table>

			<div class="row">
				<div class="col-lg-12 search">
					<form method='Get' name='sForm' action=''>
						<table width='100%' border='0' cellpadding='0' cellspacing='0' class='border'>  
							<tr class='tdbg'>   
								<td width='80' align='right'><strong>记录搜索：</strong></td>
								<td class="form-inline">
									<?php echo "<select name='field' size='1' class='form-control'>&nbsp;<option value='login'".fc_compare($field,"login"," selected","").">用户名</option></select>&nbsp;<input type='text' name='keyword'  size='20' class='form-control' value='$keyword' maxlength='50' onFocus='this.select();'>&nbsp;<input type='submit' value='搜索' class='btn btn-default'>";?>
								<td></td>
							</tr>
						</table>
					</form>
				</div>
			</div>

			<table cellSpacing=1 cellPadding=6 width="100%" bgColor=#dddddd border=0 class="table table-striped">
				<tbody>
					<tr bgColor=#f5f5f5 align=center>
						<td width="5%">编号</td>
						<td width="30%">会员名</td>
						<td width="10%">改密</td>
						<td width="10%">删除</td>
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

<?php include_once('foot.php');?>
