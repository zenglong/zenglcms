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
class cache
{
	var $progress;
	function __construct()
	{
		$this->progress = new progress();
		//global $zengl_cms_tpl_dir;
		//$this->tpl = new tpl($zengl_cms_tpl_dir . 'clear_filecache.tpl',$zengl_cms_tpl_dir . 'clear_filecache_cache.php');
	}
	/*
	 * 输出进度条的开头
	 * 参数$title 标题和初始进度名
	 * 参数$total 总共需要操作的记录数
	 * */
	function progress_begin($title,$total)
	{
		$this->progress->begin($title, $total);
	}
	
	/*
	 * 输出进度条
	 * 参数$msg 进度条的消息
	 * */
	function progress($msg,$isNeedAddProgress = true)
	{
		$this->progress->step($msg,$isNeedAddProgress);
	}
	
	/*
	 * 输出进度条结束
	* 参数$msg 进度条的消息*/
	function progress_end($msg,$isNeedAddLog = true)
	{
		$this->progress->end($msg,$isNeedAddLog);
	}
	
	function clear_tpl_cache($tpl)
	{
		$dirhandle = opendir($tpl);
		$pattern = "/_cache([0-9]*|[0-9]+_[0-9]+).php\$/";
		$cachenum = 0;
		while($tpl_cache = readdir($dirhandle))
		{
			if(preg_match($pattern, $tpl_cache))
			{
				unlink($tpl . '/' . $tpl_cache);
				$cachenum++;
				$this->progress("$cachenum 删除 $tpl 目录的 $tpl_cache 文件");
			}
		}
		$this->progress("共删除 $tpl 目录下 $cachenum 个缓存文件");
	}
	function clear_file_cache($file_cache_dir)
	{
		$dirhandle = opendir($file_cache_dir);
		$pattern = "/_cache_inc(_debug)?.php$/";
		$cachenum = 0;
		while ($file_cache = readdir($dirhandle))
		{
			if(preg_match($pattern, $file_cache))
			{
				unlink($file_cache_dir . '/' . $file_cache);
				$cachenum++;
				$this->progress("$cachenum 删除 $file_cache_dir 目录的 $file_cache 文件");
			}
		}
		$this->progress("共删除 $file_cache_dir 目录下 $cachenum 个缓存文件");
	}
	function clear_caches($tpl,$file_cache_dir,$del_sec_cache=true)
	{
		global $zengl_cms_tpl_dir;
		global $zengl_cms_filecache_dir;
		$tpl = $zengl_cms_tpl_dir . 'cache';
		if($this->progress->isBegin)
			$has_begin_prev = true;
		else
			$has_begin_prev = false;
		$this->progress_begin('准备清理缓存', 5);
		$this->clear_tpl_cache($tpl);
		$this->clear_tpl_cache($tpl . "/comment_cache");
		$file_cache_dir = substr($zengl_cms_filecache_dir,0,-1);
		$this->clear_file_cache($file_cache_dir);
		$tmpsec = new section();
		if(file_exists($tmpsec->array_file))
		{
			unlink($tmpsec->array_file);
			$this->progress("删除栏目缓存文件$tmpsec->array_file");
		}
		if($has_begin_prev)
			$this->progress("缓存清理完毕");
		else
			$this->progress_end("缓存清理完毕");
		
		/*global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/clear_caches_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/clear_caches_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file clear_caches_class.php does not exist!');*/
	}
	function clear_comment_cache()
	{
		global $zengl_cms_tpl_dir;
		global $rvar_articleID;
		$article_array = explode(',', $rvar_articleID);
		if(!$article_array || count($article_array) == 0)
			new error('评论缓存删除失败','无效的ID值序列',true,true);
		
		$article_array = array_unique($article_array);
		foreach ($article_array as $val)
		{
			if(is_numeric($val))
			{
				continue;
			}
			else
				new error('评论缓存删除失败','ID值序列必须为数字',true,true);
		}
		$article_str = implode('|', $article_array);
		//$tpl = substr($zengl_cms_tpl_dir,0,-1);
		$tpl = $zengl_cms_tpl_dir . 'cache/comment_cache';
		$dirhandle = opendir($tpl);
		$pattern = "/show_comment_cache($article_str)(_[0-9]+)?.php\$/";
		$cachenum = 0;
		while($tpl_cache = readdir($dirhandle))
		{
			if(preg_match($pattern, $tpl_cache))
			{
				unlink($tpl . '/' . $tpl_cache);
				$cachenum++;
				echo "&nbsp;&nbsp;$cachenum 删除 $tpl 目录的 $tpl_cache 文件<br/>";
			}
		}
		echo "&nbsp;&nbsp;共删除 $tpl 目录下 $cachenum 个缓存文件<br/>";
	}
}
?>