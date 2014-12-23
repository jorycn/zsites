<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<style type="text/css">
.label {width:15%;}
</style>
<script type="text/javascript" language="javascript">
<!--
function backPrv(){
	window.location.href="index.php?_m=mod_message&_a=admin_list";	
}
//-->
</script>
<!--修改后台留言-->
<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$user = $_POST["username"];
	$email = $_POST["email"];
	$tele = $_POST["tele"];
	$message = $_POST["message"];
	$create_time = strtotime($_POST["create_time"]);
	$published = $_POST["published"];
	$reply = $_POST["admin_reply"];
	$id = $_POST["id"];
	
	if($user == ""){
		echo "<script>alert('昵称不能为空！');</script>";
	}
	if($email == ""){
		echo "<script>alert('电子邮件不能为空！');</script>";
	}
	if($tele == ""){
		echo "<script>alert('电话不能为空！');</script>";
	}
	if($message == ""){
		echo "<script>alert('留言内容不能为空！');</script>";
	}
	$ct = preg_match("/^[0-9]{4}(\-|\/)[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/",$create_time);
	if($create_time == "" || $ct === false){
		echo "<script>alert('创建时间为空或者格式不正确！');</script>";
		echo "<script>location.href='index.php?_m=mod_message&_a=admin_view&mess_id=$id';</script>";
		break;
	}
	$upsql = "update ".THISSITE."_messages set username='".$user."',email='".$email."',tele='".$tele."',message='".$message."',published='".$published."',create_time='".$create_time."',admin_reply='".$reply."' where id=".$id;
	mysql_query($upsql);
	echo "<script>alert('修改成功！');location.href='index.php?_m=mod_message&_a=admin_list&rurl=1';</script>";
}
?>
<form action="" method="post" name="myform">
<table id="downloadform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;">
    <tfoot>
      <tr>
    	<td colspan="2">
        <?php echo Html::input('button', 'cancel', __('Cancel'), 'onclick="backPrv()"');?>
        </td>
      </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Nickname'); ?></td>
            <td class="entry">
				<input type="text" name="username" value="<? echo $message->username; ?>" />
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('E-mail'); ?></td>
            <td class="entry">
				<input type="text" name="email" value="<? echo $message->email; ?>" />
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Telephone'); ?></td>
            <td class="entry">
				<input type="text" name="tele" value="<? echo $message->tele; ?>" />
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Message'); ?></td>
            <td class="entry">
				<textarea name="message" cols="30" rows="5"><? echo htmlentities($message->message,ENT_COMPAT,"UTF-8"); ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Create Time'); ?></td>
            <td class="entry">
				<?php
            	$create_time = date("Y-m-d H:i:s", $message->create_time);
           	    ?>
				<input type="text" name="create_time" value="<? echo $create_time; ?>" />  
            </td>
        </tr>
		<?php
			$sql = "select * from ".THISSITE."_messages where id=".$message->id;
			$que = mysql_query($sql);
			$row = mysql_fetch_array($que);
			?>
		<tr>
            <td class="label">显示</td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'published', '1', 
                Toolkit::switchText($row["published"], 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            </td>
        </tr>	
		<tr>
			
            <td class="label">站长回复</td>
            <td class="entry">
				<textarea name="admin_reply" cols="30" rows="5"><? echo $row["admin_reply"]; ?></textarea>
				<input type="hidden" name="id" value="<? echo $message->id; ?>" />
            </td>
        </tr>
		<tr>
            <td colspan="2" style="padding-left:254px;"><input type="submit" name="sub" value="提交" /></td>
        </tr>
    </tbody>
</table>
</form>