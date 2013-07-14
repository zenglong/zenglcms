<?php 
global $zengl_cms_rootdir;
global $rvar_sec_ID;
global $rvar_sec_page;
global $adminHtml_genhtml;
global $rvar_is_recur;
global $rvar_articleID;
global $rvar_tpl_action;
global $sec_allrownum;
header( "Content-Type:   text/html;   charset=UTF-8 ");
$adminHtml_genhtml = 'yes';
$rvar_is_recur = 'yes';
$this->sql->query("select * from {$this->sql->tables_prefix}articles");
$totalrow_num = $this->sql->get_num();
$totalsec_page = ceil($totalrow_num / $this->page_size);
$this->progress_begin("准备生成静态页面", $totalsec_page + $totalrow_num + 1);
$this->getallsections();
$this->index_articles();
foreach ($this->all as $secId => $array)
{
	$rvar_sec_ID = $secId;
	$rvar_tpl_action = 'default';
	$this->list_articles();
	$pagecount = ceil($sec_allrownum/$this->page_size);
	$rvar_tpl_action = 'listajax';
	for($i=2;$i<=$pagecount;$i++)
	{
	$rvar_sec_page = $i;
	$this->list_articles();
	}
}
$rvar_tpl_action = '';
$sql = new sql('utf8');
		$sql->query("select * from $sql->tables_prefix" . "articles");
		while ($sql->parse_results()) {
		$rvar_articleID = $sql->row['articleID'];
			$this->show_article();
}
$this->progress('生成完毕!');
$this->progress_end("生成完毕,<a href='{$zengl_cms_rootdir}index.html' target='_blank'>" .
"点此查看主页</a>",false);
$adminHtml_genhtml = 'no'; //sql对象撤销时用于释放数据库连接用的。
?>