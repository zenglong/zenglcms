<?php
$title = "ZENGLCMS网站安装第三步：";
$filetpl = 'install/show_setconfig.tpl';
$filecache = 'install/show_setconfig_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
else if(!is_writable(dirname($filecache)))
	die(dirname($filecache).' directory is not writable!');

$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('title', 'echo $title',true);
$tpl->setVar('action', 'echo \'install.php?action=setconfig\';',true);
$tpl->cache();
include $filecache;
?>