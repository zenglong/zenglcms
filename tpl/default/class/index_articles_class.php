<?php
global $rvar_flagUsePHP;
global $zengl_cms_rootdir;
global $adminHtml_genhtml;
global $zengl_cms_use_html;
include_once 'mytpl_help_func.php';

if($adminHtml_genhtml=='yes' || $rvar_flagUsePHP == 'yes')
	;
else if($zengl_cms_use_html == 'yes')
{
	$filename = 'index.html';
	if(file_exists($filename))
		exit('<script type="text/javascript"> location.href="' . $filename . '";</script>');
}
$rvar_sec_ID = 0;
$rvar_is_recur = 'yes';
$section = new section(false,false);
$section->permis->sql = $section->sql = &$this->sql;
$sql = &$this->sql;
if($this->all == null)
{
	$section->getall();
	$this->all =&$section->all;
}
$tablename = "{$sql->tables_prefix}articles";
$table_section = "{$sql->tables_prefix}section";
$sqlstr_recent = "select * from $tablename order by time desc limit 0,6"; //最近更新
$sqlstr_imgs = "select * from $tablename where level = 2 and smimgpath != '' order by time desc limit 0,10"; //幻灯图片
$sqlstr_divs = "select a.sec_ID as sec_ID, a.articleID as articleID, a.title as title, ". 
		    "b.sec_weights as sec_weights ".
			 "from $tablename as a left join $table_section as b ".
			 " on a.sec_ID = b.sec_ID order by b.sec_weights desc,a.sec_ID asc,a.articleID desc";
//$sql->query();
//$sql->get_num();
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/index.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/index_cache.php';

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
$title = "zengl开源网";

if($adminHtml_genhtml == 'yes')
{
	$flaghtml = true;
	ob_start();
}
else
	$flaghtml = false;

$magic_quote = get_magic_quotes_gpc();

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
	file_put_contents('index.html',ob_get_contents());
	ob_end_clean();
	//header( "Content-Type:   text/html;   charset=UTF-8 ");
	$this->progress("生成主页<index.html>成功");
}
?>