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
</head>
<body>
<h2>{zengl title}</h2>
	<form action={zengl action_loc} method="post">
		栏目：
		<select name="secID" id='secID'>
			{zengl options}
		</select> <br/>
		所属父栏目：<span id='parentSec'></span> <br/>
		输入该栏目在父栏目中要调整的位置：<input type="text" name="position" value='' /> <br/>
		(如果想要该栏目位于父栏目第一个位置就输入1，以此类推！)
		<input type="submit" value="提交" />
		<input type="hidden" name="action" id="action" value='setmenu' />
	</form>
</body>
</html>