<?php
global $rvar_tpl_action;
global $rvar_sec_ID;
global $rvar_articleID;
global $adminHtml_genhtml;
global $rvar_tag;
global $rvar_keyword; //查询关键词
if(!isset($rvar_tag)) $rvar_tag = '';
if(!isset($rvar_keyword)) $rvar_keyword = '';

if(!isset($rvar_tpl_action) || $rvar_tpl_action == '') $rvar_tpl_action = 'default';
$list_tpl_class_pre = 'list_articles_';
$list_tpl_aboutus_class_pre = 'aboutus_';
$list_tpl_class_suffix = '_class.php';
$section = new section(false,false);
$section->sql = $section->permis->sql = &$this->sql;
if($this->all == null)
{
	$section->getall();
	$this->all =&$section->all;
}
switch($this->all[$rvar_sec_ID]['type'])
{
	case 'aboutus':
		switch($rvar_tpl_action)
		{
			case 'default':
				$sql = &$this->sql;
				$sql->query("select * from {$sql->tables_prefix}articles where sec_ID={$rvar_sec_ID} ".
							"limit 0,1");
				$sql->parse_results();
				$rvar_articleID = $sql->row['articleID'];
				if($sql->row === false)
					die('you have no aboutus article!');
				$i_am_in_list = true;
				include $list_tpl_aboutus_class_pre . $rvar_tpl_action . $list_tpl_class_suffix;
				break;
			default:
				die('unknown tpl_action in list_articles_class.php when type is aboutus');
		}
		break;
	case 'linkurl':
	case 'linkurl_newopen':
		if($adminHtml_genhtml == 'yes')
		{
			$this->progress("栏目:{$this->all[$rvar_sec_ID]['sec_name']} 为外链 {$this->all[$rvar_sec_ID]['linkurl']} ".
					"跳过不处理");
		}
		else
			new error("外链栏目警告：","栏目:{$this->all[$rvar_sec_ID]['sec_name']} 是外链 : {$this->all[$rvar_sec_ID]['linkurl']}".
				"不存在可以显示的列表",true,true);
		break;
	case 'friendlink':
		if($adminHtml_genhtml == 'yes')
		{
			$this->progress("栏目:{$this->all[$rvar_sec_ID]['sec_name']} 是友情链接  ".
					"跳过不处理");
		}
		else
			new error("友情链接栏目警告：","栏目:{$this->all[$rvar_sec_ID]['sec_name']} 是友情链接 ".
			"只能用于其他模板中进行数据输出，不存在可以显示的列表",true,true);
		break;
	case 'public_notice':
		if($adminHtml_genhtml == 'yes')
		{
			$this->progress("栏目:{$this->all[$rvar_sec_ID]['sec_name']} 是站点公告 ".
					"跳过不处理");
		}
		else
			new error("站点公告栏目警告：","栏目:{$this->all[$rvar_sec_ID]['sec_name']} 是站点公告 ".
				"只能用于其他模板中进行数据输出，不存在可以显示的列表",true,true);
		break;
	default:
		if($this->all[$rvar_sec_ID]['type'] == 'article' ||
			$rvar_tag != '' ||
			$rvar_keyword != '')
		{
			switch($rvar_tpl_action)
			{
				case 'default':
					include $list_tpl_class_pre . $rvar_tpl_action . $list_tpl_class_suffix;
					break;
				case 'listajax':
					include $list_tpl_class_pre . 'ajax' . $list_tpl_class_suffix;
					break;
				default:
					die('unknown tpl_action in list_articles_class.php when type is article');
			}
		}
		else
			new error("栏目警告：","栏目ID:{$rvar_sec_ID} 不存在!",true,true);
		break;
}
?>