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
 * 该文档只是最开始开发CMS时调试用的，v0.0.1版本中并没用到。
 * 本文档用于数据库的连接，直接包含该文档，可以简化数据库的连接操作，本程序先连接服务器，再选择数据库，最后设置utf8的字符集。
 * mysql_set_charset要求PHP 5 >= 5.2.3 ,所以php 5>=5.2.3是本程序的最低要求！
 * 作者：zenglong
 * 创建时间：2011年12月14日 */
include 'config.php';
$link=mysql_connect($db_hostname,$db_username,$db_password);
if(!$link)
{
	die("Could not connect: " . mysql_error());
}
if(!mysql_select_db($db_database_name,$link))
	echo "Select database failure: ". mysql_error() . "<br/>";
if(!mysql_set_charset('utf8',$link))
	echo "set charset utf8 failure: ". mysql_error() . "<br/>";
?>