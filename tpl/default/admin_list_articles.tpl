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
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/style.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/admin_list_articles.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.paginate.js"></script> 
<script type="text/javascript" src="{zengl theme}/js/jquery.simplemodal.1.4.2.min.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
		$(".delArticle").click(function(){
					if(confirm("是否将此信息删除?")){
					 	location.href = $(this)[0].href;
						return false;
					}
					else { 
						return false;
					} 
						});
		$("#sec_ID").change(function() {
					if($('#isrecur')[0].checked)
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list&sec_ID='+$(this)[0].value+'&is_recur='+$('#isrecur')[0].value;
					else
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list&sec_ID='+$(this)[0].value+'&is_recur=no';
						});
		$('#isrecur').change(function(){
					if($(this)[0].checked)
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list&sec_ID='+$("#sec_ID")[0].value+'&is_recur='+$(this)[0].value;
					else
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list&sec_ID='+$("#sec_ID")[0].value+'&is_recur=no';
						});
		$('#selectAll').click(function(){
					$('.checkID').attr('checked',true);
					return false;
						});
		$('#unselect').click(function(){
					$('.checkID').attr('checked',false);
					return false;
						});
		$('#multidel').click(function(){
					delStr = '您要删除以下文章吗：<br/>';
					checknum = $('.checkID').size();
					for(i=0;i<checknum;i++)
					{
						if($('.checkID:eq('+i+')')[0].checked)
							delStr += '(id:' + $('.checkID:eq('+i+')')[0].value + ') 标题:' + $('.td_title:eq('+i+')').text() + '<br/>';
					}
					$('#del_dialog p').html(delStr);
					$('#del_dialog').modal({maxWidth:400});
					return false;
						});
		$('#multiHTML').click(function(){
					htmlStr = '您要静态化以下文章吗：<br/>';
					checknum = $('.checkID').size();
					for(i=0;i<checknum;i++)
					{
						if($('.checkID:eq('+i+')')[0].checked)
							htmlStr += '(id:' + $('.checkID:eq('+i+')')[0].value + ') 标题:' + $('.td_title:eq('+i+')').text() + '<br/>';
					}
					$('#html_dialog p').html(htmlStr);
					$('#html_dialog').modal({maxWidth:400});
					return false;
						});
		var multimove_modal;
		var multimove_sec_id;
		$('#multimove').click(function(){
					$('#sec_ID_copy').empty();
					$('#sec_ID_copy').append($('#sec_ID option').not('[value="0"]').clone());
					multimove_modal = $('#move_dialog').modal({maxWidth:400});
					return false;
						});
		$('#del_dialog').hide();
		$('#html_dialog').hide();
		$('#move_dialog').hide();
		$('#move_dialog_ask').hide();
		$('.yes').click(function(){
					locationStr = '{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=multidel';
					checknum = $('.checkID').size();
					count = 0;
					for(i=0;i<checknum;i++)
					{
						if($('.checkID:eq('+i+')')[0].checked)
						{
							if(count == 0)
								locationStr += '&id=' + $('.checkID:eq('+i+')')[0].value
							else
								locationStr += ',' + $('.checkID:eq('+i+')')[0].value;
							count++;
						}
					}
					location.href = locationStr;
					return false;
						});
		$('.html_yes').click(function(){
					locationStr = '{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=multiHTML';
					checknum = $('.checkID').size();
					count = 0;
					for(i=0;i<checknum;i++)
					{
						if($('.checkID:eq('+i+')')[0].checked)
						{
							if(count == 0)
								locationStr += '&id=' + $('.checkID:eq('+i+')')[0].value
							else
								locationStr += ',' + $('.checkID:eq('+i+')')[0].value;
							count++;
						}
					}
					location.href = locationStr;
					return false;
						});
		$('.move_yes').click(function(){
					moveStr = '您要移动下面的文章到栏目：' + $('#sec_ID_copy').find("option:selected").text() + '吗?<br/>';
					multimove_sec_id = $('#sec_ID_copy').val();
					checknum = $('.checkID').size();
					for(i=0;i<checknum;i++)
					{
						if($('.checkID:eq('+i+')')[0].checked)
							moveStr += '(id:' + $('.checkID:eq('+i+')')[0].value + ') 标题:' + $('.td_title:eq('+i+')').text() + '<br/>';
					}
					$('#move_dialog_ask p').html(moveStr);
					multimove_modal.close();
					$('#move_dialog_ask').modal({maxWidth:400});
					return false;
						});
		$('.move_ask_yes').click(function(){
					locationStr = '{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=multimove&sec_ID=' + 
									multimove_sec_id;
					checknum = $('.checkID').size();
					count = 0;
					for(i=0;i<checknum;i++)
					{
						if($('.checkID:eq('+i+')')[0].checked)
						{
							if(count == 0)
								locationStr += '&id=' + $('.checkID:eq('+i+')')[0].value
							else
								locationStr += ',' + $('.checkID:eq('+i+')')[0].value;
							count++;
						}
					}
					location.href = locationStr;
					return false;
						});
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
					                           $("#article").load("{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=listajax&sec_ID=" + 
					                           $("#sec_ID")[0].value + '&is_recur=' + $isrecur + 
					                          '&sec_page=' + sec_page, 
					                          function(response, status, xhr) {
					                          		 set_a_style();
					                          	  });
				                     		} 
	    					}); 
	    $("#article").ajaxSend(function(event, request, settings){
        				$(this).html("<img src='images/loading.gif' /> 正在读取。。。");
        				//$('#article').css("height",128 + "px");
        					});
        is_init_a_style = false;
       function set_a_style()
        {
			$(".widelink_content a").hover(function(){
							$(this).css({"background":'red'});
						},function(){
							$(this).css({"background":'black'});
						}).css({"background":'black',"color":'white'}).prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
			if(!is_init_a_style)
			{
				$(".widelink a").hover(function(){
							$(this).css({"background":'red'});
						},function(){
							$(this).css({"background":'#f389ca'});
						}).css({"background":'#f389ca',"color":'white'}).prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
				is_init_a_style = true;
			}
		}
		set_a_style();
	});
</script>
<title>{zengl title}</title>
</head>
<body>
<div id = "maindiv">
<h2>{zengl title}</h2>

<div id = "section_input">
	{zengl sections} &nbsp;&nbsp; 
	<input type="checkbox" name="isrecur" id="isrecur" value="yes" {zengl ischecked}>是否显示子栏目</input>
	<input type="hidden" name="sec_ID" id="sec_ID" value="{zengl sec_ID}">
</div>
<br/>
<div class="article">
	<div id="article" class = 'widelink_content'>
		<table>
			<tr align='center'><th>文章ID</th><th>选择</th><th>文章标题</th><th>所属栏目</th><th>时间</th><th>状态</th><th>操作</th></tr>
			{zengl for_articles}
			<tr>
				<td align='center' >{zengl article_id}</td>
				<td align='center'><input type="checkbox" class="checkID" value="{zengl article_id}"></td>
				<td><a class = 'td_title' href={zengl article_loc} target='_blank' title='{zengl article_tip}'>{zengl article_title}</a></td> 
				<td>{zengl sec_name} </td>
				<td> {zengl article_time} </td>
				<td> {zengl article_html_status} </td>
				<td> 
					<a href={zengl article_edit}>编辑</a>
					<a href={zengl article_del} class='delArticle'>删除</a> 
				</td>
			</tr>
			{zengl endfor}
		</table>
	</div>
	<div id="buttom_op" class = 'widelink'>
		<a id='selectAll' href='#'>全选</a>
		<a id='unselect' href='#'>取消选择</a>
		<a id='multidel' href='#'>批量删除</a>
		<a id='multimove' href='#'>批量移动</a>
		<a id='multiHTML' href='#'>批量静态化</a>
	</div>
	<div id="pages"></div>
</div>

<div id='del_dialog'>
	<p>你要删除文件吗?</p>
	<div class='buttons'>
		<div class='no simplemodal-close'>No</div><div class='yes'>Yes</div>
	</div>
</div>

<div id='html_dialog'>
	<p>你要静态化文件吗?</p>
	<div class='buttons'>
		<div class='html_no simplemodal-close'>No</div><div class='html_yes'>Yes</div>
	</div>
</div>

<div id='move_dialog'>
	<p>请选择要转移到下面哪个栏目：</p>
	<select id="sec_ID_copy">
	</select>
	<div class='buttons'>
		<div class='move_no simplemodal-close'>No</div><div class='move_yes'>Yes</div>
	</div>
</div>

<div id='move_dialog_ask'>
	<p>你要移动文件吗?</p>
	<div class='buttons'>
		<div class='move_ask_no simplemodal-close'>No</div><div class='move_ask_yes'>Yes</div>
	</div>
</div>

</div>
</body>
</html>