<?php
global $rvar_tpl_action;
if(!isset($rvar_tpl_action) || $rvar_tpl_action == '') $rvar_tpl_action = 'default';
$show_tpl_class_pre = 'show_article_';
$show_tpl_class_suffix = '_class.php';
switch($rvar_tpl_action)
{
	case 'default':
		include $show_tpl_class_pre . $rvar_tpl_action . $show_tpl_class_suffix;
		break;
	default:
		die('unknown tpl_action in show_article_class.php');
}
?>