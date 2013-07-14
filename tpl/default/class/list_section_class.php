<?php 
if($this->all == null)
	$this->getall();
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/list_sections.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/list_sections_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('options', '<?php $this->recursive_show_options(1,4,$select); ?>');
	$tpl->cache();
	include $tpl->filecache;
}
?>