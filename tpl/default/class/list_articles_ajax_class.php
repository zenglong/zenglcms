<?php
global $zengl_cms_rootdir;
global $rvar_sec_ID;
global $rvar_sec_page;
global $rvar_tag;
global $rvar_is_recur;
global $rvar_flagUsePHP;
global $listshow_article_smimg_default;
global $adminHtml_genhtml;
global $zengl_cms_use_html;

if($adminHtml_genhtml=='yes' || $rvar_flagUsePHP == 'yes' || $rvar_tag != '')
	;
else if($zengl_cms_use_html == 'yes')
{
	$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
	$filename = $sec_dirpath . '/index-'. $rvar_sec_page . '.html';
	if(file_exists($filename))
		exit('<script type="text/javascript"> location.href="' . $filename .'";</script>');
}
$section = new section(true,true);
$sql = &$this->sql;
if(isset($rvar_sec_ID) || isset($rvar_tag))
	$startPage = ($rvar_sec_page - 1) * $this->page_size;
else
	$startPage = 0;
if($this->all == null)
{
	$section->getall();
	$this->all =&$section->all;
}
if($rvar_tag != '')
{
	$tags = new tags(&$sql);
	$tags->query($startPage,$this->page_size);
}
else if($rvar_sec_ID!='' && $rvar_sec_ID!='0' )
{
	if($rvar_is_recur!='yes')
		$sql->query("select * from {$sql->tables_prefix}articles where sec_ID=$rvar_sec_ID order by articleID desc
		limit $startPage,{$this->page_size}");
	else
	{
		$this->sqlstr = "select * from $sql->tables_prefix" . "articles where ";
		$this->recur_sec_sqlstr($rvar_sec_ID);
		$this->sqlstr = substr($this->sqlstr, 0, -3);
		$this->sqlstr .= "order by articleID desc limit $startPage,{$this->page_size}";
		$sql->query($this->sqlstr);
	}
}
else
	$sql->query("select * from $sql->tables_prefix" . "articles order by articleID desc limit $startPage,{$this->page_size}");
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/list_articles_ajax.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/list_articles_ajax_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if($adminHtml_genhtml == 'yes')
{
	$flaghtml = true;
	ob_start();
}
else
	$flaghtml = false;
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	/* $loc = '"'.$zengl_cms_rootdir.'add_edit_del_show_list_article.php?hidden=show' .
			'&articleID=<?php echo $sql->row["articleID"] ?>"'; */
	$loc = '
	<?php
		if(!$flaghtml)
			echo "\"". $zengl_cms_rootdir . "add_edit_del_show_list_article.php?hidden=show" .
				"&articleID=" . $sql->row["articleID"] . "\"";
		else
		{
			$secId = $sql->row["sec_ID"];
			if($this->all[$secId]["sec_dirpath"] != "")
				$sec_dirpath = "html/" . $this->all[$secId]["sec_dirpath"] . "/" .
								 $this->all[$secId]["sec_dirname"];
			else
				$sec_dirpath = "html/" . $this->all[$secId]["sec_dirname"];
			echo "\"". $zengl_cms_rootdir . $sec_dirpath . "/article-" . $sql->row["articleID"] .
				 ".html" . "\"";
		}
	?>';
	$edit = '"'.$zengl_cms_rootdir.'add_edit_del_show_list_article.php?hidden=edit' .
			'&articleID=<?php echo $sql->row["articleID"] ?>"';
	$del = '"'.$zengl_cms_rootdir.'add_edit_del_show_list_article.php?hidden=del' .
			'&articleID=<?php echo $sql->row["articleID"] ?>"';

	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('for_articles', '<?php while ($sql->parse_results()) { ?>');
	$tpl->setVar('article_loc', $loc);
	$tpl->setVar('article_title', '<?php echo subUTF8($sql->row["title"],32); ?>');
	$tpl->setVar('article_tip', '
			$smimgpath = $sql->row["smimgpath"];
			if($smimgpath == "")
				$smimgpath = $listshow_article_smimg_default;
			if($flaghtml)
				$smimgpath = $zengl_cms_rootdir . $smimgpath;
			$article_title = $sql->row["title"] . "<br/>" .
								"<img src=\"$smimgpath\">" . "<br/>" .
								$sql->row["descript"];
			echo htmlentities($article_title,ENT_QUOTES,"utf-8");',true);
	$tpl->setVar('sec_name', '<?php echo $this->all[$sql->row["sec_ID"]]["sec_name"]; ?>');
	$tpl->setVar('article_time','<?php echo date("Y/n/j G:i:s",$sql->row["time"]) ?>');
	$tpl->setVar('article_edit', $edit);
	$tpl->setVar('article_del', $del);
	$tpl->setVar('endfor', '<?php } ?>');
	$tpl->cache();
	include $filecache;
}
if($flaghtml)
{
	$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
	mkdirs($sec_dirpath);
	file_put_contents($sec_dirpath . '/index-' . $rvar_sec_page . '.html' ,ob_get_contents());
	ob_end_clean();
	echo "生成栏目{$this->all[$rvar_sec_ID]['sec_name']} $sec_dirpath/index-".
		 "$rvar_sec_page.html 成功<br/>";
	flush_buffers();
}
?>