<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ZSites站群管理平台</title>
<link rel="stylesheet" type="text/css" href="./static/css/system.css">
<link rel="stylesheet" type="text/css" href="../../static/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../../static/datepicker/bootstrap-datetimepicker.min.css">
<script type="text/javascript" src="../static/js/jquery-2.0.2.min.js"></script>
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