<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link href="{zengl theme}/css/jquery-ui/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />
<link href="{zengl theme}/css/jquery-ui/jquery-ui-timepicker-addon.css" rel="stylesheet" />
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery-ui/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery-ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery-ui/jquery.ui.datepicker-zh-CN.js.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery-ui/jquery-ui-timepicker-zh-CN.js"></script>
<script type="text/javascript">
var ck_orig_html = '';
var ck_interval;
var ck_orig_upload_url = '';
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
function setimgsmimg(smimgpath)
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
	$('#isneed_getsmimg_input')[0].value = 'no';
}

function setimgsmimg_ineditor(smimgpath)
{
	if($('#inputsmimg')[0].value == '')
	{
		if($('#checksmimg')[0].value == 0)
			return;
		setimgsmimg(smimgpath);
	}
}

function onRestore_Draft()
{
	$('#ck_save_feedback').load('{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=ajax_get_draft',
							function(response, status, xhr){
									$(this).html('');
									if (status == "error") {
										$(this).html('发生错误:'+ xhr.status + " " + xhr.statusText);
										return;
									}
									json_ret = jQuery.parseJSON(response);
									if(json_ret.hasDraft == 'no')
									{
										alert('没有保存过的草稿!');
										$(this).html('没有保存过的草稿!');
										return;
									}
									var r=confirm("是否将数据恢复到"+json_ret.time+" 数据将会覆盖掉当前编辑器的内容");
									if(r==true)
									{
										CKEDITOR.instances.edit_content.setData(json_ret.content);
										$(this).html('数据恢复到'+json_ret.time);
									}
									else
									{
										$(this).html('最近一次保存是:'+json_ret.time);
									}
							});
}

function onSave_Draft(isAuto)
{
	var ck_html = CKEDITOR.instances.edit_content.getData();
	//alert(ck_html);
	var ck_strlen = ck_html.length;
	var ck_isauto = '&isAutoSave=no';
	if((ck_orig_html == ck_html || ck_strlen < 10) && isAuto == true) return ;
	if(isAuto == true)
		ck_isauto = '&isAutoSave=yes';
	$('#ck_save_feedback').load('{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=ajax_save_to_draft' + ck_isauto,
					{'content': ck_html}, function(response, status, xhr){
												if (status == "error") {
													$(this).html('发生错误:'+ xhr.status + " " + xhr.statusText);
												}
												else
													$(this).html(response);
												ck_orig_html = ck_html;
											});
}

function onAutoSave_Draft() {
	ck_interval = setInterval('onSave_Draft(true)', 30000);
	$('#ck_AutoSave_draft').html('<a href="javascript:onCloseAutoSave_Draft();">关闭系统自动保存草稿功能</a>');
}

function onCloseAutoSave_Draft()
{
	clearInterval(ck_interval);
	$('#ck_AutoSave_draft').html('<a href="javascript:onAutoSave_Draft();">开启系统自动保存草稿功能</a>');
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
		$("#ck_save_feedback").ajaxSend(function(event, request, settings){
				if(settings.url.indexOf('{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=ajax_save_to_draft') != -1)
				{
					if(settings.url.indexOf('&isAutoSave=no') != -1)
						$(this).html("<img src='{zengl cms_root_dir}images/loading.gif' /> 正在保存草稿中。。。");
					else
						$(this).html("<img src='{zengl cms_root_dir}images/loading.gif' /> 系统自动保存草稿中。。。");
				}
				else if(settings.url.indexOf('{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=ajax_get_draft') != -1)
				{
					$(this).html("<img src='{zengl cms_root_dir}images/loading.gif' /> 正在获取草稿中。。。");
				}
		});

		onAutoSave_Draft();

		$('#time').datetimepicker({
			showSecond: true,
			timeFormat: "HH:mm:ss",
			dateFormat: "yy-mm-dd"
		});

		$('#addtime').datetimepicker({
			showSecond: true,
			timeFormat: "HH:mm:ss",
			dateFormat: "yy-mm-dd"
		});

		$('#article_form').submit(function(){
			if($("#article_title").val() == '')
			{
				alert('标题为空!');
				return false;
			}
			else if($("#article_author").val() == '')
			{
				alert('作者为空!');
				return false;
			}
			else if(CKEDITOR.instances.edit_content.getData() == '')
			{
				alert('内容为空!');
				return false;
			}
		});
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
#ck_restore_draft,#ck_save_draft,#ck_AutoSave_draft,#ck_save_feedback{
	font-size:14px;
	color: #006699;
}
#ck_restore_draft a,#ck_save_draft a,#ck_AutoSave_draft a,#ck_save_feedback a{
	font-size:14px;
	color: #006699;
	text-decoration: none;
}

.demo-description {
	clear: both;
	padding: 12px;
	font-size: 1.3em;
	line-height: 1.4em;
}

.ui-draggable, .ui-droppable {
	background-position: top;
}
</style>
</head>
<body>
<div id="header_title">{zengl title}</div>
<form action={zengl action} method="post" id="article_form">
<table>
<tbody>
	<tr>
		<td class="first_td">标题：</td>
		<td><input type="text" name="title" value='{zengl article_title}' id="article_title"/> </td>
	</tr>
	<tr>
		<td class="first_td">作者：</td>
		<td><input type="text" name="author" value='{zengl article_author}' id="article_author"/> </td>
	</tr>
	<tr>
		<td class="first_td">所属栏目：</td>
		<td>
			<select name="sec_ID">
				{zengl options}
			</select> 
		</td>
	</tr>
	<tr>
		<td class="first_td">文章缩略图：</td>
		<td>
			<input type="text" name="smimgpath" id='inputsmimg' value='' />
			<img border="0" src='' id = 'imgsmimg' width="180">
			<a href="javascript:void(0);" title="点我浏览服务器缩略图列表" onClick='openwindow("{zengl smimglist}","浏览缩略图列表",900,500);'>浏览</a>
			<a href="javascript:void(0);" title="清除以便重新设置缩略图" onClick='setimgsmimg("");this.blur();'>重置</a>
			<input id='checksmimg' name='checksmimg' type="checkbox" value="1" checked="checked"><label for="checksmimg">是否自动获取缩略图</label>
		</td>
	</tr>
	<tr>
		<td class="first_td">tags标签：</td>
		<td><input type="text" name="tags" value='{zengl article_tags}' /></td>
	</tr>
	<tr>
		<td class="first_td">描述：</td>
		<td><textarea rows="20" cols="40" name="descript" id="edit_descript" style="width: 575px; height: 108px;">{zengl article_descript}</textarea></td>
	</tr>
	<tr>
		<td class="first_td">内容：</td>
		<td>
		{php $watermark_setting_array = config_get_db_setting('watermark');}
		<textarea rows="20" cols="40" name="content" id="edit_content">{zengl article_content}</textarea>
<script type="text/javascript">
	CKEDITOR.replace('edit_content',
	{
		language: 'zh-cn',
		filebrowserBrowseUrl: 'list_upload_archive.php?action=list',
		filebrowserUploadUrl: 'list_upload_archive.php?action=upload&isNeedWaterMark={if $watermark_setting_array[watermark_switch]=="on"}yes{else}no{/if}',
		extraPlugins:"zengl_page,zengl_codetable"
	});
	CKEDITOR.on( 'dialogDefinition', function( ev )
    {
		  // Take the dialog name and its definition from the event data.
		  var dialogName = ev.data.name;
		  var dialogDefinition = ev.data.definition;

		  // Check if the definition is from the dialog we're
		  // interested in (the 'image' dialog). This dialog name found using DevTools plugin
		  if ( dialogName == 'image' )
		  {
				 // Get a reference to the 'Image Info' tab.
				 var uploadTab = dialogDefinition.getContents( 'Upload' );
				 uploadTab.add({
							type : 'checkbox',
							id : 'mywatermark',
							label : '是否添加水印',
							'default' : {if $watermark_setting_array[watermark_switch]=='on'} true {else} false {/if},
							onClick : function() {
								ck_orig_upload_url = $('.cke_dialog_ui_input_file').contents().find("form").attr('action');
								if(this.getValue() == true)
								{
									$('.cke_dialog_ui_input_file').contents().find("form").attr('action',ck_orig_upload_url.replace('isNeedWaterMark=no',
																															 'isNeedWaterMark=yes'));
								}
								else
								{
									$('.cke_dialog_ui_input_file').contents().find("form").attr('action',ck_orig_upload_url.replace('&isNeedWaterMark=yes',
																															 '&isNeedWaterMark=no'));
								}
								//alert($('.cke_dialog_ui_input_file').contents().find("form").attr('action'));
							}
						});

				var originalOnOkFunction = dialogDefinition.onOk;
				dialogDefinition.onOk = function(ev) 
				{
					var dialog = CKEDITOR.dialog.getCurrent();
					src_url = dialog.getContentElement('info', 'txtUrl').getValue();
					setimgsmimg_ineditor(src_url);
					$('#isneed_getsmimg_input')[0].value = 'yes';
					originalOnOkFunction.call(this);  //change here
				}
		  }
    });
	CKEDITOR.config.tabSpaces = 4;
	CKEDITOR.config.smiley_path='ckeditor/plugins/smiley/images/';
	CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;    //回车，增加BR标签
	CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_P;  //shift+回车，增加P标签
	//CKEDITOR.config.smiley_images=['angel_smile.gif', 'angry_smile.gif'];
</script>
		</td>
	</tr>
	<tr>
		<td colspan="2" height="40">
			<span id="ck_restore_draft"><a href="javascript:onRestore_Draft();">恢复之前保存的草稿</a></span>&nbsp;
			<span id="ck_save_draft"><a href="javascript:onSave_Draft(false);">手动保存为草稿</a></span>&nbsp;
			<span id="ck_AutoSave_draft"></span>&nbsp;
			<span id="ck_save_feedback"></span>
		</td>
	</tr>
	<tr>
		<td class="first_td">添加时间：</td>
		{if isset($sql->row[addtime])}
		<td><input id="addtime" type="text" name="addtime" value="{date('Y-m-d H:i:s',$sql->row[addtime])}"/>
		{else}
		<td><input id="addtime" type="text" name="addtime" value=""/>
		{/if}
		<span style="font-size:14px;color: #006699;">(留空则由系统设置为当前编辑时间)</span></td>
	</tr>
	<tr>
		<td class="first_td">编辑时间：</td>
		<td><input id="time" type="text" name="time"/><span style="font-size:14px;color: #006699;">(点击左侧可以弹出时间设置对话框,默认留空,留空上传时cms自动设为服务器当前时间,可以自行调整)</span></td>
	</tr>
	<tr>
		<td class="first_td">内容页模板：</td>
		{if isset($sql->row[tpl])}
		<td><input id="tpl" type="text" name="tpl" value="{htmlspecialchars($sql->row[tpl])}"/>
		{else}
		<td><input id="tpl" type="text" name="tpl" value=""/>
		{/if}
		<span style="font-size:14px;color: #006699;">(留空则使用默认模板,请使用完整文件名,如show_article.tpl)</span></td>
	</tr>
	<tr>
		<td class="first_td">文章级别：</td>
		<td>
			<select name="level" WIDTH="155" STYLE="width: 155px">
				<option value="0" >级别选择</option>
			{php $article_setting_array = config_get_db_setting('article');$article_setting_array = explode("|",$article_setting_array['article_level']);$count=1;}
			{loop $article_setting_array $tmp_str}
				{if isset($sql->row[level]) && $sql->row[level] == $count}
				<option value="{$count}" selected="selected">{$tmp_str}</option>
				{else}
				<option value="{$count}" >{$tmp_str}</option>
				{/if}
				{php $count++;}
			{/loop}
			</select>
		</td>
	</tr>
	<tr>
		<td class="first_td">浏览次数：</td>
		<td><input type="text" name="scans" value='{zengl article_scans}' /></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="提交" /></td>
	</tr>
</tbody>
</table>
<input type="hidden" name="hidden" value={zengl hidden}>
<input type="hidden" name="articleID" value={zengl articleID}>
<input type="hidden" name="isneed_getsmimg" value="no" id="isneed_getsmimg_input">
</form>
</body>
</html>