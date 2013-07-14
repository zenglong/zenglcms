<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/login.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#authImg").click(function() {
						$(this)[0].src = {zengl auth_src} + Math.random();
						});
		$("#title a").hover(function(){
						$(this).css({"background":'red'});
					},function(){
						$(this).css({"background":'black'});
					});
		$("#title a").css({"background":'black',"color":'white'});
	});
	function subtest()
	{
		username = $("#username")[0].value;
		password = $("#password")[0].value;
		if(username == '' || password == '')
		{
			alert("用户名，密码都不能为空！");
			return false;
		}
		else if(username.length > 50)
		{
			alert("用户名不能超过50个字符！");
			return false;
		}
		
		return true;
	}
</script>
<title>{zengl title}</title>
</head>
<body>
<div id = 'main'>
	<div id = "main_head">
	</div>
	<div id = "main_mid">
		<div id="title">
			{zengl title} &nbsp;&nbsp;&nbsp;&nbsp; <a href='{zengl retloc}'>&nbsp;返回来页&nbsp;</a>
		</div>
		<div id = 'content'>
			<form action={zengl action} method="post" onsubmit="return subtest();">
			<dl>
				<dt>用户名：</dt>
				<dd><input type="text" name="username" id="username" size="30" maxlength="60" /></dd>
				<dt>密&nbsp;&nbsp;码：</dt>
				<dd><input type="password" name="password" id="password" size="30" maxlength="60" /></dd>
				<dt>验证码：</dt>
				<dd id = "authumdiv"><input type="text" name="authnum" id="authnum" size="15" maxlength="60" /> </dd>
				<dd id = "authImgdiv"><img id='authImg' src={zengl auth_src} /> </dd>
				<dt>&nbsp;&nbsp;</dt>
				<dd><input type="submit" value="提交" style="margin-top: 20px;"/></dd>
			</dl>
			</form>
		</div>
	</div>
	<div id = "main_last">
	</div>
</div>
</body>
</html>