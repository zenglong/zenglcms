<?php
global $rvar_tpl_action;
global $rvar_articleID;
global $adminHtml_genhtml;
global $rvar_isfromhtml;
if(!isset($rvar_tpl_action) || $rvar_tpl_action == '') $rvar_tpl_action = 'default';
if(!isset($rvar_isfromhtml)) $rvar_isfromhtml = '';
$show_tpl_class_pre = 'show_article_';
$show_tpl_aboutus_class_pre = 'aboutus_';
$show_tpl_class_suffix = '_class.php';
$this->getallsections();
$sql = &$this->sql;
$sql->query("select * from $sql->tables_prefix" . "articles where articleID = " . $rvar_articleID);
$sql->parse_results();
$rvar_sec_ID = $sql->row['sec_ID'];
switch($this->all[$rvar_sec_ID]['type'])
{
	case 'aboutus':
		switch($rvar_tpl_action)
		{
			case 'default':
				include $show_tpl_aboutus_class_pre . $rvar_tpl_action . $show_tpl_class_suffix;
				break;
			default:
				die('unknown tpl_action in show_article_class.php when type is aboutus');
		}
		break;
	case 'linkurl':
	case 'linkurl_newopen':
		if($adminHtml_genhtml == 'yes')
		{
			$this->progress("文章：<{$sql->row['title']}> 所属栏目<{$this->all[$rvar_sec_ID]['sec_name']}> 是外链 <{$this->all[$rvar_sec_ID]['linkurl']}> ".
					" 跳过不处理");
		}
		else
			new error("外链栏目警告:","文章：{$sql->row['title']} 所处栏目： {$this->all[$rvar_sec_ID]['sec_name']} ".
					"是外链 : {$this->all[$rvar_sec_ID]['linkurl']} 无法显示，请调整文章到非外链栏目中!",true,true);
		break;
	case 'friendlink':
		if($adminHtml_genhtml == 'yes')
		{
			$this->progress("文章：<{$sql->row['title']}> 所属栏目<{$this->all[$rvar_sec_ID]['sec_name']}> 是友情链接  ".
					"跳过不处理");
		}
		else
			new error("友情链接栏目警告：","文章：{$sql->row['title']} 所处栏目：{$this->all[$rvar_sec_ID]['sec_name']} 是友情链接 ".
			"只能用于其他模板中进行数据输出，不存在可以显示的列表",true,true);
		break;
	case 'public_notice':
		if($adminHtml_genhtml == 'yes')
		{
			$this->progress("文章：<{$sql->row['title']}> 所属栏目<{$this->all[$rvar_sec_ID]['sec_name']}> 是站点公告 ".
					"跳过不处理");
		}
		else
			new error("站点公告栏目警告：","文章：{$sql->row['title']} 所处栏目：{$this->all[$rvar_sec_ID]['sec_name']} 是站点公告 ".
			"只能用于其他模板中进行数据输出，不存在可以显示的列表",true,true);
		break;
	default:
		if($this->all[$rvar_sec_ID]['type'] == 'article' ||
			$rvar_isfromhtml == 'yes')
		{
			switch($rvar_tpl_action)
			{
				case 'default':
					include $show_tpl_class_pre . $rvar_tpl_action . $show_tpl_class_suffix;
					break;
				default:
					die('unknown tpl_action in show_article_class.php when type is article');
			}
		}
		else
			new error("无效的栏目类型","文章所属的栏目类型无效",true,true);
		break;
}
?>