<?php 
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/comment_success.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/comment_success_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	return;
}
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
$tpl->setVar('title', 'echo $title',true);
$tpl->setVar('content', 'echo $content',true);
$tpl->setVar('jmp_locs', 'echo $jmploc',true);
$tpl->cache();
include $tpl->filecache;
?>