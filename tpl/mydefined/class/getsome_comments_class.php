<?php
global $zengl_cms_rootdir;
global $rvar_isfromhtml;
$sql = &$this->sql;
$tablename = "{$sql->tables_prefix}comment";
$table_article = "{$sql->tables_prefix}articles";
$sql->query("select $tablename.content as content,$tablename.articleID as articleID, " . 
		" $table_article.sec_ID as sec_ID from $tablename left join $table_article " . 
		" on $tablename.articleID = $table_article.articleID order by $tablename.showtime desc" .
		" limit 0,5");
if($sql->get_num()<=0)
	die('<span>暂无评论</span>');
$article = new article();
$magic_quote = get_magic_quotes_gpc();
$output = '<span class="comment_head">最新评论：&nbsp;<a href="'.$zengl_cms_rootdir.'comment_operate.php?action=get_list"'.
		 ' class="comment_list" title="显示完整评论列表">(more...)</a></span>';
while($sql->parse_results())
{
	if($rvar_isfromhtml == 'yes')
	{
		$sec_path = $article->GetSecDirFullPath($sql->row["sec_ID"]);
		$output .= '<span class="comment_content"><a href="' . $zengl_cms_rootdir . $sec_path . '/article-' . 
			  	 	$sql->row["articleID"] . '.html" title="点击查看详情">';
	}
	else
		$output .= '<span class="comment_content"><a href="' . $zengl_cms_rootdir . "add_edit_del_show_list_article.php?hidden=show&articleID=" .
				$sql->row["articleID"] . '" title="点击查看详情">';
	if(empty($magic_quote))
		$output .= subUTF8(strip_tags($sql->row["content"]),32);
	else
		$output .=subUTF8(strip_tags(stripslashes($sql->row["content"])),32);
	$output .= '</a></span>';
}
echo $output;
?>