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
<style>
table{
	width:100%;
}
tr td{
	background:#c7fab5;
}
tr .first_td{
	width:150px;
}
#header_title{
	font-size:18px;
	background:#c7fab5;
	margin-bottom:10px;
}
</style>
</head>
<body>
<div id="header_title">{zengl title}</div>
<form action={zengl action} enctype="MULTIPART/FORM-DATA" method="post">
<table>
<tbody>
	<tr>
		<td class="first_td">新附件名(留空则不改变)：</td>
		<td><input type="text" name="title" value='' id="title"/></td>
	</tr>
	<tr>
		<td class="first_td"><input type="checkbox" name="isautoname" id="isautoname" value="no"></input></td>
		<td>是否自动设置附件名?</td>
	</tr>
	<tr>
		<td class="first_td">选择要上载覆盖的文件(留空则不覆盖原文件)</td>
		<td><input type="file" name="upload" size="38" /></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="提交" /></td>
	</tr>
</tbody>
</table>
</form>
</body>
</html>