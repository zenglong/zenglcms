<?php 
global $zengl_cms_rootdir;
global $rvar_articleID;
global $rvar_commentID;
global $rvar_type;
if(!(isset($rvar_commentID) && is_numeric($rvar_commentID)) ||
		!(isset($rvar_articleID) && is_numeric($rvar_articleID)))
	new error('无法发表评论','无效的评论ID！',true,true);
help_setcookie_pre_url();
$title = 'ZENGLCMS评论回复：';
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/reply_comment.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/reply_comment_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if(isset($rvar_type) && $rvar_type=='admin')
{
	$action_loc = '"'.$zengl_cms_rootdir.'comment_operate.php?action=reply&type=admin&redirect=other"';
	$onload_loc = '';
}
else
{
	$action_loc = '"'.$zengl_cms_rootdir.'comment_operate.php?action=reply"';
	$onload_loc = ' onload=\'IFrameResize();\'';
}
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	return;
}
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('title', 'echo $title',true);
$tpl->setVar('action', 'echo $action_loc',true);
$tpl->setVar('onload', 'echo $onload_loc',true);
$tpl->setVar('commentID', 'echo $rvar_commentID',true);
$tpl->setVar('articleID', 'echo $rvar_articleID',true);
$tpl->cache();
include $filecache;
?>