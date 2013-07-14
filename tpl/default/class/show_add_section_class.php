<?php 
global $zengl_cms_rootdir;
$this->getall();
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/add_section.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/add_section_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
$title = '添加栏目';
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
		(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('title', 'echo $title',true);
	$tpl->setVar('action_loc', $zengl_cms_rootdir . 'add_del_edit_section.php?action=add');
	$tpl->setVar('options', '$this->recursive_show_options(1,4);',true);
	$tpl->cache();
	include $tpl->filecache;
}
?>