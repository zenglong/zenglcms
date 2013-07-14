<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
{zengl sec_array}
<script type="text/javascript">
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
        			$('#edit_dirpath').text(sec_array[selected]['sec_dirpath']);
				});
			$('#isrecur').change(function(){
					if($(this)[0].checked)
						$(this)[0].value = 'yes';
					else
						$(this)[0].value = 'no';
				});
			$('#is_edit').change(function(){
					$('#editdiv').toggle($(this)[0].checked);
					$('#recurdiv').toggle(!$(this)[0].checked);
					if($(this)[0].checked)
						$('#action')[0].value = {zengl action_edit};
					else
						$('#action')[0].value = {zengl action};
				});
			$('#editdiv').toggle(false);
			for(var i in sec_array)
			{
				$('#sec_weights').append("<div>(id:" + sec_array[i]['sec_ID'] + ") " +
											sec_array[i]['sec_name']+" 权重："+ 
											sec_array[i]['sec_weights'] +"</div>");
			}
		});
</script>
<title>{zengl title}</title>
</head>
<body>
<h2>{zengl title}</h2>
<form action={zengl action_loc} method="post">
要删除或编辑的栏目名：
<select name="delID" id='delID'>
	{zengl options}
</select> <br/>
删除，编辑，移动后所属栏目名：
<select name="afterdelID" id='afterdelID'>
	{zengl options}
</select> &nbsp;&nbsp;
<div id='recurdiv'><input type="checkbox" name="isrecur" id="isrecur" value="no">是否递归删除子栏目</input></div>
<div id='editdiv'>修改后的栏目名(留空则为原栏目名)<input type="text" name="edit_name" id="edit_name" value="" /><br/>
栏目静态目录名(留空则为栏目名的拼音)<input type="text" name="edit_dirname" id="edit_dirname" value="" /><br/>
栏目权重：<input type="text" name="edit_weights" id="edit_weights" value="" /><br/>
栏目调试信息 dirpath: <span id="edit_dirpath"></span>
</div>
<input type="checkbox" name="is_edit" id="is_edit" value="no">是否要修改或移动栏目(不勾选则为删除操作)</input><br/>
<input type="submit" value="提交" />
<input type="hidden" name="action" id="action" value={zengl action} />
</form>
<div id='sec_weights'>
</div>
</body>
</html>