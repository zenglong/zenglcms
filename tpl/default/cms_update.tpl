<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/cms_update.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.simplemodal.1.4.2.min.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
		$('#update_dialog').modal({maxWidth:400});
		$('.update_yes').click(function(){
			locationStr = '{zengl cms_root_dir}update_operate.php?action={zengl update_loc}';
			locationStr += '&user=' + $('#username')[0].value;
			locationStr += '&pass=' + $('#password')[0].value;
			location.href = locationStr;
			return false;
		});
	});
</script>
<title>{zengl title}</title>
</head>
<body>
<div id = "maindiv">
<h2>{zengl title}</h2>

<div id='update_dialog'>
	<p>请输入{zengl title}的用户名和密码：</p>
	user:<input type="text" name="username" id="username" size="30" maxlength="60" /><br/>
	pass:<input type="password" name="password" id="password" size="30" maxlength="60" /><br/>
	<br/>
	<div class='buttons'>
		<div class='update_no simplemodal-close'>No</div><div class='update_yes'>Yes</div>
	</div>
</div>

</div>
</body>
</html>