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
				<h1>网站设置</h1>
				<div id="footer">
					<div class="button2"><a href="?_a=upgrade">系统升级</a></div>
					<div class="button2"><a href="?_a=tpldown">模板库</a></div>
					<div class="button2"><a href="?_a=domain">泛域名设置</a></div>
					<div class="button2"><a href="?_a=setting">基本设置</a></div>
				</div>	
                    <table cellspacing="0" cellpadding="0">
						<tr>
                            <td width="18%" class="icon" valign="top">泛域名说明</td>
                            <td width="82%">
							  新获得的网站自动获得域名的条件<br>
                              1、建站宝必须在主机头为空的IIS下<br>
							  2、请将域名泛解析到服务器上<br>
							  3、设置泛域名规则<br>
							  4、如果您无法满足以上条件。请为每个网站人工绑定域名，并且分析是否可以正常访问

                            </td>
                          </tr>
						 <form name="myform" action="index.php" method="post">
						  <tr>
                            <td width="18%" class="icon" valign="top">泛域名</td>
                            <td width="82%">
                              <input type="text" name="defaultdomain"  value="<?php echo DEFAULTDOMAIN;?>" />
                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon"></td>
                            <td width="82%">
							  <input type="hidden" name="_a" value="savedomain">
                              <input type="submit" name="submit"  value="保存" />
							  <?php
							  if (DEFAULTDOMAIN!=""){
							  	for ($i=1;$i<6;$i++){
									echo "<br><a href='".gettrueurl(0,$i.DEFAULTDOMAIN)."' target='_blank'>".$i.DEFAULTDOMAIN."</a>";
								}
							  } 
							  ?>
                            </td>
                          </tr>
						  </form>
                        </table>
               	
            	</div>    
          </div>    
        </div>
		<?php echo cssheight(50);?>
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
