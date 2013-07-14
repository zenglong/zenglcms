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
i_need_func('err,permis,conf,tpl',__FILE__,true); //最后的参数为true时，file_cache中会生成调试版本的缓存。
//i_need_func('err,permis,conf,tpl',__FILE__);
include $my_need_files;

import_request_variables("gpc","rvar_");
header( "Content-Type:   text/html;   charset=UTF-8 ");
$permis = new permis(new session(true));
if(!$permis->check_perm(CMS_UPDATE))
{
	new error('禁止访问','用户权限无法升级CMS系统！',true,true);
}

if(!isset($rvar_user) || !isset($rvar_pass))
{
	if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/cms_update_class.php'))
		include_once $zengl_theme_tpl_class;
	else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
			'/class/cms_update_class.php'))
	{
		$zengl_theme = $zengl_old_theme;
		include_once $zengl_theme_tpl_class;
	}
	else
		die('tpl class file cms_update_class.php does not exist!');
}
else if($rvar_user != $cms_update_user || $rvar_pass != $cms_update_pass)
{
	new error('CMS升级失败！','CMS升级所需的用户名密码错误,请在config中配置正确!',true,true);
}

if($rvar_action == 'update')
{
	if(!file_exists('UpdateCms/update.php'))
		new error('CMS升级失败！','CMS升级所需的update.php不存在！',true,true);
	unlink('./update.php');
	copy('UpdateCms/update.php','./update.php');
	echo "<script type=\"text/javascript\">location.href = '{$zengl_cms_rootdir}update.php?" .
		"action=update&user=" . $rvar_user . "&pass=" . $rvar_pass .
		"'</script>";
}
else
	new error('CMS升级失败！','无效的请求参数！',true,true);
?>