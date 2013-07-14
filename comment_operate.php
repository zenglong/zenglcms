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
i_need_func('comment,err,cache',__FILE__,true); //最后的参数为true时，file_cache中会生成调试版本的缓存。
//i_need_func('comment,err,cache',__FILE__);
include $my_need_files;

import_request_variables("gpc","rvar_");
$comment = new comment(new sql('utf8'));
if($rvar_action == 'add')
{
	if(!$comment->check_perm(COMMENT_ADD))
		new error('禁止访问','用户权限无法发表评论！',true,true);
	if(!$comment->check_param('add'))
		$comment->show_add();
	else
		$comment->add();
}
else if($rvar_action == 'reply')
{
	if(!$comment->check_perm(COMMENT_ADD))
		new error('禁止访问','用户权限无法回复评论！',true,true);
	if(!$comment->check_param('reply'))
		$comment->show_reply();
	else
		$comment->reply();
}
else if($rvar_action == 'show')
{
	if(!$comment->check_perm(COMMENT_SHOW))
		new error('禁止访问','用户权限无法查看评论！',true,true);
	if(!$comment->check_param('show'))
		new error('评论查看失败','无效的请求参数！',true,true);
	else
		$comment->show();
}
else if($rvar_action == 'admin_comment_list')
{
	if(!$comment->check_perm(COMMENT_ADMIN))
		new error('禁止访问','用户权限无法管理评论！',true,true);
	$comment->admin_comment_list();
}
else if($rvar_action == 'del_comment')
{
	if(!$comment->check_perm(COMMENT_DEL))
		new error('禁止访问','用户权限无法删除评论！',true,true);
	if(!$comment->check_param('del_comment'))
		new error('评论删除失败','无效的请求参数！',true,true);
	else
		$comment->del_comment();
}
else if($rvar_action == 'multidel_comments')
{
	if(!$comment->check_perm(COMMENT_DEL))
		new error('禁止访问','用户权限无法删除评论！',true,true);
	if(!$comment->check_param('multidel_comments'))
		new error('评论删除失败','无效的请求参数！',true,true);
	else 
		$comment->multidel_comments();
}
else if($rvar_action == 'clearCache')
{
	if(!$comment->check_perm(CLEAR_ALL_CACHE))
		new error('禁止访问','用户权限无法删除评论缓存！',true,true);
	if(!$comment->check_param('clearCache'))
		new error('评论缓存删除失败','无效的请求参数！',true,true);
	header( "Content-Type:   text/html;   charset=UTF-8 ");
	$cache = new cache();
	$cache->clear_comment_cache();
}
else if($rvar_action == 'admin_set_comment_num')
{
	if(!$comment->check_perm(SET_CONFIG))
		new error('禁止访问','用户无权配置系统数据！',true,true);
	if(!$comment->check_param('admin_set_comment_num'))
		new error('用户配置失败','无效的请求参数！',true,true);
	set_config('zengl_admin_comment_listnum', $rvar_commentNum);
	header( "Content-Type:   text/html;   charset=UTF-8 ");
	echo "<script type=\"text/javascript\">location.href = '{$zengl_cms_rootdir}comment_operate.php?" .
		"action=admin_comment_list" . "'</script>";
}
else if($rvar_action == 'admin_reply_list')
{
	if(!$comment->check_perm(COMMENT_ADMIN))
		new error('禁止访问','用户权限无法管理回复！',true,true);
	$comment->admin_reply_list();
}
else if($rvar_action == 'del_reply')
{
	if(!$comment->check_perm(COMMENT_DEL))
		new error('禁止访问','用户权限无法删除评论回复！',true,true);
	if(!$comment->check_param('del_reply'))
		new error('回复删除失败','无效的请求参数！',true,true);
	else
		$comment->del_reply();
}
else if($rvar_action == 'multidel_replys')
{
	if(!$comment->check_perm(COMMENT_DEL))
		new error('禁止访问','用户权限无法删除回复！',true,true);
	if(!$comment->check_param('multidel_replys'))
		new error('评论删除失败','无效的请求参数！',true,true);
	else
		$comment->multidel_replys();
}
else if($rvar_action == 'admin_set_reply_num')
{
	if(!$comment->check_perm(SET_CONFIG))
		new error('禁止访问','用户无权配置系统数据！',true,true);
	if(!$comment->check_param('admin_set_reply_num'))
		new error('用户配置失败','无效的请求参数！',true,true);
	set_config('zengl_admin_reply_listnum', $rvar_replyNum);
	header( "Content-Type:   text/html;   charset=UTF-8 ");
	echo "<script type=\"text/javascript\">location.href = '{$zengl_cms_rootdir}comment_operate.php?" .
			"action=admin_reply_list" . "'</script>";
}
else if($rvar_action == 'getsome')
	$comment->getsome();
else
	new error('评论操作失败','无效的请求参数！',true,true);
?>