<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/style.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/show_comment.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.paginate.js"></script>  
<script type="text/javascript">
/**
* 自动调整IFrame的高度
* @ author Dekn 
* @ 2005-11-28
*/

$(document).ready(function() {
	function IFrameResize(){

	 //alert(this.document.body.scrollHeight); //弹出当前页面的高度
	 var obj = parent.document.getElementById("show_comments");  //取得父页面IFrame对象
	 //alert(obj.height); //弹出父页面中IFrame中设置的高度
	 obj.height = this.document.body.scrollHeight+100;  //调整父页面中IFrame的高度为此页面的高度
	
	}
	$('#refresh').click(function(){
       					location.href = "{zengl cms_root_dir}comment_operate.php?action=show"+'&articleID='+{zengl articleID};
       					return false;
        		});
    IFrameResize();
    sec_pagenum = {zengl sec_PageNum};
	if(sec_pagenum > 0)
	{
	    $("#pages").paginate({ 
					        count    : {zengl sec_PageNum}, 
					        start    : {zengl sec_StartPage}, 
					        display  : {zengl sec_DisplaySize}, 
					        text_color				  : 'white',
					        background_color        : 'black',
					        text_hover_color          : 'white', 
					        background_hover_color    : 'red',
					        images                    : false, 
					        mouse                    : 'press', 
					        onChange      : function(sec_page){
							                       location.href = "{zengl cms_root_dir}comment_operate.php?action=show"+
							                       			 '&sec_page=' + sec_page+'&articleID='+{zengl articleID};
							                       return false;
					                     		} 
		    					});
	} 
});
</script>
</head>
<body>
<div class='comment_add'>
	<a href='{zengl cms_root_dir}comment_operate.php?action=add&articleID={zengl articleID}' 
		class='comment_add_a'>发表评论</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' id='refresh'>点偶刷新...</a>
		<span class='wait'><span>
</div>
{zengl for_comment}
	{zengl if_newcomment}
	<div>
		<div class='comment_head'>
		</div>
		<div class='comment_middle'>
			{zengl user}&nbsp;&nbsp;&nbsp;&nbsp;发表时间:{zengl time}&nbsp;&nbsp;&nbsp;&nbsp;
			<a href={zengl reply_str} class='replyClick'>回复</a>&nbsp;&nbsp;&nbsp;&nbsp;{zengl commentNum}/{zengl commentCnt}
			<br/><br/>
			{zengl content}<br/>
			{zengl ip}
	{zengl endif}
	{zengl if_hasreply}
		<div class='reply'>
			<span class="reply_str">回复</span>
			<div class="reply_user">{zengl reply_user}&nbsp;&nbsp;&nbsp;&nbsp;回复时间:{zengl reply_time}
			&nbsp;&nbsp;&nbsp;&nbsp;{zengl replyNum}/{zengl replyCnt_{zengl replyCnt}}</div>
			<div class="reply_content">{zengl reply_content}</div>
			<div>{zengl reply_ip}</div>
		</div>
	{zengl endif}
{zengl endfor}
{zengl if_count_big_zero}
		</div>
		<div class='comment_bottom'>
		</div>
	</div>
	<div id="pages"></div>
{zengl endif}
</body>
</html>