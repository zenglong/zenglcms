<?php 
global $zengl_cms_tpl_dir;
global $zengl_cms_rootdir;
global $rvar_sec_page;
global $rvar_query_word;
$sql = $this->sql;
$tablename = $sql->tables_prefix . 'tags';
$startPage = ($rvar_sec_page - 1) * $this->page_size;
if($rvar_query_word != '')
{
	$tmpword = "'%" . $sql->escape_str($rvar_query_word) . "%'";
	$sql->query("select * from $tablename where lower(tag_name) like $tmpword order by count desc" .
			" limit $startPage,{$this->page_size}");
}
else
	$sql->query("select * from $tablename order by count desc limit $startPage,{$this->page_size}");
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . "/tag_query_ajax.tpl";
$filecache = $zengl_cms_tpl_dir . 'cache/tag_query_ajax_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	return;
}
$loc = "'" . $zengl_cms_rootdir . 'add_edit_del_show_list_article.php?hidden=list'.
		'&tag=<?php echo $sql->row["tag_ID"] ?>' . "'";
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('for_tags', '<?php while ($sql->parse_results()) { ?>');
$tpl->setVar('tag_loc', $loc);
$tpl->setVar('tag_name', '<?php echo $sql->row["tag_name"]; ?>');
$tpl->setVar('tag_count', '<?php echo $sql->row["count"]; ?>');
$tpl->setVar('endfor', '<?php } ?>');
$tpl->cache();
include $filecache;
?>