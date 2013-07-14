<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript">
$(document).ready(function() {
			$('#secID').change(function(){
					var selectedObj = $("#secID option:selected");  
        			//获取当前selected的值  
        			var selected = selectedObj.get(0).value;
        			if(selected == 0)
        			{
        				$("#gen_article").attr("checked",true);
        				$("#is_recur").attr("checked",true);
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
	text-align:center;
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
				<td class="first_td">栏目列表</td>
				<td><select name="secID" id='secID'>{zengl options}</select></td>
			</tr>
			<tr>
				<td class="first_td"><input type="checkbox" name="checkbox[]" id="gen_article" value="gen_article"></input></td>
				<td>是否要生成该栏目的文章静态页面</td>
			</tr>
			<tr>
				<td class="first_td"><input type="checkbox" name="checkbox[]" id="is_recur" value="is_recur"></input></td>
				<td>是否递归生成子栏目</td>
			</tr>
			<tr>
				<td colspan="2" align="right"><input type="submit" value="提交" /></td>
			</tr>
		</tbody>
		</table>
		<input type="hidden" name="hidden" id="hidden" value='gensechtml' />
	</form>
</body>
</html>