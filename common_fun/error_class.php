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
class error
{
	/* var $outstring;
	var $filetpl;
	var $filecache; */
	function __construct()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		$arg_num = func_num_args();
		if($arg_num < 2)
			die('error class args must more then 2 ');
		$args = func_get_args();
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme .
				'/class/error_tpl_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/error_tpl_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file error_tpl_class.php does not exist!');
	}
}
class success
{
	/* var $outstring;
	var $filetpl;
	var $filecache; */
	function __construct()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		$arg_num = func_num_args();
		if($arg_num < 2)
			die('error class args must more then 2 ');
		$args = func_get_args();
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme .
				'/class/success_tpl_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/success_tpl_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file success_tpl_class.php does not exist!');
	}
}
?>