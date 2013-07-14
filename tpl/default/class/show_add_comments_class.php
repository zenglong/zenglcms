<?php 
global $zengl_cms_rootdir;
global $rvar_articleID;
if(!(isset($rvar_articleID) && is_numeric($rvar_articleID)))
	new error('无法发表评论','无效的文章ID！',true,true);
$title = 'ZENGLCMS评论发表：';
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/add_comment.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/add_comment_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	return;
}
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('title', 'echo $title',true);
$tpl->setVar('action', '"'.$zengl_cms_rootdir.'comment_operate.php?action=add"');
$tpl->setVar('articleID', 'echo $rvar_articleID',true);
$tpl->cache();
include $tpl->filecache;
?>