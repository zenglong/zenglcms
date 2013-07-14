<?php 
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/cms_update.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/cms_update_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
$title = "CMS系统升级";
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	exit();
}
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
$tpl->setVar('title', 'echo $title',true);
$tpl->setVar('update_loc', 'echo $rvar_action',true);
$tpl->setVar('cms_root_dir', '<?php echo $zengl_cms_rootdir; ?>');
$tpl->cache();
include $filecache;
exit();
?>