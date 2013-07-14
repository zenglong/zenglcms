<?php
global $rvar_flagUsePHP;
global $zengl_cms_rootdir;
global $adminHtml_genhtml;
global $zengl_cms_use_html;

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
$section = new section(true,true);
$sql = &$this->sql;
if($this->all == null)
{
	$section->getall();
	$this->all =&$section->all;
}
$tablename = "{$sql->tables_prefix}articles";
$table_section = "{$sql->tables_prefix}section";
$sqlstr_imgs = "select * from $tablename order by time desc";
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
	$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
	$tpl->setVar('sec_array', 'echo js_array($this->all, \'sec_array\');',true);
	$tpl->setVar('ishtml','echo $adminHtml_genhtml',true);
	$tpl->setVar('title', 'echo $title',true);
	$tpl->setVar('header', '$this->header();',true);
	$tpl->setVar('username', '
			if(!$flaghtml)
				echo $username;
			else
				echo ""
			',true);
	$tpl->setVar('user_operate', '
			if(!$flaghtml)
				echo $user_op;
			else
				echo "";
			',true);
	$tpl->setVar('secmenu', '<?php 	$section->recur_show_secs(1,1,');
	$tpl->setVar('secmenu_end', '); ?>');
	$tpl->setVar('for_recent_updates', '
			$sql->query($sqlstr_imgs);
			if($sql->get_num() == 0)
				echo "暂无文章！";
			else
			{
				$update_num = 0;
				while ($sql->parse_results()) {
					if($update_num >= 6)
						break;
			',true);
	$tpl->setVar('update_loc', '
			$update_sec = $sql->row["sec_ID"];
			$update_sec = $this->GetSecDirFullPath($update_sec);
			if(!$flaghtml)
				echo $zengl_cms_rootdir . "add_edit_del_show_list_article.php?hidden=show&articleID=" . 
					 $sql->row["articleID"];
			else
				echo $zengl_cms_rootdir . $update_sec . "/" . "article-" .
						$sql->row["articleID"] . ".html";
			',true);
	$tpl->setVar('update_title', 'echo $sql->row["title"]',true);
	$tpl->setVar('update_sm_title', 'echo subUTF8($sql->row["title"],30)',true);
	$tpl->setVar('for_recent_end', '$update_num++; } }',true);
	$tpl->setVar('for_imgs', '
			$sql->reset_row();
			if($sql->rownum == 0)
				echo "暂无图片！";
			else
			{
				$img_num = 0;
				while ($sql->parse_results()) {
					if($img_num >= 10)
						break;
					if($sql->row["smimgpath"] == "")
						continue;
			',true);
	$tpl->setVar('img_loc', '
			$img_sec = $sql->row["sec_ID"];
			$img_sec = $this->GetSecDirFullPath($img_sec);
			if(!$flaghtml)
				echo $zengl_cms_rootdir . "add_edit_del_show_list_article.php?hidden=show&articleID=" . 
					 $sql->row["articleID"];
			else
				echo $zengl_cms_rootdir . $img_sec . "/" . "article-" .
						$sql->row["articleID"] . ".html";
			',true);
	$tpl->setVar('img_src', '
			if(empty($magic_quote))
				echo $sql->row["smimgpath"];
			else
				echo stripslashes($sql->row["smimgpath"]);
			',true);
	$tpl->setVar('img_title', 'echo $sql->row["title"]',true);
	$tpl->setVar('for_imgs_end', '
					$img_num++;
				} 
				if($img_num == 0)
					echo "暂无图片！";
			}
			',true);
	$tpl->setVar('articles_divs', '$sql->query($sqlstr_divs);$this->index_articles_divs();',true);
	$tpl->setVar('cms_root_dir', 'echo $zengl_cms_rootdir;',true);
	$tpl->setVar('footer', '$this->footer();',true);
	$tpl->cache();
	include $filecache;
}
if($flaghtml)
{
	file_put_contents('index.html',ob_get_contents());
	ob_end_clean();
	//header( "Content-Type:   text/html;   charset=UTF-8 ");
	echo "生成主页<index.html>成功<br/>";
}
?>