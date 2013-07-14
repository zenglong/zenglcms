<?php 
global $zengl_cms_rootdir;
if($this->sql == null)
	$this->sql = new sql('utf8');
$this->getallsections();
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/write_article.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/write_article_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
$title = 'ZENGLCMS文章发表：';
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
	$tpl->setVar('title', $title);
	$tpl->setVar('action', '"'.$zengl_cms_rootdir.'add_edit_del_show_list_article.php"');
	$tpl->setVar('hidden', '"add"');
	$tpl->setVar('articleID',0);
	$tpl->setVar('article_title', '');
	$tpl->setVar('article_author', '');
	$tpl->setVar('article_smimgpath', '');
	$tpl->setVar('smimglist', $zengl_cms_rootdir . 'list_upload_archive.php?action=listsmimg');
	$tpl->setVar('article_tags', '');
	$tpl->setVar('article_descript', '');
	$tpl->setVar('options', '$this->recursive_show_options(1,4);',true);
	$tpl->setVar('article_content', '');
	$tpl->setVar('article_scans', '0');
	$tpl->setVar('cms_root_dir', 'echo $zengl_cms_rootdir;',true);
	$tpl->template_parse();
	$tpl->cache();
	include $tpl->filecache;
}
?>