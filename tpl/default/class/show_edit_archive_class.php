<?php 
global $rvar_archiveID;
global $zengl_cms_rootdir;
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/edit_archive.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/edit_archive_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
$title = '编辑附件：';
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	return;
}
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
$tpl->setVar('title', 'echo $title;',true);
$tpl->setVar('action', '"'.$zengl_cms_rootdir.'list_upload_archive.php?action=edit'.
		'&archiveID=<?php echo $rvar_archiveID; ?>"');
$tpl->cache();
include $tpl->filecache;
?>