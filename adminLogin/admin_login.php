<?php
include '../config.php';
session_start();
if(isset($_SESSION['userLevel']) && $_SESSION['userLevel'] == 1) //如果超级管理员已经登录，则直接跳转到admin.php
{
	exit("<script type=\"text/javascript\">location.href = '{$zengl_cms_rootdir}admin.php?" .
	"arg=show'</script>");
}
$_SESSION['adminloginflag'] = 'ImFromAdminLogin';
echo "<script type=\"text/javascript\">location.href = '{$zengl_cms_rootdir}login_out_register.php?" .
		"action=login'</script>";
?>