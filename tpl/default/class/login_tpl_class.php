<?php 
global $zengl_cms_rootdir;
global $rvar_redirect;
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/login.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/login_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	return;
}
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
$tpl->setVar('title', '用户登录界面');
$tpl->setVar('retloc', '<?php echo help_get_pre_url(); ?>');
$tpl->setVar('action', '"'.$zengl_cms_rootdir.'login_out_register.php?action=login&redirect='. $rvar_redirect .'"');
$tpl->setVar('auth_src', '"'.$zengl_cms_rootdir.'login_out_register.php?action=authimg&rnd="');
$tpl->cache();
include $filecache;
?>