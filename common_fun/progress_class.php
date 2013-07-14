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
class progress
{
	var $width; //进度条宽度像素值
	var $pix; //进度条每执行一任务的需走的像素值
	var $cur; //进度条当前游标
	var $totaltask; //进度条总任务数
	var $isBegin = false; //是否已经begin了
	
	/*
	 * 输出进度条的开头
	* 参数$title 标题和初始进度名
	* 参数$total 总共需要操作的记录数
	* */
	function begin($title,$total)
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if($this->isBegin)
		{
			$this->setNewTotalTask($total);
			return;
		}
		else
		{
			$this->isBegin = true;
		}
		if(file_exists($zengl_cms_tpl_dir . $zengl_theme . '/admin_progress.tpl'))
			$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/admin_progress.tpl';
		else if(file_exists($zengl_cms_tpl_dir . $zengl_old_theme . '/admin_progress.tpl'))
			$filetpl = $zengl_cms_tpl_dir . $zengl_old_theme . '/admin_progress.tpl';
		else 
			die($zengl_cms_tpl_dir . $zengl_theme . '/admin_progress.tpl does not exist!');
		$filecache = $zengl_cms_tpl_dir . 'cache/admin_progress_cache.php';
		$this->totaltask = $total; //设置总任务数
		$this->width = $width = 780; //显示的进度条长度，单位 px
		$this->pix = $pix = $this->width / $this->totaltask; //每条记录的操作所占的进度条单位长度
		$this->cur = $progress = 0;  //当前进度条长度
		echo  str_pad('',4096);
		if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
				(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
			include $filecache;
		else
		{
			$tpl = new tpl($filetpl, $filecache);
			$tpl->template_parse();
			$tpl->cache();
			include $filecache;
		}
		flush_buffers();
	}
	/*
	 * 输出进度条  执行完一条任务，步进一格
	* 参数$msg 进度条的消息
	* */
	function step($msg,$isNeedAddProgress = true)
	{
		echo '<script language="JavaScript">';
		if($isNeedAddProgress == true)
		{
			$this->cur += $this->pix;
			$this->cur = min($this->width, intval($this->cur));
		}
		echo 'updateProgress("'.$msg.'",'.$this->cur.');';
		echo 'addlog("'.$msg.'\n");';
		echo '</script>';
		flush_buffers();
	}
	
	/*
	 * 输出进度条结束
	* 参数$msg 进度条的消息*/
	function end($msg,$isNeedAddLog = true)
	{
		echo '<script language="JavaScript">';
		echo 'updateProgress("'.$msg.'",'.$this->width.');';
		if($isNeedAddLog == true)
			echo 'addlog("'.$msg.'\n");';
		echo '</script>';
		echo '</body></html>';
		flush_buffers();
	}
	
	/*
	 * 重新设置进度条的总任务数
	 * 参数$total 总任务个数
	 * 参数isReset 是否重置游标
	 * */
	function setNewTotalTask($total,$isReset = true)
	{
		$this->totaltask = $total; //设置总任务数
		$this->pix = $this->width / $this->totaltask; //每条任务的操作所占的进度条单位长度
		if($isReset == true)
			$this->cur = 0;  //当前进度条长度
	}
}
?>