<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/style.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/admin_list_comments.css" media="screen">
<!--<link rel="stylesheet" type="text/css" href="{zengl theme}/css/tip-darkgray.css" media="screen">-->
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/tip-yellowsimple.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.paginate.js"></script> 
<script type="text/javascript" src="{zengl theme}/js/jquery.simplemodal.1.4.2.min.js"></script> 
<script type="text/javascript" src="{zengl theme}/js/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.tablesorter.min.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
	$('#selectAll').click(function(){
					$('.checkID').attr('checked',true);
					$('.checkID').parent().parent().css('background-color', '#f389ca');
					return false;
						});
	$('#unselect').click(function(){
					$('.checkID').attr('checked',false);
					$('.checkID').parent().parent().css('background-color', '');
					return false;
						});
	$('.checkID').click(function(){
					if($(this)[0].checked)
					{
						$(this).parent().parent().css('background-color', '#f389ca');
					}
					else
						$(this).parent().parent().css('background-color', '');
					});
	clickdel_href = '';
	$('#del_dialog').hide();
	$('.del_comment').click(function(){
					delStr = '您要删除以下评论吗：<br/>';
					delStr += $(this).parent().parent().find('.comment_sm_content_td').text();
					//$(this).parent().parent().find('.comment_sm_content_td').css('background-color', 'red');
					$('#del_dialog p').html(delStr);
					clickdel_href = $(this).attr("href");
					$('#del_dialog').modal({maxWidth:400});
					return false;
				});
	$('#multidel').click(function(){
					delStr = '您要删除以下评论吗：<br/>';
					clickdel_href = "{zengl cms_root_dir}comment_operate.php?action=multidel_comments";
					checknum = $('.checkID').size();
					count = 0;
					commentidStr = '';
					articleidStr = '';
					for(i=0;i<checknum;i++)
					{
						if($('.checkID:eq('+i+')')[0].checked)
						{
							delStr += '(id:' + $('.checkID:eq('+i+')')[0].value + ') 内容:' + $('.comment_sm_content_td:eq('+i+')').text() + '<br/>';
							if(count == 0)
							{
								commentidStr += '&commentID=' + $('.checkID:eq('+i+')')[0].value;
								articleidStr += '&articleID=' + $('.articleID:eq('+i+')')[0].value;
							}
							else
							{
								commentidStr += ',' + $('.checkID:eq('+i+')')[0].value;
								articleidStr += ',' + $('.articleID:eq('+i+')')[0].value;
							}
							count++;
						}
					}
					clickdel_href += commentidStr + articleidStr;
					$('#del_dialog p').html(delStr);
					$('#del_dialog').modal({maxWidth:400});
					return false;
						});
	$('#clearCache').click(function(){
					delStr = '您要删除以下文章的评论缓存吗：<br/>';
					clickdel_href = "{zengl cms_root_dir}comment_operate.php?action=clearCache";
					checknum = $('.checkID').size();
					count = 0;
					articleidStr = '';
					for(i=0;i<checknum;i++)
					{
						if($('.checkID:eq('+i+')')[0].checked)
						{
							delStr += '(id:' + $('.checkID:eq('+i+')')[0].value + ') 内容:' + $('.article_title_a:eq('+i+')').text() + '<br/>';
							if(count == 0)
								articleidStr += '&articleID=' + $('.articleID:eq('+i+')')[0].value;
							else
								articleidStr += ',' + $('.articleID:eq('+i+')')[0].value;
							count++;
						}
					}
					clickdel_href += articleidStr;
					$('#del_dialog p').html(delStr);
					$('#del_dialog').modal({maxWidth:400});
					return false;
						});
	$('.yes').click(function(){
					if(clickdel_href != '')
						location.href = clickdel_href;
					return false;
				});
	$('#set_comment_list').click(function(){
					valnum = $('#comment_list_num')[0].value;
					if(isNaN(valnum) || valnum == '')
					{
						alert("必须是数字");
						return false;
					}
					location.href = "{zengl cms_root_dir}comment_operate.php?action=admin_set_comment_num"+
										"&commentNum="+valnum;
					return false;
				});
		
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
					        					location.href = "{zengl cms_root_dir}comment_operate.php?action=admin_comment_list&sec_page="+sec_page+
					        										{zengl page_change};
				                     		} 
	    					}); 
	    					
	  $(".widelink_content a,#set_comment_list").hover(function(){
							$(this).css({"background":'red'});
						},function(){
							$(this).css({"background":'black'});
						}).css({"background":'black',"color":'white'}).prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
	  $(".widelink a").hover(function(){
							$(this).css({"background":'red'});
						},function(){
							$(this).css({"background":'#f389ca'});
						}).css({"background":'#f389ca',"color":'white'}).prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
	  $('.comment_table').tablesorter(); 
	});
</script>
<title>{zengl title}</title>
</head>
<body>
<div id = "maindiv">
	<h2>{zengl title}</h2>
	<input type='text' value='{zengl comment_list_num}' id='comment_list_num' size='5' />&nbsp;&nbsp;
	<a href='#' id='set_comment_list'>设置显示数目</a> &nbsp;&nbsp; 当前:{zengl current_num}/共:{zengl totalnum}
	<div class="comments">
		<div id="comments" class = 'widelink_content'>
			<table class='comment_table'>
				<thead> 
					<tr align='center'><th>评论ID&nbsp;&nbsp;&nbsp;&nbsp;</th><th>选择&nbsp;&nbsp;&nbsp;&nbsp;</th><th>评论内容&nbsp;&nbsp;&nbsp;&nbsp;</th><th>所属文章&nbsp;&nbsp;&nbsp;&nbsp;</th><th>所属用户&nbsp;&nbsp;&nbsp;&nbsp;</th><th>评论昵称&nbsp;&nbsp;&nbsp;&nbsp;</th><th>发表时间&nbsp;&nbsp;&nbsp;&nbsp;</th><th>操作&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>
				</thead> 
				<tbody> 
				{zengl for_comments}
				<tr>
					<td align='center' class='comment_td' >{zengl comment_id}</td>
					<td align='center' class='comment_td'><input type="checkbox" class="checkID" value="{zengl comment_id}"></td>
					<td class='comment_td'><a href={zengl comment_a} target='_blank' title={zengl comment_content} class = 'comment_sm_content_td'>
						{zengl comment_small_content}</a></td>
					<td class='comment_td'><a href={zengl article_title_a} title={zengl article_tip} class = 'article_title_a'>{zengl article_title}</a></td>
					<td class='comment_td'><a href={zengl username_a}>{zengl username}</a></td>
					<td class='comment_td'><a href={zengl nickname_a}>{zengl nickname}</a></td>
					<td class='comment_td'>{zengl time}</td>
					<td class='comment_td'><a href={zengl comment_del} class='del_comment'>删除</a>/<a href={zengl comment_reply} class='reply_comment'>回复</a></td>
					<td class='comment_td'><input class='articleID' type='hidden' value="{zengl articleID}" /></td>
				</tr>
				{zengl endfor}
				</tbody> 
			</table>
		</div>
		<div id="buttom_op" class = 'widelink'>
			<a id='selectAll' href='#'>全选</a>
			<a id='unselect' href='#'>取消选择</a>
			<a id='multidel' href='#'>批量删除</a>
			<a id='clearCache' href='#'>清除缓存</a>
		</div>
		<div id="pages"></div>
	</div>
	
	<div id='del_dialog'>
		<p>你确定要删除评论吗?</p>
		<div class='buttons'>
			<div class='no simplemodal-close'>No</div><div class='yes'>Yes</div>
		</div>
	</div>
</div>
</body>
</html>