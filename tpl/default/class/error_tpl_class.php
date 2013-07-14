<?php 
global $rvar_redirect;
global $zengl_cms_rootdir;
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . "/error.tpl";
$filecache = $zengl_cms_tpl_dir . "cache/error_cache.php";
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	if($args[2]==true)
	{
		include $filecache;
		if($args[3]==true)
			exit();
	}
	return;
}
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
$tpl->setVar('title', 'echo $args[0]',true);
$tpl->setVar('content', 'echo $args[1]',true);
$tpl->setVar('cms_root_dir', 'echo $zengl_cms_rootdir;',true);
$tpl->setVar('jmp_locs', '
		if($rvar_redirect!="")
			$rvar_redirect .= "_err";
		get_jmp_locs($rvar_redirect);
		',true);
$tpl->cache();
if($args[2]==true)
{
	include $filecache;
	if($args[3]==true)
		exit();
}
?>