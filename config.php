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
/**
 * 本文档是系统的配置文档，有关数据库的主机，用户名，密码等信息，还有数据库名和数据库表前缀等信息。
 * 作者：zenglong
 * 创建时间：2011年12月14日 */
$zengl_cms_version = "v1.2.0";
define('SQLITE', 0); //sqlite数据库类型
define('MYSQL', 1); //mysql数据库类型
//$db_type = SQLITE; /**数据库类型*/
$db_type = SQLITE;
$db_hostname = "localhost";
$db_username = "root";
$db_password = "admin";
//$db_database_name="zengl_cms";
$zengl_userset_mysqldb_name = "zenglcmsv12test";
$zengl_userset_sqlitedb_name = "db/zengl_cms.db";
if($db_type == MYSQL)
	$db_database_name = $zengl_userset_mysqldb_name;
else if($db_type == SQLITE)
	$db_database_name = $zengl_userset_sqlitedb_name;
else
	die('config invalid db type!');
//$db_database_name="../db/zengl_cms.db";
$db_tables_prefix = "zengl_";
$db_pass_suffix = "aRxx677NWw"; //随机的密码掩码
$db_database_bak_path = "db";
$db_database_bak_prefix_name = "zengl_cms_db";
$db_database_bak_prefix = $db_database_bak_path . '/'. $db_database_bak_prefix_name;
$db_database_bak_suffix = "bak";
$db_bak_pernum = 100; //每个备份保留多少行数据。
$db_restore_user = "root";
$db_restore_pass = "admin";
//上传目录：
$zengl_upload_dir = "upload/";
$zengl_cms_tpl_dir = "tpl/";
$zengl_cms_rootdir = "/";
$zengl_admin_comment_listnum = 10;
$zengl_admin_reply_listnum = 15;
$cms_update_user = "root";
$cms_update_pass = "admin";
$archive_smimg_width = 200;
$archive_smimg_height = 150;
$archive_smimg_default = "images/attach.jpg"; //默认的附件缩略图图标
$listshow_article_smimg_default= "images/文章默认缩略图.jpg";
$archive_smimg_dirname = "smimg";
$article_descript_charnum = 200;
$zengl_theme = "mydefined"; //CMS当前使用的主题风格名
$zengl_old_theme = "default"; //CMS之前使用的主题风格名
$zengl_cms_full_domain = "www.zenglcmstestv120.qq"; //CMS网站的完整域名
$zengl_cms_use_html = "yes"; //CMS网站全站前端是否使用静态页面显示
$zengl_cms_init_name = "root"; //zenglcms初始名字
$zengl_cms_init_pass = "admin"; // zenglcms初始密码
$zengl_cms_isneed_register = "no"; //zenglcms是否开启注册
$zengl_cms_isneed_login = "yes"; //zenglcms是否开启登录
$zengl_cms_comment_shownum = 10; //zenglcms文章评论的显示数目
$ZlCfg_IndexSecCount = 6; //zenglcms首页显示多少栏目的文章。
$ZlCfg_IndexArticleCount = 10; //zenglcms首页每个栏目列表显示多少文章。
$ZlCfg_ListArticleCount = 21; //zenglcms文章列表页面每个分页显示多少文章。
$ZLCfg_FileFontDir = "file_resource/font_resource/"; //水印字体文件的目录
$ZLCfg_DBDataDir = "file_resource/db_data_resource/"; //存放数据库初始化等相关数据的目录
date_default_timezone_set('PRC'); //设置中国时区

function set_config($name,$val){
	$configfile = 'config.php';
	$content = file_get_contents($configfile);
	$pattern = '/^(\s)*\$' . $name . '(\s)*=(\s)*["\'a-zA-Z0-9\.\/\_]+(\s)*;/m';
	$content = preg_replace($pattern, '$' . $name . ' = ' . $val . ';', $content,1);
	file_put_contents($configfile, $content);
}

function config_get_db_setting($group_name,$arg_sql = null)
{
	global $zengl_cms_filecache_dir;
	$file_cache = $zengl_cms_filecache_dir . 'config_db_set_'.$group_name.'_cache_inc.php';
	$group_set_array = array();
	if(!file_exists($file_cache))
	{
		if($arg_sql == null)
			$sql = new sql('utf8');
		else
			$sql = &$arg_sql;
		$sql->query("select * from $sql->tables_prefix" . "setting where set_group = '" . $group_name."'");
		while ($sql->parse_results()) {
			$group_set_array[$sql->row['name']] = $sql->row['value'];
		}
		file_put_contents($file_cache, serialize($group_set_array));
	}
	$group_set_array = unserialize(file_get_contents($file_cache));
	return $group_set_array;
}

function config_set_db_setting($sql , $group_name, $name , $value)
{
	global $zengl_cms_filecache_dir;
	$file_cache = $zengl_cms_filecache_dir . 'config_db_set_'.$group_name.'_cache_inc.php';
	if(file_exists($file_cache))
		unlink($file_cache);
	$value = $sql->escape_str($value);
	$sql->query("UPDATE {$sql->tables_prefix}setting SET value='$value'" . 
			" WHERE set_group='$group_name' and name= '$name'");
}
?>
