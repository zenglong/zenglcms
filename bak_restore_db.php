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
i_need_func('err,sql,conf,help,tpl,permis,cache,db',__FILE__,true); //最后的参数为true时，file_cache中会生成调试版本的缓存。
//i_need_func('err,sql,conf,help,tpl,permis,cache,db',__FILE__);
include $my_need_files;

import_request_variables("gpc","rvar_");
header( "Content-Type:   text/html;   charset=UTF-8 ");
$sql = new sql('utf8');
$permis = new permis(new session(true));
if(!$permis->check_perm(BAK_RESTORE_DB))
{
	new error('禁止访问','用户权限无法备份恢复数据库！',true,true);
}

if(!isset($rvar_user) || !isset($rvar_pass))
{
	global $zengl_cms_tpl_dir;
	global $zengl_theme;
	global $zengl_old_theme;
	if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/db_restore_class.php'))
		include_once $zengl_theme_tpl_class;
	else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
			'/class/db_restore_class.php'))
	{
		$zengl_theme = $zengl_old_theme;
		include_once $zengl_theme_tpl_class;
	}
	else
		die('tpl class file db_restore_class.php does not exist!');
}
else if($rvar_user != $db_restore_user || $rvar_pass != $db_restore_pass)
{
	new error('备份或恢复失败！','备份或恢复数据库所需的用户名密码错误,请在config中配置正确!',true,true);
}

if($rvar_sqltype == 'mysql' || $rvar_sqltype == 'sqlite')
	$sqltype = $rvar_sqltype;
else if($sql->db_type == MYSQL)
	$sqltype = 'mysql';
else if($sql->db_type == SQLITE)
	$sqltype = 'sqlite';
else
	new error('数据库类型错误','无效的数据库类型',true,true);

if($rvar_action == 'bak')
{
	$permis->progress_begin('准备备份数据库', 2);
	$permis->progress("准备备份数据库表为 " . $sqltype . " 格式：");
	$sql->progress = &$permis->progress;
	//flush_buffers();
	$sql->bak_tables('user','articles','level','section','archives','tags','comment','CommentReply','setting',$db_database_bak_prefix,
						$db_database_bak_suffix,$db_bak_pernum,$sqltype);
	$permis->progress_end("备份完毕！");
}
else if($rvar_action == 'restore')
{
	$permis->progress_begin("准备创建数据库表结构到 " . $sqltype ." 数据库中：",5);
	//flush_buffers();
	$db = new db(&$sql);
	$db->progress = null;
	$db->progress = &$permis->progress;
	$dirname = dirname($db_database_bak_prefix);
	if($sqltype == 'mysql')
	{
		if(!file_exists($dirname . '/' .$sql->mysql_bak_format_file))
		{
			$permis->progress_end("错误：当前数据库是{$sqltype}类型，但是导出的备份文件不是{$sqltype}格式的，".
					"请导出为{$sqltype}格式后再试!");
			die();
		}
	}
	elseif($sqltype == 'sqlite')
	{
		if(!file_exists($dirname . '/' .$sql->sqlite_bak_format_file))
		{
			$permis->progress_end("错误：当前数据库是{$sqltype}类型，但是导出的备份文件不是{$sqltype}格式的，".
					"请导出为{$sqltype}格式后再试!");
			die();
		}
	}
	$db->create_db_tables('onlyinit');
	$permis->progress("准备恢复数据库表到 " . $sqltype ." 数据库中：");
	//flush_buffers();
	$sql->progress = null;
	$sql->progress =&$permis->progress;
	$sql->restore_tables($db_database_bak_prefix,
							$db_database_bak_suffix,$sqltype);
	$permis->progress("恢复完毕！");
	$cache = new cache();
	$cache->progress = null;
	$cache->progress = &$permis->progress;
	$cache->clear_caches();
	$permis->update_permis();
	$permis->progress_end('请刷新浏览器来显示效果！');
}
else 
	new error('数据库备份/恢复操作失败','无效的请求参数！',true,true);
?>