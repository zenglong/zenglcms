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
$zengl_cms_version = 'v1.0.0';
define(SQLITE, 0); //sqlite数据库类型
define(MYSQL, 1); //mysql数据库类型
$db_type = MYSQL; /**数据库类型*/
//$db_type = MYSQL;
$db_hostname = "localhost";
$db_username = "test";
$db_password = "test";
//$db_database_name="zengl_cms";
$zengl_userset_mysqldb_name = "zenglcmsOrig";
$zengl_userset_sqlitedb_name = "../db/zengl_cms.db";
if($db_type == MYSQL)
	$db_database_name = $zengl_userset_mysqldb_name;
else if($db_type == SQLITE)
	$db_database_name = $zengl_userset_sqlitedb_name;
else
	die('config invalid db type!');
//$db_database_name="../db/zengl_cms.db";
$db_tables_prefix = "zengl_";
$db_pass_suffix = "dq6UtTPPkK"; //随机的密码掩码
$db_database_bak_path = "db";
$db_database_bak_prefix_name = 'zengl_cms_db';
$db_database_bak_prefix = $db_database_bak_path . '/'. $db_database_bak_prefix_name;
$db_database_bak_suffix = 'bak';
$db_bak_pernum = 100; //每个备份保留多少行数据。
$db_restore_user = 'root';
$db_restore_pass = 'admin';
//上传目录：
$zengl_upload_dir = 'upload/';
$zengl_cms_tpl_dir = 'tpl/';
$zengl_cms_rootdir = "/";
$zengl_admin_comment_listnum = 10;
$zengl_admin_reply_listnum = 15;
$cms_update_user = 'root';
$cms_update_pass = 'admin';
$archive_smimg_width = 200;
$archive_smimg_height = 150;
$archive_smimg_default = 'upload/attach.jpg';
$listshow_article_smimg_default= 'images/文章默认缩略图.jpg';
$archive_smimg_dirname = 'smimg';
$article_descript_charnum = 200;
$zengl_theme = 'default'; //CMS当前使用的主题风格名
$zengl_old_theme = 'default'; //CMS之前使用的主题风格名
$zengl_cms_full_domain = "www.zenglcmsorig.qq"; //CMS网站的完整域名
//$zengl_cms_use_html = 'yes'; //CMS网站全站前端是否使用静态页面显示
$zengl_cms_use_html = "yes"; //CMS网站全站前端是否使用静态页面显示
$zengl_cms_init_name = "test"; //zenglcms初始名字
$zengl_cms_init_pass = "test"; // zenglcms初始密码
$zengl_cms_isneed_register = "no"; //zenglcms是否开启注册
$zengl_cms_isneed_login = "yes"; //zenglcms是否开启登录
$zengl_cms_comment_shownum = 10; //zenglcms文章评论的显示数目
$ZlCfg_IndexSecCount = 6; //zenglcms首页显示多少栏目的文章。
$ZlCfg_IndexArticleCount = 10; //zenglcms首页每个栏目列表显示多少文章。
$ZlCfg_ListArticleCount = 21; //zenglcms文章列表页面每个分页显示多少文章。
date_default_timezone_set('PRC'); //设置中国时区

function set_config($name,$val){
	$configfile = 'config.php';
	$content = file_get_contents($configfile);
	$pattern = '/^(\s)*\$' . $name . '(\s)*=(\s)*["\'a-zA-Z0-9\.\/\_]+(\s)*;/m';
	$content = preg_replace($pattern, '$' . $name . ' = ' . $val . ';', $content,1);
	file_put_contents($configfile, $content);
}
?>
