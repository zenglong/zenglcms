<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<base href="http://{zengl cms_site_domain}{zengl cms_root_dir}" /> 
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="keywords" content="{zengl keyword}">
<meta name="description" content="{zengl descript}"/>
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/superfish.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/colorbox.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/show_article.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/hoverIntent.js"></script>
<script type="text/javascript" src="{zengl theme}/js/superfish.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.tagsphere.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.colorbox-min.js"></script> 
{zengl sec_array}
<script type="text/javascript">
var ishtml = '{zengl ishtml}';
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
						return false;
					}
					location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=list&sec_ID='+s+'&is_recur=yes';
					return false;
						});
		$(".wide_link a,#sections a,.sf-menu a").hover(function(){
						$(this).css({"background":'red'});
					},function(){
						$(this).css({"background":'black'});
					}).css({"background":'black',"color":'white'});
		$(".wide_link a,#sections a").prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
		
		if($('#tags_cloud ul li').length > 0)
		{
			$('#tags_cloud').tagcloud({centrex:100, centrey:100,fps:20});
		}
		else
			$('#tags_cloud').hide();
		
		$('.more_tags').colorbox({iframe:true, width:"80%", height:"80%"});
		
		$('#show_comments').attr('src','{zengl cms_root_dir}comment_operate.php?action=show&articleID={zengl articleID}'+
								'&random='+Math.random());
		$('#spanScansCount').load('{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=getScansCount'+
								'&articleID={zengl articleID}',
								function(response, status, xhr) {
										 $('#getScansWait').hide();	                          		 
									});
		if(ishtml == 'yes')
			$('#user_area').load('{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=show&articleID={zengl articleID}&isfromhtml=yes',
									function(response, status, xhr){
										$('#user_area a').prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;").hover(function(){
																$(this).css({"background":'red'});
															},function(){
																$(this).css({"background":'black'});
															}).css({"background":'black',"color":'white'});
									});
	});
</script>
<title>{zengl title}</title>
</head>
<body>
<div id = "maindiv">
	{zengl header}
	<div id = "user_area" class = 'wide_link'>{zengl username}  {zengl user_operate}  </div> 
	{zengl secmenu} "menu_id" , "sf-menu" {zengl secmenu_end}
	<br/><br/><br/>
	<div id = 'sections'>{zengl sections}</div>
	<div id='article_div'>
		<div id = "article_head">
			&nbsp;
		</div>
		<div id = "article_middle">
			<div id = "article_content">
				<h2>{zengl title}</h2>
					{zengl author}<br/>
					{zengl time} &nbsp;&nbsp;浏览次数：<span id='spanScansCount'></span>
					<span id='getScansWait'><img src='{zengl cms_root_dir}images/loading.gif' /> 正在读取。。。</span>
				<div id='tags_div'>{zengl tags}</div>
				<div id = 'descript_smimg_div'>
					<div id='smimgdiv'><img src='{zengl smimgpath}' /></div>
					<div id='descriptdiv'>{zengl descript}</div>
				</div>
				<div id='contentdiv'>
					{zengl content}<br/>
				</div>
			</div>
		</div>
		<div id = "article_last">
		</div>
		<div id = "article_pre_next">
			{zengl pre_article} <br/>
			{zengl next_article}
		</div>
	</div>
	<div id='tags_comment'>友情提示：单击下面标签云框来启动滚动或停止滚动！</div>
	<div id='tags_cloud' style="width:200px; height:200px; background-color:#000;">
		<ul>
			{zengl for_tags}
			<li><a href={zengl tag_loc} title='{zengl tag_name}'>{zengl tag_name}</a></li>
			{zengl endfor}
		</ul>
	</div>
	<div class='more_tags_div'><a href={zengl more_tags_loc} class='more_tags'>(more tags...)</a></div>
	
	<div id= 'comments_wrap'>
		<iframe id='show_comments' border=0 marginWidth=0
	       frameSpacing=0 marginHeight=0 frameBorder=0
	       noResize  scrolling="no" width=100% height=100% vspale="0" ></iframe>
    </div>
    
   
</div>

{zengl footer}
</body>
</html>