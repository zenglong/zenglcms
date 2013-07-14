<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript">
<!--
function getUrlParam(paramName)
{
  var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
  var match = window.location.search.match(reParam) ;
 
  return (match && match.length > 1) ? match[1] : '' ;
}
$(document).ready(function() {
		$(".delArchive").click(function(){
			if(confirm("是否将此信息删除?")){
			 	location.href = $(this)[0].href;
				return false;
			}
			else { 
				return false;
			}
		});
		$('.a_upload').click(function(){
			var funcNum = getUrlParam('CKEditorFuncNum');
			var fileUrl = $(this).attr('href');
			this.blur();
			window.opener.CKEDITOR.tools.callFunction(funcNum, fileUrl);
			window.close();
			return false;
		});
		$('#page_sel').change(function(){
			window.location.href="{zengl rootdir}list_upload_archive.php?action=list" +
					'&sec_page='+$(this).val();
		});
  });
-->
</script>
<title>{zengl title}</title>
</head>
<body>
<h2>{zengl title}</h2>
<ul style="list-style-type:none;">
{zengl for_uploads}
<li>
{zengl upload_num}&nbsp;
<img border="0" src="{zengl smimgsrc}"> &nbsp;&nbsp; 
<a href={zengl upload_loc} class = 'a_upload'>{zengl upload_file}</a> &nbsp;&nbsp; {zengl upload_time}
&nbsp;&nbsp; 大小：{zengl upload_size}K
&nbsp;&nbsp;<a href={zengl archive_edit}>编辑</a>
<a href={zengl archive_del} class="delArchive">删除</a>
</li>
{zengl endfor}
</ul>
<div>
<select id="page_sel">
{zengl for_pages}
<option value="{zengl page_val}" {zengl page_option}>第{zengl page_val}页</option>
{zengl endfor}
</select>
</div>
</body>
</html>