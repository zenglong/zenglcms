<?php 
global $zengl_cms_rootdir;
global $rvar_sec_ID;
global $rvar_sec_page;
global $adminHtml_genhtml;
global $rvar_is_recur;
global $rvar_articleID;
global $rvar_checkbox;
global $rvar_secID;
global $rvar_tpl_action;
global $sec_allrownum;
header( "Content-Type:   text/html;   charset=UTF-8 ");
$is_gen_article = false;
$is_recur_sec = false;
if($rvar_secID <= 0)
{
	$rvar_secID = 1;
	$is_gen_article = true;
	$is_recur_sec = true;
}
if($rvar_checkbox != null)
{
	foreach ($rvar_checkbox as $value)
	{
		if($value == 'gen_article')
			$is_gen_article = true;
		if($value == 'is_recur')
			$is_recur_sec = true;
	}
}
$adminHtml_genhtml = 'yes';
$rvar_is_recur = 'yes';
$this->getallsections();
$array = array();
if($is_recur_sec)
{
	$this->recur_sec_array(&$array,$rvar_secID);
	array_unique($array);
}
else
	array_push($array, $rvar_secID);
$this->progress_begin('准备生成静态页面',count($array) + 1);
foreach ($array as $secId)
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
if($is_gen_article)
{
$sql = new sql('utf8');
if($is_recur_sec)
{
$this->sqlstr = "select *  from $sql->tables_prefix" . "articles where ";
				$this->recur_sec_sqlstr($rvar_secID);
				$this->sqlstr = substr($this->sqlstr, 0, -3);
}
else
{
$this->sqlstr = "select *  from $sql->tables_prefix" . "articles where " .
"sec_ID=$rvar_secID";
}
$sql->query($this->sqlstr);
$this->progress->setNewTotalTask($sql->get_num()+1);
while ($sql->parse_results()) {
$rvar_articleID = $sql->row['articleID'];
$this->show_article();
}
}
$this->index_articles();
$this->progress_end("生成完毕,<a href='{$zengl_cms_rootdir}index.html' target='_blank'>" .
"点此查看主页!</a>",false);
$adminHtml_genhtml = 'no'; //sql对象撤销时用于释放数据库连接用的。
?>