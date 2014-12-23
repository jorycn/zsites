<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$id_seed = Toolkit::randomStr();
$id_seed = preg_replace("/\d/",'',$id_seed);
?>
<script type="text/javascript" language="javascript">
<!--
function on_msg_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("messfrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["messform"].reset();
        msg_reload_captcha("<?php echo $id_seed; ?>");
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK" || o_result.forward == "index.php?_m=mod_message&_a=form") {
	    stat.style.display = "none";
        alert("<?php _e('Thank you! Your message has been submitted successfully!'); ?>");
        // reload
        reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["messform"].reset();
    msg_reload_captcha("<?php echo $id_seed; ?>");
    document.getElementById("messfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function msg_reload_captcha(<?php echo $id_seed; ?>) {
    var captcha = document.getElementById("msg_captcha<?php echo $id_seed; ?>");
    if (captcha) {
        captcha.src = "captcha.php?s=" + random_str(6);
    }
}
<?php 
if(SessionHolder::get('page/status', 'view') == 'edit')
{
	echo <<<JS
function message_edit()
{
	$('#tb_mb_message1').css('display','block');
}

function message_cancel()
{
	$('#tb_mb_message1').css('display','none');
}
JS;
}
?>
//-->
</script>


<div class="art_list1" <?php if(SessionHolder::get('page/status', 'view') == 'edit') echo "style='position:relative;' onmouseover='message_edit();' onmouseout='message_cancel();'";?>>
	<!-- 编辑时动态触发 【start】-->
	<!--
	<div class="mod_toolbar" id="tb_mb_message1" style="display: none; height: 28px; position: absolute; right: 2px; background: none repeat scroll 0% 0% rgb(247, 182, 75); width: 70px;">
		<a onclick="popup_window('admin/index.php?_m=mod_message&_a=admin_list','<?php //echo _e('Message');?>&nbsp;&nbsp;<?php //echo _e('Edit Content');?>',false,false,true);return false;" title="<?php //echo _e('Edit Content');?>" href="#"><img border="0" alt="<?php //echo _e('Edit Content');?>" src="images/edit_content.gif">&nbsp;<?php //echo _e('Edit Content');?></a>
	</div>
	-->
	<!-- 编辑时动态触发 【end】-->
<?php
$act =& ParamHolder::get('_m');
if (($act != 'frontpage') && !empty($act)) {
?>
	<div class="art_list_title"><?php echo $page_cat; ?></div>
<?php }?>
	<span id="messfrm_stat" class="status" style="display:none;"></span>
<?php
$mess_form = new Form('index.php', 'messform', 'check_mess_info');
$mess_form->p_open('mod_message', 'messInsert', '_ajax');

/* 修改留言开始 */
$perNumber=5; 														//每页显示的记录数 
$page=$_GET['page']; 												//获得当前的页面值.每次点击页数提交页数，重新加载页面，重新执行
$count=mysql_query("select count(*) from ".THISSITE."_messages where published=1");   //获得记录总数 
$rs=mysql_fetch_array($count);								    	//返回根据从结果集取得的行生成的数组，如果没有更多行则返回 false
$totalNumber=$rs[0]; 												//统计该表中的数据总数
$totalPage=ceil($totalNumber/$perNumber); //计算出总页数 ，ceil进一法取整，返回不小于 value 的下一个整数，value 如果有小数部分则进一位
if(!isset($page)){ 
	$page=1; 
} 																	//如果没有值,则赋值1 .默认打开页面显示第一页
$startCount=($page-1)*$perNumber;									//分页开始,根据此方法计算出开始的记录
$sql="select * from ".THISSITE."_messages where  published=1 order by id desc limit ".$startCount.",".$perNumber;
$result=mysql_query($sql); 
?>
<!--mess_main-->
<div id="mess_main">
	<?php
	while($row = mysql_fetch_array($result)){
	?>
	<table width="100%">
	<tr><td width="150">第<? echo $row["id"]; ?>条</td><td width="150">昵称：<? echo $row["username"]; ?></td><td width="*">留言时间：<? echo date("Y-m-d H:i:s",$row["create_time"]); ?></td></tr>
	<tr><td width="150" height="100"></td>
	<td width="150"></td>
	<td>
	<? echo $row["message"]; ?>
	<?php
				if($row["admin_reply"] != ""){
				?>
				<div style="width:555px; height:20px; line-height:20px; float:left;">站长回复：</div>
				<div style="width:495px; height:35px; line-height:35px; float:left; padding-left:60px;"><? echo $row["admin_reply"]; ?></div>
				<?
				}
				?>
	</td>
	</tr>
	</table>
	<br>
	<?php
	}
	?>
	<div class="mess_list">
		<?php
		if ($page != 1)
		{ //页数不等于1 
		?> 
			<a href="index.php?_m=mod_message&_a=form&page=<?php echo $page - 1;?>">上一页</a> <!--显示上一页--> 
		<?php 
		} 
		for ($i=1;$i<=$totalPage;$i++)
		{ //循环显示出页面 
		?> 
			<a href="index.php?_m=mod_message&_a=form&page=<?php echo $i;?>"><?php echo $i ;?></a> 
		<?php 
		}
		if ($page<$totalPage)
		{ //如果page小于总页数,显示下一页链接 
		?> 
			<a href="index.php?_m=mod_message&_a=form&page=<?php echo $page + 1;?>">下一页</a> 
		<?php 
		} 
		?>
	</div>
</div>
<div id="mess_main">
<div class="mess_list">
	<div class="mess_title"><?php _e('Nickname'); ?></div><div class="mess_input"><?php echo Html::input('text', 'mess[username]', '', '', $mess_form, 'RequiredTextbox', __('Please input your username!')); ?></div>
 </div>
 <div class="mess_list">
	<div class="mess_title"><?php _e('E-mail'); ?></div><div class="mess_input"><?php echo Html::input('text', 'mess[email]', '', '', $mess_form, 'RequiredTextbox',  __('Please input your email!')); ?></div>
</div>
<div class="mess_list">
	<div class="mess_title"><?php _e('Telephone'); ?></div><div class="mess_input"><?php echo Html::input('text', 'mess[tele]', '', '', $mess_form, 'RequiredTextbox',  __('Please input your telephone!')); ?></div>
</div>
<div class="mess_list">
	<div class="mess_title"><?php _e('Content'); ?></div><div class="mess_textarea"><?php echo Html::textarea('mess[message]', '', 'rows="6" cols="36"', $mess_form, 'RequiredTextbox', __('Please input your content!')); ?></div>
</div>
<?php if (SITE_LOGIN_VCODE) { ?>
<div class="mess_list">
	<div class="mess_title"><?php _e('Security'); ?></div><div class="mess_input"><img style="top:3px;margin-right:4px;" id="msg_captcha<?php echo $id_seed; ?>" src="captcha.php" class="captchaimg" border="0" />
	<?php echo Html::input('text', 'mess[rand_rs]', '', 'style="width:52px;"', $mess_form, 'RequiredTextbox',  __('Please give me an answer!')); ?></div>
</div><?php } ?>
<div class="mess_list">
<div class="mess_submit"><input name="sub" type="submit" class="subd" value="<?php _e('Submit');?>" /></div>
</div>
<div class="message_bg"></div>
</div>
<!--mess_main end-->
<div class="list_bot"></div>
</div>
<?php
$mess_form->close();
$running_msg = __('Saving message...');
$custom_js = <<<JS
$("#messfrm_stat").css({"display":"block"});
$("#messfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_msg_success, on_failure);
return false;

JS;
$mess_form->addCustValidationJs($custom_js);
$mess_form->writeValidateJs();
?>