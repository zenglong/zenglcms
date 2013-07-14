<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
{if !$flaghtml}
	{php $mytheme_path = $zengl_cms_tpl_dir . $zengl_theme;}
{else}
	{php $mytheme_path = $zengl_cms_rootdir . $zengl_cms_tpl_dir . $zengl_theme;}
{/if}

{php $secId = $rvar_sec_ID;}
{if isset($this->all[$secId])}
	{if $this->all[$secId]["sec_dirpath"] != ""}
		{php $sec_dirpath = "html/" . $this->all[$secId]["sec_dirpath"] . "/" . $this->all[$secId]["sec_dirname"];}
	{else}
		{php $sec_dirpath = "html/" . $this->all[$secId]["sec_dirname"];}
	{/if}
{else}
	{php $sec_dirpath = "";}
{/if}
<link rel="stylesheet" type="text/css" href="{$mytheme_path}/css/superfish.css" media="screen">
<link rel="stylesheet" type="text/css" href="{$mytheme_path}/css/style.css" media="screen">
<link rel="stylesheet" type="text/css" href="{$mytheme_path}/css/colorbox.css" media="screen">
<link rel="stylesheet" type="text/css" href="{$mytheme_path}/css/tip-yellowsimple.css" media="screen">
<link rel="stylesheet" type="text/css" href="{$mytheme_path}/css/list_article.css" media="screen">
<script type="text/javascript" src="{$mytheme_path}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{$mytheme_path}/js/hoverIntent.js"></script>
<script type="text/javascript" src="{$mytheme_path}/js/superfish.js"></script>
<script type="text/javascript" src="{$mytheme_path}/js/jquery.paginate.js"></script> 
<script type="text/javascript" src="{$mytheme_path}/js/jquery.colorbox-min.js"></script> 
<script type="text/javascript" src="{$mytheme_path}/js/jquery.poshytip.min.js"></script>
<script type="text/javascript">
var sec_dirpath = '';
$(document).ready(function() {
		$('ul.sf-menu').superfish();
		function list_articles()
		{
			li_num = $('#article span').size();
			want_num = 7;
			img_num = Math.ceil(li_num / want_num);
			for(i=0,j=0;i<img_num;i++,j=0)
			{
				j+=i * want_num;
				$('#article span:eq(' + j + ')').before("<div id='article_li_" + j + "'>");
				for(tmp=j;tmp<j + want_num && tmp<li_num;tmp++)
				{
					$('#article span:eq(' + tmp + ')').appendTo('#article_li_' + j);
				}
			}
		}
		list_articles();
		sec_pagenum = {ceil($sql->rownum/$this->page_size)};
		if(sec_pagenum > 0)
			$("#pages").paginate({ 
				        count    : sec_pagenum {php $sql->query($sql->sql_desc . " limit 0,$this->page_size");}, 
				        start    : 1, 
				        display  : {$this->display_size},
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
								/*if($('#isrecur')[0].checked)
									$isrecur =  $('#isrecur')[0].value;
								else
									$isrecur = "no";*/
								$isrecur = "yes"; //默认递归显示子栏目内容
								if((query_value = encodeURI($('#query_word_hidden')[0].value)) != '')
									query_value = '&keyword=' + query_value;
								else
									query_value = '';
								if($('#tags').length<=0 && query_value == '')
								{
									  {if $adminHtml_genhtml == 'yes'}
										  sec_dirpath = '{$sec_dirpath}';
										  if(sec_page != 1)
											loadstr = '{$zengl_cms_rootdir}' + sec_dirpath + '/index-' + sec_page + '.html';
										  else
										  {
											location.href = '{$zengl_cms_rootdir}' + sec_dirpath + '/';
											return false;
										  }
									  {else}
										  loadstr = "{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=listajax&sec_ID=" + 
													{$rvar_sec_ID} + '&is_recur=' + $isrecur + 
													'&sec_page=' + sec_page;
									  {/if}
								}
								else if(query_value != '')
								{
									type = $('input[name=query_type]:checked').val();
									loadstr = "{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=listajax" + 
												query_value + '&query_type=' + type + '&sec_page=' + sec_page;
								}
								else
									loadstr = "{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=listajax&tag=" + 
												{$rvar_tag} + '&sec_page=' + sec_page;
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
	    				if(settings.url != '{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=show&articleID=1&isfromhtml=yes')
        					$(this).html("<img src='{$zengl_cms_rootdir}images/loading.gif' /> 正在读取。。。");
        				//$('#article').css("height",128 + "px");
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
			$("#menu_id a,.widelink a,.widelink_content a,#query_div a").hover(function(){
							$(this).css({"background":'red'});
						},function(){
							$(this).css({"background":'black'});
						}).css({"background":'black',"color":'white'});
			if(!is_init_a_style)
			{
				$(".widelink a,#query_div a").prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
				/*
					下面先通过调用getStringWidth函数得到菜单中每个菜单项里的文本的字符串宽度，
					然后根据这个宽度值来设置菜单的像素宽。从而可以达到在各种浏览器下都可以用的菜单换行效果。
				*/
				$("#menu_id li a").each(function(i){
					$(this).css({"width":getStringWidth($(this).text())});
				});
				is_init_a_style = true;
			}
			$(".widelink_content a").prepend("&nbsp;&nbsp;").append("&nbsp;&nbsp;");
		}

		set_a_style();
		
		if($('.more_tags').length>0)
			$('.more_tags').colorbox({iframe:true, width:"80%", height:"80%"});
		$('.article_title').poshytip({
					//className: 'tip-darkgray',
					className: 'tip-yellowsimple',
					bgImageFrameSize: 11,
					offsetX: -25 
				});
		
		$("#query_btn").click(function(){
					value = encodeURI($('#query_word')[0].value);
					if(value == '')
					{
						alert('请填写关键词');
						return false;
					}
					type = $("input[name='query_type']:checked").val();
					timestamp=new Date().getTime();
					location.href = "{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=list&keyword=" + 
										value + '&query_type=' + type + '&timestamp=' + timestamp;
					return false;
				});

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
	});
</script>
<title>{$title}</title>
{if $this->all[$rvar_sec_ID][keyword] != ''}
<meta name="keywords" content="{$this->all[$rvar_sec_ID][keyword]}">
{/if}
{if $this->all[$rvar_sec_ID][sec_description] != ''}
<meta name="description" content="{str_replace(array("\r","\n","\t","'","\""),"",$this->all[$rvar_sec_ID][sec_description])}"/>
{/if}
</head>
<body>
<div id = "maindiv">
{php $this->header();}
<div id = "user_area" class = 'widelink'>{if !$flaghtml}{$username}{/if}  {if !$flaghtml}{$user_op}{/if}  </div> 

{mytpl_recur_show_secs(&$this,1,1,"menu_id","sf-menu")}
<br/><br/>
{if isset($tagstr)}{$tagstr}{/if}
<br/>
<div>&nbsp;</div>
<div id="query_div" style="width:600px;">
<input type = 'text' id='query_word' value="{$rvar_keyword}" />&nbsp;&nbsp;<a href='#' id='query_btn'>查询</a>
<input type = 'radio' name = 'query_type' value = '1' id='query_type_title' {if $rvar_query_type==1 || $rvar_query_type == ""}checked='checked'{/if}> <label for='query_type_title'>仅按标题查询</label>
<input type = 'radio' name = 'query_type' value = '2' id='query_type_content' {if $rvar_query_type==2}checked='checked'{/if}> <label for='query_type_content'>全文查询</label>
<input type = 'hidden' id='query_word_hidden' value="{$rvar_keyword}" />
</div>

<div class="article">
	<table>
	<tr>
	<td>
	<div id="article" class = 'widelink_content'>
		{while $sql->parse_results()}
			{php $secId = $sql->row['sec_ID'];}
			{if isset($this->all[$secId])}
				{if $this->all[$secId]["sec_dirpath"] != ""}
					{php $sec_dirpath = "html/" . $this->all[$secId]["sec_dirpath"] . "/" . $this->all[$secId]["sec_dirname"];}
				{else}
					{php $sec_dirpath = "html/" . $this->all[$secId]["sec_dirname"];}
				{/if}
			{else}
				{php $sec_dirpath = "";}
			{/if}

			{php $smimgpath = $sql->row["smimgpath"];}
			{if $smimgpath == ""}
				{php $smimgpath = $listshow_article_smimg_default;}
			{/if}
			{if !$flaghtml}
				{if $rvar_keyword != ""}
					{php $article_loc = $zengl_cms_rootdir . "add_edit_del_show_list_article.php?hidden=show" . "&amp;articleID=" . $sql->row["articleID"] . "&keyword=". urlencode($rvar_keyword);}
				{else}
					{php $article_loc = $zengl_cms_rootdir . "add_edit_del_show_list_article.php?hidden=show" . "&amp;articleID=" . $sql->row["articleID"];}
				{/if}
			{else}
				{php $article_loc = $zengl_cms_rootdir . $sec_dirpath . "/article-" . $sql->row["articleID"] . ".html";}
				{php $smimgpath = $zengl_cms_rootdir . $smimgpath;}
			{/if}
			{php $article_title = $sql->row["title"] . "<br/>" . "<img src='$smimgpath'>" . "<br/>" . $sql->row["descript"];}
		<span>
			<a href="{$article_loc}" title='{htmlentities($article_title,ENT_QUOTES,"utf-8")}' class= 'article_title'>{subUTF8($sql->row["title"],32)}</a> &nbsp; 
			{$this->all[$sql->row[sec_ID]][sec_name]} &nbsp;&nbsp; {date("Y/n/j G:i:s",$sql->row[time])} 
		</span>
		{/while}
	</div>
	<div id="pages"></div>
	</td>
	<td valign="top">
	<div id="recommend_articles">
		<span id="recommend_title">站点推荐：</span>
		{if $sql->db_type == MYSQL}
			{php $sql->query("select * from " .$sql->tables_prefix . "articles where level=1 order by rand() limit 8");}
		{else}
			{php $sql->query("select * from " .$sql->tables_prefix . "articles where level=1 order by random() limit 8");}
		{/if}
		<table>
		{while $sql->parse_results()}
			{php $secId = $sql->row['sec_ID'];}
			{if isset($this->all[$secId])}
				{if $this->all[$secId]["sec_dirpath"] != ""}
					{php $sec_dirpath = "html/" . $this->all[$secId]["sec_dirpath"] . "/" . $this->all[$secId]["sec_dirname"];}
				{else}
					{php $sec_dirpath = "html/" . $this->all[$secId]["sec_dirname"];}
				{/if}
			{else}
				{php $sec_dirpath = "";}
			{/if}

			{if !$flaghtml}
			{php $article_loc = $zengl_cms_rootdir . "add_edit_del_show_list_article.php?hidden=show" . "&amp;articleID=" . $sql->row["articleID"];}
			{else}
			{php $article_loc = $zengl_cms_rootdir . $sec_dirpath . "/article-" . $sql->row["articleID"] . ".html";}
			{/if}
			<tr><td><a href="{$article_loc}" title="{$sql->row[title]}">{subUTF8($sql->row["title"],32)}</a></td></tr>
		{/while}
		</table>
	</div>
	</td>
	</tr>
	</table>
</div>

</div>
{php $this->footer();}
</body>
</html>