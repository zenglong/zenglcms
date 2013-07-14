<?php 
global $zengl_cms_rootdir;
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/header.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/header_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
		(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	return;
}
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('cms_root_dir', 'echo $zengl_cms_rootdir;',true);
$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
$tpl->cache();
include $filecache;
?>