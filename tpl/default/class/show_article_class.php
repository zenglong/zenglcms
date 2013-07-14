<?php
global $rvar_articleID;
global $rvar_isfromhtml;
global $rvar_flagUsePHP;
global $zengl_cms_rootdir;
global $listshow_article_smimg_default;
global $adminHtml_genhtml;
global $zengl_cms_full_domain;
global $zengl_cms_use_html;

$section = new section(true,true);
if(!isset($rvar_articleID))
	die("invalidate articleID!");

$filetpl = $zengl_cms_tpl_dir . $zengl_theme .'/show_article.tpl';
//$filecache = $zengl_cms_tpl_dir . 'show_article_cache'. $rvar_articleID .'.php';
$filecache = $zengl_cms_tpl_dir . 'cache/show_article_cache.php';

if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');

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

if($rvar_isfromhtml == 'yes')
	exit($username . ' ' . $user_op); 

$sql = new sql('utf8');
$sql->query("select * from $sql->tables_prefix" . "articles where articleID = " . $rvar_articleID);
$sql->parse_results();
$time = date("Y/n/j G:i:s",$sql->row['time']);
$rvar_sec_ID = $sql->row['sec_ID'];
$article_title = $sql->row['title'];
$magic_quote = get_magic_quotes_gpc();

if($adminHtml_genhtml=='yes' || $rvar_flagUsePHP == 'yes')
	;
else if($zengl_cms_use_html == 'yes')
{
	$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
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

if(file_exists($filecache) &&( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('cms_site_domain', 'echo $zengl_cms_full_domain;',true);
	$tpl->setVar('theme', '
			if(!$flaghtml)
				echo $zengl_cms_tpl_dir . $zengl_theme;
			else
				echo $zengl_cms_rootdir . $zengl_cms_tpl_dir . $zengl_theme;
			',true);
	$tpl->setVar('sec_array', 'echo js_array($this->all, \'sec_array\');',true);
	$tpl->setVar('ishtml','echo $adminHtml_genhtml',true);
	$tpl->setVar('keyword','echo $tagstr',true);
	$tpl->setVar('header','$this->header();',true);
	$tpl->setVar('username', '
			if(!$flaghtml)
				echo $username;
			else
				echo "";
			',true);
	$tpl->setVar('user_operate', '
			if(!$flaghtml)
				echo $user_op;
			else
				echo "";
			',true);
	$tpl->setVar('secmenu', '<?php 	$section->recur_show_secs(1,1,');
	$tpl->setVar('secmenu_end', '); ?>');
	$tpl->setVar('sections', '
			$this->show_article_sections($sql->row["sec_ID"],1);
			if($flaghtml)
				$this->secstr = "";
			',true);
	$tpl->setVar('title', 'echo $sql->row[\'title\']',true);
	$tpl->setVar('author', 'echo $sql->row[\'author\']',true);
	$tpl->setVar('time', 'echo $time',true);
	$tpl->setVar('tags', 'echo $taghtml',true);
	$tpl->setVar('smimgpath', 'echo $smimgpath',true);
	$tpl->setVar('descript', 'echo $sql->row[\'descript\']',true);
	$tpl->setVar('for_tags', 'foreach($tag_clouds as $tag_name => $tag_ID) {',true);
	$tpl->setVar('tag_loc',$zengl_cms_rootdir . 'add_edit_del_show_list_article.php?hidden=list' .
			'&tag=<?php echo $tag_ID; ?>');
	$tpl->setVar('tag_name', 'echo $tag_name;',true);
	$tpl->setVar('endfor','}',true);
	$tpl->setVar('content', '
			if(empty($magic_quote))
				echo $sql->row[\'content\'];
			else
				echo stripslashes($sql->row[\'content\']);',true);
	$tpl->setVar('more_tags_loc', "'{$zengl_cms_rootdir}tags_operate.php?action=getall'");
	$tpl->setVar('cms_root_dir', 'echo $zengl_cms_rootdir;',true);
	$tpl->setVar('articleID', 'echo $rvar_articleID',true);
	$tpl->setVar('pre_article', '
			$sql->query("select * from {$sql->tables_prefix}articles where articleID < ' . 
			'{$rvar_articleID} and sec_ID = $rvar_sec_ID order by articleID DESC  limit 1 ");
			$sql->parse_results();
			if($sql->row["articleID"] == null)
				echo "上一篇：没有了";
			else if(!$flaghtml)
				echo "上一篇：<a href=\"{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=show&articleID={$sql->row[\'articleID\']}\">{$sql->row[\'title\']}</a>";
			else
				echo "上一篇：<a href=\"". $sec_dirpath ."/article-{$sql->row[\'articleID\']}.html\">{$sql->row[\'title\']}</a>";
			',true);
	$tpl->setVar('next_article', '
			$sql->query("select * from {$sql->tables_prefix}articles where articleID > ' .
			'{$rvar_articleID} and sec_ID = $rvar_sec_ID order by articleID ASC  limit 1 ");
			$sql->parse_results();
			if($sql->row["articleID"] == null)
				echo "下一篇：没有了";
			else if(!$flaghtml)
				echo "下一篇：<a href=\"{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=show&articleID={$sql->row[\'articleID\']}\">{$sql->row[\'title\']}</a>";
			else
				echo "下一篇：<a href=\"". $sec_dirpath ."/article-{$sql->row[\'articleID\']}.html\">{$sql->row[\'title\']}</a>";
			',true);
	$tpl->setVar('footer','$this->footer();',true);
	$tpl->cache();
	include $filecache;
}
if($flaghtml)
{
	//$sec_dirpath = $this->GetSecDirFullPath($rvar_sec_ID);
	mkdirs($sec_dirpath);
	file_put_contents($sec_dirpath . '/article-' . $rvar_articleID . '.html',ob_get_contents());
	ob_end_clean();
	echo "生成文章 {$article_title} $sec_dirpath/article-" . 
		  "$rvar_articleID.html 成功<br/>";
	flush_buffers();
}
?>