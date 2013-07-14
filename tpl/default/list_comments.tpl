<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/style.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/list_comments.css" media="screen">
<!--<link rel="stylesheet" type="text/css" href="{zengl theme}/css/tip-darkgray.css" media="screen">-->
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/tip-yellowsimple.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.paginate.js"></script> 
<script type="text/javascript" src="{zengl theme}/js/jquery.simplemodal.1.4.2.min.js"></script> 
<script type="text/javascript" src="{zengl theme}/js/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.tablesorter.min.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
	$('.comment_sm_content_td').poshytip({
					//className: 'tip-darkgray',
					className: 'tip-yellowsimple',
					bgImageFrameSize: 11,
					offsetX: -25 
				});
	sec_pagenum = {zengl sec_PageNum};
	if(sec_pagenum > 0)
			$("#pages").paginate({ 
				        count    : {zengl sec_PageNum} {zengl sec_query}, 
				        start    : {zengl startPage}, 
				        display  : {zengl sec_DisplaySize}, 
				        text_color				  : 'white',
				        background_color        : 'black',
				        text_hover_color          : 'white', 
				        background_hover_color    : 'red',
				        images                    : false, 
				        mouse                    : 'press', 
				        onChange      : function(sec_page){ 
					        					location.href = "{zengl cms_root_dir}comment_operate.php?action=get_list&sec_page="+sec_page+
					        										{zengl page_change};
				                     		} 
	    					}); 
	    					
	  $(".widelink_content a,#set_comment_list").prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
	  $(".widelink a").prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
	  $("#comments table tr").hover(function(){
				$(this).addClass("td_hover");
				$(this).find("a").css({"color":'#fff'});
			},function(){
				$(this).removeClass("td_hover");
				$(this).find("a").css({"color":'#000'});
		});
	  $('.comment_table').tablesorter(); 
	});
</script>
<title>{zengl title}</title>
</head>
<body>
<div id = "maindiv">
	<div id="header_title">{zengl title}</div>
	&nbsp;&nbsp; 当前:{zengl current_num}/共:{zengl totalnum} &nbsp; <a href="{zengl cms_root_dir}comment_operate.php?action=get_list">返回至所有评论</a>
	<div class="comments">
		<div id="comments" class = 'widelink_content'>
			<table class='comment_table'>
				<thead> 
					<tr align='center'><th>评论ID&nbsp;&nbsp;&nbsp;&nbsp;</th><th>评论内容&nbsp;&nbsp;&nbsp;&nbsp;</th><th>所属文章&nbsp;&nbsp;&nbsp;&nbsp;</th><th>评论昵称&nbsp;&nbsp;&nbsp;&nbsp;</th><th>更新时间&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>
				</thead> 
				<tbody> 
				{zengl for_comments}
				<tr>
					<td align='center' class='comment_td' >{zengl comment_id}</td>
					<td class='comment_td'><a href={zengl comment_a} target='_parent' title={zengl comment_content} class = 'comment_sm_content_td'>
						{zengl comment_small_content}</a></td>
					<td class='comment_td'><a href={zengl article_title_a} title={zengl article_tip} class = 'article_title_a'>{zengl article_title}</a></td>
					<td class='comment_td'><a href={zengl nickname_a}>{zengl nickname}</a></td>
					<td class='comment_td'>{zengl time}</td>
					<td class='comment_td'><input class='articleID' type='hidden' value="{zengl articleID}" /></td>
				</tr>
				{zengl endfor}
				</tbody> 
			</table>
		</div>
		<div id="pages"></div>
	</div>
	
</div>
</body>
</html>