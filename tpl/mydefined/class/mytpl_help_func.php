<?php 
global $unexpect_list_sections_array;
global $unexpect_menu_sections_array;
$unexpect_list_sections_array = array("linkurl","linkurl_newopen","aboutus","friendlink","public_notice");
$unexpect_menu_sections_array = array("friendlink","public_notice");

function mytpl_recur_show_secs($article = null,$id = '',$count='',$idname = '',$classname = '')
{
	global $zengl_cms_rootdir;
	global $adminHtml_genhtml;
	global $unexpect_menu_sections_array;
	if($count == 1)
		echo "<ul id='$idname' class='$classname'>";
	else
		echo "<ul>";

	if($count == 1)
	{
		if($article->all == null)
			$article->getall();
		if($adminHtml_genhtml == 'yes')
			echo "<li><a href='{$zengl_cms_rootdir}index.html'>主页</a></li>";
		else
			echo "<li><a href='{$zengl_cms_rootdir}index.php'>主页</a></li>";
		
		if($article->all[$id]['type']=='linkurl') //外链则显示外链地址
			echo "<li><a href='{$article->all[$id]['linkurl']}'>{$article->all[$id]['sec_name']}</a></li>";
		else if($article->all[$id]['type']=='linkurl_newopen')
			echo "<li><a href='{$article->all[$id]['linkurl']}' target='_blank'>{$article->all[$id]['sec_name']}</a></li>";
		else if(!in_array($article->all[$id]['type'], $unexpect_menu_sections_array)) //根据栏目类型判断是否可以显示在菜单中
		{
			if($adminHtml_genhtml == 'yes') //普通栏目静态路径
			{ 
				echo "<li><a href='{$zengl_cms_rootdir}html/";
				if($article->all[$id]['sec_dirpath'] != '')
					echo "{$article->all[$id]['sec_dirpath']}/{$article->all[$id]['sec_dirname']}";
				else
					echo "{$article->all[$id]['sec_dirname']}";
				echo "'>{$article->all[$id]['sec_name']}</a></li>";
			}
			else //普通栏目动态路径
				echo "<li><a href='{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=list&amp;sec_ID=".$id.
					"&amp;is_recur=yes'>{$article->all[$id]['sec_name']}</a></li>";
		}
	}
	if($article->all[$id]['sec_content']!='')
	{
		$content = explode(',', $article->all[$id]['sec_content']);
		foreach ($content as $next)
		{
			if($article->all[$next]['type'] == 'linkurl') //外链则显示外链地址
				echo "<li><a href='{$article->all[$next]['linkurl']}'>{$article->all[$next]['sec_name']}</a>";
			else if($article->all[$next]['type'] == 'linkurl_newopen') //外链则显示外链地址
				echo "<li><a href='{$article->all[$next]['linkurl']}' target='_blank'>{$article->all[$next]['sec_name']}</a>";
			else if(!in_array($article->all[$next]['type'], $unexpect_menu_sections_array)) //根据栏目类型判断是否可以显示在菜单中
			{
				if($adminHtml_genhtml == 'yes') //普通栏目静态路径
				{ 
					echo "<li><a href='{$zengl_cms_rootdir}html/";
					if($article->all[$next]['sec_dirpath'] != '')
						echo "{$article->all[$next]['sec_dirpath']}/{$article->all[$next]['sec_dirname']}";
					else
						echo "{$article->all[$next]['sec_dirname']}";
					echo "'>{$article->all[$next]['sec_name']}</a>";
				}
				else //普通栏目动态路径
					echo "<li><a href='{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=list&amp;sec_ID=".$next.
						"&amp;is_recur=yes'>{$article->all[$next]['sec_name']}</a>";
			}
			
			if($article->all[$next]['sec_content']!='')
				$article->mytpl_recur_show_secs(&$article,$next, $count+1);
			echo "</li>";
		}
	}
	echo "</ul>";
}
function mytpl_index_articles_divs($article = null)
{
	global $adminHtml_genhtml;
	global $zengl_cms_rootdir;
	global $unexpect_list_sections_array;
	$sql = &$article->sql;
	$i = '0';
	$count = 0;
	$sec_count = 0;
	if($adminHtml_genhtml == 'yes')
		$flaghtml = true;
	else
		$flaghtml = false;
	while ($sql->parse_results())
	{
		if(in_array($article->all[$sql->row['sec_ID']]['type'],$unexpect_list_sections_array)) //判断是否可以显示在列表中
			continue;
		if($sql->row['sec_ID'] != $i)
		{
			if(++$sec_count > $article->index_sec_count)
				break;
			if(!$flaghtml)
				$loc_more = "{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=list&amp;sec_ID={$sql->row['sec_ID']}" . 
						  	"&amp;is_recur=yes";
			else
			{
				$secId = $sql->row['sec_ID'];
				if($article->all[$secId]['sec_dirpath'] != '')
					$sec_dirpath = 'html/' . $article->all[$secId]['sec_dirpath'] . '/' .
									  $article->all[$secId]['sec_dirname'];
				else
					$sec_dirpath = 'html/' . $article->all[$secId]['sec_dirname'];
				$loc_more = "{$zengl_cms_rootdir}{$sec_dirpath}" . '/';
			}
			if($i != '0')
				echo "</div></div><div class='article_img_footer'></div></div>";
			$i = $sql->row['sec_ID'];
			echo "<div class='wrap_div'>
					<div class='article_img_header'>
					</div>
					<div class='article_img_middle'>
					<div class='wrap_header'>
					<span>
					{$article->all[$sql->row["sec_ID"]]["sec_name"]}
					&nbsp;&nbsp; <a href='$loc_more' title='查看<{$article->all[$sql->row["sec_ID"]]["sec_name"]}>更多的文章信息'>
					more...</a>
					</span>
					</div>
					<div class='wrap_content'>";	
			$count = 0;
		}
		if(!$flaghtml)
			$loc_article = "{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=show&amp;articleID={$sql->row['articleID']}";
		else 
		{
			$loc_article = "{$zengl_cms_rootdir}{$sec_dirpath}" . '/' . 'article-' .
							 $sql->row['articleID'] . '.html';
		}
		if(++$count <= $article->index_count)
		{
			$title = subUTF8($sql->row['title'],30);
			echo "<span><a href = '$loc_article' title= '{$sql->row['title']}'>$title</a></span>";
		}
	}
	if($i!=0)
		echo "</div></div><div class='article_img_footer'></div></div>";
}
function mytpl_unexpect_sec_sqlstr($article = null,$colname = 'sec_ID')
{
	global $unexpect_list_sections_array;
	$sec_sqlstr = '';
	foreach ($article->all as $k => $v)
	{
		if(in_array($v['type'],$unexpect_list_sections_array))
		{
			$sec_sqlstr .= "{$colname} != {$v['sec_ID']} and ";
		}
	}
	return $sec_sqlstr;
}
?>