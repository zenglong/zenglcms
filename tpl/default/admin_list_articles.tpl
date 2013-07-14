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
<script type="text/javascript" src="{zengl theme}/js/jquery.tablesorter.min.js"></script>
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
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list&sec_ID='+$(this)[0].value+'&is_recur='+$('#isrecur')[0].value + '&page_display_num=' + {$this->page_size} + '&list_order={$rvar_list_order}';
					else
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list&sec_ID='+$(this)[0].value+'&is_recur=no' + '&page_display_num=' + {$this->page_size} + '&list_order={$rvar_list_order}';
						});
		$('#isrecur').change(function(){
					if($(this)[0].checked)
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list&sec_ID='+$("#sec_ID")[0].value+'&is_recur='+$(this)[0].value + '&page_display_num=' + {$this->page_size} + '&list_order={$rvar_list_order}';
					else
						location.href='{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list&sec_ID='+$("#sec_ID")[0].value+'&is_recur=no' + '&page_display_num=' + {$this->page_size} + '&list_order={$rvar_list_order}';
						});
		$('#selectAll').click(function(){
					$('.checkID').attr('checked',true);
					return false;
						});
		$('#Invert_Selection').click(function(){
					checknum = $('.checkID').size();
					var ischecked;
					for(i=0;i<checknum;i++)
					{
						ischecked = $('.checkID:eq('+i+')')[0].checked;
						if(ischecked)
							$('.checkID:eq('+i+')').attr('checked',false);
						else
							$('.checkID:eq('+i+')').attr('checked',true);
					}
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
				        start    : {$rvar_sec_page},
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
					        				/*	if($('#isrecur')[0].checked)
					        					  $isrecur =  $('#isrecur')[0].value;
					        					else
					        					  $isrecur = "no";
					                           $("#article").load("{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=listajax&sec_ID=" + 
					                           $("#sec_ID")[0].value + '&is_recur=' + $isrecur + 
					                          '&sec_page=' + sec_page, 
					                          function(response, status, xhr) {
					                          		 set_a_style();
					                          	  });*/
											{if $rvar_keyword != ''}
											timestamp=new Date().getTime();
											location.href = "{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list" +
											  '&sec_page=' + sec_page + '&page_display_num={$this->page_size}' + '&keyword={urlencode($rvar_keyword)}' + '&query_type={$rvar_query_type}' + '&timestamp=' + timestamp + '&list_order={$rvar_list_order}';
											{else}
											if($('#isrecur')[0].checked)
					        					  $isrecur =  $('#isrecur')[0].value;
					        				else
					        					  $isrecur = "no";
											location.href = "{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list&sec_ID=" + 
														   $("#sec_ID")[0].value + '&is_recur=' + $isrecur + 
														  '&sec_page=' + sec_page + '&page_display_num=' + {$this->page_size} + '&list_order={$rvar_list_order}';
											{/if}
				                     		}
	    					}); 
	    $("#article").ajaxSend(function(event, request, settings){
        				$(this).html("<img src='images/loading.gif' /> 正在读取。。。");
        				//$('#article').css("height",128 + "px");
        					});
        is_init_a_style = false;
        function set_a_style()
        {
			$(".widelink_content a").prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
			if(!is_init_a_style)
			{
				$(".widelink a").prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
				is_init_a_style = true;
			}
			$("#article table tr").hover(function(){
					 $(this).addClass("td_hover");
					 $(this).find("a").css({"color":'#fff'});
					 $(this).find(".state_span_green,.state_span_red").css({"color":'#fff'});
				},function(){
					$(this).removeClass("td_hover");
					$(this).find("a").css({"color":'#000'});
					$(this).find(".state_span_green").css({"color":'#348e15'});
					$(this).find(".state_span_red").css({"color":'#ff0000'});
			});
			$('#list_articles_table').tablesorter();
			$('#buttom_op div').hover(function(){
				$(this).css({"background":'url({zengl theme}/images/admin_list_articles_css_img/greengradient.png) repeat-x',"color":'#fff'});
				$(this).find("a").css({"color":'#fff'});
			},function(){
				$(this).css({"background":'url({zengl theme}/images/admin_list_articles_css_img/graygradient.png) top left repeat-x',"color":'#000'});
				$(this).find("a").css({"color":'#000'});
			});
		}
		set_a_style();

		$(".smimg_cls").mouseover(function(){
			var src = $(this).find('img').attr('src');
			var top = $(this).offset().top;
			var left = $(this).offset().left;
			$("#smimg_tip").html('完整缩略图：<br/><img src="' + src + '" alt="图片加载中。。。"/>')
						   .css({"top" :(top-20) + "px",
								"left" :(left+90) + "px"}).show();
		}).mouseout(function(){
			$("#smimg_tip").hide();
		});

		$("#multiLevelSel").change(function(){
			locationStr = '{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=multiLevelChange&level=' + 
									$(this).val();
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
			if(count <= 0)
			{
				alert('请选择要设置的文章!');
				$(this).val(-1);
				return;
			}
			if($(this).val() < 0)
			{
				alert('请选择要设置的级别!');
				return;
			}
			location.href = locationStr;
		});

		$('#set_page_display_num').click(function(){
			{if $rvar_keyword != ''}
			timestamp=new Date().getTime();
			location.href = "{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list" +
			  '&sec_page=' + 1 + '&page_display_num=' + $('#page_display_num').val() + '&keyword={urlencode($rvar_keyword)}' + '&query_type={$rvar_query_type}' + '&timestamp=' + timestamp + '&list_order={$rvar_list_order}';
			{else}
			if($('#isrecur')[0].checked)
				  $isrecur =  $('#isrecur')[0].value;
			else
				  $isrecur = "no";
			location.href = "{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list&sec_ID=" + 
														   $("#sec_ID")[0].value + '&is_recur=' + $isrecur + 
														  '&sec_page=' + {$rvar_sec_page} + '&page_display_num=' + $('#page_display_num').val() +  '&list_order={$rvar_list_order}';
			{/if}
			return false;
		});

		$('#set_query_value').click(function(){
			value = encodeURI($('#query_word')[0].value);
			type = $('#query_type').val();
			timestamp=new Date().getTime();
			location.href = "{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list" +
											  '&sec_page=1' + '&page_display_num={$this->page_size}' + '&keyword=' + value + '&query_type=' + type + '&timestamp=' + timestamp + '&list_order={$rvar_list_order}';
		});

		$("#article_list_order").change(function(){
			var list_order_value = $(this).val();
			{if $rvar_keyword != ''}
			timestamp=new Date().getTime();
			location.href = "{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list" +
			  '&sec_page=1' + '&page_display_num={$this->page_size}' + '&keyword={urlencode($rvar_keyword)}' + '&query_type={$rvar_query_type}' + '&list_order=' + list_order_value +'&timestamp=' + timestamp;
			{else}
			if($('#isrecur')[0].checked)
				  $isrecur =  $('#isrecur')[0].value;
			else
				  $isrecur = "no";
			location.href = "{zengl cms_root_dir}add_edit_del_show_list_article.php?hidden=admin&action=list&sec_ID=" + 
						   $("#sec_ID")[0].value + '&is_recur=' + $isrecur + 
						  '&sec_page=1' + '&page_display_num=' + {$this->page_size} + '&list_order='+list_order_value;
			{/if}
		});
	});
</script>
<title>{zengl title}</title>
</head>
<style>
a:active {outline:none;blr:expression(this.onFocus=this.blur());}
img {
   border: 0;
}
#smimg_tip{
	display:none;
	position:absolute;
	color:#fff;
	text-align:center;
	background:#51B906;
	border:solid 1px green;
}
</style>
<body>
<div id = "maindiv">
<div id="header_title">{zengl title}</div>
<div id="smimg_tip"></div>

<div id = "section_input">
	{zengl sections} &nbsp;&nbsp; 
	<input type="checkbox" name="isrecur" id="isrecur" value="yes" {zengl ischecked}>是否显示子栏目</input>
	<input type="hidden" name="sec_ID" id="sec_ID" value="{zengl sec_ID}">
	<input type="text" id="page_display_num" value="{$this->page_size}" style="width:50px;">&nbsp;<a href="#" id="set_page_display_num">设置每页显示数</a>
	&nbsp;当前:{php echo $sql->get_num();}
	/共:{$article_totalnum}&nbsp;
	<select id="query_type">
		<option value="1"  {if $rvar_query_type==1 || $rvar_query_type == ""}selected="selected"{/if}>仅按标题查询</option>
		<option value="2"  {if $rvar_query_type==2}selected="selected"{/if}>全文查询</option>
	</select>&nbsp;
	<input type="text" id="query_word" value="{$rvar_keyword}" />&nbsp;<a href="#" id="set_query_value">查询</a>
	&nbsp;
	<select id="article_list_order">
		<option value="id_desc" {if $rvar_list_order == 'id_desc'}selected="selected"{/if}>按文章ID降序</option>
		<option value="id_asc" {if $rvar_list_order == 'id_asc'}selected="selected"{/if}>按文章ID升序</option>
		<option value="level_desc" {if $rvar_list_order == 'level_desc'}selected="selected"{/if}>按文章级别降序</option>
		<option value="level_asc" {if $rvar_list_order == 'level_asc'}selected="selected"{/if}>按文章级别升序</option>
		<option value="scansCount_desc" {if $rvar_list_order == 'scansCount_desc'}selected="selected"{/if}>按浏览量降序</option>
		<option value="scansCount_asc" {if $rvar_list_order == 'scansCount_asc'}selected="selected"{/if}>按浏览量升序</option>
		<option value="smimgpath_desc" {if $rvar_list_order == 'smimgpath_desc'}selected="selected"{/if}>按缩略图降序</option>
		<option value="smimgpath_asc" {if $rvar_list_order == 'smimgpath_asc'}selected="selected"{/if}>按缩略图升序</option>
		<option value="title_desc" {if $rvar_list_order == 'title_desc'}selected="selected"{/if}>按文章标题降序</option>
		<option value="title_asc" {if $rvar_list_order == 'title_asc'}selected="selected"{/if}>按文章标题升序</option>
		<option value="sec_ID_desc" {if $rvar_list_order == 'sec_ID_desc'}selected="selected"{/if}>按所属栏目ID降序</option>
		<option value="sec_ID_asc" {if $rvar_list_order == 'sec_ID_asc'}selected="selected"{/if}>按所属栏目ID升序</option>
		<option value="addtime_desc" {if $rvar_list_order == 'addtime_desc'}selected="selected"{/if}>按添加时间降序</option>
		<option value="addtime_asc" {if $rvar_list_order == 'addtime_asc'}selected="selected"{/if}>按添加时间升序</option>
		<option value="time_desc" {if $rvar_list_order == 'time_desc'}selected="selected"{/if}>按编辑时间降序</option>
		<option value="time_asc" {if $rvar_list_order == 'time_asc'}selected="selected"{/if}>按编辑时间升序</option>
	</select>
</div>
<br/>
<div class="article">
	<div id="article" class = 'widelink_content'>
		<table id="list_articles_table">
			<thead>
			<tr align='center'><th width="90">文章ID</th><th width="70">选择</th><th>级别</th><th>浏览量</th><th>缩略图</th><th>文章标题</th><th>所属栏目</th><th>添加时间</th><th>编辑时间</th><th>状态</th><th>操作</th></tr>
			</thead>
			<tbody>
			{zengl for_articles}
			<tr>
				<td align='center' >{zengl article_id}</td>
				<td align='center'><input type="checkbox" class="checkID" value="{zengl article_id}"></td>
				<td align='center'>{$sql->row[level]}</td>
				<td align='center'>{$sql->row[scansCount]}</td>
				{if $sql->row[smimgpath] != ''}
				<td align='center' class="smimg_cls"><img border="0" src="{$sql->row[smimgpath]}" width="15" ></td>
				{else}
				<td align='center'>&nbsp;</td>
				{/if}
				<td><a class = 'td_title' href={zengl article_loc} target='_blank' title='{zengl article_tip}' onfocus="this.blur()">{zengl article_title}</a></td> 
				<td>{zengl sec_name} </td>
				<td>{date("Y/n/j G:i:s",$sql->row[addtime])}</td>
				<td> {zengl article_time} </td>
				<td> {zengl article_html_status} </td>
				<td> 
					<a href={zengl article_edit} title="编辑" onfocus="this.blur()"><img src="{zengl theme}/images/admin_list_articles_css_img/edit.jpg" width="23"></a>
					<a href={zengl article_del} class='delArticle' title="删除" onfocus="this.blur()"><img src="{zengl theme}/images/admin_list_articles_css_img/del.jpg" width="23"></a> 
				</td>
			</tr>
			{zengl endfor}
			</tbody>
		</table>
	</div>
	<div id="buttom_op" class = 'widelink'>
		<div id='selectAll'>全选</div>
		<div id='Invert_Selection'>反选</div>
		<div id='unselect'>取消选择</div>
		<!--<div><a id='unselect' href='#' onfocus="this.blur()">取消选择</a></div>-->
		<div id='multidel'>批量删除</div>
		<div id='multimove'>批量移动</div>
		<div id='multiHTML'>批量静态化</div>
		<select id="multiLevelSel" WIDTH="100" STYLE="width: 100px;margin-top:8px;margin-left:10px;">
			<option value="-1" >批量设置级别</option>
			<option value="0" >取消级别</option>
		{php $article_setting_array = config_get_db_setting('article');$article_setting_array = explode("|",$article_setting_array['article_level']);$count=1;}
		{loop $article_setting_array $tmp_str}
			<option value="{$count}" >{$tmp_str}</option>
			{php $count++;}
		{/loop}
		</select>
	</div>
	<div id="buttom_op_clear"></div>
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