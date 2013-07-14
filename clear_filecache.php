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
i_need_func('permis,sess,cache,err', __FILE__,true); //最后的参数为true时，file_cache中会生成调试版本的缓存。
//i_need_func('permis,sess,cache,err', __FILE__);
include $my_need_files;

import_request_variables("gpc","rvar_");
$permis = new permis(new session(true));
if($rvar_clear='all' && $permis->check_perm(CLEAR_ALL_CACHE))
{
	$cache = new cache();
	$cache->clear_caches();
}
else 
{
	new error('无法删除','无效的参数或无权删除缓存文件。',true,true);
}
?>