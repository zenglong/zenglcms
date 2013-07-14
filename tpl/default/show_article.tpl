<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<base href="http://{$zengl_cms_full_domain}{$zengl_cms_rootdir}" /> 
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="keywords" content="{$tagstr}">
<meta name="description" content="{str_replace(array("\r","\n","\t","'","\""),"",$sql->row[descript])}"/>
{if	!$flaghtml}
	{php $mytheme_path = $zengl_cms_tpl_dir . $zengl_theme;}
{else}
	{php $mytheme_path = $zengl_cms_rootdir . $zengl_cms_tpl_dir . $zengl_theme;}
{/if}
<link rel="stylesheet" type="text/css" href="{$mytheme_path}/css/superfish.css" media="screen">
<link rel="stylesheet" type="text/css" href="{$mytheme_path}/css/colorbox.css" media="screen">
<link rel="stylesheet" type="text/css" href="{$mytheme_path}/css/show_article.css" media="screen">
<script type="text/javascript" src="{$mytheme_path}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{$mytheme_path}/js/hoverIntent.js"></script>
<script type="text/javascript" src="{$mytheme_path}/js/superfish.js"></script>
<script type="text/javascript" src="{$mytheme_path}/js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="{$mytheme_path}/js/jquery.tagsphere.js"></script>
<script type="text/javascript" src="{$mytheme_path}/js/jquery.colorbox-min.js"></script> 
<script type="text/javascript" src="{$mytheme_path}/js/jquery.paginate.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
		$('ul.sf-menu').superfish();
		$(".wide_link a,#sections a,.sf-menu a").hover(function(){
						$(this).css({"background":'red'});
					},function(){
						$(this).css({"background":'black'});
					}).css({"background":'black',"color":'white'});
		$(".wide_link a,#sections a").prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
		pagenum = {$pagenum};
		if(pagenum > 1)
		{
			$('#pages a').hover(function(){
						$(this).css({"background":'red'});
					},function(){
						$(this).css({"background":'black'});
					}).css({"background":'black',"color":'white'
					}).prepend("&nbsp;").append("&nbsp;");
			$('#pages .cur_page').css({"background":'red',"color":'white'
					}).prepend("&nbsp;").append("&nbsp;");
		}

		/*由中英文字符串的个数得到字符串的像素宽度*/
	       function getStringWidth(str) {
			var width = len = str.length;
			for(var i=0; i < len; i++) {
				if(str.charCodeAt(i) >= 255) {
					width++;
				}
			}
			return width * 8 + 'px';
		}
		/*
			下面先通过调用getStringWidth函数得到菜单中每个菜单项里的文本的字符串宽度，
			然后根据这个宽度值来设置菜单的像素宽。从而可以达到在各种浏览器下都可以用的菜单换行效果。
		*/
		$("#menu_id li a").each(function(i){
			$(this).css({"width":getStringWidth($(this).text())});
		});
		
		if($('#tags_cloud ul li').length > 0)
		{
			$('#tags_cloud').tagcloud({centrex:100, centrey:100,fps:20});
		}
		else
			$('#tags_cloud').hide();
		
		$('.more_tags').colorbox({iframe:true, width:"80%", height:"80%"});
		
		$('#show_comments').attr('src','{$zengl_cms_rootdir}comment_operate.php?action=show&articleID={$rvar_articleID}'+
								'&random='+Math.random());
		$('#spanScansCount').load('{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=getScansCount'+
								'&articleID={$rvar_articleID}',
								function(response, status, xhr) {
										 $('#getScansWait').hide();	                          		 
									});

		{if $adminHtml_genhtml == 'yes'}
			$('#user_area').load('{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=show&articleID={$rvar_articleID}&isfromhtml=yes',
									function(response, status, xhr){
										$('#user_area a').prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;").hover(function(){
																$(this).css({"background":'red'});
															},function(){
																$(this).css({"background":'black'});
															}).css({"background":'black',"color":'white'});
									});
		{/if}
	});
</script>
<title>
{$sql->row[title]}
{if $pagenum > 1}
	(第{$rvar_page}分页/共{$pagenum}页)
{/if}
</title>
</head>
<body>
<div id = "maindiv">
	{php $this->header();}
	<div id = "user_area" class = 'wide_link'>{if !$flaghtml}{$username}{/if}  {if !$flaghtml}{$user_op}{/if}  </div> 
	{mytpl_recur_show_secs(&$this,1,1,"menu_id","sf-menu")}
	<br/><br/><br/>
	<div id = 'sections'>{php $this->show_article_sections($sql->row["sec_ID"],1);}</div>
	{if $flaghtml}
		{php $this->secstr = "";}
	{/if}
	<table>
	<tr>
	<td>
	<div id='article_div'>
		<div id = "article_head">
			&nbsp;
		</div>
		<div id = "article_middle">
			<div id = "article_content">
				<h2>
					{$sql->row[title]}
					{if $pagenum > 1}
						(第{$rvar_page}分页/共{$pagenum}页)
					{/if}
				</h2>
					{$sql->row[author]}<br/>
					添加时间:{date("Y/n/j G:i:s",$sql->row[addtime])} {if $sql->row['time'] != $sql->row['addtime']}更新时间:{date("Y/n/j G:i:s",$sql->row[time])}{/if} &nbsp;&nbsp;浏览次数：<span id='spanScansCount'></span>
					<span id='getScansWait'><img src='{$zengl_cms_rootdir}images/loading.gif' /> 正在读取。。。</span>
				<div id='tags_div'>{$taghtml}</div>
				<div id = 'descript_smimg_div'>
					<div id='smimgdiv'><img src='{$smimgpath}' /></div>
					<div id='descriptdiv'>{str_replace(array("\r","\n","\t"),'',$sql->row[descript])}</div>
				</div>
				<div id='contentdiv'>
					{if empty($magic_quote)}
						{$article_content}
					{else}
						{stripslashes($article_content)}
					{/if}
					<br/>
					<div id="pages">
					{php for($p=1;$p<=$pagenum && $pagenum > 1;$p++){ }
						{if $p == $rvar_page}
					&nbsp;<span class='cur_page'>{$p}</span>&nbsp;
						{elseif !$flaghtml}
					&nbsp;<a href="{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=show&articleID={$sql->row[articleID]}&page={$p}">{$p}</a>&nbsp;
						{elseif $p == 1}
					&nbsp;<a href="{$sec_dirpath}/article-{$sql->row[articleID]}.html">{$p}</a>&nbsp;
						{else}
					&nbsp;<a href="{$sec_dirpath}/article-{$sql->row[articleID]}-{$p}.html">{$p}</a>&nbsp;
						{/if}
					{php } }
					</div>
				</div>
			</div>
		</div>
		<div id = "article_last">
		</div>
		<div id = "article_pre_next">
			{php $sql->query("select * from ".$sql->tables_prefix."articles where articleID < $rvar_articleID and sec_ID = $rvar_sec_ID order by articleID DESC  limit 1 ");  $sql->parse_results();}
			{if $sql->row["articleID"] == null}
			上一篇：没有了
			{elseif !$flaghtml}
			上一篇：<a href="{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=show&articleID={$sql->row[articleID]}">{$sql->row[title]}</a>
			{else}
			上一篇：<a href="{$sec_dirpath}/article-{$sql->row[articleID]}.html">{$sql->row[title]}</a>
			{/if}
			<br/>
			{php $sql->query("select * from ".$sql->tables_prefix."articles where articleID > $rvar_articleID and sec_ID = $rvar_sec_ID order by articleID ASC  limit 1 ");  $sql->parse_results();}
			{if $sql->row["articleID"] == null}
			下一篇：没有了
			{elseif !$flaghtml}
			下一篇：<a href="{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=show&articleID={$sql->row[articleID]}">{$sql->row[title]}</a>
			{else}
			下一篇：<a href="{$sec_dirpath}/article-{$sql->row[articleID]}.html">{$sql->row[title]}</a>
			{/if}
		</div>
	</div>
	<div id= 'comments_wrap'>
		<iframe id='show_comments' border=0 marginWidth=0
	       frameSpacing=0 marginHeight=0 frameBorder=0
	       noResize  scrolling="no" width=100% height=100% vspale="0" ></iframe>
    </div>
	</td>
	<td valign="top">
	<div id='tags_comment'>友情提示：单击下面标签云框来启动滚动或停止滚动！</div>
	<div id='tags_cloud' style="width:200px; height:200px; background-color:#000;">
		<ul>
			{loop $tag_clouds $tag_name $tag_ID}
			<li><a href="add_edit_del_show_list_article.php?hidden=list&tag={$tag_ID}" title='{$tag_name}'>{$tag_name}</a></li>
			{/loop}
		</ul>
	</div>
	<div class='more_tags_div'><a href="{$zengl_cms_rootdir}tags_operate.php?action=getall" class='more_tags'>(more tags...)</a></div>
	<div class='more_tags_div'><img src="{$mytheme_path}/images/rightimg.png"/></div>
	<div class='more_tags_div'>和你在一起</div>
	</td>
	</tr>
	</table>
</div>

{php $this->footer();}
</body>
</html>