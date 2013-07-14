<?php 
global $rvar_sec_ID;
global $rvar_is_recur;
global $rvar_sec_page;
global $zengl_cms_rootdir;
$section = new section(true,true);
$sql = &$this->sql;
if($this->check_login())
{
	if($this->session->userLevel != 1)
		$permis_sql = "userID = {$this->session->userID}";
	else
		$permis_sql = "";
}
else
	new error('禁止访问','用户权限无法管理文章,如果是游客请先登录！',true,true);
if(isset($rvar_sec_ID))
	$startPage = ($rvar_sec_page - 1) * $this->page_size;
else
	$startPage = 0;
if($this->all == null)
{
	$section->getall();
	$this->all =&$section->all;
}
if($rvar_sec_ID!='' && $rvar_sec_ID!='0' )
{
	if($rvar_is_recur!='yes')
	{
		if($permis_sql!='')
			$sql->query("select *  from {$sql->tables_prefix}articles where $permis_sql and sec_ID=$rvar_sec_ID " .
			" order by time desc limit $startPage,{$this->page_size}");
		else
			$sql->query("select *  from {$sql->tables_prefix}articles where sec_ID=$rvar_sec_ID " .
			" order by time desc limit $startPage,{$this->page_size}");
	}
	else
	{
		if($permis_sql!='')
			$this->sqlstr = "select *  from $sql->tables_prefix" . "articles where $permis_sql and ( ";
		else
			$this->sqlstr = "select *  from $sql->tables_prefix" . "articles where ( ";
		$this->recur_sec_sqlstr($rvar_sec_ID);
		$this->sqlstr = substr($this->sqlstr, 0, -3);
		$this->sqlstr .= ")order by time desc limit $startPage,{$this->page_size}";
		$sql->query($this->sqlstr);
	}
}
else
{
	if($permis_sql!='')
		$sql->query("select * from $sql->tables_prefix" . "articles where $permis_sql order by time desc limit $startPage,{$this->page_size}");
	else
		$sql->query("select * from $sql->tables_prefix" . "articles order by time desc limit $startPage,{$this->page_size}");
}
$sql->get_num();
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/admin_list_article_ajax.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/admin_list_article_ajax_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if($rvar_is_recur=='yes')
	$ischecked = 'checked';
else
	$ischecked = '';

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

	$tpl = new tpl($filetpl, $filecache);
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
	$tpl->cache();
	include $filecache;
}
?>