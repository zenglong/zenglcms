<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<title>{zengl title}</title>
<meta name="generator" content="zenglcms"/>
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/admin_middle.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
</head>
<body>
<table cellpadding="0" cellspacing="0" width="100%" height="100%">
<tr>
<td class="side" title="点击关闭/打开侧栏">
<div id="side" class="side_on">&nbsp;</div>
</td>
</tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
	$(".side").click(function(){
		if($('#side').attr('class') == 'side_on') {
			top.document.getElementsByName("fra")[0].cols = '0,7,*';
			$('#side').attr('class','side_off');
		} else {
			top.document.getElementsByName("fra")[0].cols = '240,7,*';
			$('#side').attr('class','side_on');
		}
	});
});
</script>
</body>
</html>