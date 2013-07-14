<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.tablesorter.min.js"></script>
{zengl sec_array}
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
			$('#delID').change(function(){
					var selectedObj = $("#delID option:selected");  
        			//获取当前selected的值  
        			var selected = selectedObj.get(0).value;
        			var parentID = sec_array[selected]['sec_parent_ID'];
        			$('#afterdelID').val(parentID);
        			$('#edit_name').val(sec_array[selected]['sec_name']);
        			$('#edit_dirname').val(sec_array[selected]['sec_dirname']);
        			$('#edit_weights').val(sec_array[selected]['sec_weights']);
					$('#edit_list_tpl').val(sec_array[selected]['tpl']);
					$('#edit_article_tpl').val(sec_array[selected]['article_tpl']);
					$('#edit_linkurl').val(sec_array[selected]['linkurl']);
					$('#sec_type').val(sec_array[selected]['type']);
					$('#sec_type').change();
					$('#edit_keyword').val(sec_array[selected]['keyword']);
					$('#edit_description').text(sec_array[selected]['sec_description']);
        			$('#edit_dirpath').text(sec_array[selected]['sec_dirpath']);
				});
			$('#sec_type').change(function(){
					var sec_type = $('#sec_type').val();
					if(sec_type != 'linkurl' && sec_type != 'linkurl_newopen')
					{
						$('#edit_linkurl').prop('disabled', true);
					}
					else
					{
						$('#edit_linkurl').prop('disabled', false);
					}
				});
			$('#sec_type').change();
			$('#isrecur').change(function(){
					if($(this)[0].checked)
						$(this)[0].value = 'yes';
					else
						$(this)[0].value = 'no';
				});
			$('#is_edit').change(function(){
					$('.editdiv').toggle($(this)[0].checked);
					$('#recurdiv').toggle(!$(this)[0].checked);
					if($(this)[0].checked)
						$('#action')[0].value = {zengl action_edit};
					else
						$('#action')[0].value = {zengl action};
				});
			$('.editdiv').toggle(false);
			/*for(var i in sec_array)
			{
				$('#sec_weights').append("<div>(id:" + sec_array[i]['sec_ID'] + ") " +
											sec_array[i]['sec_name']+" 权重："+ 
											sec_array[i]['sec_weights'] +"</div>");
			}*/
			$('.sec_InfoTable').tablesorter(); 
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
.sec_InfoTable thead tr .header {
	background-image: url({zengl theme}/images/tableSort/bg.gif);
	background-repeat: no-repeat;
	background-position: center right;
	cursor: pointer;
}

.sec_InfoTable thead tr .headerSortUp {
	background-image: url({zengl theme}/images/tableSort/asc.gif);
}

.sec_InfoTable thead tr .headerSortDown {
	background-image: url({zengl theme}/images/tableSort/desc.gif);
}

.sec_InfoTable thead tr .headerSortUp, .sec_InfoTable thead tr .headerSortDown{
	background-color: #8DBDD8;
}
</style>
</head>
<body>
<div id="header_title">{zengl title}</div>
<form action={zengl action_loc} method="post" onsubmit="return sures()">
<table>
<tbody>
	<tr>
		<td class="first_td">要删除或编辑的栏目名：</td>
		<td>
			<select name="delID" id='delID'>
				{zengl options}
			</select>
		</td>
	</tr>
	<tr>
		<td class="first_td">删除，编辑，移动后所属栏目名：</td>
		<td>
			<select name="afterdelID" id='afterdelID'>
				{zengl options}
			</select>
		</td>
	</tr>
	<tr id='recurdiv'>
		<td class="first_td"><input type="checkbox" name="isrecur" id="isrecur" value="no"></input>
		<td>是否递归删除子栏目</td>
	</tr>
	<tr class='editdiv'>
		<td class="first_td">修改后的栏目名(留空则为原栏目名)</td>
		<td><input type="text" name="edit_name" id="edit_name" value="" /></td>
	</tr>
	<tr class='editdiv'>
		<td class="first_td">栏目静态目录名(留空则为栏目名的拼音)</td>
		<td><input type="text" name="edit_dirname" id="edit_dirname" value="" /></td>
	</tr>
	<tr class='editdiv'>
		<td class="first_td">栏目权重：</td>
		<td><input type="text" name="edit_weights" id="edit_weights" value="" /></td>
	</tr>
	<tr class='editdiv'>
		<td class="first_td">栏目列表模板：</td>
		<td><input type="text" name="edit_list_tpl" id="edit_list_tpl" value="" />(留空则使用默认模板,请使用完整文件名,如list_articles.tpl之类的)</td>
	</tr>
	<tr class='editdiv'>
		<td class="first_td">栏目内容页模板：</td>
		<td><input type="text" name="edit_article_tpl" id="edit_article_tpl" value="" />(留空则使用默认模板)</td>
	</tr>
	<tr class='editdiv'>
		<td class="first_td">栏目外链地址：</td>
		<td><input type="text" name="edit_linkurl" id="edit_linkurl" value="" />(外链栏目才允许输入)</td>
	</tr>
	<tr class='editdiv'>
		<td class="first_td">栏目关键词：</td>
		<td><input type="text" name="edit_keyword" id="edit_keyword" value="" style="width:350px;"/>(用于SEO)</td>
	</tr>
	<tr class='editdiv'>
		<td class="first_td">栏目描述：</td>
		<td><textarea name="edit_description" id="edit_description" rows="5" cols="50"></textarea></td>
	</tr>
	<tr class='editdiv'>
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
	<tr class='editdiv'>
		<td class="first_td">栏目调试信息 dirpath: </td>
		<td><span id="edit_dirpath"></span></td>
	</tr>
	<tr>
		<td class="first_td"><input type="checkbox" name="is_edit" id="is_edit" value="no"></input></td>
		<td>是否要修改或移动栏目(不勾选则为删除操作)</td>
	</tr>
	<tr>
		<td colspan="2" align="right"><input type="submit" value="提交" /></td>
	</tr>
</tbody>
</table>
<input type="hidden" name="action" id="action" value={zengl action} />
</form>
<div id='sec_weights'>
<table class = "sec_InfoTable">
<thead>
<tr align='center'>
	<th width="9%">栏目ID</th>
	<th width="10%">栏目名称</th>
	<th width="10%">栏目目录名</th>
	<th width="10%">栏目权重</th>
	<th width="10%">列表模板</th>
	<th width="10%">内容模板</th>
	<th width="10%">类型</th>
	<th width="31%">外链地址</th>
</tr>
</thead>
<tbody>
{loop $this->all $k $v}
	<tr>
		<td>{$v[sec_ID]}</td>
		<td>{$v[sec_name]}</td>
		<td>{$v[sec_dirname]}</td>
		<td>{$v[sec_weights]}</td>
		<td>{$v[tpl]}</td>
		<td>{$v[article_tpl]}</td>
		<td>{$v[type]}</td>
		<td align='center'>{$v[linkurl]}</td>
	</tr>
{/loop}
</tbody>
</table>
</div>
</body>
</html>