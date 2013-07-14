{php $unexpect_section = array("linkurl","linkurl_newopen","aboutus");}
{while $sql->parse_results()}
	{php $secId = $sql->row['sec_ID'];}
	{if isset($this->all[$secId])}
		{if in_array($this->all[$secId]["type"],$unexpect_section)}
			{php continue;}
		{elseif $this->all[$secId]["sec_dirpath"] != ""}
			{php $sec_dirpath = "html/" . $this->all[$secId]["sec_dirpath"] . "/" . $this->all[$secId]["sec_dirname"];}
		{else}
			{php $sec_dirpath = "html/" . $this->all[$secId]["sec_dirname"];}
		{/if}
	{else}
		{php $sec_dirpath = "";}
	{/if}

	{php $smimgpath = $sql->row["smimgpath"];}
	{if $smimgpath == ""}
		{php $smimgpath = $listshow_article_smimg_default;}
	{/if}
	{if !$flaghtml}
		{if $rvar_keyword != ""}
			{php $article_loc = $zengl_cms_rootdir . "add_edit_del_show_list_article.php?hidden=show" . "&amp;articleID=" . $sql->row["articleID"] . "&keyword=". urlencode($rvar_keyword);}
		{else}
			{php $article_loc = $zengl_cms_rootdir . "add_edit_del_show_list_article.php?hidden=show" . "&amp;articleID=" . $sql->row["articleID"];}
		{/if}
	{else}
		{php $article_loc = $zengl_cms_rootdir . $sec_dirpath . "/article-" . $sql->row["articleID"] . ".html";}
		{php $smimgpath = $zengl_cms_rootdir . $smimgpath;}
	{/if}
	{php $article_title = $sql->row["title"] . "<br/>" . "<img src='$smimgpath'>" . "<br/>" . $sql->row["descript"];}
<span>
	<a href="{$article_loc}" title='{htmlentities($article_title,ENT_QUOTES,"utf-8")}' class= 'article_title'>{subUTF8($sql->row["title"],32)}</a> &nbsp; 
	{$this->all[$sql->row[sec_ID]][sec_name]} &nbsp;&nbsp; {date("Y/n/j G:i:s",$sql->row[time])} 
</span>
{/while}