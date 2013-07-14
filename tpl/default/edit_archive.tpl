<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript">
$(document).ready(function() {
		$('#isautoname').change(function(){
					if($(this)[0].checked)
						$(this)[0].value = "yes";
					else
						$(this)[0].value = "no";
					$('#title').toggle(!$(this)[0].checked);
						});
		$('#isautoname').attr("checked",false);
	});
</script>
<title>{zengl title}</title>
</head>
<body>
<h2>{zengl title}</h2>
<form action={zengl action} enctype="MULTIPART/FORM-DATA" method="post">
新附件名(留空则不改变)：<input type="text" name="title" value='' id="title"/> &nbsp;&nbsp;
<input type="checkbox" name="isautoname" id="isautoname" value="no">是否自动设置附件名?</input><br/>
选择要上载覆盖的文件(留空则不覆盖原文件) <input type="file" name="upload" size="38" /><br/>
<input type="submit" name="submit" value="提交" /> 
</form>
</body>
</html>