function Checkcompany() {
    var easycws = UserReg.company.value.toLowerCase();
	if(document.UserReg.company.value.length<2)
	{
		enter_name.innerHTML = "<img src='../images/check_err.gif' height='13' width='13'>&nbsp;<font color='#FF0000'>用户名不得小于2个字符</font>";
	return false;
	}
	else if(document.UserReg.company.value.length>8)
	{
		enter_name.innerHTML = "<img src='../images/check_err.gif' height='13' width='13'>&nbsp;<font color='#FF0000'>用户名不得多于8个字符</font>";
	return false;
	}
	else if (!/[\u4e00-\u9fa5]/g.test(document.UserReg.company.value) && document.UserReg.company.value.length<4)
	{
		enter_name.innerHTML = "<img src='../images/check_err.gif' height='13' width='13'>&nbsp;<font color='#FF0000'>英文用户名不得少于4个字符</font>";
	return false;
	}
	else if (!/[\u4e00-\u9fa5]/g.test(document.UserReg.company.value) && document.UserReg.company.value.length>15)
	{
		enter_name.innerHTML = "<img src='../images/check_err.gif' height='13' width='13'>&nbsp;<font color='#FF0000'>英文用户名不得大于15个字符</font>";
	return false;
	}
	else{
		enter_name.innerHTML = "&nbsp;<font color='#008000'>检测中...</font>";
		var oBao = new ActiveXObject("Microsoft.XMLHTTP");
		oBao.open("post","Checkaddcompany.asp?company=" + easycws,false);
		oBao.send();
		var text = unescape(oBao.responseText);
		if(text=="True")
		{
			enter_name.innerHTML = "<img src='../images/check_right.gif' height='13' width='13'>&nbsp;<font color='#2F5FA1'>不存在</font>";
			return true;
		}
		else{
			enter_name.innerHTML = "<img src='../images/check_err.gif' height='13' width='13'>&nbsp;<font color='#FF0000'>已存在</font>";
		}
	}
}
