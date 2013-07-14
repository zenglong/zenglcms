<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
{zengl sec_array}
<script type="text/javascript">
$(document).ready(function() {
			$('#secID').change(function(){
					var selectedObj = $("#secID option:selected");  
        			//获取当前selected的值  
        			var selected = selectedObj.get(0).value;
        			if(selected <= 0)
        				return;
        			var parentID = sec_array[selected]['sec_parent_ID'];
        			if(parentID > 0)
        				$('#parentSec').text(sec_array[parentID]['sec_name']);
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
<form action={zengl action_loc} method="post">
<table>
<tbody>
	<tr>
		<td class="first_td">栏目：</td>
		<td>
			<select name="secID" id='secID'>
				{zengl options}
			</select>
		</td>
	</tr>
	<tr>
		<td class="first_td">所属父栏目：</td>
		<td><span id='parentSec'></span></td>
	</tr>
	<tr>
		<td class="first_td">输入该栏目在父栏目中要调整的位置：</td>
		<td><input type="text" name="position" value='' /></td>
	</tr>
	<tr>
		<td colspan="2">(如果想要该栏目位于父栏目第一个位置就输入1，以此类推！)</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" value="提交" />
		</td>
	</tr>
</tbody>
</table>
<input type="hidden" name="action" id="action" value='setmenu' />
</form>
</body>
</html>