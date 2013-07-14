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
//error_reporting(0); //不显示错误
error_reporting(5); /*只显示严重的错误*/

class CompactCode
{
	static protected $out;
	static protected $tokens;

	static public function compact($source)
	{
		// 解析 PHP 源代码
		self::$tokens = token_get_all($source);
		self::$out = '';

		reset(self::$tokens);

		// 递归判断每个标记符的类型
		while ($t = current(self::$tokens)) {
			if (is_array($t)) {
				// 过滤空白、注释
				if ($t[0] == T_WHITESPACE || $t[0] == T_DOC_COMMENT || $t[0] == T_COMMENT) {
					self::skipWhiteAndComments();
					continue;
				}
				self::$out .= $t[1];
			} else {
				self::$out .= $t;
			}

			next(self::$tokens);
		}

		return self::$out;
	}

	static private function skipWhiteAndComments()
	{
		// 增加个空格，用于分割关键字
		self::$out .= ' ';
		while ($t = current(self::$tokens)) {
			// 再次贪婪查找
			if (is_array($t) && ($t[0] == T_WHITESPACE || $t[0] == T_DOC_COMMENT || $t[0] == T_COMMENT)) {
				next(self::$tokens);
			} else {
				return;
			}
		}
	}
	static public function compact_comment($source)
	{
		// 解析 PHP 源代码
		self::$tokens = token_get_all($source);
		self::$out = '';
	
		reset(self::$tokens);
	
		// 递归判断每个标记符的类型
		while ($t = current(self::$tokens)) {
			if (is_array($t)) {
				// 过滤空白、注释
				if ($t[0] == T_DOC_COMMENT || $t[0] == T_COMMENT) {
					self::skipComments();
					continue;
				}
				self::$out .= $t[1];
			} else {
				self::$out .= $t;
			}
	
			next(self::$tokens);
		}
	
		return self::$out;
	}
	
	static private function skipComments()
	{
		// 增加个空格，用于分割关键字
		self::$out .= ' ';
		while ($t = current(self::$tokens)) {
			// 再次贪婪查找
			if (is_array($t) && ($t[0] == T_DOC_COMMENT || $t[0] == T_COMMENT)) {
				next(self::$tokens);
			} else {
				return;
			}
		}
	}
}

$my_need_file_deps = array('err'=>array('help','tpl'),
		'conf'=>'',
		'sql'=>array('err','conf','progress'),
		'sess'=>'',
		'tpl'=>'',
		'user'=>array('tpl','sql','conf','err','sess','help'),
		'auth'=>array('sess'),
		'article'=>array('sess','sql','err','tpl','conf','user','sec','permis','help','tags','progress'),
		'sec'=>array('sess','sql','err','tpl','user','permis','help','conf'),
		'permis'=>array('sess','sql','progress'),
		'cache'=>array('tpl','sec','conf','progress'),
		'help'=>array('conf'),
		'archive'=>array('permis','sess','sql','conf','err','tpl','watermark'),
		'admin'=>array('permis','sess','sql','tpl','conf','serverinfo'),
		'tags'=>array('sql','conf','tpl','help'),
		'comment'=>array('sql','permis','help','err','tpl','conf','article'),
		'db'=>array('sql','help','permis','conf','user','progress'),
		'serverinfo'=>array('conf'),
		'progress'=>array('conf','tpl','help'),
		'watermark'=>array('conf','err'),
		);

$my_need_file_paths = array('err'=>'common_fun/error_class.php',
		'conf'=>'config.php',
		'sql'=>'common_fun/sql_class.php',
		'sess'=>'common_fun/session_class.php',
		'tpl'=>'common_fun/tpl_class.php',
		'user'=>'common_fun/user_class.php',
		'auth'=>'common_fun/auth_class.php',
		'article'=>'common_fun/article_class.php',
		'sec'=>'common_fun/section_class.php',
		'permis'=>'common_fun/permission_class.php',
		'cache'=>'common_fun/cache_class.php',
		'help'=>'common_fun/help_func.php',
		'archive'=>'common_fun/archive_class.php',
		'admin'=>'common_fun/admin_class.php',
		'tags'=>'common_fun/tags_class.php',
		'comment'=>'common_fun/comment_class.php',
		'db'=>'common_fun/db_class.php',
		'serverinfo'=>'common_fun/serverinfo_class.php',
		'progress'=>'common_fun/progress_class.php',
		'watermark'=>'common_fun/watermark_class.php',
		);

$my_need_funcs = array();
$my_need_func_paths = array();

$my_need_files='';

$zengl_cms_filecache_dir = 'file_cache/';

function import_files()
{
	global $my_need_files;
	$debug = true;
	$args = func_get_args();
	$count = count($args);
	$my_need_files = '';
	$debug = array_pop($args);
	$my_need_files = array_pop($args);
	if($debug == true)
		$my_need_files = str_replace('.', '_debug.', $my_need_files);
	
	if(file_exists($my_need_files))
	{
		$isnew = true;
		foreach ($args as $arg)
		{
			if(!file_exists($arg))
				die("$arg's file is not exists ,please check!");
			if(filemtime($my_need_files) <= filemtime($arg))
				$isnew = false;
		}
		if($isnew)
		{
			return;
		}
	}
	$file_content = '';
	foreach ($args as $arg)
	{
		if(is_string($arg))
		{
			if(!file_exists($arg))
				die("$arg's file is not exists ,please check!");
			if(!$debug)
				$file_content .= CompactCode::compact(file_get_contents($arg)) . "\n";
			else 
				$file_content .= CompactCode::compact_comment(file_get_contents($arg));
		}
	}
	file_put_contents($my_need_files, $file_content);
}
function import_files_array($file_array,$debug)
{
	global $my_need_files;
	
	if($debug == true)
		$my_need_files = str_replace('.', '_debug.', $my_need_files);
	if(file_exists($my_need_files))
	{
		$isnew = true;
		foreach ($file_array as $arg)
		{
			if(!file_exists($arg))
				die("$arg's file is not exists ,please check!");
			if(filemtime($my_need_files) <= filemtime($arg))
				$isnew = false;
		}
		if($isnew)
		{
			return;
		}
	}
	$file_content = '';
	foreach ($file_array as $arg)
	{
		if(is_string($arg))
		{
			if(!file_exists($arg))
				die("$arg's file is not exists ,please check!");
			if(!$debug)
				$file_content .= CompactCode::compact(file_get_contents($arg)) . "\n";
			else
				$file_content .= CompactCode::compact_comment(file_get_contents($arg));
		}
	}
	file_put_contents($my_need_files, $file_content);
}
function func_deps($func_array)
{
	global $my_need_file_deps;
	global $my_need_funcs;
	foreach ($func_array as $func)
	{
		if(is_array($my_need_file_deps[$func]))
		{
			func_deps($my_need_file_deps[$func]);
			$my_need_funcs[] = $func;
		}
		else 
			$my_need_funcs[] = $func;
	}
}
function i_need_func($funcstr,$file_cache,$debug=false)
{
	global $my_need_file_paths;
	global $my_need_funcs;
	global $my_need_func_paths;
	global $my_need_files;
	global $zengl_cms_filecache_dir;
	$func_array = explode(',', $funcstr);
	func_deps($func_array);
	$my_need_funcs = array_unique($my_need_funcs);
	foreach ($my_need_funcs as $dep)
	{
		if($my_need_file_paths[$dep] == '')
			die("$dep's file_path is null ,please check file_func.php file!");
		$my_need_func_paths[] = $my_need_file_paths[$dep];
	}
	$file_cache = basename($file_cache,'.php');
	$file_cache = $zengl_cms_filecache_dir . $file_cache .'_cache_inc.php';
	$my_need_files = $file_cache;
	import_files_array($my_need_func_paths,$debug);
}
?>
