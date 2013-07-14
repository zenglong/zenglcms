<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/admin_left.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript">
	var is_fold = false;
	$(document).ready(function(){
	   $("#admin_menu p.menu_head").click(function()
	    {
			/*$(this).css({backgroundImage:"url({zengl theme}/images/admin_left_css_img/down.png)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
			$(this).siblings().css({backgroundImage:"url({zengl theme}/images/admin_left_css_img/left.png)"});*/
			if(!is_fold)
			{
				$(this).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
			}
			else
			{
				$(this).next("div.menu_body").slideToggle(500);
			}
		});

	   $("#menu_fold_unfold").click(function(){
			if(!is_fold)
				$("div.menu_body").slideDown("slow");
			else
				$("div.menu_body").slideUp("slow");
			
			is_fold = !is_fold;
		});

		$("#menu_fold_unfold").click();
	}); 
</script>
<title>{zengl title}</title>
<base target="right"/>
<!--[if IE]>
<style type="text/css">html{overflow-x:hidden;overflow-y:auto;}</style>
<![endif]-->
<script type="text/javascript">window.onerror= function(){return true;}</script>
</head>
<body>
	<div class='menu_list' id='admin_menu'>
		<div><img src="{zengl theme}/images/admin_left_css_img/admin_zenglcms_header_bg.jpg"/></div>
		<p id="menu_fold_unfold" class='menu_head'>展开/折叠</p>

		{zengl can} SEC_ADD, SEC_DEL, SEC_EDIT {zengl _can}
			<p class='menu_head'>栏目操作</p>
			<div class = 'menu_body'>
				{zengl can} SEC_ADD {zengl _can}
				<a href='{zengl addsec_loc}'>添加栏目</a>
				{zengl end_can}
				{zengl can} SEC_DEL, SEC_EDIT {zengl _can}
				<a href='{zengl editdel_sec_loc}' >编辑,删除栏目</a>
				<a href='{zengl sec_menu_loc}' >栏目菜单调整</a>
				{zengl end_can}
			</div>
		{zengl end_can}
		
		{zengl can} ARTICLE_ADD, ARTICLE_EDIT, ARTICLE_DEL {zengl _can}
			<p class='menu_head'>文章操作</p>
			<div class = 'menu_body'>
				{zengl can} ARTICLE_ADD {zengl _can}
				<a href='{zengl add_article_loc}' >添加文章</a>
				{zengl end_can}
				{zengl can} ARTICLE_EDIT, ARTICLE_DEL {zengl _can}
				<a href='{zengl editdel_article_loc}' >编辑,删除文章</a>
				{zengl end_can}
			</div>
		{zengl end_can}
		
		{zengl can} ARCHIVE_UPLOAD, ARCHIVE_LIST {zengl _can}
			<p class='menu_head'>附件操作</p>
			<div class = 'menu_body'>
				{zengl can} ARCHIVE_UPLOAD {zengl _can}
				<a href="#">上传附件(待实现)</a>
				{zengl end_can}
				{zengl can} ARCHIVE_LIST {zengl _can}
				<a href='{zengl edit_archive_loc}' >查看编辑附件</a>
				{zengl end_can}
			</div>
		{zengl end_can}
		
		{zengl can} COMMENT_ADMIN {zengl _can}
			<p class='menu_head'>评论、回复管理</p>
			<div class = 'menu_body'>
				<a href='{zengl admin_comment_loc}' >评论管理</a>
				<a href='{zengl admin_reply_loc}' >回复管理</a>
			</div>
		{zengl end_can}
		
		<p class='menu_head'>用户管理(待实现)</p>
		
		{zengl can} ADMIN_HTML {zengl _can}
			<p class='menu_head'>HTML静态管理</p>
			<div class = 'menu_body'>
				<a href='{zengl onekey_gen_html_loc}' >一键生成HTML</a>
				<a href='{zengl gen_html_forsec_loc}' >根据栏目生成HTML</a>
				<a href='{zengl onekey_rm_html_loc}' >一键删除HTML</a>
			</div>
		{zengl end_can}
		
		{zengl can} ADMIN_FILEMANAGE,CLEAR_ALL_CACHE,BAK_RESTORE_DB,CMS_UPDATE {zengl _can}
			<p class='menu_head'>系统操作</p>
			<div class = 'menu_body'>
				{zengl can} SET_CONFIG{zengl _can}
					<a href='{zengl set_config_loc}' >系统配置</a>
				{zengl end_can}
				{zengl can} ADMIN_FILEMANAGE {zengl _can}
					<!--<a href='{zengl admin_filemanage_loc}' target="_blank">在线文件管理器</a>-->
				{zengl end_can}
				{zengl can} CLEAR_ALL_CACHE {zengl _can}
					<a href='{zengl clear_caches_loc}' >一键清除所有缓存</a>
				{zengl end_can}
				{zengl can} BAK_RESTORE_DB {zengl _can}
					<a href='{zengl update_permis_loc}' >更新用户权限</a>
					<a href='{zengl bak_db_loc}' >一键备份数据库</a>
					<a href='{zengl restore_db_loc}' >一键恢复数据库</a>
				{zengl end_can}
				{zengl can} CMS_UPDATE {zengl _can}
					<a href='{zengl cms_update_loc}' >系统升级</a>
				{zengl end_can}
			</div>
		{zengl end_can}
	</div>
	
</body>
</html>