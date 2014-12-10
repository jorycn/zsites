<?php include_once('header.php') ?>

    <div class="container g-wrap">
      <h1 class="tit">ZSites站群管理平台</h1>
      
      <div class="row">
        <div class="col-lg-3">
          <?php include_once('left.php');?>
        </div>
        <div class="col-lg-9">
            <div class="row">
              <div class="col-lg-12"><h1>域名检测-<?php echo $sitename;?></h1></div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">检测域名</div>
                        <div class="panel-body">
                            <table cellspacing="0" cellpadding="0" class="table">
							
		                          <tr>
		                            <td width="18%" class="icon">检测域名</td>
		                            <td width="82%"><?php echo $domain;?></td>
		                          </tr>
								  <tr>
		                            <td width="18%" class="icon" valign="top">域名解析</td>
		                            <td width="82%">
									cname记录解析到:<?php echo substr(DEFAULTDOMAIN,1,strlen(DEFAULTDOMAIN));?><br>
									A记录解析到:<?php echo $_SERVER['REMOTE_ADDR'];?><br>
									检测结果(服务器分析结果)：
									<?php
									$check=1;
									$ip = gethostbyname($domain);
									if ($ip==$_SERVER['REMOTE_ADDR']){
										echo "<font color=blue>正常</font>";
									}else{
										echo "<font color=red>解析不正常</font>";
										$check=0;
									}
									?>
									</td>
		                          </tr>
								  <tr>
		                            <td width="18%" class="icon">站点绑定</td>
		                            <td width="82%">
									<?php
									if ($check==1){
										$url="http://$domain?action=ping";
										$result =file_get_contents($url);
										if ($result==md5($_SERVER['HTTP_HOST'])){
											echo "<font color=blue>正常,可访问站点$siteid</font>";
										}else{
											$check=0;
											echo "<font color=red>异常,请先绑定域名到此站点，或者将此站点做成泛域名解析</font>";
										}
										
									}else{
										echo "<font color=red>请先解析</font>";
										$check=0;
									}
									$sql="update ms_domain set status='$check' where domainid=".$domainid;
									$result=mysql_query($sql);
									?></td>
		                          </tr>
								  <tr>
		                            <td class="icon">检测结果</td>
		                            <td><?php echo fc_compare($check,1,"<font color=blue>正常</a>","<font color=red>异常</font>");?></td>
		                          </tr>
								   <tr>
		                            <td class="icon">返回站点</td>
		                            <td><a href="?_a=manage&siteid=<?php echo $siteid;?>">返回站点</a></td>
		                          </tr>
		                        </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

<?php include_once('foot.php');?>
