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
i_need_func('db,permis,err,tpl,serverinfo,conf,cache',__FILE__,true); //最后的参数为true时，file_cache中会生成调试版本的缓存。
//i_need_func('db,permis,err,tpl,serverinfo,conf,cache',__FILE__);
include $my_need_files;

import_request_variables("gpc","rvar_");
header( "Content-Type:   text/html;   charset=UTF-8 ");

$install = new install();

if(file_exists('install/install.lock'))
	die('已经安装过CMS了，如果要再次安装，请删除install目录里的install.lock！');

if($rvar_action == 'update_permis')
{
	$permis = new permis(new session(true), new sql('utf8'));
	$permis->update_permis();
}
else if($rvar_action == 'setconfig')
	$install->SetConfig();
else if($rvar_action == 'install4')
{
	$db = new db(new sql('utf8'));
	$db->progress = null;
	$db->progress = new progress();
	$db->progress->begin('准备创建数据库', 2);
	$db->create_db_tables();
	$cache = new cache();
	$cache->progress = null;
	$cache->progress = &$db->progress;
	$cache->clear_caches();
	touch('install/install.lock');
	$db->progress->end("生成install.lock安装锁,防止再次误安装&nbsp;&nbsp;<a href='adminLogin/admin_login.php' target='_blank'>从后台登录</a>&nbsp;&nbsp;&nbsp;&nbsp;" . 
		  "<a href = 'index.php' target='_blank'>访问首页的动态页面</a>",false);
}
else if($rvar_action == 'install3')
	$install->ShowSetConfig();
else if($rvar_action == 'install2')
	$install->Check_Require();
else
	$install->ShowLicence();

class install
{
	function ShowLicence()
	{
		if(file_exists($zengl_theme_tpl_index =  'install/licence_class.php'))
			include $zengl_theme_tpl_index;
		else
			die('install licence tpl class file licence_class.php does not exist!');
	}
	function check_version($version,$min_version)
	{
		$version = explode('.', $version);
		$min_version = explode('.', $min_version);
		if($version[0] < $min_version[0])
			return false;
		else if($version[0] == $min_version[0])
		{
			if($version[1] < $min_version[1])
				return false;
			else if($version[1] == $min_version[1])
			{
				if($version[2] < $min_version[2])
					return false;
				else
					return true;
			}
			else
				return true;
		}
		else 
			return true;
	}
	function Check_Require()
	{
		if(file_exists($zengl_theme_tpl_index =  'install/check_require_class.php'))
			include $zengl_theme_tpl_index;
		else
			die('install check require tpl class file check_require_class.php does not exist!');
	}
	function genRandomString($len)
	{
		$chars = array(
				"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
				"l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
				"w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
				"H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
				"S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
				"3", "4", "5", "6", "7", "8", "9"
		);
		$charsLen = count($chars) - 1;
		shuffle($chars);    // 将数组打乱
		$output = "";
		for ($i=0; $i<$len; $i++)
		{
			$output .= $chars[mt_rand(0, $charsLen)];
		}
		return $output;
	}
	function ShowSetConfig()
	{
		if(file_exists($zengl_theme_tpl_index =  'install/show_setconfig_class.php'))
			include $zengl_theme_tpl_index;
		else
			die('install check require tpl class file show_setconfig_class.php does not exist!');
	}
	function SetConfig()
	{
		global $rvar_dbtype;
		global $rvar_mysql_hostname;
		global $rvar_mysql_username;
		global $rvar_mysql_password;
		global $rvar_mysql_dbname;
		global $rvar_sqlite_dbpath;
		global $rvar_tables_prefix;
		global $rvar_db_bakpath;
		global $rvar_cms_rootdir;
		global $rvar_cms_domain;
		global $rvar_cms_init_username;
		global $rvar_cms_init_pass;
		if($rvar_dbtype == '0')
		{
			if($rvar_sqlite_dbpath == '')
				die('<img src="install/no.jpg"><font color="red">sqlite数据库文件路径不能为空。</font>');
			else if(is_dir(dirname($rvar_sqlite_dbpath)) && 
					 is_writable(dirname($rvar_sqlite_dbpath)))
			{
				set_config('db_type', 'SQLITE');
				set_config('zengl_userset_sqlitedb_name', '"'.addslashes($rvar_sqlite_dbpath).'"');
				echo '将数据库配置为sqlite<br/>';
			}
			else
				die('<img src="install/no.jpg"><font color="red">sqlite数据库文件所在目录不存在或不可写。</font>');
		}
		else
		{
			if($rvar_mysql_hostname == '' || $rvar_mysql_username == '' ||
			   $rvar_mysql_dbname == '')
				die('<img src="install/no.jpg"><font color="red">mysql数据库主机名,用户名,数据库名都不能为空。</font>');
			else
			{
				set_config('db_type', 'MYSQL');
				set_config('db_hostname', '"' . addslashes($rvar_mysql_hostname) . '"');
				set_config('db_username', '"' . addslashes($rvar_mysql_username) . '"');
				set_config('db_password', '"' . addslashes($rvar_mysql_password) . '"');
				set_config('zengl_userset_mysqldb_name', '"' . addslashes($rvar_mysql_dbname) . '"');
				echo '将数据库配置为mysql<br/>';
			}
		}
		
		if($rvar_tables_prefix == '')
		{
			$rvar_tables_prefix = "zengl_";
		}
		set_config('db_tables_prefix', '"' . addslashes($rvar_tables_prefix) . '"');
		set_config('db_pass_suffix', '"' . $this->genRandomString(10) . '"');
		set_config('db_database_bak_path', '"' . addslashes($rvar_db_bakpath) . '"');
		set_config('zengl_cms_rootdir', '"' . addslashes($rvar_cms_rootdir) . '"');
		set_config('zengl_cms_full_domain', '"' . addslashes($rvar_cms_domain) . '"');
		set_config('zengl_cms_init_name', '"' . addslashes($rvar_cms_init_username) . '"');
		set_config('zengl_cms_init_pass', '"' . addslashes($rvar_cms_init_pass) . '"');
		echo '系统配置完毕,接下来安装数据库<br/>';
		echo '<script type="text/javascript">location.href="install.php?action=install4"</script>';
	}
}
?>