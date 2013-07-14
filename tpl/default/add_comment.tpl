<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<title>{zengl title}</title>
<script type="text/javascript">
	function IFrameResize(){
	
		 //alert(this.document.body.scrollHeight); //弹出当前页面的高度
		 var obj = parent.document.getElementById("show_comments");  //取得父页面IFrame对象
		 //alert(obj.height); //弹出父页面中IFrame中设置的高度
		 obj.height = this.document.body.scrollHeight+100;  //调整父页面中IFrame的高度为此页面的高度
		
		}
</script>
</head>
<body onload='IFrameResize();'>
<h2>{zengl title}</h2>
<form action={zengl action} method="post">
昵称：<input type="text" name="user" value='' /> <br/>
内容:<br/>
<textarea rows="20" cols="40" name="content" id="edit_content"></textarea><br/>
<script type="text/javascript">
	CKEDITOR.replace('edit_content',
	{
		language: 'zh-cn',
		toolbar :
		[
    		[ 'Source', '-', 'Bold', 'Italic' ,'-','Smiley']
		]
	});
	CKEDITOR.config.tabSpaces = 10;
	CKEDITOR.config.smiley_path='ckeditor/plugins/smiley/images/';
</script>
<input type = 'hidden' name='articleID' value='{zengl articleID}'/>
<input type="submit" value="提交" />
</form>
</body>
</html>