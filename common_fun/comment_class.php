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
class comment{
	var $session;
	var $sql;
	var $permis;
	var $page_size = 3;
	var $display_size = 7;
	function __construct($sql)
	{
		global $zengl_cms_comment_shownum;
		$this->permis = new permis();
		$this->session = new session();
		$this->session->get_userinfo();
		$this->permis->set_sess(&$this->session);
		$this->page_size = $zengl_cms_comment_shownum;
		if(isset($sql))
		{
			$this->sql = $sql;
			$this->permis->sql = &$this->sql;
		}
	}
	function check_perm($permis)
	{
		if($permis == COMMENT_ADD || $permis == COMMENT_SHOW || $permis == COMMENT_ADMIN || $permis == COMMENT_DEL ||
			$permis == CLEAR_ALL_CACHE || $permis == SET_CONFIG)
		{
			if($this->permis->check_perm($permis))
				return true;
			else
				return false;
		}
		else
			return false;
	}
	function check_param($action)
	{
		global $rvar_user;
		global $rvar_content;
		global $rvar_articleID;
		global $rvar_commentID;
		global $rvar_replyID;
		global $rvar_commentNum;
		global $rvar_replyNum;
		if($action == 'add')
		{
			if($rvar_user == '' || $rvar_content == '' || !(isset($rvar_articleID) && is_numeric($rvar_articleID)))
				return false;
			else
				return true;
		}
		else if($action == 'reply')
		{
			if($rvar_user == '' || $rvar_content == '' || !(isset($rvar_articleID) && is_numeric($rvar_articleID)) ||
			   !(isset($rvar_commentID) && is_numeric($rvar_commentID)))
				return false;
			else
				return true;
		}
		else if($action == 'show')
		{
			if(!(isset($rvar_articleID) && is_numeric($rvar_articleID)))
				return false;
			else
				return true;
		}
		else if($action == 'del_comment')
		{
			if(!(isset($rvar_articleID) && is_numeric($rvar_articleID)) || 
				!(isset($rvar_commentID) && is_numeric($rvar_commentID)))
				return false;
			else
				return true;
		}
		else if($action == 'multidel_comments')
		{
			if(isset($rvar_articleID) && isset($rvar_commentID))
				return true;
			else
				return false;
		}
		else if($action == 'clearCache')
		{
			if(isset($rvar_articleID))
				return true;
			else
				return false;
		}
		else if($action == 'admin_set_comment_num')
		{
			if(isset($rvar_commentNum) && is_numeric($rvar_commentNum))
				return true;
			else
				return false;
		}
		else if($action == 'del_reply')
		{
			if(!(isset($rvar_articleID) && is_numeric($rvar_articleID)) ||
					!(isset($rvar_replyID) && is_numeric($rvar_replyID)))
				return false;
			else
				return true;
		}
		else if($action == 'multidel_replys')
		{
			if(isset($rvar_articleID) && isset($rvar_replyID))
				return true;
			else
				return false;
		}
		else if($action == 'admin_set_reply_num')
		{
			if(isset($rvar_replyNum) && is_numeric($rvar_replyNum))
				return true;
			else
				return false;
		}
		else 
			return false;
	}
	function add()
	{
		global $rvar_user;
		global $rvar_content;
		global $rvar_articleID;
		global $zengl_cms_tpl_dir;
		$session = new session(true);
		$time = time();
		$permis = &$this->permis;
		$permis->gen_otheruid_permis(COMMENT_DEL, PER_DENY);
		$sql = &$this->sql;
		$sql->insert('comment','username,showtime,time,content,articleID,uid,ip_address,permis',
				$rvar_user,$time,$time,$rvar_content,$rvar_articleID,$session->userID,getIP(),
				$permis->gen_permis_str());
		if($sql->err == SQL_SUCCESS)
		{
			$filecache = $zengl_cms_tpl_dir.'cache/comment_cache/show_comment_cache'.$rvar_articleID.'.php';
			touch($filecache);
			//new success('ZENGLCMS评论发表情况：','评论发表成功！',true,true);
			$this->success_tpl('ZENGLCMS评论发表情况：', '评论发表成功！', "'".$zengl_cms_rootdir.
								'comment_operate.php?action=show&articleID='.$rvar_articleID."'");
		}
	}
	function show_add()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/show_add_comments_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/show_add_comments_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file show_add_comments_class.php does not exist!');
	}
	function success_tpl($title,$content,$jmploc)
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . 
				'/class/comment_success_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/comment_success_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file comment_success_class.php does not exist!');
	}
	function show()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/show_comment_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/show_comment_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file show_comment_class.php does not exist!');
	}
	function reply()
	{
		global $rvar_user;
		global $rvar_content;
		global $rvar_commentID;
		global $rvar_articleID;
		global $rvar_type;
		global $rvar_redirect;
		global $zengl_cms_tpl_dir;
		$session = new session(true);
		$time = time();
		$permis = &$this->permis;
		$permis->gen_otheruid_permis(COMMENT_DEL, PER_DENY);
		$sql = &$this->sql;
		$sql->insert('CommentReply','username,time,content,commentID,uid,ip_address,permis',
				$rvar_user,$time,$rvar_content,$rvar_commentID,$session->userID,getIP(),
				$permis->gen_permis_str());
		$sql->query("UPDATE {$sql->tables_prefix}comment SET showtime=$time " .
					"WHERE comment_ID=$rvar_commentID");
		header( "Content-Type:   text/html;   charset=UTF-8 ");
		if($sql->err == SQL_SUCCESS)
		{
			$filecache = $zengl_cms_tpl_dir.'cache/comment_cache/show_comment_cache'.$rvar_articleID.'.php';
			touch($filecache);
			if($rvar_type=='admin')
				new success('ZENGLCMS评论回复情况：', '评论回复成功！',true,true);
			else
				$this->success_tpl('ZENGLCMS评论回复情况：', '评论回复成功！', "'".$zengl_cms_rootdir.
						'comment_operate.php?action=show&articleID='.$rvar_articleID."'");
		}
		else
		{
			if($rvar_type=='admin')
			{
				new error('ZENGLCMS评论回复情况：', '评论回复失败！', true,true);
			}
			else
				$this->success_tpl('ZENGLCMS评论回复情况：', '评论回复失败！', "'".$zengl_cms_rootdir.
						'comment_operate.php?action=show&articleID='.$rvar_articleID."'");
		}
	}
	function show_reply()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme .
				'/class/show_reply_comment_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/show_reply_comment_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file show_reply_comment_class.php does not exist!');
	}
	function admin_comment_list()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/admin_list_comments_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/admin_list_comments_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file admin_list_comments_class.php does not exist!');
	}
	function del_comment()
	{
		global $zengl_cms_tpl_dir;
		global $rvar_commentID;
		global $rvar_articleID;
		$sql = &$this->sql;
		$tablename = "{$sql->tables_prefix}comment";
		$tablename_reply = "{$sql->tables_prefix}CommentReply";
		$sql->query("DELETE FROM $tablename_reply WHERE commentID=$rvar_commentID");
		$sql->query("DELETE FROM $tablename WHERE comment_ID=$rvar_commentID");
		if($sql->err== SQL_SUCCESS)
		{
			$filecache = $zengl_cms_tpl_dir.'show_comment_cache'.$rvar_articleID.'.php';
			touch($filecache);
			new success('ZENGLCMS评论删除情况：', '评论及评论相关的回复都删除成功！', true,true);
		}
	}
	function multidel_comments()
	{
		global $zengl_cms_tpl_dir;
		global $rvar_commentID;
		global $rvar_articleID;
		$sql = &$this->sql;
		$tablename = "{$sql->tables_prefix}comment";
		$tablename_reply = "{$sql->tables_prefix}CommentReply";
		$article_array = explode(',', $rvar_articleID);
		$comment_array = explode(',', $rvar_commentID);
		if(!$article_array || !$comment_array || count($article_array) == 0 || count($comment_array)==0)
			new error('评论删除失败','无效的ID值序列',true,true);
		
		$article_array = array_unique($article_array);
		$comment_array = array_unique($comment_array);
		$delstr_reply = "DELETE FROM $tablename_reply";
		$delstr_comment = "DELETE FROM $tablename";
		foreach ($comment_array as $key => $val)
		{
			if(is_numeric($val))
			{
				if($key == 0)
				{
					$delstr_reply .= " WHERE commentID=" .$val;
					$delstr_comment .= " WHERE comment_ID=".$val;
				}
				else
				{
					$delstr_reply .= " OR commentID=" .$val;
					$delstr_comment .= " OR comment_ID=".$val;
				}
			}
			else 
				new error('评论删除失败','ID值序列必须为数字',true,true);
		}
		$sql->query($delstr_reply);
		$sql->query($delstr_comment);
		if($sql->err== SQL_SUCCESS)
		{
			foreach ($article_array as $val)
			{
				if(is_numeric($val))
				{
					$filecache = $zengl_cms_tpl_dir.'show_comment_cache'.$val.'.php';
					touch($filecache);
				}
				else
					new error('评论删除失败','ID值序列必须为数字',true,true);
			}
			new success('ZENGLCMS评论删除情况：', '评论及评论相关的回复都删除成功！', true,true);
		}
	}
	function admin_reply_list()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/admin_list_replys_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/admin_list_replys_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file admin_list_replys_class.php does not exist!');
	}
	function del_reply()
	{
		global $zengl_cms_tpl_dir;
		global $rvar_replyID;
		global $rvar_articleID;
		$sql = &$this->sql;
		$tablename = "{$sql->tables_prefix}CommentReply";
		$sql->query("DELETE FROM $tablename WHERE reply_ID=$rvar_replyID");
		if($sql->err== SQL_SUCCESS)
		{
			$filecache = $zengl_cms_tpl_dir.'show_comment_cache'.$rvar_articleID.'.php';
			touch($filecache);
			new success('ZENGLCMS评论回复的删除情况：', '相关回复已经删除成功！', true,true);
		}
	}
	function multidel_replys()
	{
		global $zengl_cms_tpl_dir;
		global $rvar_replyID;
		global $rvar_articleID;
		$sql = &$this->sql;
		$tablename = "{$sql->tables_prefix}CommentReply";
		$article_array = explode(',', $rvar_articleID);
		$reply_array = explode(',', $rvar_replyID);
		if(!$article_array || !$reply_array || count($article_array) == 0 || count($reply_array)==0)
			new error('回复删除失败','无效的ID值序列',true,true);
		
		$article_array = array_unique($article_array);
		$reply_array = array_unique($reply_array);
		$delstr_reply = "DELETE FROM $tablename";
		foreach ($reply_array as $key => $val)
		{
			if(is_numeric($val))
			{
				if($key == 0)
				{
					$delstr_reply .= " WHERE reply_ID=" .$val;
				}
				else
				{
					$delstr_reply .= " OR reply_ID=" .$val;
				}
			}
			else
				new error('回复删除失败','ID值序列必须为数字',true,true);
		}
		$sql->query($delstr_reply);
		if($sql->err== SQL_SUCCESS)
		{
			foreach ($article_array as $val)
			{
				if(is_numeric($val))
				{
					$filecache = $zengl_cms_tpl_dir.'show_comment_cache'.$val.'.php';
					touch($filecache);
				}
				else
					new error('回复删除失败','ID值序列必须为数字',true,true);
			}
			new success('ZENGLCMS回复删除情况：', '相关的回复都删除成功！', true,true);
		}
	}
	function getsome()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/getsome_comments_class.php'))
			return include $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/getsome_comments_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			return include $zengl_theme_tpl_class;
		}
		else
			die('tpl class file getsome_comments_class.php does not exist!');
	}
	/**
	 * 在网页前端，显示评论列表
	 */
	function get_list()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/list_comments_class.php'))
			return include $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/list_comments_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			return include $zengl_theme_tpl_class;
		}
		else
			die('tpl class file list_comments_class.php does not exist!');
	}
}
?>