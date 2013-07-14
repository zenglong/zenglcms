<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript">
function sures()
{
	if(confirm('确定提交？'))
	{
		return true;
	}
	else
	{
		return false;
	}
}
$(document).ready(function() {
		$('#sec_type').change(function(){
				var sec_type = $('#sec_type').val();
				if(sec_type != 'linkurl' && sec_type != 'linkurl_newopen')
				{
					$('#linkurl').prop('disabled', true);
				}
				else
				{
					$('#linkurl').prop('disabled', false);
				}
			});
		$('#sec_type').change();
		$('#secname').blur(function(){
			var post_secname = $(this).val();
			if($('#sec_dirname').val() == '')
			{
				$.post("add_del_edit_section.php",{action:'ajaxGetPinyin',value:post_secname},function(result){
					$('#sec_dirname').val(result);
				});
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
	width:300px;
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
<form action={zengl action_loc} method="post" onsubmit="return sures()">
<table>
<tbody>
	<tr>
		<td class="first_td">栏目名：</td>
		<td><input type="text" name="secname" value='' id="secname"/></td>
	</tr>
	<tr>
		<td class="first_td">栏目静态目录名：</td>
		<td><input type="text" name="sec_dirname" value='' id="sec_dirname"/>(留空则为栏目名的拼音)</td>
	</tr>
	<tr>
		<td class="first_td">栏目权重：</td>
		<td><input type="text" name="sec_weights" value='' />(留空则为50,权重用于主页显示)</td>
	</tr>
	<tr>
		<td class="first_td">栏目列表模板：</td>
		<td><input type="text" name="list_tpl" value='' />(留空则使用默认模板,请使用完整文件名,如list_articles.tpl之类的)</td>
	</tr>
	<tr>
		<td class="first_td">栏目内容页模板：</td>
		<td><input type="text" name="article_tpl" value='' />(留空则使用默认模板)</td>
	</tr>
	<tr>
		<td class="first_td">栏目外链地址：</td>
		<td><input type="text" name="linkurl" value='' id="linkurl"/>(外链栏目才允许输入)</td>
	</tr>
	<tr>
		<td class="first_td">栏目关键词：</td>
		<td><input type="text" name="keyword" value='' id="keyword" style="width:350px;"/>(用于SEO)</td>
	</tr>
	<tr>
		<td class="first_td">栏目描述：</td>
		<td><textarea name="description" id="description" rows="5" cols="50"></textarea></td>
	</tr>
	<tr>
		<td class="first_td">栏目类型：</td>
		<td>
			<select name="sec_type" id="sec_type">
			{php $section_setting_array = config_get_db_setting('section'); $section_type_string = str_replace('：',':',$section_setting_array['section_type']); $section_type_array = explode("\n",$section_type_string);}
			{loop $section_type_array $tmp_str}
				{php $tmp_array = explode(':',str_replace(array("\r","\n","\t"),"",$tmp_str));}
				<option value="{$tmp_array[1]}">{$tmp_array[0]}</option>
			{/loop}
			</select>
		</td>
	</tr>
	<tr>
		<td class="first_td">所属父栏目：</td>
		<td><select name="parentID">{zengl options}</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="提交" /></td>
	</tr>
</tbody>
</table>
</form>
</body>
</html>