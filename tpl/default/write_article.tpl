<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript">
function openwindow(url,name,iWidth,iHeight)
{
	var url; //转向网页的地址;
	var name; //网页名称，可为空;
	var iWidth; //弹出窗口的宽度;
	var iHeight; //弹出窗口的高度;
	var iTop = (window.screen.availHeight-30-iHeight)/2; //获得窗口的垂直位置;
	var iLeft = (window.screen.availWidth-10-iWidth)/2; //获得窗口的水平位置;
	window.open(url,name,'height='+iHeight+',innerHeight='+iHeight+',width='+iWidth+
				  ',innerWidth='+iWidth+',top='+iTop+',left='+iLeft+',toolbar=no,menubar=no,scrollbars=yes,' + 
				  'resizeable=no,location=no,status=no');
}
function setimgsmimg(smimgpath,force)
{
	if($('#inputsmimg')[0].value == '' || force == true)
	{
		if(smimgpath != '')
		{
			$('#inputsmimg')[0].value = smimgpath;
			$('#imgsmimg').attr('src',smimgpath);
			$('#imgsmimg').show();
		}
		else
		{
			$('#inputsmimg')[0].value = '';
			$('#imgsmimg').hide();
		}
	}
}
$(document).ready(function() {
		setimgsmimg('{zengl article_smimgpath}',false);
		$('#checksmimg').click(function(){
			if($(this).prop('checked'))
			{
				$(this).prop('value',1);
			}
			else
			{
				$(this).prop('value',0);
			}
		});
	});
</script>
<title>{zengl title}</title>
</head>
<body>
<h2>{zengl title}</h2>
<form action={zengl action} method="post">
标题：<input type="text" name="title" value='{zengl article_title}' /> <br/>
作者：<input type="text" name="author" value='{zengl article_author}' /> <br/>
所属栏目：
<select name="sec_ID">
	{zengl options}
</select> <br/>
文章缩略图：<input type="text" name="smimgpath" id='inputsmimg' value='' /> 
<img border="0" src='' id = 'imgsmimg'> &nbsp;&nbsp;
<a href="javascript:void(0);" title="点我浏览服务器缩略图列表" 
   onClick='openwindow("{zengl smimglist}","浏览缩略图列表",900,500);'>浏览</a>
<a href="javascript:void(0);" title="清除以便重新设置缩略图" onClick='setimgsmimg("",true);this.blur();'>重置</a>
<input id='checksmimg' name='checksmimg' type="checkbox" value="1" checked="checked">是否自动获取缩略图  <br/>
tags标签：<input type="text" name="tags" value='{zengl article_tags}' /> <br/>
描述：<br/>
<textarea rows="20" cols="40" name="descript" id="edit_descript" style="width: 575px; height: 108px;">
{zengl article_descript}</textarea><br/>
内容:<br/>
<textarea rows="20" cols="40" name="content" id="edit_content">{zengl article_content}</textarea><br/>
<script type="text/javascript">
	CKEDITOR.replace('edit_content',
	{
		language: 'zh-cn',
		filebrowserBrowseUrl: 'list_upload_archive.php?action=list',
		filebrowserUploadUrl: 'list_upload_archive.php?action=upload'
	});
	CKEDITOR.config.tabSpaces = 10;
	CKEDITOR.config.smiley_path='ckeditor/plugins/smiley/images/';
	//CKEDITOR.config.smiley_images=['angel_smile.gif', 'angry_smile.gif'];
</script>
浏览次数：<input type="text" name="scans" value='{zengl article_scans}' /> <br/>
<input type="submit" value="提交" />
<input type="hidden" name="hidden" value={zengl hidden}>
<input type="hidden" name="articleID" value={zengl articleID}>
</form>
</body>
</html>