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
</head>
<body>
<h2>{zengl title}</h2>
	<form action={zengl action_loc} method="post">
		栏目：
		<select name="secID" id='secID'>
			{zengl options}
		</select> <br/>
		<input type="checkbox" name="checkbox[]" id="gen_article" value="gen_article">是否要生成该栏目的文章静态页面</input><br/>
		<input type="checkbox" name="checkbox[]" id="is_recur" value="is_recur">是否递归生成子栏目</input><br/>
		<input type="submit" value="提交" />
		<input type="hidden" name="hidden" id="hidden" value='gensechtml' />
	</form>
</body>
</html>