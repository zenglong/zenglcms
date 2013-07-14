<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>{zengl title}</title>
</head>
<body>
<h2>{zengl title}</h2>
	欢迎来到ZENGLCMS管理首页！<br/>
	{zengl can} SET_CONFIG {zengl _can}
	{zengl check_adminLogin_dir}<br/>
	当前使用的数据库：{zengl current_dbtype}<br/>
	服务器时间：{zengl server_time}<br/>
	服务器解译引擎：{zengl server_engine}<br/>
	PHP版本：{zengl php_version} <br/>
	MYSQL版本：{zengl mysql_version}<br/>
	HTTP版本：{zengl http_version} <br/>
	网站根目录：{zengl web_rootdir}<br/>
	最大执行时间：{zengl max_exec_time}<br/>
	文件上传限制：{zengl upload_max_size}<br/>
	全局变量 register_globals：{zengl global}<br/>
	安全模式 safe_mode：{zengl safe_mode}<br/>
	图形处理 GD Library：{zengl gd_lib}<br/>
	内存占用：{zengl mem_usage}<br/>
	{zengl end_can}
</body>
</html>