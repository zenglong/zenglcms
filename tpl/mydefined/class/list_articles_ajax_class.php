<?php
global $zengl_cms_rootdir;
global $rvar_sec_page;
global $rvar_tag;
global $rvar_keyword; //查询关键词
global $rvar_query_type; //查询类型
global $rvar_is_recur;
global $rvar_flagUsePHP;
global $listshow_article_smimg_default;
global $adminHtml_genhtml;
global $zengl_cms_use_html;
include_once 'mytpl_help_func.php';

//防止一些php notice警告信息
if(!isset($rvar_sec_ID)) $rvar_sec_ID = '';
if(!isset($rvar_sec_page)) $rvar_sec_page = '';
if(!isset($rvar_tag)) $rvar_tag = '';
if(!isset($rvar_keyword)) $rvar_keyword = '';
if(!isset($rvar_query_type)) $rvar_query_type = '';
if(!isset($rvar_is_recur)) $rvar_is_recur = '';
if(!isset($rvar_flagUsePHP)) $rvar_flagUsePHP = '';

if($adminHtml_genhtml=='yes' || $rvar_flagUsePHP == 'yes' || $rvar_tag != '' ||
		$rvar_keyword != '')
	;
else if($zengl_cms_use_html == 'yes')
{
	$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
	$filename = $sec_dirpath . '/index-'. $rvar_sec_page . '.html';
	if(file_exists($filename))
		exit('<script type="text/javascript"> location.href="' . $filename .'";</script>');
}
$sql = &$this->sql;
if(isset($rvar_sec_ID) || isset($rvar_tag))
	$startPage = ($rvar_sec_page - 1) * $this->page_size;
else
	$startPage = 0;

if($rvar_tag != '')
{
	$tags = new tags(&$sql);
	$tags->query($startPage,$this->page_size);
}
else if($rvar_keyword) //根据关键词查询
{
	$escape_keyword = $sql->escape_str($rvar_keyword);
	$query_condition = "title like '%{$escape_keyword}%'";
	if($rvar_query_type == '2')
		$query_condition .= " or content like '%{$escape_keyword}%'";
	$query_order = "order by articleID desc";
	$sql->query("select *  from {$sql->tables_prefix}articles where ".
		mytpl_unexpect_sec_sqlstr(&$this,'sec_ID') ." ({$query_condition}) {$query_order} 
		limit $startPage,{$this->page_size}");
}
else if($rvar_sec_ID!='' && $rvar_sec_ID!='0' )
{
	if($rvar_is_recur!='yes')
		$sql->query("select * from {$sql->tables_prefix}articles where sec_ID=$rvar_sec_ID order by articleID desc
		limit $startPage,{$this->page_size}");
	else
	{
		$this->sqlstr = "select * from $sql->tables_prefix" . "articles".
		" where ".mytpl_unexpect_sec_sqlstr(&$this,'sec_ID')." ( ";
		$this->recur_sec_sqlstr($rvar_sec_ID);
		$this->sqlstr = substr($this->sqlstr, 0, -3);
		$this->sqlstr .= ") order by articleID desc limit $startPage,{$this->page_size}";
		$sql->query($this->sqlstr);
	}
}
else
	$sql->query("select * from $sql->tables_prefix" . "articles order by articleID desc limit $startPage,{$this->page_size}");

$mydefined_tpl_ajax_name = str_replace('.tpl', '_ajax.tpl' , $this->all[$rvar_sec_ID]['tpl']);
$mydefined_tpl_path = $zengl_cms_tpl_dir . $zengl_theme . '/'. $mydefined_tpl_ajax_name;
if($this->all[$rvar_sec_ID]['tpl'] != '' &&
   file_exists($mydefined_tpl_path))
{
	$filetpl = $mydefined_tpl_path;
	$filecache = $zengl_cms_tpl_dir . 'cache/' . convertToCache($mydefined_tpl_ajax_name);
}
else
{
	$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/list_articles_ajax.tpl';
	$filecache = $zengl_cms_tpl_dir . 'cache/list_articles_ajax_cache.php';
}
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
	$tpl = new tpl($filetpl, $filecache);
	$tpl->template_parse();
	$tpl->cache();
	include $filecache;
}
if($flaghtml)
{
	$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
	mkdirs($sec_dirpath);
	file_put_contents($sec_dirpath . '/index-' . $rvar_sec_page . '.html' ,ob_get_contents());
	ob_end_clean();
	$this->progress("生成栏目{$this->all[$rvar_sec_ID]['sec_name']} $sec_dirpath/index-".
		 			"$rvar_sec_page.html 成功",false);
	//flush_buffers();
}
?>