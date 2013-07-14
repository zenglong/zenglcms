<?php 
global $rvar_articleID;
global $zengl_cms_rootdir;
$this->getallsections();
$sql = &$this->sql;
$sql->query("select * from $this->tablename where articleID = " . $rvar_articleID);
$sql->parse_results();
$title = 'ZENGLCMS文章编辑：';
$tags = new tags(new sql('utf8'));
$tag_array = $tags->find($rvar_articleID);
$tagstr = implode(',', array_keys($tag_array));
$magic_quote = get_magic_quotes_gpc();
if(is_numeric($sql->row['scansCount']) && $sql->row['scansCount']!='')
	$scansCount = $sql->row['scansCount'];
else
	$scansCount = 0;
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/write_article.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/write_article_edit_cache.php';
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
$tpl->setVar('title', 'echo $title',true);
$tpl->setVar('action', '"' . $zengl_cms_rootdir . 'add_edit_del_show_list_article.php"');
$tpl->setVar('hidden', '"edit"');
$tpl->setVar('articleID','echo $rvar_articleID',true);
$tpl->setVar('article_title', 'echo $sql->row[\'title\']',true);
$tpl->setVar('article_author', 'echo $sql->row[\'author\']',true);
$tpl->setVar('article_smimgpath', 'echo $sql->row[\'smimgpath\']',true);
$tpl->setVar('smimglist', $zengl_cms_rootdir . 'list_upload_archive.php?action=listsmimg');
$tpl->setVar('article_tags', 'echo $tagstr',true);
$tpl->setVar('article_descript', 'echo $sql->row[\'descript\']',true);
$tpl->setVar('options', '$this->recursive_show_options(1,4,$sql->row["sec_ID"]);',true);
$tpl->setVar('article_content', '
		if(empty($magic_quote))
			echo $sql->row[\'content\'];
		else
			echo stripslashes($sql->row[\'content\']);',true);
$tpl->setVar('article_scans', 'echo $scansCount',true);
$tpl->cache();
include $filecache;
?>