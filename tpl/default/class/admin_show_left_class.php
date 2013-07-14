<?php 
global $zengl_cms_rootdir;
$permis = &$this->permis;
$title = "可执行的操作：";
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/admin_left.tpl';
//$filecache = convertToCache($filetpl);
$filecache = $zengl_cms_tpl_dir . 'cache/admin_left_cache.php';

if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');

$this->userpower = $permis->get_perms_array();
foreach($this->userpower as $key => $value)
{
	if($value == PER_ALLOW)
		$this->userpower[$key] = true;
	else if($value == PER_DENY)
		$this->userpower[$key] = false;
}
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) )  &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme;',true);
	$tpl->setVar('title', 'echo $title;',true);
	$tpl->setVar('can', '<?php if($this->check_perms( ');
	$tpl->setVar('_can', ' )){ ?>');
	$tpl->setVar('end_can', '}',true);
	$tpl->setVar('addsec_loc',$zengl_cms_rootdir . 'add_del_edit_section.php?action=add');
	$tpl->setVar('editdel_sec_loc',$zengl_cms_rootdir . 'add_del_edit_section.php?action=edit');
	$tpl->setVar('add_article_loc',$zengl_cms_rootdir . 'add_edit_del_show_list_article.php?hidden=add');
	$tpl->setVar('editdel_article_loc',$zengl_cms_rootdir . 'add_edit_del_show_list_article.php?hidden=admin&action=list');
	$tpl->setVar('edit_archive_loc',$zengl_cms_rootdir . 'list_upload_archive.php?action=list');
	$tpl->setVar('clear_caches_loc',$zengl_cms_rootdir . 'clear_filecache.php?clear=all');
	$tpl->setVar('update_permis_loc',$zengl_cms_rootdir . 'permis_operate.php?action=update_permis');
	$tpl->setVar('bak_db_loc',$zengl_cms_rootdir . 'bak_restore_db.php?action=bak');
	$tpl->setVar('restore_db_loc',$zengl_cms_rootdir . 'bak_restore_db.php?action=restore');
	$tpl->setVar('admin_comment_loc',$zengl_cms_rootdir . 'comment_operate.php?action=admin_comment_list');
	$tpl->setVar('admin_reply_loc',$zengl_cms_rootdir . 'comment_operate.php?action=admin_reply_list');
	$tpl->setVar('cms_update_loc',$zengl_cms_rootdir . 'update_operate.php?action=update');
	$tpl->setVar('onekey_gen_html_loc',$zengl_cms_rootdir . 'add_edit_del_show_list_article.php?hidden=onekeyhtml');
	$tpl->setVar('onekey_rm_html_loc',$zengl_cms_rootdir . 'add_edit_del_show_list_article.php?hidden=onekey_rm_html');
	$tpl->setVar('sec_menu_loc',$zengl_cms_rootdir . 'add_del_edit_section.php?action=setmenu');
	$tpl->setVar('gen_html_forsec_loc',$zengl_cms_rootdir . 'add_edit_del_show_list_article.php?hidden=gensechtml');
	$tpl->setVar('admin_filemanage_loc',$zengl_cms_rootdir . 'phpfilemanager.php');
	$tpl->setVar('set_config_loc',$zengl_cms_rootdir . 'config_operate.php?action=show');
	$tpl->cache();
	include $filecache;
}
?>