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
                	<?php include_once('left.php');
					?>
                </ul>
            </div>
           
            <div id="right">
            	<div id="right_bor">
				<h1>服务器版管理</h1>
					<div class="form-tips">
						<ol>
							<li>赚钱计划：<a href="http://www.jzbao.net/buy"><font color="#FF0000">服务器版授权即将销售一空,一个城市只限一套,数量有限!</font></a></li>
							<li>支持广泛：VPS或者服务器均可，甚至一个空间也可以(一个域名一个网站),PHP+MYSQL,最低配置都能跑。
							<li>不限数量：不限建站数量限制，终身授权</li>
							<li>海量模板：2000套模板全部提供</li>
							<li>平台支持：整合魅力赢平台,<a href="http://www.melyy.net/site" target="_blank">一分钟在线开通网站</a>，支持星外，宏杰，华众等众多虚拟主机管理系统</li>
						</ol>
					</div>
                    <table cellspacing="0" cellpadding="0">
					  <tr>
						<td width="18%" class="icon">服务器ID</td>
						<td width="82%"><input type="text" name="id" style="width:230px;" value="<?php echo $server;?>"/></td>
					  </tr>
					  <tr>
						<td width="18%" class="icon">服务器IP</td>
						<td width="82%"><input type="text" name="ip"  value="<?php echo $ip;?>"/></td>
					  </tr>
					  <tr>
						<td width="18%" class="icon">注册状态</td>
						<td width="82%"><?php echo fc_compare($checksn,1,"注册","未注册");?></td>
					  </tr>
					  <tr>
						<td width="18%" class="icon">注册序列号</td>
						<form action=""><td width="82%"><textarea name="sn" cols="30" rows="5"><?php echo $sn;?></textarea>多IP请用,分开
						  <input type="hidden" name="_a" value="savesn">
						 <br> <input type="submit" name="submit"  value="保存" /><a href="http://www.jzbao.net" target="_blank">获得序列号</a></td></form>
					  </tr>  
					</table>
               	
            	</div>    
          </div>    
        </div>
		
        <?php include_once('foot.php');?>
    </div>
</body>
</html>
