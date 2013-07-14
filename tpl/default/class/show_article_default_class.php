<?php
global $rvar_articleID;
global $rvar_isfromhtml;
global $rvar_flagUsePHP;
global $rvar_page; //分页号
global $rvar_keyword; //搜索关键字
global $zengl_cms_rootdir;
global $listshow_article_smimg_default;
global $adminHtml_genhtml;
global $zengl_cms_full_domain;
global $zengl_cms_use_html;
include_once 'mytpl_help_func.php';

if(!isset($rvar_articleID)) $rvar_articleID = '';
if(!isset($rvar_isfromhtml)) $rvar_isfromhtml = '';
if(!isset($rvar_flagUsePHP)) $rvar_flagUsePHP = '';
if(!isset($rvar_page)) $rvar_page = '';
if(!isset($rvar_keyword)) $rvar_keyword = '';

$section = new section(false,false);
$section->sql = $section->permis->sql = &$this->sql;
if(!isset($rvar_articleID) || !is_numeric($rvar_articleID))
	die("invalidate articleID!");

$this->getallsections();
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

if($rvar_isfromhtml == 'yes') //静态页面ajax返回用户登录注册等链接
	exit($username . ' ' . $user_op); 

//$sql = new sql('utf8');
$sql = &$this->sql;
$sql->query("select * from $sql->tables_prefix" . "articles where articleID = " . $rvar_articleID);
$sql->parse_results();
//$time = date("Y/n/j G:i:s",$sql->row['time']);
$rvar_sec_ID = $sql->row['sec_ID'];
$article_title = $sql->row['title'];
$article_content_array = explode('[zengl pagebreak]', $sql->row['content']); //文章内容进行分页拆分
$pagenum = count($article_content_array);
if( $rvar_page=='' && $rvar_keyword != '' && $pagenum > 1)
{
	foreach ($article_content_array as $tmp_page => $tmp_content)
	{
		if(strpos($tmp_content,$rvar_keyword) !== false) //设置搜索关键词所在的分页，只设置到第一个匹配的分页
		{
			$rvar_page = ($tmp_page + 1);
		}
	}
}
if( !is_numeric($rvar_page) || $rvar_page == '' ||
   		$rvar_page <= 0 ) //如果没提供分页号，或者小于等于0 ，则设为第一页
	$rvar_page = 1;
else if($rvar_page > $pagenum) //如果超过最后一分页，则设为最后一分页
	$rvar_page = $pagenum;
$article_content = $article_content_array[$rvar_page - 1]; //得到该页的内容
$magic_quote = get_magic_quotes_gpc();

if($adminHtml_genhtml=='yes' || $rvar_flagUsePHP == 'yes')
	;
else if($zengl_cms_use_html == 'yes')
{
	$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
	if($rvar_page > 1)
		$filename = $sec_dirpath . '/article-' . $rvar_articleID . '-' . $rvar_page . '.html';
	else
		$filename = $sec_dirpath . '/article-' . $rvar_articleID . '.html';
	if(file_exists($filename))
		exit('<script type="text/javascript"> location.href="' . $filename .'";</script>');
}

if($sql->row['smimgpath']!='')
	$smimgpath = htmlentities($sql->row['smimgpath'],ENT_QUOTES,"utf-8");
else
	$smimgpath = $listshow_article_smimg_default;
$tags = new tags(new sql('utf8'));
$tag_clouds = $tags->get_some(15);
$tag_array = $tags->find($rvar_articleID);
$tagstr = '';
$taghtml = '';
if(is_array($tag_array) && count($tag_array) > 0)
{
	$taghtml = '标签：';
	$lastval = end($tag_array);
	foreach ($tag_array as $tag => $tagID)
	{
		if($tagID != $lastval)
		{
			$tagstr .= "$tag,";
			$taghtml .= "<a href='" . $zengl_cms_rootdir . "add_edit_del_show_list_article.php?hidden=list" .
					"&tag=$tagID'>$tag</a>,";
		}
		else
		{
			$tagstr .= "$tag";
			$taghtml .= "<a href='" . $zengl_cms_rootdir . "add_edit_del_show_list_article.php?hidden=list" .
					"&tag=$tagID'>$tag</a>";
		}
	}
}

if($adminHtml_genhtml == 'yes')
{
	$flaghtml = true;
	ob_start();
	$smimgpath = $zengl_cms_rootdir . $smimgpath;
	$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
}
else
	$flaghtml = false;

if($sql->row['tpl'] != '' &&
   file_exists($zengl_cms_tpl_dir . $zengl_theme . '/' . $sql->row['tpl']))
{
	$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/' . $sql->row['tpl'];
	$filecache = $zengl_cms_tpl_dir . 'cache/' . convertToCache($sql->row['tpl']);
}
else if($this->all[$rvar_sec_ID]['article_tpl'] != '' && 
   file_exists($zengl_cms_tpl_dir . $zengl_theme . '/'.$this->all[$rvar_sec_ID]['article_tpl']))
{
	$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/'.$this->all[$rvar_sec_ID]['article_tpl'];
	$filecache = $zengl_cms_tpl_dir . 'cache/'. convertToCache($this->all[$rvar_sec_ID]['article_tpl']);
}
else
{
	$filetpl = $zengl_cms_tpl_dir . $zengl_theme .'/show_article.tpl';
	$filecache = $zengl_cms_tpl_dir . 'cache/show_article_cache.php';
}

if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');

if(file_exists($filecache) &&( filemtime($filecache) > filemtime($filetpl) ) &&
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
	//$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
	if($this->all[$rvar_sec_ID]['type'] == 'linkurl' ||
	   $this->all[$rvar_sec_ID]['type'] == 'linkurl_newopen') //外链则不处理静态化
	{
		ob_end_clean();
		$this->progress("栏目{$this->all[$rvar_sec_ID]['sec_name']} 为外链 {$this->all[$rvar_sec_ID]['linkurl']} ".
						"文章{$article_title} 跳过不处理");
		return;
	}
	mkdirs($sec_dirpath);
	if($pagenum <= 1)
	{
		file_put_contents($sec_dirpath . '/article-' . $rvar_articleID . '.html',ob_get_contents());
		ob_end_clean();
		$this->progress("生成文章 {$article_title} $sec_dirpath/article-" .
						"$rvar_articleID.html 成功");
		//flush_buffers();
	}
	else
	{
		for($temp=1;$temp<=$pagenum;$temp++)
		{
			if($temp > 1)
			{
				$sql->query("select * from $sql->tables_prefix" . "articles where articleID = " . $rvar_articleID);
				$sql->parse_results();
				$rvar_page = $temp;
				ob_start();
				$article_content = $article_content_array[$temp-1]; //得到该页的内容
				include $filecache;
				$html_path = $sec_dirpath . '/article-' . $rvar_articleID . '-' . $temp . '.html';
			}
			else
				$html_path = $sec_dirpath . '/article-' . $rvar_articleID . '.html';
			file_put_contents($html_path,ob_get_contents());
			ob_end_clean();
			if($temp == 1)
				$this->progress("生成文章 {$article_title} 第{$temp}分页 {$html_path} 成功");
			else
				$this->progress("生成文章 {$article_title} 第{$temp}分页 {$html_path} 成功",false);
			//flush_buffers();
		}
	}
}
?>