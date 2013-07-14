<?php 
global $zengl_cms_rootdir;
$this->getall();
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/section_setmenu.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/section_setmenu_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
		(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
	$tpl->setVar('title', '调整栏目菜单位置:');
	$tpl->setVar('action_loc', $zengl_cms_rootdir . 'add_del_edit_section.php');
	$tpl->setVar('options', '<?php $this->recursive_show_options(1,4); ?>');
	$tpl->setVar('sec_array', 'echo js_array($this->all, \'sec_array\');',true);
	$tpl->cache();
	include $tpl->filecache;
}
?>