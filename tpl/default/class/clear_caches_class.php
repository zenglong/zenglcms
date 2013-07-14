<?php 
global $zengl_cms_filecache_dir;
if($tpl == '')
//$tpl = substr($zengl_cms_tpl_dir,0,-1);
	$tpl = $zengl_cms_tpl_dir . 'cache';
if($file_cache_dir == '')
	$file_cache_dir = substr($zengl_cms_filecache_dir,0,-1);
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/clear_filecache.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/clear_filecache_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
$title = '删除文件记录：';
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
		(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	return;
}
$tmp_tpl = new tpl($filetpl, $filecache);
$tmp_tpl->setVar('title', 'echo $title',true);
$tmp_tpl->setVar('del_tpl_cache', '
		if($tpl!=\'\')
		{
			$this->clear_tpl_cache($tpl);
			$this->clear_tpl_cache($tpl . "/comment_cache");
		}
		',true);
$tmp_tpl->setVar('del_file_cache','
		if($file_cache_dir!=\'\')
			$this->clear_file_cache($file_cache_dir);',true);
$tmp_tpl->setVar('del_sec_cache','
		if($del_sec_cache)
		{
			$tmpsec = new section();
			if(file_exists($tmpsec->array_file))
			{
				unlink($tmpsec->array_file);
				echo "删除栏目缓存文件$tmpsec->array_file";
			}
			else
				echo "";
		}',true);
$tmp_tpl->cache();
include $tmp_tpl->filecache;
?>