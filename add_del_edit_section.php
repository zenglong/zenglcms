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
i_need_func('sec,err',__FILE__,true); //最后的参数为true时，file_cache中会生成调试版本的缓存。
//i_need_func('sec,err',__FILE__);
include $my_need_files;

import_request_variables("gpc","rvar_");
$section = new section(true,true);
if($rvar_action == 'add')
{
	if(!$section->check_perm(SEC_ADD))
		new error('禁止访问','用户权限无法添加栏目,如果是游客请先登录！',true,true);
	if(!$section->check_param())
		$section->show_add_section();
	else 
		$section->add();
}
else if($rvar_action == 'del')
{
	if(!$section->check_perm(SEC_DEL))
		new error('禁止访问','用户权限无法执行删除栏目的操作,如果是游客请先登录！',true,true);
	if(!$section->check_param())
		$section->show_del_section();
	else
		$section->del();
}
else if($rvar_action == 'edit')
{
	if(!$section->check_perm(SEC_EDIT))
		new error('禁止访问','用户权限无法执行编辑、移动栏目的操作,如果是游客请先登录！',true,true);
	if(!$section->check_param())
		$section->show_del_section();
	else
		$section->edit();
}
else if($rvar_action == 'setmenu')
{
	if(!$section->check_perm(SEC_EDIT))
		new error('禁止访问','用户权限无法执行栏目相关的操作,如果是游客请先登录！',true,true);
	if(!$section->check_param())
		$section->show_setmenu();
	else
		$section->real_setmenu();
}
else
	new error('参数错误！','无效的执行参数！',true,true);
?>