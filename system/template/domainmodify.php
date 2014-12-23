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
				<h1>域名修改</h1>
					
                    <table cellspacing="0" cellpadding="0">
						 <form name="myform" action="index.php" method="post">
						   <tr>
                            <td width="18%" class="icon">域名</td>
                            <td width="82%">
                             <?php echo $domain;?>
                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">显示模板</td>
                            <td width="82%">
							  <select name="domaintemplate">
							  <?php
								$array=getDir("../template");
								for ($i=0;$i<sizeof($array);$i++){
									$thisvalue=$array[$i];
									if ($thisvalue){
										echo "<option value='".$thisvalue."'".fc_compare($domaintemplate,$thisvalue," selected","").">".$thisvalue."</option>";
									}
								}
							  ?>
							  </select>

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">主关键词</td>
                            <td width="82%">
                              <input type="text" name="mainkey"  value="<?php echo $mainkey;?>" />将自动替换格式2

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon" valign="top">转换关键词</td>
                            <td width="82%">
                              <textarea name="domainkey" cols="30" rows="5" /><?php echo $domainkey;?></textarea><br>格式如下<br>1:被替换关键词|替换后的关键词<br>2:被替换关键词<br>3:一行一个
                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon">状态</td>
                            <td width="82%">
                               <input type="radio" name="status"  value="0"<?php echo fc_compare($status,0," checked","")?>/>暂停<input type="radio" name="status"  value="1"<?php echo fc_compare($status,1," checked","")?>/>运行

                            </td>
                          </tr>
						  <tr>
                            <td width="18%" class="icon"></td>
                            <td width="82%">
							  <input type="hidden" name="comeurl" value="<?php echo $comeurl;?>">
							  <input type="hidden" name="domainid" value="<?php echo $domainid;?>">
							  <input type="hidden" name="_a" value="savemodifydomain">
                              <input type="submit" name="submit"  value="保存" />
                            </td>
                          </tr>
						  </form>
                        </table>
               	
            	</div>    
          </div>    
        </div>
		
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
