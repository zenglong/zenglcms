<?php 
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/footer.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/footer_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
		(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	return;
}
$tpl = new tpl($filetpl, $filecache);
$tpl->cache();
include $filecache;
?>