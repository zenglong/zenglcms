<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<style type="text/css" media=screen>
<!--
	#main{
		width:700px;
		margin:0 auto;
	}
	#content{
		width:698px;
		border: 1px solid green;
	}
-->
</style>
<title>{zengl title}</title>
</head>
<body>
<div id='main'>
<h2>{zengl title}</h2>
	<div id='content'>
		系统环境检测：<br/>
		PHP版本：{zengl php_version} <br/>
		MYSQL版本：{zengl mysql_version}<br/>
		图形处理 GD Library的支持：{zengl gd_lib}<br/>
		sqlite的支持：{zengl sqlite}<br/>
		网站CMS所在目录的可写性：{zengl writable_cms_dir}<br/>
		common_fun目录可写性：{zengl writable_common_fun}<br/>
		file_cache目录可写性：{zengl writable_file_cache}<br/>
		tpl目录可写性：{zengl writable_tpl_dir}<br/>
		upload目录可写性：{zengl writable_upload_dir}<br/>
		UpdateCms目录可写性：{zengl writable_UpdateCms_dir}<br/>
		config.php配置文件可写性：{zengl writable_config_php}<br/>
	</div>
	<p align='center'><a href='{zengl next_loc}'>下一步</a></p>
</div>
</body>
</html>