<?php
global $rvar_sec_ID;
global $rvar_is_recur;
global $rvar_tag;
global $rvar_keyword; //查询关键词
global $rvar_query_type; //查询类型
global $rvar_flagUsePHP;
global $zengl_cms_rootdir;
global $listshow_article_smimg_default;
global $adminHtml_genhtml;
global $zengl_cms_use_html;
global $sec_allrownum;
include_once 'mytpl_help_func.php';

//防止一些php notice警告信息
if(!isset($rvar_sec_ID)) $rvar_sec_ID = '';
if(!isset($rvar_is_recur)) $rvar_is_recur = '';
if(!isset($rvar_tag)) $rvar_tag = '';
if(!isset($rvar_keyword)) $rvar_keyword = '';
if(!isset($rvar_query_type)) $rvar_query_type = '';
if(!isset($rvar_flagUsePHP)) $rvar_flagUsePHP = '';

if($adminHtml_genhtml=='yes' || $rvar_flagUsePHP == 'yes' || $rvar_tag != '' ||
		$rvar_keyword != '')
	;
else if($zengl_cms_use_html == 'yes')
{
	$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
	$filename = $sec_dirpath . '/';
	if(file_exists($filename))
		exit('<script type="text/javascript"> location.href="' . $filename .'";</script>');
}
$title = "ZENGLCMS显示文章列表";
$section = new section(false,false);
$section->sql = $section->permis->sql = &$this->sql;
$sql = &$this->sql;
if($this->all == null)
{
	$section->getall();
	$this->all =&$section->all;
}

if($rvar_tag != '')
{
	$tags = new tags(&$sql);
	$tag_word = $tags->query();
	$tagstr = "<div id='tags'>包含标签 <font color='red'>" . $tag_word . '</font>'.
			" <a class='more_tags' href='{$zengl_cms_rootdir}tags_operate.php?action=getall'>" .
			"(more tags...)</a> 的所有文章列表如下：</div>";
}
else if($rvar_keyword) //根据关键词查询
{
	$escape_keyword = $sql->escape_str($rvar_keyword);
	$query_condition = "title like '%{$escape_keyword}%'";
	if($rvar_query_type == '2')
		$query_condition .= " or content like '%{$escape_keyword}%'";
	$query_order = "order by articleID desc";
	$sql->query("select *  from {$sql->tables_prefix}articles where {$query_condition} {$query_order}");
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
if($this->all[$rvar_sec_ID]['tpl'] != '' && 
   file_exists($zengl_cms_tpl_dir . $zengl_theme . '/'.$this->all[$rvar_sec_ID]['tpl']))
{
	$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/'.$this->all[$rvar_sec_ID]['tpl'];
	$filecache = $zengl_cms_tpl_dir . 'cache/'. convertToCache($this->all[$rvar_sec_ID]['tpl']);
}
else
{
	$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/list_articles.tpl';
	$filecache = $zengl_cms_tpl_dir . 'cache/list_articles_cache.php';
}

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

if($rvar_sec_ID != '' && isset($this->all[$rvar_sec_ID]["sec_name"]))
	$title = $this->all[$rvar_sec_ID]["sec_name"]; //add by zl
else if($rvar_tag != '')
	$title = '标签<'.$tag_word.'>查询';
else if($rvar_keyword != '')
	$title = '关键词<'.$rvar_keyword.'>查询';
else
	$title = '文章列表';

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
	if($this->all[$rvar_sec_ID]['type'] == 'linkurl_newopen' ||
	   $this->all[$rvar_sec_ID]['type'] == 'linkurl') //外链则不处理静态化
	{
		ob_end_clean();
		$this->progress("栏目{$this->all[$rvar_sec_ID]['sec_name']} 为外链 {$this->all[$rvar_sec_ID]['linkurl']} 跳过不生成!");
		return $sec_allrownum;
	}
	$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
	mkdirs($sec_dirpath);
	file_put_contents($sec_dirpath . '/index.html',ob_get_contents());
	ob_end_clean();
	$this->progress("生成栏目{$this->all[$rvar_sec_ID]['sec_name']} $sec_dirpath/index.html 成功");
	//flush_buffers();
	return $sec_allrownum;
}
?>