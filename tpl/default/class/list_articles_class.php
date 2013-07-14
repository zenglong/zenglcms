<?php
global $rvar_sec_ID;
global $rvar_is_recur;
global $rvar_tag;
global $rvar_flagUsePHP;
global $zengl_cms_rootdir;
global $listshow_article_smimg_default;
global $adminHtml_genhtml;
global $zengl_cms_use_html;

if($adminHtml_genhtml=='yes' || $rvar_flagUsePHP == 'yes' || $rvar_tag != '')
	;
else if($zengl_cms_use_html == 'yes')
{
	$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
	$filename = $sec_dirpath . '/';
	if(file_exists($filename))
		exit('<script type="text/javascript"> location.href="' . $filename .'";</script>');
}
$title = "ZENGLCMS显示文章列表";
$section = new section(true,true);
$sql = &$this->sql;
if($this->all == null)
{
	$section->getall();
	$this->all =&$section->all;
}
if($rvar_tag != '')
{
	$tags = new tags(&$sql);
	$tagstr = "<div id='tags'>包含标签 <font color='red'>" . $tags->query() . '</font>'.
			" <a class='more_tags' href='{$zengl_cms_rootdir}tags_operate.php?action=getall'>" .
			"(more tags...)</a> 的所有文章列表如下：</div>";
}
else if($rvar_sec_ID!='' && $rvar_sec_ID!='0' )
{
	if($rvar_is_recur!='yes')
		$sql->query("select *  from {$sql->tables_prefix}articles where sec_ID=$rvar_sec_ID order by articleID desc");
	else
	{
		$this->sqlstr = "select *  from $sql->tables_prefix" . "articles where ";
		$this->recur_sec_sqlstr($rvar_sec_ID);
		$this->sqlstr = substr($this->sqlstr, 0, -3);
		//$this->sqlstr .= "order by time desc";
		$this->sqlstr .= "order by articleID desc";
		$sql->query($this->sqlstr);
	}
}
else
	$sql->query("select * from $sql->tables_prefix" . "articles order by articleID desc");
$sec_allrownum = $sql->get_num();
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/list_articles.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/list_articles_cache.php';

if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');

if($this->check_login())
	$username = $this->session->username;
else
	$username = '游客';

if($username == '游客')
{
	$user_op = '<a href="'.$zengl_cms_rootdir.'login_out_register.php?action=login">登录</a>&nbsp;&nbsp;' .
			'<a href="'.$zengl_cms_rootdir.'login_out_register.php?action=register">注册</a>';
}
else
	$user_op = '<a href="'.$zengl_cms_rootdir.'admin.php?arg=show">控制面板</a>&nbsp;&nbsp;' .
	'<a href="'.$zengl_cms_rootdir.'login_out_register.php?action=logout">注销</a>&nbsp;&nbsp;' .
	'<a href="'.$zengl_cms_rootdir.'login_out_register.php?action=login">重新登录</a>&nbsp;&nbsp;' .
	'<a href="'.$zengl_cms_rootdir.'login_out_register.php?action=register">注册</a>&nbsp;&nbsp;';

if($rvar_is_recur=='yes')
	$ischecked = 'checked';
else
	$ischecked = '';

if($adminHtml_genhtml == 'yes')
{
	$flaghtml = true;
	ob_start();
}
else
	$flaghtml = false;

$title = $this->all[$rvar_sec_ID]["sec_name"]; //add by zl

if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{ 
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
	$pageNumStr = '<?php echo ceil($sql->rownum/$this->page_size); ?>';
	$sec_query = '<?php $sql->query($sql->sql_desc . " limit 0,{$this->page_size}"); ?>';

	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('theme', '
			if(!$flaghtml)
				echo $zengl_cms_tpl_dir . $zengl_theme;
			else
				echo $zengl_cms_rootdir . $zengl_cms_tpl_dir . $zengl_theme;
			',true);
	$tpl->setVar('sec_array', 'echo js_array($this->all, \'sec_array\');',true);
	$tpl->setVar('ishtml','echo $adminHtml_genhtml',true);
	$tpl->setVar('sec_dirpath','
			$secId = $rvar_sec_ID;
			if($this->all[$secId]["sec_dirpath"] != "")
				$sec_dirpath = "html/" . $this->all[$secId]["sec_dirpath"] . "/" .
								 $this->all[$secId]["sec_dirname"];
			else
				$sec_dirpath = "html/" . $this->all[$secId]["sec_dirname"];
			echo $sec_dirpath;
			',true);
	$tpl->setVar('sec_PageNum', $pageNumStr);
	$tpl->setVar('sec_query', $sec_query);
	$tpl->setVar('sec_DisplaySize', 'echo $this->display_size;',true);
	$tpl->setVar('title', 'echo $title',true);
	$tpl->setVar('header', '$this->header();',true);
	$tpl->setVar('username', '
			if(!$flaghtml)
				echo $username;
			else
				echo "";
			',true);
	$tpl->setVar('secmenu', '<?php 	$section->recur_show_secs(1,1,');
	$tpl->setVar('secmenu_end', '); ?>');
	$tpl->setVar('sections', '$section->list_sections($rvar_sec_ID);',true);
	$tpl->setVar('ischecked', 'echo $ischecked',true);
	$tpl->setVar('sec_ID', 'echo $rvar_sec_ID;',true);
	$tpl->setVar('user_operate', '
			if(!$flaghtml)
				echo $user_op;
			else
				echo "";
			',true);
	$tpl->setVar('tagid', 'echo $rvar_tag;',true);
	$tpl->setVar('tags', 'echo $tagstr;',true);
	$tpl->setVar('for_articles', 'while ($sql->parse_results()) { ',true);
	$tpl->setVar('article_loc', $loc);
	$tpl->setVar('article_title', 'echo subUTF8($sql->row["title"],32);',true);
	//$tpl->setVar('article_tip', 'echo $sql->row["title"]',true);
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
	$tpl->setVar('sec_name', 'echo $this->all[$sql->row["sec_ID"]]["sec_name"];',true);
	$tpl->setVar('article_time','echo date("Y/n/j G:i:s",$sql->row["time"])',true);
	$tpl->setVar('article_edit', $edit);
	$tpl->setVar('article_del', $del);
	$tpl->setVar('endfor', '}',true);
	$tpl->setVar('cms_root_dir', 'echo $zengl_cms_rootdir;',true);
	$tpl->setVar('footer', '$this->footer();',true);
	$tpl->cache();
	include $filecache;
}
if($flaghtml)
{
	$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
	mkdirs($sec_dirpath);
	file_put_contents($sec_dirpath . '/index.html',ob_get_contents());
	ob_end_clean();
	echo "生成栏目{$this->all[$rvar_sec_ID]['sec_name']} $sec_dirpath/index.html 成功<br/>";
	flush_buffers();
	return $sec_allrownum;
}
?>