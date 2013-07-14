<?php 
global $zengl_cms_rootdir;
global $zengl_admin_reply_listnum;
global $rvar_sec_page;
global $rvar_commentID;
global $rvar_articleID;
global $rvar_username;
global $rvar_nickname;
$admin_pagenum = $zengl_admin_reply_listnum;
if($rvar_sec_page == null)
	$rvar_sec_page = 1;
$startPage = ($rvar_sec_page - 1) * $admin_pagenum;
$sql = &$this->sql;
$tablename = "{$sql->tables_prefix}CommentReply";
$table_comment = "{$sql->tables_prefix}comment";
$table_article = "{$sql->tables_prefix}articles";
$table_user = "{$sql->tables_prefix}user";
$whereStr = '';
$page_change = "''";
$magic_quote = get_magic_quotes_gpc();

if(isset($rvar_commentID) && is_numeric($rvar_commentID))
{
	$whereStr = " where $table_comment.comment_ID = " . $rvar_commentID;
	$page_change = "'&commentID=".$rvar_commentID . "'";
}
else if(isset($rvar_articleID) && is_numeric($rvar_articleID))
{
	$whereStr = " where $table_comment.articleID = " . $rvar_articleID;
	$page_change = "'&articleID=".$rvar_articleID . "'";
}
else if(isset($rvar_username))
{
	if($rvar_username == '-1')
	{
		$whereStr = " where $table_user.username is null";
		$page_change = "'&username=-1'";
	}
	else if(is_string($rvar_username))
	{
		$whereStr = " where $table_user.username = '" . $sql->escape_str($rvar_username)."'";
		$page_change = "'&username=".htmlentities(urlencode($rvar_username)) . "'";
	}
}
else if(isset($rvar_nickname) && is_string($rvar_nickname))
{
	$whereStr = " where $tablename.username = '" . $sql->escape_str($rvar_nickname)."'";
	$page_change = "'&nickname=".htmlentities(urlencode($rvar_nickname)) . "'";
}
$sqlstr = "select $tablename.username as nickname,$tablename.time as time,$tablename.content as content," .
"$tablename.ip_address as ip_address,$tablename.reply_ID as reply_ID,".
"$tablename.commentID as commentID," .
"$table_comment.content as comment_content,$table_comment.articleID as articleID," .
"$table_article.title as article_title,$table_user.username as username ".
" from $tablename left join $table_comment on $tablename.commentID = $table_comment.comment_ID ".
" left join  $table_article on ".
"$table_comment.articleID = $table_article.articleID left join $table_user on " .
"$tablename.uid = $table_user.userID ". $whereStr .
" order by $tablename.time desc";
$sql->query($sqlstr);
$totalnum = $sql->get_num();
$pageNumStr = 'echo ceil($sql->rownum/$admin_pagenum);';
$sec_query = '$sql->query($sql->sql_desc . " limit {$startPage},{$admin_pagenum}"); ';
$del = '"'.$zengl_cms_rootdir.'comment_operate.php?action=del_reply' .
		'&replyID=<?php echo $sql->row["reply_ID"]; ?>&articleID=<?php echo $sql->row["articleID"]; ?>"';
$comment_a = '"'.$zengl_cms_rootdir.'comment_operate.php?action=admin_reply_list' .
		'&commentID=<?php echo $sql->row["commentID"]; ?>&sec_page=1"';
$article_a = '"'.$zengl_cms_rootdir.'comment_operate.php?action=admin_reply_list' .
		'&articleID=<?php echo $sql->row["articleID"]; ?>&sec_page=1"';
$username_a = '"'.$zengl_cms_rootdir.'comment_operate.php?action=admin_reply_list' .
		'&username=<?php
		if($sql->row["username"]==null)
		echo "-1";
		else
		echo htmlentities(urlencode($sql->row["username"]));
		?>&sec_page=1"';
$nickname_a = '"'.$zengl_cms_rootdir.'comment_operate.php?action=admin_reply_list' .
		'&nickname=<?php echo htmlentities(urlencode($sql->row["nickname"]));?>'.
		'&sec_page=1"';
$reply_a = '"'.$zengl_cms_rootdir.'add_edit_del_show_list_article.php?hidden=show' .
		'&articleID=<?php echo $sql->row["articleID"] ?>"';
$title = '回复管理';
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/admin_list_replys.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/admin_list_replys_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
{
	include $filecache;
	return;
}
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
$tpl->setVar('sec_PageNum', $pageNumStr,true);
$tpl->setVar('sec_query', $sec_query,true);
$tpl->setVar('startPage', 'echo $rvar_sec_page',true);
$tpl->setVar('sec_DisplaySize', 'echo $this->display_size;',true);
$tpl->setVar('cms_root_dir', 'echo $zengl_cms_rootdir;',true);
$tpl->setVar('page_change', 'echo $page_change;',true);
$tpl->setVar('title', 'echo $title',true);
$tpl->setVar('reply_list_num', 'echo $admin_pagenum;',true);
$tpl->setVar('current_num', 'echo $sql->get_num();',true);
$tpl->setVar('totalnum', 'echo $totalnum;',true);
$tpl->setVar('for_replys', 'while($sql->parse_results()) {',true);
$tpl->setVar('reply_id', 'echo $sql->row["reply_ID"]',true);
$tpl->setVar('reply_a', $reply_a);
//$tpl->setVar('reply_content', 'echo "\'".htmlspecialchars($sql->row["content"])."\'";',true);
$tpl->setVar('reply_content', '
		if(empty($magic_quote))
			echo "\'".htmlentities($sql->row["content"],ENT_QUOTES,"utf-8")."\'";
		else
			echo "\'".htmlentities(stripslashes($sql->row["content"]),ENT_QUOTES,"utf-8")."\'";
		',true);
$tpl->setVar('reply_small_content', '
		if(empty($magic_quote))
			echo subUTF8(strip_tags($sql->row["content"]),32);
		else
			echo subUTF8(strip_tags(stripslashes($sql->row["content"])),32);
		',true);
$tpl->setVar('comment_a', $comment_a);
$tpl->setVar('comment_content', '
		if(empty($magic_quote))
			echo "\'".htmlspecialchars($sql->row["comment_content"])."\'";
		else
			echo "\'".htmlspecialchars(stripslashes($sql->row["comment_content"]))."\'";
		',true);
$tpl->setVar('comment_small_content', '
		if(empty($magic_quote))
			echo subUTF8(strip_tags($sql->row["comment_content"]),32);
		else
			echo subUTF8(strip_tags(stripslashes($sql->row["comment_content"])),32);
		',true);
$tpl->setVar('article_title_a', $article_a);
$tpl->setVar('article_tip', 'echo "\'".strip_tags($sql->row["article_title"])."\'";',true);
$tpl->setVar('article_title', 'echo subUTF8(strip_tags($sql->row["article_title"]),32);',true);
$tpl->setVar('username_a', $username_a);
$tpl->setVar('username',
		'if($sql->row["username"]==null)
		echo "游客";
		else
		echo $sql->row["username"];',true);
$tpl->setVar('nickname_a', $nickname_a);
$tpl->setVar('nickname', 'echo $sql->row["nickname"]',true);
$tpl->setVar('time', 'echo date("Y/n/j G:i:s",$sql->row["time"]);',true);
$tpl->setVar('reply_del', $del);
$tpl->setVar('articleID', 'echo $sql->row["articleID"];',true);
$tpl->setVar('endfor', '}',true);
$tpl->cache();
include $tpl->filecache;
?>