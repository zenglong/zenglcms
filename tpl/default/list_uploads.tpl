<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.simplemodal.1.4.2.min.js"></script> 
<script type="text/javascript" src="{zengl theme}/js/jquery.tablesorter.min.js"></script>
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
		$(".smimg_cls").mouseover(function(){
			var src = $(this).parent().parent().find('.a_upload').attr('href');
			var top = $(this).offset().top;
			var left = $(this).offset().left;
			$("#smimg_tip").html('大图预览：<br/><img src="' + src + '" alt="图片加载中。。。"/>')
						   .css({"top" :(top-20) + "px",
								"left" :(left+90) + "px"}).show();
		}).mouseout(function(){
			$("#smimg_tip").hide();
		});

		$('#list_table').tablesorter();

		$('#selectall').click(function(){
			$('.checkID').attr('checked',true);
			return false;
		});

		$('#unselect').click(function(){
			$('.checkID').attr('checked',false);
			return false;
		});

		$('#invertselect').click(function(){
			checknum = $('.checkID').size();
			var ischecked;
			for(i=0;i<checknum;i++)
			{
				ischecked = $('.checkID:eq('+i+')')[0].checked;
				if(ischecked)
					$('.checkID:eq('+i+')').attr('checked',false);
				else
					$('.checkID:eq('+i+')').attr('checked',true);
			}
			return false;
		});

		$('#multidel').click(function(){
			delStr = '您要删除以下附件吗：<br/>';
			checknum = $('.checkID').size();
			for(i=0;i<checknum;i++)
			{
				if($('.checkID:eq('+i+')')[0].checked)
					delStr += '(id:' + $('.checkID:eq('+i+')')[0].value + ') 附件名:' + $('.a_upload:eq('+i+')').text() + '<br/>';
			}
			$('#del_dialog p').html(delStr);
			$('#del_dialog').modal({maxWidth:400});
			return false;
		});

		$('#del_dialog').hide();
		$('.yes').click(function(){
			locationStr = '{$zengl_cms_rootdir}list_upload_archive.php?action=multi_del';
			checknum = $('.checkID').size();
			count = 0;
			for(i=0;i<checknum;i++)
			{
				if($('.checkID:eq('+i+')')[0].checked)
				{
					if(count == 0)
						locationStr += '&archiveID=' + $('.checkID:eq('+i+')')[0].value
					else
						locationStr += ',' + $('.checkID:eq('+i+')')[0].value;
					count++;
				}
			}
			location.href = locationStr;
			return false;
		});
  });
-->
</script>
<title>{zengl title}</title>
<style>
table{
	width:100%;
}
tr td{
	background:#c7fab5;
	text-align:center;
}
#header_title{
	font-size:18px;
	background:#c7fab5;
	margin-bottom:10px;
}
#smimg_tip{
	display:none;
	position:absolute;
	color:#fff;
	text-align:center;
	background:#51B906;
}
#list_table thead tr .header {
	background-image: url({zengl theme}/images/tableSort/bg.gif);
	background-repeat: no-repeat;
	background-position: center right;
	cursor: pointer;
}

#list_table thead tr .headerSortUp {
	background-image: url({zengl theme}/images/tableSort/asc.gif);
}

#list_table thead tr .headerSortDown {
	background-image: url({zengl theme}/images/tableSort/desc.gif);
}

#list_table thead tr .headerSortUp, #list_table thead tr .headerSortDown{
	background-color: #8DBDD8;
}
img{
	border:0;
}
#simplemodal-overlay {background-color:#777;}
#simplemodal-container {background-color:#85d949; border:8px solid #51B906; padding:12px;}
#del_dialog .buttons div,#html_dialog .buttons div,#move_dialog .buttons div,#move_dialog_ask .buttons div{
	background-image:url({zengl theme}/images/admin_list_articles_css_img/dialog_button.gif); 
	background-repeat: repeat-x;
	cursor: pointer;
	float: right;
	font-weight: bold;
	height: 26px;
	margin-left: 4px;
	text-align: center;
	width: 70px;
}
</style>
</head>
<body>
<div id="header_title">{zengl title}</div>
<div id="smimg_tip"></div>
<table id="list_table">
	<thead> 
		<tr>
			<th>序号</th><th width="70">选择</th><th>缩略图</th><th>附件名</th><th>上传时间</th><th>大小(K)</th><th>操作</th>
		<tr>
	</thead>
	<tbody>
{zengl for_uploads}
	<tr>
		<td>{zengl upload_num}</td>
		<td align='center'><input type="checkbox" class="checkID" value="{$sql->row[archive_ID]}"></td>
		<td><img border="0" src="{zengl smimgsrc}" width="80" class="smimg_cls"></td>
		<td><a href={zengl upload_loc} class = 'a_upload'>{zengl upload_file}</a></td>
		<td>{zengl upload_time}</td>
		<td>{zengl upload_size}</td>
		<td><a href={zengl archive_edit} title="编辑" onfocus="this.blur()"><img src="{zengl theme}/images/admin_list_articles_css_img/edit.jpg" width="23"></a>&nbsp;
		<a href={zengl archive_del} class="delArchive" title="删除" onfocus="this.blur()"><img src="{zengl theme}/images/admin_list_articles_css_img/del.jpg" width="23"></a></td>
	</tr>
{zengl endfor}
	</tbody>
</table>
<div id="operate">
	<a href="#" id="selectall">全选</a>&nbsp;
	<a href="#" id="invertselect">反选</a>&nbsp;
	<a href="#" id="unselect">取消选择</a>&nbsp;
	<a href="#" id="multidel">批量删除</a>&nbsp;
</div>
<br/>
<div>
<select id="page_sel">
{zengl for_pages}
<option value="{zengl page_val}" {zengl page_option}>第{zengl page_val}页</option>
{zengl endfor}
</select>
</div>
<div id='del_dialog'>
	<p>你要删除文件吗?</p>
	<div class='buttons'>
		<div class='no simplemodal-close'>No</div><div class='yes'>Yes</div>
	</div>
</div>
<div style="height:200px;">
&nbsp;
</div>
</body>
</html>