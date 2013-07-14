<?php 
global $rvar_sec_ID;
global $rvar_is_recur;
global $zengl_cms_rootdir;
$section = new section(true,true);
$sql = &$this->sql;
if($this->check_login())
{
	if($this->session->userID != 1)
		$permis_sql = "userID = {$this->session->userID}";
	else
		$permis_sql = "";
}
else
	new error('禁止访问','用户权限无法管理文章,如果是游客请先登录！',true,true);
if($this->all == null)
{
	$section->getall();
	$this->all =&$section->all;
}
if($rvar_sec_ID!='' && $rvar_sec_ID!='0' )
{
	if($rvar_is_recur!='yes')
	{
		if($permis_sql != '')
			$sql->query("select *  from {$sql->tables_prefix}articles where $permis_sql  and sec_ID=$rvar_sec_ID " .
			" order by time desc");
		else
			$sql->query("select *  from {$sql->tables_prefix}articles where sec_ID=$rvar_sec_ID " .
			" order by time desc");
	}
	else
	{
		if($permis_sql != '')
			$this->sqlstr = "select *  from $sql->tables_prefix" . "articles where $permis_sql and (";
		else
			$this->sqlstr = "select *  from $sql->tables_prefix" . "articles where (";
		$this->recur_sec_sqlstr($rvar_sec_ID);
		$this->sqlstr = substr($this->sqlstr, 0, -3);
		$this->sqlstr .= ")order by time desc";
		$sql->query($this->sqlstr);
	}
}
else
{
	if($permis_sql != '')
		$sql->query("select * from $sql->tables_prefix" . "articles where $permis_sql order by time desc");
	else
		$sql->query("select * from $sql->tables_prefix" . "articles order by time desc");
}
$sql->get_num();
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/admin_list_articles.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/admin_list_articles_cache.php';

if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');

if($rvar_is_recur=='yes')
	$ischecked = 'checked';
else
	$ischecked = '';

$title = '编辑,删除文章';

if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	$loc = '"'.$zengl_cms_rootdir.'add_edit_del_show_list_article.php?hidden=show' .
			'&articleID=<?php echo $sql->row["articleID"] ?>"';
	$edit = '"'.$zengl_cms_rootdir.'add_edit_del_show_list_article.php?hidden=edit' .
			'&articleID=<?php echo $sql->row["articleID"] ?>"';
	$del = '"'.$zengl_cms_rootdir.'add_edit_del_show_list_article.php?hidden=del' .
			'&articleID=<?php echo $sql->row["articleID"] ?>"';
	$pageNumStr = '<?php echo ceil($sql->rownum/$this->page_size); ?>';
	$sec_query = '<?php $sql->query($sql->sql_desc . " limit 0,{$this->page_size}"); ?>';

	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
	$tpl->setVar('sec_PageNum', $pageNumStr);
	$tpl->setVar('sec_query', $sec_query);
	$tpl->setVar('sec_DisplaySize', '<?php echo $this->display_size; ?>');
	$tpl->setVar('title', '<?php echo $title ?>');
	$tpl->setVar('sections', '<?php $section->list_sections($rvar_sec_ID);?>');
	$tpl->setVar('ischecked', '<?php echo $ischecked ?>');
	$tpl->setVar('sec_ID', '<?php echo $rvar_sec_ID; ?>');
	$tpl->setVar('for_articles', '<?php while ($sql->parse_results()) { ?>');
	$tpl->setVar('article_id', '<?php echo $sql->row["articleID"]; ?>');
	$tpl->setVar('article_loc', $loc);
	$tpl->setVar('article_title', '<?php echo subUTF8($sql->row["title"],32); ?>');
	$tpl->setVar('article_tip', '<?php echo $sql->row["title"]; ?>');
	$tpl->setVar('sec_name', '<?php echo $this->all[$sql->row["sec_ID"]]["sec_name"]; ?>');
	$tpl->setVar('article_time','<?php echo date("Y/n/j G:i:s",$sql->row["time"]) ?>');
	$tpl->setVar('article_html_status', '
			if(file_exists($this->GetSecDirFullPath($sql->row["sec_ID"]) . "/article-". $sql->row["articleID"] .".html"))
				echo "<span STYLE=\"color: #00ff00\">已静态化</span>";
			else
				echo "<span STYLE=\"color: #ff0000\">未静态化</span>";
			',true);
	$tpl->setVar('article_edit', $edit);
	$tpl->setVar('article_del', $del);
	$tpl->setVar('endfor', '<?php } ?>');
	$tpl->setVar('cms_root_dir', '<?php echo $zengl_cms_rootdir; ?>');
	$tpl->cache();
	include $filecache;
}
?>