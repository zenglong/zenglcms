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
i_need_func('permis,err,sess,sql',__FILE__,true); //最后的参数为true时，file_cache中会生成调试版本的缓存。
//i_need_func('permis,err,sess,sql',__FILE__);
include $my_need_files;

import_request_variables("gpc","rvar_");
header( "Content-Type:   text/html;   charset=UTF-8 ");
$permis = new permis(new session(true), new sql('utf8'));

if($rvar_action == 'update_permis')
{
	if(!$permis->check_perm(BAK_RESTORE_DB))
		new error('更新权限失败！','用户权限无法执行该操作！',true,true);
	$permis->update_permis();
}
else
	new error('权限操作失败！','无效的请求参数',true,true);
?>