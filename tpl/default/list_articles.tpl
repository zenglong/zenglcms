<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- /** 该模板是显示文章列表的模板，for_articles标签会被替换为读取mysql返回的results集合的while循环，
	 endfor 将被替换为'}'作为while循环的结束， article_title会被替换为结果集中的某行记录的title数据，
	 也就是某文章的标题。
	 不过要特别注意的是由于目前使用的是简单的str_replace替换(执行效率高些)，所以标签中的空格只能有一个，以后会考虑用正则表达式来处理该问题。
	 其实空格的多少以及标签如何定义完全可以在list_articles.php文件里的str_replace函数中自由的定义，所以也不算是个问题。
	 还有个要注意的是不要在注释里用完整的标签，因为这些标签也会被替换，就会发生错误！
	 作者：zenglong
	 创建时间：2011年12月14日 */ 
-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/superfish.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/style.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/colorbox.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/tip-yellowsimple.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/list_article.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/hoverIntent.js"></script>
<script type="text/javascript" src="{zengl theme}/js/superfish.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.paginate.js"></script> 
<script type="text/javascript" src="{zengl theme}/js/jquery.colorbox-min.js"></script> 
<script type="text/javascript" src="{zengl theme}/js/jquery.poshytip.min.js"></script>
{zengl sec_array}
<script type="text/javascript">
var ishtml = '{zengl ishtml}';
var sec_dirpath = '';
$(document).ready(function() {
		if(ishtml == 'yes')
			$('#section_input').hide();
		$('.admin_editdel').hide();
		$("#sec_ID").change(function() {
					if($('#isrecur')[0].checked)
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=list&sec_ID='+$(this)[0].value+'&is_recur='+$('#isrecur')[0].value;
					else
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=list&sec_ID='+$(this)[0].value+'&is_recur=no';
						});
		$('#isrecur').change(function(){
					if($(this)[0].checked)
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=list&sec_ID='+$("#sec_ID")[0].value+'&is_recur='+$(this)[0].value;
					else
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=list&sec_ID='+$("#sec_ID")[0].value+'&is_recur=no';
						});
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
					if($('#isrecur')[0].checked)
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=list&sec_ID='+s+'&is_recur='+$('#isrecur')[0].value;
					else
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=list&sec_ID='+s+'&is_recur=no';
					return false;
						});
		function list_articles()
		{
			//li_num = $('#article ol li').size();
			li_num = $('#article span').size();
			want_num = 7;
			//margin_top = 140;
			img_num = Math.ceil(li_num / want_num);
			//$('#article').css("height",img_num * 280 + "px");
			for(i=0,j=0;i<img_num;i++,j=0)
			{
				j+=i * want_num;
				$('#article span:eq(' + j + ')').before("<div id='article_li_" + j + "'>");
				for(tmp=j;tmp<j + want_num && tmp<li_num;tmp++)
				{
					$('#article span:eq(' + tmp + ')').appendTo('#article_li_' + j);
				}
				/*if ($.browser.msie) {
					if ($.browser.version == "6.0") 
						$('#article_li_' + j).css('padding-bottom','80px');
				}*/
			}
		}
		list_articles();
		sec_pagenum = {zengl sec_PageNum};
		if(sec_pagenum > 0)
			$("#pages").paginate({ 
				        count    : {zengl sec_PageNum} {zengl sec_query}, 
				        start    : 1, 
				        display  : {zengl sec_DisplaySize}, 
				        //border                    : true, 
				        //border_color            : '#BEF8B8', 
				        //text_color              : '#79B5E3',
				        text_color				  : 'white',
				        //background_color        : '#E3F2E1',
				        background_color        : 'black',
				        //border_hover_color        : '#68BA64', 
				        //text_hover_color          : '#2573AF', 
				        //background_hover_color    : '#CAE6C6',
				        text_hover_color          : 'white', 
				        background_hover_color    : 'red',
				        images                    : false, 
				        mouse                    : 'press', 
				        onChange      : function(sec_page){ 
					        					if($('#isrecur')[0].checked)
					        					  $isrecur =  $('#isrecur')[0].value;
					        					else
					        					  $isrecur = "no";
					        					  if($('#tags').length<=0)
					        					  {
					        					  	  if(ishtml == 'yes')
					        					  	  {
					        					  	  	  sec_dirpath = '{zengl sec_dirpath}';
					        					  	  	  if(sec_page != 1)
					        					  	  	  	loadstr = '{zengl cms_root_dir}' + sec_dirpath + '/index-' + sec_page + '.html';
					        					  	  	  else
					        					  	  	  {
					        					  	  	  	location.href = '{zengl cms_root_dir}' + sec_dirpath + '/';
					        					  	  	  	return false;
					        					  	  	  }
					        					  	  }
					        					  	  else
							        					  loadstr = "{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=listajax&sec_ID=" + 
							                          	 $("#sec_ID")[0].value + '&is_recur=' + $isrecur + 
							                          	 '&sec_page=' + sec_page;
						                          }
						                       else
						                       	  loadstr = "{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=listajax&tag=" + 
						                           	 {zengl tagid} + '&sec_page=' + sec_page;
					                           $("#article").load(loadstr, 
										                          function(response, status, xhr) {
										                          		 $('.admin_editdel').hide();
										                          		 list_articles();
										                          		 set_a_style();
										                          		 $('.tip-yellowsimple').remove();
									                          		 	 $('.article_title').poshytip({
																				className: 'tip-yellowsimple',
																				bgImageFrameSize: 11,
																				offsetX: -25 
																				});
										                          	  });
				                     		} 
	    					}); 
	    $("#article").ajaxSend(function(event, request, settings){
	    				if(settings.url != '{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=show&articleID=1&isfromhtml=yes')
        					$(this).html("<img src='{zengl cms_root_dir}images/loading.gif' /> 正在读取。。。");
        				//$('#article').css("height",128 + "px");
        					});
        is_init_a_style = false;
       function set_a_style()
        {
			$("#menu_id a,.widelink a,.widelink_content a").hover(function(){
							$(this).css({"background":'red'});
						},function(){
							$(this).css({"background":'black'});
						}).css({"background":'black',"color":'white'});
			if(!is_init_a_style)
			{
				$(".widelink a").prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
				is_init_a_style = true;
			}
			$(".widelink_content a").prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
		}
		set_a_style();
		
		if($('.more_tags').length>0)
			$('.more_tags').colorbox({iframe:true, width:"80%", height:"80%"});
			//$('.more_tags').colorbox({width:"80%", height:"80%"});
		$('.article_title').poshytip({
					//className: 'tip-darkgray',
					className: 'tip-yellowsimple',
					bgImageFrameSize: 11,
					offsetX: -25 
				});
		if(ishtml == 'yes')
			$('#user_area').load('{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=show&articleID=1&isfromhtml=yes',
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
<div id = "user_area" class = 'widelink'>{zengl username}  {zengl user_operate}  </div> 
<div id = "section_input">
	{zengl sections} &nbsp;&nbsp; 
	<input type="checkbox" name="isrecur" id="isrecur" value="yes" {zengl ischecked}>是否显示子栏目</input>
	<input type="hidden" name="sec_ID" id="sec_ID" value="{zengl sec_ID}">
</div>

{zengl secmenu} "menu_id" , "sf-menu" {zengl secmenu_end}
<br/><br/>
{zengl tags}
<br/>

<div class="article">
	<div id="article" class = 'widelink_content'>
		{zengl for_articles}
		<span>
			<a href={zengl article_loc} title='{zengl article_tip}' class= 'article_title'>{zengl article_title}</a> &nbsp 
			{zengl sec_name} &nbsp;&nbsp; {zengl article_time} 
			<a href={zengl article_edit} class='admin_editdel'>编辑</a>
			<a href={zengl article_del} class='admin_editdel'>删除</a>
		</span>
		{zengl endfor}
	</div>
	<div id="pages"></div>
</div>

</div>
{zengl footer}
</body>
</html>