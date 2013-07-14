<?php
global $zengl_cms_rootdir;
$title = "后台管理：";
$username = $this->session->username;
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/admin.tpl';
//$filecache = convertToCache($filetpl);
$filecache = $zengl_cms_tpl_dir . 'cache/admin_cache.php';

if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');

if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) )  &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
	$tpl->setVar('title', 'echo $title;',true);
	$tpl->setVar('username', 'echo $username;',true);
	$tpl->setVar('login_loc', $zengl_cms_rootdir . 'login_out_register.php?action=login');
	$tpl->setVar('logout_loc', $zengl_cms_rootdir . 'login_out_register.php?action=logout&redirect=admin_logout');
	$tpl->setVar('index_loc', $zengl_cms_rootdir . 'index.php');
	$tpl->setVar('left_loc', $zengl_cms_rootdir . 'admin.php?arg=showleft');
	$tpl->setVar('right_loc', $zengl_cms_rootdir . 'admin.php?arg=showright');
	$tpl->cache();
	include $filecache;
}
?>