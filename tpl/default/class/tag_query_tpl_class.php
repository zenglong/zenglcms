<?php 
global $zengl_cms_rootdir;
global $rvar_query_word;
$sql = $this->sql;
$tablename = $sql->tables_prefix . 'tags';
if($rvar_query_word != '')
{
	$tmpword = "'%" . $sql->escape_str($rvar_query_word) . "%'";
	$sql->query("select * from $tablename where lower(tag_name) like $tmpword order by count desc");
	$rvar_query_word = htmlspecialchars($rvar_query_word);
}
else
	$sql->query("select * from $tablename order by count desc");
$sql->get_num();
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . "/tag_query.tpl";
$filecache = $zengl_cms_tpl_dir . 'cache/tag_query_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');

$title = '文章标签：';
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	return;
}
$loc = "'" . $zengl_cms_rootdir . 'add_edit_del_show_list_article.php?hidden=list'.
		'&tag=<?php echo $sql->row["tag_ID"] ?>' . "'";
$pageNumStr = '<?php echo ceil($sql->rownum/$this->page_size); ?>';
$sec_query = '<?php $sql->query($sql->sql_desc . " limit 0,{$this->page_size}"); ?>';
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
$tpl->setVar('title','echo $title;',true);
$tpl->setVar('query_word','echo $rvar_query_word;',true);
$tpl->setVar('sec_PageNum', $pageNumStr);
$tpl->setVar('sec_query', $sec_query);
$tpl->setVar('sec_DisplaySize', 'echo $this->display_size;',true);
$tpl->setVar('rootdir', 'echo $zengl_cms_rootdir;',true);
$tpl->setVar('for_tags', 'while ($sql->parse_results()) {',true);
$tpl->setVar('tag_loc', $loc);
$tpl->setVar('tag_name', 'echo $sql->row["tag_name"];',true);
$tpl->setVar('tag_count', 'echo $sql->row["count"];',true);
$tpl->setVar('endfor', '}',true);
$tpl->cache();
include $filecache;
?>