<?php 
global $db_type;
$title = "欢迎光临：";
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/admin_right.tpl';
//$filecache = convertToCache($filetpl);
$filecache = $zengl_cms_tpl_dir . 'cache/admin_right_cache.php';

if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');

$serverinfo = new ServerInfo();
$permis = &$this->permis;
$this->userpower = $permis->get_perms_array();
foreach($this->userpower as $key => $value)
{
	if($value == PER_ALLOW)
		$this->userpower[$key] = true;
	else if($value == PER_DENY)
		$this->userpower[$key] = false;
}

if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) )  &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('title', 'echo $title;',true);
	$tpl->setVar('can', '<?php if($this->check_perms( ');
	$tpl->setVar('_can', ' )){ ?>');
	$tpl->setVar('end_can', '}',true);
	$tpl->setVar('check_adminLogin_dir', '
			if(is_dir("adminLogin"))
				echo "<font color=\"red\">后台登录目录adminLogin没有改名，请改个目录名，还可以将后台登录文件admin_login.php改名，这样会提高系统安全性！</font>";
			',true);
	$tpl->setVar('current_dbtype', '
			if($db_type == SQLITE)
				echo "sqlite数据库";
			else
				echo "mysql数据库";
			',true);
	$tpl->setVar('server_time', 'echo $serverinfo->GetServerTime();',true);
	$tpl->setVar('server_engine', 'echo $serverinfo->GetServerSoftwares();',true);
	$tpl->setVar('php_version', 'echo $serverinfo->GetPhpVersion();',true);
	$tpl->setVar('mysql_version', 'echo $serverinfo->GetMysqlVersion();',true);
	$tpl->setVar('http_version', 'echo $serverinfo->GetHttpVersion();',true);
	$tpl->setVar('web_rootdir', 'echo $serverinfo->GetDocumentRoot();',true);
	$tpl->setVar('max_exec_time', 'echo $serverinfo->GetMaxExecutionTime();',true);
	$tpl->setVar('upload_max_size', 'echo $serverinfo->GetServerFileUpload();',true);
	$tpl->setVar('global', 'echo $serverinfo->GetRegisterGlobals();',true);
	$tpl->setVar('safe_mode', 'echo $serverinfo->GetSafeMode();',true);
	$tpl->setVar('gd_lib', 'echo $serverinfo->GetGdVersion();',true);
	$tpl->setVar('mem_usage', 'echo $serverinfo->GetMemoryUsage();',true);
	$tpl->cache();
	include $filecache;
}
?>