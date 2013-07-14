<?php 
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/config_showset.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/config_showset_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');

$title = '系统配置界面：';
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
		(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	return;
}
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
$tpl->setVar('title', 'echo $title',true);
$tpl->setVar('action', 'echo "\'config_operate.php\'";',true);
$tpl->setVar('dbtype_options', '
		if($db_type == SQLITE)
		{
			echo "<option value=\'0\' selected=\'selected\'> Sqlite 本地文件数据库类型 </option>";
			echo "<option value=\'1\'> MySql 服务型数据库类型 </option>";
		}
		else
		{
			echo "<option value=\'0\'> Sqlite 本地文件数据库类型 </option>";
			echo "<option value=\'1\' selected=\'selected\'> MySql 服务型数据库类型 </option>";
		}
		',true);
$tpl->setVar('mysql_hostname', 'echo htmlspecialchars($db_hostname)',true);
$tpl->setVar('mysql_username', 'echo htmlspecialchars($db_username)',true);
$tpl->setVar('mysql_password', 'echo htmlspecialchars($db_password)',true);
$tpl->setVar('mysql_dbname', 'echo htmlspecialchars($zengl_userset_mysqldb_name)',true);
$tpl->setVar('sqlite_dbpath', 'echo htmlspecialchars($zengl_userset_sqlitedb_name)',true);
$tpl->setVar('tables_prefix', 'echo htmlspecialchars($db_tables_prefix)',true);
$tpl->setVar('db_bakpath', 'echo htmlspecialchars($db_database_bak_path)',true);
$tpl->setVar('db_bak_pernum', 'echo htmlspecialchars($db_bak_pernum)',true);
$tpl->setVar('db_restore_user', 'echo htmlspecialchars($db_restore_user)',true);
$tpl->setVar('db_restore_pass', 'echo htmlspecialchars($db_restore_pass)',true);
$tpl->setVar('cms_rootdir', 'echo htmlspecialchars($zengl_cms_rootdir)',true);
$tpl->setVar('cms_domain', 'echo htmlspecialchars($zengl_cms_full_domain)',true);
$tpl->setVar('init_username', 'echo htmlspecialchars($zengl_cms_init_name)',true);
$tpl->setVar('init_pass', 'echo htmlspecialchars($zengl_cms_init_pass)',true);
$tpl->setVar('cms_update_user', 'echo htmlspecialchars($cms_update_user)',true);
$tpl->setVar('cms_update_pass', 'echo htmlspecialchars($cms_update_pass)',true);
$tpl->setVar('use_html', '
		if($zengl_cms_use_html == "yes")
			echo "checked=\'checked\'";
		else
			echo "";
		',true);
$tpl->setVar('isneed_register', '
		if($zengl_cms_isneed_register == "yes")
			echo "checked=\'checked\'";
		else
			echo "";
		',true);
$tpl->setVar('isneed_login', '
		if($zengl_cms_isneed_login == "yes")
			echo "checked=\'checked\'";
		else
			echo "";
		',true);
$tpl->setVar('article_descript_charnum', 'echo htmlspecialchars($article_descript_charnum)',true);
$tpl->setVar('zengl_cms_comment_shownum', 'echo htmlspecialchars($zengl_cms_comment_shownum)',true);
$tpl->setVar('zengl_index_sec_count', 'echo htmlspecialchars($ZlCfg_IndexSecCount)',true);
$tpl->setVar('zengl_index_article_count', 'echo htmlspecialchars($ZlCfg_IndexArticleCount)',true);
$tpl->setVar('zengl_list_article_count', 'echo htmlspecialchars($ZlCfg_ListArticleCount)',true);
$tpl->template_parse();
$tpl->cache();
include $filecache;
?>