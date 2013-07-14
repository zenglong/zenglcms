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
i_need_func('user,err,sess,auth,help,conf',__FILE__,true); //最后的参数为true时，file_cache中会生成调试版本的缓存。
//i_need_func('user,err,sess,auth,help,conf',__FILE__);
include $my_need_files;

$session = new session();
if($_SESSION['adminloginflag'] == 'ImFromAdminLogin')
	$zengl_cms_isneed_login = 'yes';

import_request_variables("gpc","rvar_");
if($rvar_redirect == '')
	$rvar_redirect = $rvar_action;
if($rvar_action=='register' && $rvar_username==null && $rvar_password==null)
{
	help_setcookie_pre_url();
	if($zengl_cms_isneed_register != 'yes')
		new error('无法注册！','管理员关闭注册！请与站长联系！',true,true);
	
	$user = new user();
	$user->register_display();
}
else if($rvar_action=='register' && $rvar_username!='' && $rvar_password!='')
{
	if($zengl_cms_isneed_register != 'yes')
		new error('无法注册！','管理员关闭注册！请与站长联系！',true,true);
	if(strlen($rvar_username) > 50)
		new error('注册失败！','用户名字符数大于50',true,true);
	//$session = new session();
	if($session->get_authnum()!=$rvar_authnum)
		new error('注册失败！','验证码不正确！',true,true);
	$user = new user();
	$user->register();
}
else if($rvar_action=='login' && $rvar_username==null && $rvar_password==null)
{
	help_setcookie_pre_url();
	if($zengl_cms_isneed_login != 'yes')
		new error('无法登录！','管理员关闭登录！请与站长联系！',true,true);
	
	$user = new user();
	$user->login_display();
}
else if($rvar_action=='login' && $rvar_username!='' && $rvar_password!='' && $rvar_authnum!='')
{
	if($zengl_cms_isneed_login != 'yes')
		new error('无法登录！','管理员关闭登录！请与站长联系！',true,true);
	if(strlen($rvar_username) > 50)
		new error('登录失败！','用户名字符数大于50',true,true);
	//$session = new session();
	if($session->get_authnum()!=$rvar_authnum)
		new error('登录失败！','验证码不正确！',true,true);
	$user = new user();
	$user->login();
}
else if($rvar_action=='logout')
{
	help_setcookie_pre_url();
	$user = new user();
	$user->logout();
}
else if($rvar_action=="authimg")
{
	$authimg = new authImg();
	$authimg->mkpng();
}
else 
{
	new error('禁止访问！','无效的参数请求！',true,true);
}
?>