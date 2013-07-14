<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/superfish.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/index.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/skin.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/hoverIntent.js"></script>
<script type="text/javascript" src="{zengl theme}/js/superfish.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.jcarousel.min.js"></script>
{zengl sec_array}
<script type="text/javascript">
ishtml = '{zengl ishtml}';
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
		$('ul.sf-menu a').click(function(){
					s = $(this)[0].href;
					s = s.substr(s.lastIndexOf('/')+1);
					if(isNaN(s))
					{
						location.href = $(this)[0].href;
						return false;
					}
					
					if(ishtml == 'yes')
					{
						if(sec_array[s]['sec_dirpath'] != '')
							sec_dirpath = 'html/' + sec_array[s]['sec_dirpath'] + '/' + 
											sec_array[s]['sec_dirname'];
						else
							sec_dirpath = 'html/' + sec_array[s]['sec_dirname'];
						location.href='{zengl cms_root_dir}' + sec_dirpath + '/';
					}
					else
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=list&sec_ID='+s+'&is_recur=yes';
					return false;
						});
		if(ishtml == 'yes')
			$('#user_area').load('{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=show&articleID=1&isfromhtml=yes',
									function(response, status, xhr){
									});
		if(ishtml == 'yes')
			$('.comment_img_middle').load('{zengl cms_root_dir}comment_operate.php?action=getsome&isfromhtml=yes',function(){
				$('.comment_img_middle a').hover(function(){
					$(this).addClass('a_hover').css({"color":"white"});
				},function(){
					$(this).removeClass('a_hover').css({"color":"#000"});
				});
			});
		else
			$('.comment_img_middle').load('{zengl cms_root_dir}comment_operate.php?action=getsome',function(){
				$('.comment_img_middle a').hover(function(){
					$(this).addClass('a_hover').css({"color":"white"});
				},function(){
					$(this).removeClass('a_hover').css({"color":"#000"});
				});
			});
		$(".comment_img_middle").ajaxSend(function(event, request, settings){
			if(settings.url != '{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=show&articleID=1&isfromhtml=yes')
				$(this).html("<img src='{zengl cms_root_dir}images/loading.gif' /> 正在读取评论数据。。。");
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
	});
</script>
<title>{zengl title}</title>
<meta name="keywords" content="zengl,开源网,zengl编程语言,zengl_language,zenglCMS,FCKeditor,中文手册"/>
<meta name="description" content="zengl开源网，zengl编程语言开发自己的编程语言，zenglcms开发自己的CMS系统，FCKeditor中文使用手册"/>
</head>
<body>
<div id = "maindiv">
{zengl header}
<div id = "user_area">{zengl username}  {zengl user_operate}</div>
<br/><br/>

{zengl secmenu} "menu_id" , "sf-menu" {zengl secmenu_end}
<br/><br/><br/>

<div id='centerdiv'>
	<div id='center_left'>
		<div id='recent_updates'>
			<div class='updates_img_header'>
			&nbsp;
			</div>
			<div class='updates_img_middle'>
				<div class='recent_header'>最近更新：</div>
				{zengl for_recent_updates}
				<span><a href='{zengl update_loc}' title = '{zengl update_title}'>{zengl update_sm_title}</a></span>
				{zengl for_recent_end}
			</div>
			<div class='updates_img_footer'>
			</div>
		</div>
		
		<ul id='sm_imgs' class="jcarousel-skin-tango">
			{zengl for_imgs}
			<li><a href='{zengl img_loc}'><img src='{zengl img_src}' width='200' height='150' alt='{zengl img_title}' title='{zengl img_title}' /></a></li>
			{zengl for_imgs_end}
		</ul>
		
		<div class="article">
		{zengl articles_divs}
		</div>
	</div>
	<div id='center_right'>
		<div class='comment_img_header'>
		</div>
		<div class='comment_img_middle'>
			评论侧
		</div>
		<div class='comment_img_footer'>
		</div>
	</div>
	<div style = 'clear:both;'></div>
</div>

</div>
{zengl footer}
</body>
</html>