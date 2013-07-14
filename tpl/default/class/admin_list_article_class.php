<?php 
global $rvar_sec_ID;
global $rvar_is_recur;
global $rvar_sec_page;
global $rvar_page_display_num; //每页显示数目
global $rvar_keyword; //查询关键词
global $rvar_query_type; //查询类型
global $rvar_list_order; //排序
global $zengl_cms_rootdir;

if(!isset($rvar_sec_ID)) $rvar_sec_ID = '';
if(!isset($rvar_is_recur)) $rvar_is_recur = '';
if(!isset($rvar_sec_page)) $rvar_sec_page = '';
if(!isset($rvar_page_display_num)) $rvar_page_display_num = '';
if(!isset($rvar_keyword)) $rvar_keyword = '';
if(!isset($rvar_query_type)) $rvar_query_type = '';
if(!isset($rvar_list_order)) $rvar_list_order = '';
if(is_numeric($rvar_page_display_num))
	$this->page_size = $rvar_page_display_num;
switch($rvar_list_order)
{
case 'id_desc':
	$article_sql_order = 'order by articleID desc';
	break;
case 'id_asc':
	$article_sql_order = 'order by articleID asc';
	break;
case 'level_desc':
	$article_sql_order = 'order by level desc';
	break;
case 'level_asc':
	$article_sql_order = 'order by level asc';
	break;
case 'scansCount_desc':
	$article_sql_order = 'order by scansCount desc';
	break;
case 'scansCount_asc':
	$article_sql_order = 'order by scansCount asc';
	break;
case 'smimgpath_desc':
	$article_sql_order = 'order by smimgpath desc';
	break;
case 'smimgpath_asc':
	$article_sql_order = 'order by smimgpath asc';
	break;
case 'title_desc':
	$article_sql_order = 'order by title desc';
	break;
case 'title_asc':
	$article_sql_order = 'order by title asc';
	break;
case 'sec_ID_desc':
	$article_sql_order = 'order by sec_ID desc';
	break;
case 'sec_ID_asc':
	$article_sql_order = 'order by sec_ID asc';
	break;
case 'addtime_desc':
	$article_sql_order = 'order by addtime desc';
	break;
case 'addtime_asc':
	$article_sql_order = 'order by addtime asc';
	break;
case 'time_desc':
	$article_sql_order = 'order by time desc';
	break;
case 'time_asc':
	$article_sql_order = 'order by time asc';
	break;
default:
	$article_sql_order = 'order by time desc';
	$rvar_list_order = 'time_desc';
	break;
}
//$section = new section(true,true);
$section = new section();
$section->session = &$this->session;
$section->sql = &$this->sql;
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
if($rvar_sec_page != '')
{
	$startPage = ($rvar_sec_page - 1) * $this->page_size;
}
else
{
	$startPage = 0;
	$rvar_sec_page = 1;
}
if($this->all == null)
{
	$section->getall();
	$this->all =&$section->all;
}

if($rvar_keyword) //根据关键词查询
{
	$escape_keyword = $sql->escape_str($rvar_keyword);
	if($permis_sql != '')
		$query_condition = $permis_sql + ' and ';
	else
		$query_condition = '';
	$query_condition .= "(title like '%{$escape_keyword}%'";
	if($rvar_query_type == '2')
		$query_condition .= " or content like '%{$escape_keyword}%'";
	$query_condition .= ")";
	$query_order = $article_sql_order;
	$sql->query("select *  from {$sql->tables_prefix}articles where {$query_condition} {$query_order}");
}
else if($rvar_sec_ID!='' && $rvar_sec_ID!='0' )
{
	if($rvar_is_recur!='yes')
	{
		if($permis_sql != '')
			$sql->query("select *  from {$sql->tables_prefix}articles where $permis_sql  and sec_ID=$rvar_sec_ID " .
			" " . $article_sql_order);
		else
			$sql->query("select *  from {$sql->tables_prefix}articles where sec_ID=$rvar_sec_ID " .
			" " . $article_sql_order);
	}
	else
	{
		if($permis_sql != '')
			$this->sqlstr = "select *  from $sql->tables_prefix" . "articles where $permis_sql and (";
		else
			$this->sqlstr = "select *  from $sql->tables_prefix" . "articles where (";
		$this->recur_sec_sqlstr($rvar_sec_ID);
		$this->sqlstr = substr($this->sqlstr, 0, -3);
		$this->sqlstr .= ") " . $article_sql_order;
		$sql->query($this->sqlstr);
	}
}
else
{
	if($permis_sql != '')
		$sql->query("select * from $sql->tables_prefix" . "articles where $permis_sql " . $article_sql_order);
	else
		$sql->query("select * from $sql->tables_prefix" . "articles " . $article_sql_order);
}
$article_totalnum = $sql->get_num();
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
	$sec_query = '<?php $sql->query($sql->sql_desc . " limit $startPage," . $this->page_size); ?>';

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
				echo "<span class=\"state_span_green\">已静态化</span>";
			else
				echo "<span class=\"state_span_red\">未静态化</span>";
			',true);
	$tpl->setVar('article_edit', $edit);
	$tpl->setVar('article_del', $del);
	$tpl->setVar('endfor', '<?php } ?>');
	$tpl->setVar('cms_root_dir', '<?php echo $zengl_cms_rootdir; ?>');
	$tpl->template_parse();
	$tpl->cache();
	include $filecache;
}
?>