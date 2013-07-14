<?php 
$title = "ZENGLCMS网站安装第一步：";
$filetpl = 'install/licence.tpl';
$filecache = 'install/licence_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
else if(!is_writable(dirname($filecache)))
	die(dirname($filecache).' directory is not writable!');

$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('title', 'echo $title',true);
$tpl->setVar('next_loc', 'echo \'install.php?action=install2\'',true);
$tpl->cache();
include $filecache;
?>