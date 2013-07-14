<?php 
global $zengl_cms_rootdir;
$permis = &$this->permis;
$title = "展开和折叠侧边栏";
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/admin_middle.tpl';
//$filecache = convertToCache($filetpl);
$filecache = $zengl_cms_tpl_dir . 'cache/admin_middle_cache.php';

if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');

if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) )  &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme;',true);
	$tpl->setVar('title', 'echo $title;',true);
	$tpl->cache();
	include $filecache;
}
?>