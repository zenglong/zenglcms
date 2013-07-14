<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/config_showset.css" media="screen">
<script type="text/javascript" src="tpl/default/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="tpl/default/js/ajaxfileupload.js"></script>
<script type="text/javascript">
	var totalTabNum = 4; //总tab数
	var current_tab = 0;
	var show_hide_speed = 300;
	function Tab(num)
	{
		for(i=0;i<totalTabNum;i++)
		{
			if(i == num)
			{
				$("#Tab" + i).attr("class","tab_on");
				$("#Tabs" + i).show(show_hide_speed);
			}
			else
			{
				$("#Tab" + i).attr("class","tab");
				$("#Tabs" + i).hide(show_hide_speed);
			}
		}
		current_tab = num;
		$("#TabAll_btn").show(show_hide_speed);
		$("#TabAllClose_btn").hide(show_hide_speed);
	}

	function ajaxFileUpload() {
		loading(); //动态加载小图标
		$.ajaxFileUpload({
			url: 'list_upload_archive.php?action=upload_watermark',
			secureuri: false,
			fileElementId: 'inputfile_watermark',
			dataType: 'text',
			success: function (data, status) {
				if(data.indexOf('watermark.') == -1) //上传失败
				{
					$('#watermark_loading').html(data).show();
					return;
				}
				$('#watermark_img_name').text(data);
				$('#setting_watermark_imgfilename').val(data);
				//alert($('#setting_watermark_imgfilename').val());
				$('#watermark_loading').html('上传成功!');
				var random = Math.round(Math.random()*10000)+1;
				$('#watermark_img').attr('src','images/'+data+'?random='+random);
			},
			error: function (data, status, e) {
				$('#watermark_loading').html(e);
			}
		})
		return false;
	}

	function loading() {
		$("#watermark_loading").ajaxStart(function () {
			$(this).html('<img src="images/loading.gif"/>').show();
		}).ajaxComplete(function () {
			//$(this).hide();
		});
	}

	$(document).ready(function(){
		function dbtype_change()
		{
			var selectedObj = $("#dbtype option:selected");
			var selected = selectedObj.get(0).value;
			if(selected == 0)
			{
				$('.sqliteset').slideDown(show_hide_speed);
				$('.mysqlset').slideUp(show_hide_speed);
			}
			else
			{
				$('.sqliteset').slideUp(show_hide_speed);
				$('.mysqlset').slideDown(show_hide_speed);
			}
		}
		$('#dbtype').change(function(){
			dbtype_change();
		});
		$("#TabAll_btn").click(function(){
			for(i=0;i<totalTabNum;i++)
			{
				$("#Tabs" + i).show(show_hide_speed);
			}
			$(this).hide(show_hide_speed);
			$("#TabAllClose_btn").show(show_hide_speed);
		});
		$("#TabAllClose_btn").click(function(){
			Tab(current_tab);
		});
		dbtype_change();
		Tab(current_tab);
		$('#watermark_upload_btn').click(function(){
			ajaxFileUpload();
		});
		$('#watemark_type_select').change(function(){
			var selectedObj = $("#watemark_type_select option:selected");
			var selected = selectedObj.get(0).value;
			if(selected == 0)
			{
				$('.mywatermark_text_setting').slideDown(show_hide_speed);
				$('.mywatermark_img_setting').slideUp(show_hide_speed);
			}
			else
			{
				$('.mywatermark_text_setting').slideUp(show_hide_speed);
				$('.mywatermark_img_setting').slideDown(show_hide_speed);
			}
		});
		$('#watemark_type_select').change();
	});
</script>
<title>{zengl title}</title>
</head>
<body>
<div id='main'>
<div class="menu">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
	<td valign="bottom">
		<table cellpadding="0" cellspacing="0">
		<tbody>
		<tr>
			<td width="10">&nbsp;</td>
			<td id="Tab0" class="tab_on"><a href="javascript:Tab(0);">数据库相关配置</a></td>
			<td class="tab_nav">&nbsp;</td>
			<td id="Tab1" class="tab"><a href="javascript:Tab(1);">网站重要配置</a></td>
			<td class="tab_nav">&nbsp;</td>
			<td id="Tab2" class="tab"><a href="javascript:Tab(2);">网站一般配置</a></td>
			<td class="tab_nav">&nbsp;</td>
			<td id="Tab3" class="tab"><a href="javascript:Tab(3);">图片水印相关配置</a></td>
			<td class="tab_nav">&nbsp;</td>
		</tr>
		</tbody>
		</table>
	</td>
	<td width="45">
		<div>
			<img src="{zengl theme}/images/config_showset_css_img/spacer.gif" width="40" height="24" title="刷新" onclick="window.location.reload();" style="cursor:pointer;" alt="">
			<!--<img src="{zengl theme}/images/config_showset_css_img/spacer.gif" width="20" height="24" title="后退" onclick="history.back(-1);" style="cursor:pointer;" alt="">
			<img src="{zengl theme}/images/config_showset_css_img/spacer.gif" width="20" height="24" title="前进" onclick="history.go(1);" style="cursor:pointer;" alt="">-->
		</div>
	</td>
</tr>
</tbody>
</table>
</div>

<form action={zengl action} method="post">
	<div id="Tabs0">
		<div class="tt">数据库相关配置</div>
			<table cellpadding="2" cellspacing="1" class="tb_wrap">
				<tbody>
					<tr>
						<td class="tl">选择CMS要使用的数据库类型</td>
						<td class="t2_colspan2" colspan="2"><select name='dbtype' id='dbtype' style="width:180px;">{zengl dbtype_options}</select></td>
					</tr>
					<tr>
						<td colspan="3">
							<div class='mysqlset'>
							<table class="tb">
								<tbody>
									<tr>
										<td class="tl">mysql数据库的主机名(或IP)</td>
										<td class="t2_colspan2" colspan="2"><input type='text' name='mysql_hostname' value='{zengl mysql_hostname}'></td>
									</tr>
									<tr>
										<td class="tl">mysql数据库的用户名</td>
										<td class="t2_colspan2" colspan="2"><input type='text' name='mysql_username' value='{zengl mysql_username}'></td>
									</tr>
									<tr>
										<td class="tl">mysql数据库的密码</td>
										<td class="t2_colspan2" colspan="2"><input type='text' name='mysql_password' value='{zengl mysql_password}'></td>
									</tr>
									<tr>
										<td class="tl">mysql数据库名</td>
										<td class="t2"><input type='text' name='mysql_dbname' value='{zengl mysql_dbname}'></td>
										<td class="t3">(数据库创建时的名字)</td>
									</tr>
								</tbody>
							</table>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<div class='sqliteset'>
							<table class="tb">
								<tbody>
									<tr>
										<td class="tl">设置sqlite数据库文件的路径</td>
										<td class="t2"><input type='text' name='sqlite_dbpath' value='{zengl sqlite_dbpath}'></td>
										<td class="t3">(如果是相对路径，请相对于config.php文件来填)</td>
									</tr>
								</tbody>
							</table>
							</div>
						</td>
					</tr>
					<tr>
						<td class="tl">数据库表前缀</td>
						<td class="t2"><input type='text' name='tables_prefix' value='{zengl tables_prefix}'></td>
						<td class="t3">(留空则为默认的zengl_)</td>
					</tr>
					<tr>
						<td class="tl">设置数据库的备份路径</td>
						<td class="t2"><input type='text' name='db_bakpath' value='{zengl db_bakpath}'></td>
						<td class="t3">(末尾不加斜杠)</td>
					</tr>
					<tr>
						<td class="tl">每个备份存的记录条数</td>
						<td class="t2_colspan2" colspan="2"><input type='text' name='db_bak_pernum' value='{zengl db_bak_pernum}'></td>
					</tr>
					<tr>
						<td class="tl">数据库备份恢复时要用到的用户名</td>
						<td class="t2_colspan2" colspan="2"><input type='text' name='db_restore_user' value='{zengl db_restore_user}'></td>
					</tr>
					<tr>
						<td class="tl">数据库备份恢复时要用到的密码</td>
						<td class="t2_colspan2" colspan="2"><input type='text' name='db_restore_pass' value='{zengl db_restore_pass}'></td>
					</tr>
				</tbody>
			</table>
	</div>
	<div id="Tabs1">
		<div class="tt">网站重要配置</div>
		<table cellpadding="2" cellspacing="1" class="tb_wrap">
			<tbody>
				<tr>
					<td class="tl">设置网站的根目录</td>
					<td class="t2"><input type='text' name='cms_rootdir' value='{zengl cms_rootdir}'></td>
					<td class="t3">(默认为'/',头和末尾都要加'/'如/zenglcms/)</td>
				</tr>
				<tr>
					<td class="tl">网站完整的域名</td>
					<td class="t2"><input type='text' name='cms_domain' value='{zengl cms_domain}'></td>
					<td class="t3">(如www.baidu.com)</td>
				</tr>
				<tr>
					<td class="tl">网站后台初始用户名</td>
					<td class="t2_colspan2" colspan="2"><input type='text' name='cms_init_username' value='{zengl init_username}' readonly="readonly"></td>
				</tr>
				<tr>
					<td class="tl">网站后台初始密码</td>
					<td class="t2_colspan2" colspan="2"><input type='text' name='cms_init_pass' value='{zengl init_pass}' readonly="readonly"></td>
				</tr>
				<tr>
					<td class="tl">网站更新时要用到的用户名</td>
					<td class="t2_colspan2" colspan="2"><input type='text' name='cms_update_user' value='{zengl cms_update_user}'></td>
				</tr>
				<tr>
					<td class="tl">网站更新时要用到的密码</td>
					<td class="t2_colspan2" colspan="2"><input type='text' name='cms_update_pass' value='{zengl cms_update_pass}'></td>
				</tr>
				<tr>
					<td class="tl">CMS网站是否使用静态页面</td>
					<td class="t2_colspan2" colspan="2"><input type="checkbox" name="use_html" value='yes' id="use_html" {zengl use_html}></td>
				</tr>
				<tr>
					<td class="tl">CMS网站是否开启注册</td>
					<td class="t2_colspan2" colspan="2"><input type="checkbox" name="isneed_register" value='yes' id="isneed_register" {zengl isneed_register}></td>
				</tr>
				<tr>
					<td class="tl">CMS网站是否开启前台登录</td>
					<td class="t2_colspan2" colspan="2"><input type="checkbox" name="isneed_login" value='yes' id="isneed_login" {zengl isneed_login}></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="Tabs2">
		<div class="tt">网站一般配置</div>
		<table cellpadding="2" cellspacing="1" class="tb_wrap">
			<tbody>
				<tr>
					<td class="tl">文章描述信息的字符数</td>
					<td class="t2_colspan2" colspan="2"><input type='text' name='article_descript_charnum' value='{zengl article_descript_charnum}'></td>
				</tr>
				<tr>
					<td class="tl">文章评论的单页显示数目</td>
					<td class="t2_colspan2" colspan="2"><input type='text' name='zengl_cms_comment_shownum' value='{zengl zengl_cms_comment_shownum}'></td>
				</tr>
				<tr>
					<td class="tl">首页显示多少个栏目的文章</td>
					<td class="t2_colspan2" colspan="2"><input type='text' name='zengl_index_sec_count' value='{zengl zengl_index_sec_count}'></td>
				</tr>
				<tr>
					<td class="tl">首页每个栏目列表显示多少文章</td>
					<td class="t2_colspan2" colspan="2"><input type='text' name='zengl_index_article_count' value='{zengl zengl_index_article_count}'></td>
				</tr>
				<tr>
					<td class="tl">文章列表页面每个分页显示多少文章</td>
					<td class="t2_colspan2" colspan="2"><input type='text' name='zengl_list_article_count' value='{zengl zengl_list_article_count}'></td>
				</tr>
				<tr>
					<td class="tl">网站当前主题风格</td>
					<td class="t2_colspan2" colspan="2"><input type='text' name='zengl_theme' value='{htmlspecialchars($zengl_cur_theme)}'></td>
				</tr>
				<tr>
					<td class="tl">原来的主题风格</td>
					<td class="t2"><input type='text' name='zengl_old_theme' value='{htmlspecialchars($zengl_old_theme)}'></td>
					<td class="t3">(如果当前主题风格中没有class.php处理程式,就会自动在原来的主题风格中查找)</td>
				</tr>
				<tr>
					{php $section_setting_array = config_get_db_setting('section');}
					<td class="tl">栏目类型</td>
					<td class="t2_colspan2" colspan="2"><textarea name='setting[section][section_type]' style="width:500px;height:200px;">{$section_setting_array[section_type]}</textarea></td>
				</tr>
				<tr>
					{php $article_setting_array = config_get_db_setting('article');}
					<td class="tl">文章级别</td>
					<td class="t2_colspan2" colspan="2"><input type='text' name='setting[article][article_level]'  value='{$article_setting_array[article_level]}' style="width:550px;"></td>
				</tr>
			</tbody>
		</table>
	</div>
	{php $watermark_setting_array = config_get_db_setting('watermark');}
	<div id="Tabs3">
		<div class="tt">图片水印相关配置</div>
		<table cellpadding="2" cellspacing="1" class="tb_wrap">
			<tbody>
				<tr>
					<td class="tl">是否开启水印</td>
					<td class="t2_colspan2" colspan="2">
						<input type="radio" value="on" name="setting[watermark][watermark_switch]" id="watermark_switch_on" {if $watermark_setting_array[watermark_switch]=='on'}checked="checked"{/if}>
						<label for="watermark_switch_on">开启</label>&nbsp;
						<input type="radio" value="off" name="setting[watermark][watermark_switch]" id="watermark_switch_off" {if $watermark_setting_array[watermark_switch]=='off'}checked="checked"{/if}>
						<label for="watermark_switch_off">关闭</label>&nbsp;
					</td>
				</tr>
				<tr>
					<td class="tl">选择CMS要使用的水印类型</td>
					<td class="t2_colspan2" colspan="2">
						<select name='setting[watermark][watermark_type]' id='watemark_type_select' style="width:100px;">
							<option value="1" {if $watermark_setting_array[watermark_type]=='1'}selected="selected"{/if}> 图片水印 </option>
							<option value="0" {if $watermark_setting_array[watermark_type]=='0'}selected="selected"{/if}> 文字水印 </option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="tl">水印位置</td>
					<td class="t2_colspan2" colspan="2">
						<table width="150" cellspacing="1" cellpadding="5" bgcolor="#DDDDDD">
							<tbody>
								<tr align="center">
									<td> 
										<input type="radio" value="1" name="setting[watermark][watermark_pos]" {if $watermark_setting_array[watermark_pos]=='1'}checked="checked"{/if}> 
									</td>
									<td> 
										<input type="radio" value="2" name="setting[watermark][watermark_pos]" {if $watermark_setting_array[watermark_pos]=='2'}checked="checked"{/if}>
									</td>
									<td> 
										<input type="radio" value="3" name="setting[watermark][watermark_pos]" {if $watermark_setting_array[watermark_pos]=='3'}checked="checked"{/if}> 
									</td>
								</tr>
								<tr bgcolor="#F1F2F3" align="center">
									<td> 
										<input type="radio" value="4" name="setting[watermark][watermark_pos]" {if $watermark_setting_array[watermark_pos]=='4'}checked="checked"{/if}> 
									</td>
									<td> 
										<input type="radio" value="5" name="setting[watermark][watermark_pos]" {if $watermark_setting_array[watermark_pos]=='5'}checked="checked"{/if}>
									</td>
									<td> 
										<input type="radio" value="6" name="setting[watermark][watermark_pos]" {if $watermark_setting_array[watermark_pos]=='6'}checked="checked"{/if}> 
									</td>
								</tr>
								<tr bgcolor="#F1F2F3" align="center">
									<td> 
										<input type="radio" value="7" name="setting[watermark][watermark_pos]" {if $watermark_setting_array[watermark_pos]=='7'}checked="checked"{/if}> 
									</td>
									<td> 
										<input type="radio" value="8" name="setting[watermark][watermark_pos]" {if $watermark_setting_array[watermark_pos]=='8'}checked="checked"{/if}>
									</td>
									<td> 
										<input type="radio" value="9" name="setting[watermark][watermark_pos]" {if $watermark_setting_array[watermark_pos]=='9'}checked="checked"{/if}> 
									</td>
								</tr>
								<tr bgcolor="#F1F2F3" align="center">
									<td colspan="3">
										随机 <input type="radio" value="0" name="setting[watermark][watermark_pos]" {if $watermark_setting_array[watermark_pos]=='0'}checked="checked"{/if}>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div class='mywatermark_img_setting'>
							<table class="tb">
								<tbody>
									<tr>
										<td class="tl">图片水印文件</td>
										<td class="t2_colspan2" colspan="2">
											<input type="file" id="inputfile_watermark" name="upload" value="" style="width:230px"/>
											<input type="button" value="上传" class="btn" id="watermark_upload_btn"/>
											<span id="watermark_img_name">{$watermark_setting_array[watermark_imgfilename]}</span>
											<img id="watermark_img" src="images/{$watermark_setting_array[watermark_imgfilename]}"/>
											<span id="watermark_loading"></span>
											<input type="hidden" name="setting[watermark][watermark_imgfilename]" value="{$watermark_setting_array[watermark_imgfilename]}" id="setting_watermark_imgfilename">
										</td>
									</tr>
									<tr>
										<td class="tl">图片水印透明度</td>
										<td class="t2"><input type="text" name="setting[watermark][watermark_transparent]" value="{$watermark_setting_array[watermark_transparent]}"></td>
										<td class="t3">(请填写1到100之间的值)</td>
									</tr>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div class='mywatermark_text_setting'>
							<table class="tb">
								<tbody>
									<tr>
										<td class="tl">水印文字</td>
										<td class="t2"><input type="text" name="setting[watermark][watermark_text]" value="{$watermark_setting_array[watermark_text]}"></td>
										<td class="t3">(留空则为config.php中的域名)</td>
									</tr>
									<tr>
										<td class="tl">水印字体文件名</td>
										<td class="t2"><input type="text" name="setting[watermark][watermark_fontfilename]" value="{$watermark_setting_array[watermark_fontfilename]}"></td>
										<td class="t3">
										{if file_exists('file_resource/font_resource/'.$watermark_setting_array[watermark_fontfilename])}
											已检测到该文件(可以正常使用文字水印)
										{else}
											未检测到该文件(请先用ftp等上传到file_resource/font_resource目录中,再开启文字水印)
										{/if}
										</td>
									</tr>
									<tr>
										<td class="tl">水印文字颜色</td>
										<td class="t2"><input type="text" name="setting[watermark][watermark_fontcolor]" value="{$watermark_setting_array[watermark_fontcolor]}"></td>
										<td class="t3">(请填写#ffffff这类的格式)</td>
									</tr>
									<tr>
										<td class="tl">水印文字大小</td>
										<td class="t2_colspan2" colspan="2"><input type="text" name="setting[watermark][watermark_fontsize]" value="{$watermark_setting_array[watermark_fontsize]}"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<input type='hidden' name='action' value='setconfig'>
	<div id="submit_div"><input type="submit" value="提交" class="btn"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="展开" class="btn" id="TabAll_btn"/><input type="button" value="合并" class="btn" id="TabAllClose_btn"/></div>
</form>
</div>
</body>
</html>