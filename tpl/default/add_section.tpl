<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>{zengl title}</title>
</head>
<body>
<h2>{zengl title}</h2>
<form action={zengl action_loc} method="post">
栏目名：<input type="text" name="secname" value='' /> <br/>
栏目静态目录名：<input type="text" name="sec_dirname" value='' />(留空则为栏目名的拼音) <br/>
栏目权重：<input type="text" name="sec_weights" value='' />(留空则为50,权重用于主页显示) <br/>
所属父栏目：
<select name="parentID">
	{zengl options}
</select> 
<input type="submit" value="提交" />
</form>
</body>
</html>