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
i_need_func('article,err',__FILE__,true); //最后的参数为true时，file_cache中会生成调试版本的缓存。
//i_need_func('article,err',__FILE__);
include $my_need_files;

import_request_variables("gpc","rvar_");
$article = new article(true,true);
if($rvar_hidden == 'add')
{
	if(!$article->check_perm(ARTICLE_ADD))
		new error('禁止访问','用户权限无法添加文章,如果是游客请先登录！',true,true);
	if(!$article->check_param($rvar_hidden))
		$article->show_add_article();
	else
		$article->add_article();
}
else if($rvar_hidden == 'edit')
{
	if(!$article->check_perm(ARTICLE_EDIT))
		new error('禁止访问','用户权限无法编辑文章,如果是游客请先登录！',true,true);
	$rvar_redirect = 'other';
	if(!$article->check_param($rvar_hidden))
	{
		help_setcookie_pre_url();
		$article->show_edit_article();
	}
	else
		$article->edit_article();
}
else if($rvar_hidden == 'del')
{
	if(!$article->check_perm(ARTICLE_DEL))
		new error('禁止访问','用户权限无法删除文章,如果是游客请先登录！',true,true);
	if(!$article->check_param($rvar_hidden))
		new error('无法执行','无效的提交参数！',true,true);
	$article->del_article();
}
else if($rvar_hidden == 'show')
	$article->show_article();
else if($rvar_hidden == 'getScansCount')
	$article->getScansCount();
else if($rvar_hidden == 'list')
	$article->list_articles();
else if($rvar_hidden == 'listajax')
{
	//sleep(5); 测试用的！
	$article->list_articles_ajax();
}
else if($rvar_hidden == 'admin')
{
	if(!$article->check_perm(ARTICLE_ADMIN))
		new error('禁止访问','用户权限无法管理文章,如果是游客请先登录！',true,true);
	if($rvar_action == 'list')
		$article->admin_list();
	else if($rvar_action == 'listajax')
		$article->admin_list_ajax();
	else if($rvar_action == 'multidel' || $rvar_action == 'multimove')
		$article->admin_multi_del_move();
	else if($rvar_action == 'multiHTML')
	{
		if(!$article->check_perm(ADMIN_HTML))
			new error('禁止访问','用户权限无法执行生成静态页面的操作,如果是游客请先登录！',true,true);
		$article->admin_multi_html();
	}
	else
		new error('文章操作失败','无效的请求参数！',true,true);
}
else if($rvar_hidden == 'onekeyhtml')
{
	if(!$article->check_perm(ADMIN_HTML))
		new error('禁止访问','用户权限无法执行生成静态页面的操作,如果是游客请先登录！',true,true);
	$article->OneKeyHtml();
}
else if($rvar_hidden == 'onekey_rm_html')
{
	if(!$article->check_perm(ADMIN_HTML))
		new error('禁止访问','用户权限无法执行删除静态页面的操作,如果是游客请先登录！',true,true);
	$article->OneKeyRM_HTML();
}
else if($rvar_hidden == 'gensechtml')
{
	if(!$article->check_perm(ADMIN_HTML))
		new error('禁止访问','用户权限无法执行生成静态页面的操作,如果是游客请先登录！',true,true);
	if(!$article->check_param($rvar_hidden))
		$article->ShowGenHTMLforSec();
	else
		$article->GenHTMLforSec();
}
else
	new error('文章操作失败','无效的请求参数！',true,true);
?>