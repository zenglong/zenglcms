<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript" src="tpl/default/js/jquery-1.7.1.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#dbtype').change(function(){
			var selectedObj = $("#dbtype option:selected");
			var selected = selectedObj.get(0).value;
			if(selected == 0)
			{
				$('.sqliteset').show();
				$('.mysqlset').hide();
			}
			else
			{
				$('.sqliteset').hide();
				$('.mysqlset').show();
			}
		});
		$('.mysqlset').show();
		$('.sqliteset').hide();
	});
</script>
<style type="text/css" media=screen>
<!--
	#main{
		width:700px;
		margin:0 auto;
	}
	#content{
		width:698px;
		border: 1px solid green;
		padding: 10px 10px 10px 10px;
	}
-->
</style>
<title><?php echo $title ?></title>
</head>
<body>
<div id='main'>
<h2><?php echo $title ?></h2>
	<div id='content'>
		系统安装的设置界面：
		<form action=<?php echo 'install.php?action=setconfig'; ?> method="post" target='down'>
			选择CMS要使用的数据库类型：
			<select name='dbtype' id='dbtype'>
				<option value='0'>sqlite</option>
				<option value='1' selected="selected">mysql</option>
			</select> <br/> <br/>
			<div class='mysqlset'>
				mysql数据库的主机名(或IP)：<input type='text' name='mysql_hostname' value=''><br/><br/>
				mysql数据库的用户名：<input type='text' name='mysql_username' value=''><br/><br/>
				mysql数据库的密码：<input type='text' name='mysql_password' value=''><br/><br/>
				mysql数据库名(数据库创建时的名字)：<input type='text' name='mysql_dbname' value=''><br/><br/>
			</div>
			<div class='sqliteset'>
				在下面设置sqlite数据库文件的路径(如果是相对路径，请相对于config.php文件来填)：<br/><br/>
				<input type='text' name='sqlite_dbpath' value=''><br/><br/>
			</div>
			数据库表前缀(留空则为默认的zengl_)：<input type='text' name='tables_prefix' value=''><br/><br/>
			设置数据库的备份路径(末尾不加斜杠)：<input type='text' name='db_bakpath' value=''><br/><br/>
			设置网站的根目录(默认为'/',头和末尾都要加'/'如/zenglcms/)：<input type='text' name='cms_rootdir' value='/'><br/><br/>
			网站完整的域名(如www.baidu.com):<input type='text' name='cms_domain' value=''><br/><br/>
			网站后台初始用户名：<input type='text' name='cms_init_username' value=''><br/><br/>
			网站后台初始密码：<input type='text' name='cms_init_pass' value=''><br/><br/>
			(至于数据库备份恢复时要用到的用户名和密码，以及系统升级时要用到的用户名和密码，请自行在后台配置中设置。)<br/><br/>
			<input type="submit" value="提交" /><br/><br/>	
		</form>
	</div>
</div>
<div style="width:750px;margin:20px auto">
安装进度：<br/>
<iframe name= "down" id="framedown" scrolling=auto frameborder=1 border=1 width='830' height="630"> 
</iframe>
</div>
</body>
</html>