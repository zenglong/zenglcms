<?php 
/*
	Copyright 2012 zenglong (made in china)

	For more information, please see www.zengl.com
	
	This file is part of zenglcms.
	
	zenglcms is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	zenglcms is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with zenglcms,the copy is in the file licence.txt.  If not,
	see <http://www.gnu.org/licenses/>.
*/

include 'common_fun/file_func.php';
i_need_func('permis,err,conf,cache,tpl,sql',__FILE__,true); //最后的参数为true时，file_cache中会生成调试版本的缓存。
//i_need_func('permis,err,conf,cache,tpl',__FILE__);
include $my_need_files;

$permis = new permis(new session(true), new sql('utf8'));

if(!$permis->check_perm(SET_CONFIG))
	new error('系统配置失败！','用户权限无法进行系统配置！',true,true);

import_request_variables("gpc","rvar_");

if($rvar_action == 'show')
{
	if(file_exists($tmp_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/config_showset_class.php'))
	{
		$zengl_cur_theme = $zengl_theme;
		include_once $tmp_class;
	}
	else if(file_exists($tmp_class = $zengl_cms_tpl_dir . $zengl_old_theme .
			'/class/config_showset_class.php'))
	{
		$zengl_cur_theme = $zengl_theme;
		$zengl_theme = $zengl_old_theme;
		include_once $tmp_class;
	}
	else
		die('tpl class file config_showset_class.php does not exist!');
}
else if($rvar_action == 'setconfig')
{
	header( "Content-Type:   text/html;   charset=UTF-8 ");
	$cache = new cache();
	$cache->progress_begin('准备进行系统配置...', 3);
	if($rvar_dbtype != $db_type)
	{
		if($rvar_dbtype == '0')
			set_config('db_type', 'SQLITE');
		else
			set_config('db_type', 'MYSQL');
	}
	if($rvar_mysql_hostname != $db_hostname)
	{
		set_config('db_hostname', '"'. $rvar_mysql_hostname .'"');
	}
	if($rvar_mysql_username != $db_username)
	{
		set_config('db_username', '"'. $rvar_mysql_username .'"');
	}
	if($rvar_mysql_password != $db_password)
	{
		set_config('db_password', '"'. $rvar_mysql_password .'"');
	}
	if($rvar_mysql_dbname != $zengl_userset_mysqldb_name)
	{
		set_config('zengl_userset_mysqldb_name', '"'. $rvar_mysql_dbname .'"');
	}
	if($rvar_sqlite_dbpath != $zengl_userset_sqlitedb_name)
	{
		set_config('zengl_userset_sqlitedb_name', '"'. $rvar_sqlite_dbpath .'"');
	}
	if($rvar_tables_prefix != $db_tables_prefix)
	{
		set_config('db_tables_prefix', '"'. $rvar_tables_prefix .'"');
	}
	if($rvar_db_bakpath != $db_database_bak_path)
	{
		set_config('db_database_bak_path', '"'. $rvar_db_bakpath .'"');
	}
	if($rvar_db_bak_pernum != $db_bak_pernum)
	{
		set_config('db_bak_pernum', $rvar_db_bak_pernum);
	}
	if($rvar_db_restore_user != $db_restore_user)
	{
		set_config('db_restore_user', '"'. $rvar_db_restore_user .'"');
	}
	if($rvar_db_restore_pass != $db_restore_pass)
	{
		set_config('db_restore_pass', '"'. $rvar_db_restore_pass .'"');
	}
	if($rvar_cms_rootdir != $zengl_cms_rootdir)
	{
		set_config('zengl_cms_rootdir', '"'. $rvar_cms_rootdir .'"');
	}
	if($rvar_cms_domain != $zengl_cms_full_domain)
	{
		set_config('zengl_cms_full_domain', '"'. $rvar_cms_domain .'"');
	}
	if($rvar_cms_update_user != $cms_update_user)
	{
		set_config('cms_update_user', '"'. $rvar_cms_update_user .'"');
	}
	if($rvar_cms_update_pass != $cms_update_pass)
	{
		set_config('cms_update_pass', '"'. $rvar_cms_update_pass .'"');
	}
	if($rvar_article_descript_charnum != $article_descript_charnum)
	{
		set_config('article_descript_charnum', $rvar_article_descript_charnum);
	}
	if($rvar_zengl_cms_comment_shownum != $zengl_cms_comment_shownum)
	{
		set_config('zengl_cms_comment_shownum', $rvar_zengl_cms_comment_shownum);
	}
	if($rvar_zengl_index_sec_count != $ZlCfg_IndexSecCount)
	{
		set_config('ZlCfg_IndexSecCount', $rvar_zengl_index_sec_count);
	}
	if($rvar_zengl_index_article_count != $ZlCfg_IndexArticleCount)
	{
		set_config('ZlCfg_IndexArticleCount', $rvar_zengl_index_article_count);
	}
	if($rvar_zengl_list_article_count != $ZlCfg_ListArticleCount)
	{
		set_config('ZlCfg_ListArticleCount', $rvar_zengl_list_article_count);
	}
	if($rvar_zengl_theme != $zengl_theme)
	{
		set_config('zengl_theme', '"'.$rvar_zengl_theme.'"');
	}
	if($rvar_zengl_old_theme != $zengl_old_theme)
	{
		set_config('zengl_old_theme', '"'.$rvar_zengl_old_theme.'"');
	}
	if($rvar_use_html != 'yes')
		$rvar_use_html = 'no';
	if($rvar_isneed_register != 'yes')
		$rvar_isneed_register = 'no';
	if($rvar_isneed_login != 'yes')
		$rvar_isneed_login = 'no';
	if($rvar_use_html != $zengl_cms_use_html)
	{
		set_config('zengl_cms_use_html', '"'. $rvar_use_html .'"');
	}
	if($rvar_isneed_register != $zengl_cms_isneed_register)
	{
		set_config('zengl_cms_isneed_register', '"'. $rvar_isneed_register .'"');
	}
	if($rvar_isneed_login != $zengl_cms_isneed_login)
	{
		set_config('zengl_cms_isneed_login', '"'. $rvar_isneed_login .'"');
	}
	$cache->progress('config.php配置完毕');
	$set_sql = new sql('utf8'); 
	foreach ($rvar_setting as $set_group_name => $set_group_array)
	{
		foreach ($set_group_array as $set_k => $set_v)
		{
			config_set_db_setting(&$set_sql,$set_group_name,$set_k,$set_v);
		}
		config_get_db_setting($set_group_name,&$set_sql);
	}
	$set_sql = null;
	$cache->progress('数据库中的配置完毕');
	$config_progress_width = $cache->progress->width;
	$cache->clear_caches();
	echo '<script language="JavaScript">'; //因为clear_caches清理了缓存，所以只能手动输出.
	$msg = '配置情况：系统配置成功!';
	echo 'updateProgress("'.$msg.'",'.$config_progress_width.');';
	echo 'addlog("'.$msg.'\n");';
	echo '</script>';
	echo '</body></html>';
}
else
	new error('访问失败！','无效的参数',true,true);
?>