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
i_need_func('archive,err',__FILE__,true); //最后的参数为true时，file_cache中会生成调试版本的缓存。
//i_need_func('archive,err',__FILE__);
include $my_need_files;

import_request_variables("gpc","rvar_");
$archive = new archive(true,true);
if($rvar_action == 'upload')
{
	if(!$archive->check_perm(ARCHIVE_UPLOAD))
		new error('禁止访问','用户权限无法上传附件,如果是游客请先登录！',true,true);
	if(!$archive->check_param($rvar_action))
		new error('上传失败','上传参数无效(可能是上传文件名为空等)!',true,true);
	else 
		$archive->upload();
}
elseif ($rvar_action == 'list')
{
	if(!$archive->check_perm(ARCHIVE_LIST))
		new error('禁止访问','用户权限无法浏览附件,如果是游客请先登录！',true,true);
	if(!$archive->check_param($rvar_action))
		new error('上传浏览失败','上传浏览参数无效!',true,true);
	else 
		$archive->list_uploads();
}
else if($rvar_action == 'listsmimg')
{
	if(!$archive->check_perm(ARCHIVE_LIST))
		new error('禁止访问','用户权限无法浏览附件,如果是游客请先登录！',true,true);
	if(!$archive->check_param('list'))
		new error('上传浏览失败','上传浏览参数无效!',true,true);
	else
		$archive->list_uploads_smimg();
}
elseif ($rvar_action == 'del')
{
	if(!$archive->check_param($rvar_action))
		new error('无法执行删除','无效的提交参数！',true,true);
	if(!$archive->check_perm(ARCHIVE_DEL))
		new error('禁止访问','用户权限无法删除该附件,如果是游客请先登录！',true,true);
	else
		$archive->del_archive();
}
elseif ($rvar_action == 'edit')
{
	if(!$archive->check_perm(ARCHIVE_EDIT))
		new error('禁止访问','用户权限无法编辑该附件,如果是游客请先登录！',true,true);
	if(!$archive->check_param($rvar_action))
		$archive->show_edit_archive();
	else 
		$archive->edit_archive();
}
else
	new error('附件操作失败','无效的请求参数！',true,true);
?>