<?php
global $rvar_tpl_action;
if(!isset($rvar_tpl_action) || $rvar_tpl_action == '') $rvar_tpl_action = 'default';
$list_tpl_class_pre = 'list_articles_';
$list_tpl_class_suffix = '_class.php';
switch($rvar_tpl_action)
{
	case 'default':
		include $list_tpl_class_pre . $rvar_tpl_action . $list_tpl_class_suffix;
		break;
	case 'listajax':
		include $list_tpl_class_pre . 'ajax' . $list_tpl_class_suffix;
		break;
	default:
		die('unknown tpl_action in list_articles_class.php');
}
?>