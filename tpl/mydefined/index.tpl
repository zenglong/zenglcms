<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
{php $mytheme_path = $zengl_cms_tpl_dir . $zengl_theme}
<link rel="stylesheet" type="text/css" href="{$mytheme_path}/css/superfish.css" media="screen">
<link rel="stylesheet" type="text/css" href="{$mytheme_path}/css/colorbox.css" media="screen">
<link rel="stylesheet" type="text/css" href="{$mytheme_path}/css/index.css" media="screen">
<link rel="stylesheet" type="text/css" href="{$mytheme_path}/css/skin.css" media="screen">
<script type="text/javascript" src="{$mytheme_path}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{$mytheme_path}/js/hoverIntent.js"></script>
<script type="text/javascript" src="{$mytheme_path}/js/superfish.js"></script>
<script type="text/javascript" src="{$mytheme_path}/js/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="{$mytheme_path}/js/jquery.colorbox-min.js"></script>
<script type="text/javascript">
function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        //carousel.startAuto(0);
	 carousel.stopAuto();
    });

    carousel.buttonPrev.bind('click', function() {
        //carousel.startAuto(0);
	 carousel.stopAuto();
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};
$(document).ready(function() {
		$('ul.sf-menu').superfish();
		{if $adminHtml_genhtml == 'yes'}
			$('#user_area').load('{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=show&articleID=1&isfromhtml=yes',
									function(response, status, xhr){
										$('#user_area a').prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;").hover(function(){
																$(this).css({"background":'red'});
															},function(){
																$(this).css({"background":'black'});
															}).css({"background":'black',"color":'white'});
									});
		{/if}

		{if $adminHtml_genhtml == 'yes'}
			$('.comment_img_middle').load('{$zengl_cms_rootdir}comment_operate.php?action=getsome&isfromhtml=yes',function(){
				$('.comment_img_middle a').hover(function(){
					$(this).addClass('a_hover').css({"color":"white"});
				},function(){
					$(this).removeClass('a_hover').css({"color":"#000"});
				});
				$('.comment_list').colorbox({iframe:true, width:"90%", height:"90%"});
			});
		{else}
			$('.comment_img_middle').load('{$zengl_cms_rootdir}comment_operate.php?action=getsome',function(){
				$('.comment_img_middle a').hover(function(){
					$(this).addClass('a_hover').css({"color":"white"});
				},function(){
					$(this).removeClass('a_hover').css({"color":"#000"});
				});
				$('.comment_list').colorbox({iframe:true, width:"90%", height:"90%"});
			});
		{/if}
		$(".comment_img_middle").ajaxSend(function(event, request, settings){
			if(settings.url != '{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=show&articleID=1&isfromhtml=yes')
				$(this).html("<img src='{$zengl_cms_rootdir}images/loading.gif' /> 正在读取评论数据。。。");
			});
		$('#sm_imgs').jcarousel({
			auto: 2,
		    wrap: 'last',
		    initCallback: mycarousel_initCallback
    	});
    	//$('.article .wrap_div').addClass('shadow');
    	$('#recent_updates a,.article .wrap_div a').hover(function(){
    		$(this).addClass('a_hover').css({"color":"white"});
    	},function(){
    		$(this).removeClass('a_hover').css({"color":"#000"});
    	});

       is_init_a_style = false;
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
	function set_a_style()
	{
		$("#menu_id a,.widelink a").hover(function(){
						$(this).css({"background":'red'});
					},function(){
						$(this).css({"background":'black'});
					}).css({"background":'black',"color":'white'});
		if(!is_init_a_style)
		{
			$(".widelink a").prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
			/*
				下面先通过调用getStringWidth函数得到菜单中每个菜单项里的文本的字符串宽度，
				然后根据这个宽度值来设置菜单的像素宽。从而可以达到在各种浏览器下都可以用的菜单换行效果。
			*/
			$("#menu_id li a").each(function(i){
				$(this).css({"width":getStringWidth($(this).text())});
			});
			is_init_a_style = true;
		}
	}
	set_a_style();
});
</script>
<title>{$title}</title>
<meta name="keywords" content="zengl,开源网,zengl编程语言,zengl_language,zenglCMS,FCKeditor,中文手册"/>
<meta name="description" content="zengl开源网，zengl编程语言开发自己的编程语言，zenglcms开发自己的CMS系统，FCKeditor中文使用手册"/>
</head>
<body>
<div id = "maindiv">
{php $this->header()}
<div id = "user_area" class = 'widelink'>{if !$flaghtml}{$username}{/if} {if !$flaghtml}{$user_op}{/if}</div>
<br/><br/>

{mytpl_recur_show_secs(&$this,1,1,"menu_id","sf-menu")}
<br/><br/><br/>

<!--<div id='centerdiv'>-->
	<table>
	<tr>
	<td>
	<div id='center_left'>
		<div id='recent_updates'>
			<div class='updates_img_header'>
			&nbsp;
			</div>
			<div class='updates_img_middle'>
				<div class='recent_header'>最近更新：</div>
				{php $sql->query($sqlstr_recent);}
				{if $sql->get_num()==0}
					暂无文章！
				{else}
					{php $update_num = 0;}
					{while $sql->parse_results()}
						{php $update_sec = $sql->row["sec_ID"]; $update_sec = $this->GetSecDirFullPath($update_sec);}
						{if !$flaghtml}
							<span><a href='{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=show&amp;articleID={$sql->row[articleID]}' title = '{$sql->row[title]}'>{php echo subUTF8($sql->row["title"],30);}</a></span>
						{else}
							<span><a href='{$zengl_cms_rootdir}{$update_sec}/article-{$sql->row[articleID]}.html' title = '{$sql->row[title]}'>{php echo subUTF8($sql->row["title"],30);}</a></span>
						{/if}
						{php $update_num++;}
					{/while}
				{/if}
			</div>
			<div class='updates_img_footer'>
			</div>
		</div>
		
		<ul id='sm_imgs' class="jcarousel-skin-tango">
			{php $sql->query($sqlstr_imgs);}
			{if $sql->rownum == 0}
				暂无图片！
			{else}
				{php $img_num = 0;}
				{while $sql->parse_results()}
					{php $img_sec = $sql->row["sec_ID"]; $img_sec = $this->GetSecDirFullPath($img_sec);}
					<li>
						{if !$flaghtml}
						<a href='{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=show&amp;articleID={$sql->row[articleID]}'>
						{else}
						<a href='{$zengl_cms_rootdir}{$img_sec}/article-{$sql->row[articleID]}.html'>
						{/if}
						{if empty($magic_quote)}
							<img src='{$sql->row[smimgpath]}' width='200' height='150' alt='{$sql->row[title]}' title='{$sql->row[title]}' />
						{else}
							<img src='{php echo stripslashes($sql->row["smimgpath"]);}' 
							width='200' height='150' alt='{$sql->row[title]}' title='{$sql->row[title]}' />
						{/if}
						</a>
					</li>
					{php $img_num++;}
				{/while}
			{/if}
		</ul>
		
		<div class="article">
		{php $sql->query($sqlstr_divs); mytpl_index_articles_divs(&$this);}
		</div>
	</div>
	</td>
	<td valign="top">
	<div id='center_right'>
		<div class='comment_img_header'>
		</div>
		<div class='comment_img_middle'>
			评论侧
		</div>
		<div class='comment_img_footer'>
		</div>
	</div>
	<div id='center_right'>
		<div class='comment_img_header'>
		</div>
		<div class='comment_img_public' style='background: url("{$mytheme_path}/images/index_img/index_comment_middle.jpg") repeat-y;'>
			<span class="comment_head">站点公告栏：</span>
			<span class="comment_content">
				{php $sql->query($sqlstr_public_notice);}
				{while $sql->parse_results()}
					{php $public_notice_array[]=$sql->row["content"];}
				{/while}
				{php $public_notice_count = count($public_notice_array);}
				{loop $public_notice_array $k $v}
					{if $k == $public_notice_count - 1}
					{$v}
					{else}
					<p>{$v}</p>
					{/if}
				{/loop}
			</span>
		</div>
		<div class='comment_img_footer'>
		</div>
	</div>
	<div style = 'clear:both;'></div>
	</td>
	</tr>
	</table>
<!--</div>-->

<div id="friendlink">
	友情链接：
	{php $sql->query($sqlstr_friendlink);}
	{while $sql->parse_results()}
		<a href="{$sql->row[friend_link]}" title="{$sql->row[friend_content]}" target="_blank">
		{if empty($magic_quote)}
			<img src='{$sql->row[smimgpath]}' width='150' height='80' alt='{$sql->row[friend_name]}' title='{$sql->row[friend_content]}' />
		{else}
			<img src='{php echo stripslashes($sql->row[smimgpath]);}' 
			width='150' height='80' alt='{$sql->row[friend_name]}' title='{$sql->row[friend_content]}' />
		{/if}
		</a>
	{/while}
</div>

</div>
{php $this->footer();}
</body>
</html>