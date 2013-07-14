<?php 
global $zengl_cms_rootdir;
$section = new section(true,true);
if($section->all == null)
	$section->getall();
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/gen_html_forSec.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/gen_html_forSec_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
		(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
	$tpl->setVar('title', '生成所选栏目的静态页面：');
	$tpl->setVar('action_loc', $zengl_cms_rootdir . 'add_edit_del_show_list_article.php');
	$tpl->setVar('options', '$section->recursive_show_options(1,4);',true);
	$tpl->cache();
	include $tpl->filecache;
}
?>