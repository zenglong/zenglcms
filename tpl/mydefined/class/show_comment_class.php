<?php
global $zengl_cms_rootdir;
global $rvar_sec_page;
global $rvar_articleID;
if(!(isset($rvar_articleID) && is_numeric($rvar_articleID)))
	new error('无法显示评论','无效的文章ID！',true,true);

if($rvar_sec_page == null)
	$rvar_sec_page = 1;
$magic_quote = get_magic_quotes_gpc();
$filetpl = $zengl_cms_tpl_dir . $zengl_theme .'/show_comment.tpl';
$commentCache = $zengl_cms_tpl_dir.'cache/comment_cache/show_comment_cache'.$rvar_articleID.'.php';
$hasCommentCache=file_exists($commentCache);
$filecache = $zengl_cms_tpl_dir.'cache/comment_cache/show_comment_cache'.$rvar_articleID.'_'.$rvar_sec_page.'.php';

if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');

if(file_exists($filecache) && (filemtime($filecache) > filemtime($filetpl)) &&
		(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) &&
		$hasCommentCache && (filemtime($filecache) > filemtime($commentCache)) )
{
	include $filecache;
	return;
}
if(!$hasCommentCache)
	touch($commentCache,time()-5);
$startPage = ($rvar_sec_page - 1) * $this->page_size;
$sql = &$this->sql;
$tablename = "{$sql->tables_prefix}comment";
$tablename_reply = "{$sql->tables_prefix}CommentReply";
$sql->query("select * from $tablename where articleID=$rvar_articleID");
$commentCnt = $sql->get_num();
$sqlstr = "select $tablename.username as username,$tablename.time as time,$tablename.content as content," .
"$tablename.ip_address as ip_address,$tablename.comment_ID as comment_ID,".
"$tablename_reply.reply_ID as reply_ID,$tablename_reply.username as reply_user,".
"$tablename_reply.time as reply_time,$tablename_reply.content as reply_content,".
"$tablename_reply.ip_address as reply_ip from $tablename left join $tablename_reply on ".
"$tablename.comment_ID = $tablename_reply.commentID where $tablename.articleID=$rvar_articleID ".
" order by $tablename.showtime desc,".
"$tablename_reply.time desc";
$sql->query($sqlstr);
$count = $sql->get_num();
$reply_str = '"'.$zengl_cms_rootdir.
'comment_operate.php?action=reply&commentID=<?php echo $sql->row["comment_ID"]; ?>'.
'&articleID=<?php echo $rvar_articleID ?>'.'"';
$oldid = -1;
$isfirst = true;
$commentNum = 0;
$replyNum = 0;
$replyCnt = 0;
$haspass = false;
$replyCntArray = array();
$hasReply = false;
$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
$tpl->setVar('for_comment', 'while($commentNum <= $startPage && $sql->parse_results()) { ',true);
$tpl->setVar('if_newcomment',
		'
		if($sql->row["comment_ID"] != $oldid)
		{
		$oldid = $sql->row["comment_ID"];
		$commentNum++;
		if($commentNum > $startPage)
		$haspass = true;
		}
	}
		$commentNum = $startPage;
		$oldid = -1;
		while($haspass == true || $sql->parse_results()) {
		if($haspass == true)
		$haspass = false;
		if($sql->row["comment_ID"] != $oldid)
		{
		$oldid = $sql->row["comment_ID"];
		$commentNum++;
		if($commentNum-$startPage > $this->page_size)
		break;
		if($hasReply == true)
		$replyCntArray["replyCnt_".$replyCnt++] = $replyNum;
		if($sql->row["reply_ID"] > 0)
		$hasReply = true;
		else
		$hasReply = false;
		$replyNum = 0;
		if($isfirst == false)
		{
		echo "</div><div class=\"comment_bottom\"></div></div>";
		}
		else
		$isfirst = false;',true);
$tpl->setVar('user', 'echo $sql->row["username"];',true);
$tpl->setVar('time', 'echo date("Y/n/j G:i:s",$sql->row["time"]);',true);
$tpl->setVar('reply_str', $reply_str);
$tpl->setVar('commentNum', 'echo $commentNum;',true);
$tpl->setVar('commentCnt', 'echo $commentCnt;',true);
$tpl->setVar('content', '
		if(empty($magic_quote))
			echo $sql->row["content"];
		else
			echo stripslashes($sql->row["content"]);',true);
$tpl->setVar('ip', 'echo $sql->row["ip_address"];',true);
$tpl->setVar('if_hasreply',
		'if($sql->row["reply_ID"] > 0)
		{ $replyNum++;',true);
$tpl->setVar('reply_user', 'echo $sql->row["reply_user"];',true);
$tpl->setVar('reply_time', 'echo date("Y/n/j G:i:s",$sql->row["reply_time"]);',true);
$tpl->setVar('replyNum', 'echo $replyNum;',true);
$tpl->setVar('replyCnt', 'echo $replyCnt;',true);
$tpl->setVar('reply_content', '
		if(empty($magic_quote))
			echo $sql->row["reply_content"];
		else
			echo stripslashes($sql->row["reply_content"]);',true);
$tpl->setVar('reply_ip', 'echo $sql->row["reply_ip"];',true);
$tpl->setVar('endif', '}',true);
$tpl->setVar('endfor', '}',true);
$tpl->setVar('if_count_big_zero',
		'if($commentNum-$startPage > 0)
		{
		if($hasReply == true)
		$replyCntArray["replyCnt_".$replyCnt++] = $replyNum;',true);
$tpl->setVar('sec_PageNum', 'echo ceil($commentCnt/$this->page_size);',true);
$tpl->setVar('sec_StartPage', 'echo $rvar_sec_page;',true);
$tpl->setVar('sec_DisplaySize', 'echo $this->display_size;',true);
$tpl->setVar('cms_root_dir', 'echo $zengl_cms_rootdir;',true);
$tpl->setVar('articleID', 'echo $rvar_articleID;',true);
$tpl->cache();
ob_start();
include $tpl->filecache;
$buffer = ob_get_contents();
ob_end_clean();
foreach ($replyCntArray as $key => $val)
	$buffer = str_replace("{zengl $key}", $val, $buffer);
file_put_contents($filecache,$buffer);
echo $buffer;
?>