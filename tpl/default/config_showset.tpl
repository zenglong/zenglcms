<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript" src="tpl/default/js/jquery-1.7.1.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		function dbtype_change()
		{
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
		}
		$('#dbtype').change(function(){
			dbtype_change();
		});
		dbtype_change();
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
<title>{zengl title}</title>
</head>
<body>
<div id='main'>
<h2>{zengl title}</h2>
	<div id='content'>
		系统安装的设置界面：
		<form action={zengl action} method="post">
			<fieldset>
				<legend><font color='red'>数据库相关配置:</font></legend>
				选择CMS要使用的数据库类型：
				<select name='dbtype' id='dbtype'>
					{zengl dbtype_options}
				</select> <br/> <br/>
				<div class='mysqlset'>
					mysql数据库的主机名(或IP)：<input type='text' name='mysql_hostname' value='{zengl mysql_hostname}'><br/><br/>
					mysql数据库的用户名：<input type='text' name='mysql_username' value='{zengl mysql_username}'><br/><br/>
					mysql数据库的密码：<input type='text' name='mysql_password' value='{zengl mysql_password}'><br/><br/>
					mysql数据库名(数据库创建时的名字)：<input type='text' name='mysql_dbname' value='{zengl mysql_dbname}'><br/><br/>
				</div>
				<div class='sqliteset'>
					在下面设置sqlite数据库文件的路径(如果是相对路径，请相对于config.php文件来填)：<br/><br/>
					<input type='text' name='sqlite_dbpath' value='{zengl sqlite_dbpath}'><br/><br/>
				</div>
				数据库表前缀(留空则为默认的zengl_)：<input type='text' name='tables_prefix' value='{zengl tables_prefix}'><br/><br/>
				设置数据库的备份路径(末尾不加斜杠)：<input type='text' name='db_bakpath' value='{zengl db_bakpath}'><br/><br/>
				每个备份存的记录条数：<input type='text' name='db_bak_pernum' value='{zengl db_bak_pernum}'><br/><br/>
				数据库备份恢复时要用到的用户名：<input type='text' name='db_restore_user' value='{zengl db_restore_user}'><br/><br/>
				数据库备份恢复时要用到的密码：<input type='text' name='db_restore_pass' value='{zengl db_restore_pass}'><br/><br/>
			</fieldset><br/> <br/>
			<fieldset>
				<legend><font color='red'>网站重要配置:</font></legend>
				设置网站的根目录(默认为'/',头和末尾都要加'/'如/zenglcms/)：<input type='text' name='cms_rootdir' value='{zengl cms_rootdir}'><br/><br/>
				网站完整的域名(如www.baidu.com):<input type='text' name='cms_domain' value='{zengl cms_domain}'><br/><br/>
				网站后台初始用户名：<input type='text' name='cms_init_username' value='{zengl init_username}' readonly="readonly"><br/><br/>
				网站后台初始密码：<input type='text' name='cms_init_pass' value='{zengl init_pass}' readonly="readonly"><br/><br/>
				网站更新时要用到的用户名：<input type='text' name='cms_update_user' value='{zengl cms_update_user}'><br/><br/>
				网站更新时要用到的密码：<input type='text' name='cms_update_pass' value='{zengl cms_update_pass}'><br/><br/>
				CMS网站是否使用静态页面：<input type="checkbox" name="use_html" value='yes' id="use_html" {zengl use_html}><br/><br/>
				CMS网站是否开启注册：<input type="checkbox" name="isneed_register" value='yes' id="isneed_register" {zengl isneed_register}><br/><br/>
				CMS网站是否开启前台登录：<input type="checkbox" name="isneed_login" value='yes' id="isneed_login" {zengl isneed_login}><br/><br/>
			</fieldset><br/> <br/>
			<fieldset>
				<legend><font color='red'>网站一般配置:</font></legend>
				文章描述信息的字符数：<input type='text' name='article_descript_charnum' value='{zengl article_descript_charnum}'><br/><br/>
				文章评论的单页显示数目：<input type='text' name='zengl_cms_comment_shownum' value='{zengl zengl_cms_comment_shownum}'><br/><br/>
				首页显示多少个栏目的文章：<input type='text' name='zengl_index_sec_count' value='{zengl zengl_index_sec_count}'><br/><br/>
				首页每个栏目列表显示多少文章：<input type='text' name='zengl_index_article_count' value='{zengl zengl_index_article_count}'><br/><br/>
				文章列表页面每个分页显示多少文章：<input type='text' name='zengl_list_article_count' value='{zengl zengl_list_article_count}'><br/><br/>
			</fieldset><br/> <br/>
			<input type='hidden' name='action' value='setconfig'>
			<input type="submit" value="提交" /><br/><br/>	
		</form>
	</div>
</div>
</body>
</html>