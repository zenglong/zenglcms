<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
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
</style>
</head>
<body>
<table>
<tbody>
<tr><td colspan="2">欢迎来到ZENGLCMS管理首页！</td></tr>
	{zengl can} SET_CONFIG {zengl _can}
	<tr><td colspan="2">{zengl check_adminLogin_dir}</td></tr>
	<tr>
		<td class="first_td">当前使用的数据库：</td>
		<td>{zengl current_dbtype}</td>
	</tr>
	<tr>
		<td class="first_td">服务器时间：</td>
		<td>{zengl server_time}</td>
	</tr>
	<tr>
		<td class="first_td">服务器解译引擎：</td>
		<td>{zengl server_engine}</td>
	</tr>
	<tr>
		<td class="first_td">PHP版本：</td>
		<td>{zengl php_version}</td>
	</tr>
	<tr>	
		<td class="first_td">MYSQL版本：</td>
		<td>{zengl mysql_version}</td>
	</tr>
	<tr>	
		<td class="first_td">HTTP版本：</td>
		<td>{zengl http_version}</td>
	</tr>
	<tr>	
		<td class="first_td">网站根目录：</td>
		<td>{zengl web_rootdir}</td>
	</tr>
	<tr>	
		<td class="first_td">最大执行时间：</td>
		<td>{zengl max_exec_time}</td>
	</tr>
	<tr>	
		<td class="first_td">文件上传限制：</td>
		<td>{zengl upload_max_size}</td>
	</tr>
	<tr>	
		<td class="first_td">全局变量 register_globals：</td>
		<td>{zengl global}</td>
	</tr>
	<tr>	
		<td class="first_td">安全模式 safe_mode：</td>
		<td>{zengl safe_mode}</td>
	</tr>
	<tr>	
		<td class="first_td">图形处理 GD Library：</td>
		<td>{zengl gd_lib}</td>
	</tr>
	<tr>	
		<td class="first_td">内存占用：</td>
		<td>{zengl mem_usage}</td>
	</tr>
	{zengl end_can}
</tbody>
</table>
</body>
</html>