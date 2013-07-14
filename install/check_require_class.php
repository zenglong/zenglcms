<?php 
$title = "ZENGLCMS网站安装第二步：";
$filetpl = 'install/check_require.tpl';
$filecache = 'install/check_require_cache.php';
if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');
else if(!is_writable(dirname($filecache)))
	die(dirname($filecache).' directory is not writable!');

$serverinfo = new ServerInfo();
$continue = true;
$mysql_flag = true;

$tpl = new tpl($filetpl, $filecache);
$tpl->setVar('title', 'echo $title',true);
$tpl->setVar('php_version', '
		if($this->check_version($serverinfo->GetPhpVersion(),"5.2.3"))
			echo "<img src=\"install/ok.png\">" . $serverinfo->GetPhpVersion() . " >= 5.2.3";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">" . $serverinfo->GetPhpVersion() . " < 5.2.3 (mysql_set_charset等函数出现在5.2.3的版本)</font>";
			$continue = false;
		}
		',true);
$tpl->setVar('mysql_version', '
		if(extension_loaded("mysql"))
			echo "<img src=\"install/ok.png\"> 支持mysql数据库";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\"> 不支持mysql数据库，安装可以继续，不过无法使用mysql数据库的功能。如果能用sqlite就只能用sqlite。 </font>";
			$mysql_flag = false;
		}
		',true);
$tpl->setVar('gd_lib', '
		if(extension_loaded("gd"))
			echo "<img src=\"install/ok.png\"> 支持gd库";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">不支持gd库,验证码等图形功能无法使用！</font>";
			$continue = false;
		}
		',true);
$tpl->setVar('sqlite', '
		if(extension_loaded("sqlite"))
			echo "<img src=\"install/ok.png\"> 支持sqlite数据库";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">不支持sqlite数据库,安装可以继续，如果mysql也不能用，就无法安装了!</font>";
			if(!$mysql_flag)
				$continue = false;
		}
		',true);
$tpl->setVar('writable_cms_dir', '
		if(is_writable(dirname("config.php")))
			echo "<img src=\"install/ok.png\"> CMS目录可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">CMS目录不可写，无法生成静态页面！</font>";
			$continue = false;
		}
		',true);
$tpl->setVar('writable_common_fun', '
		if(is_writable("common_fun"))
			echo "<img src=\"install/ok.png\"> common_fun目录可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">common_fun目录不可写，安装可以继续，不过web无法直接操作common_fun目录，某些主题可能会写该目录</font>";
		}
		',true);
$tpl->setVar('writable_file_cache', '
		if(is_writable("file_cache"))
			echo "<img src=\"install/ok.png\"> file_cache目录可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">file_cache目录不可写，无法生成缓存文件！</font>";
			$continue = false;
		}
		',true);
$tpl->setVar('writable_tpl_dir', '
		if(is_writable("tpl"))
			echo "<img src=\"install/ok.png\"> tpl目录可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">tpl目录不可写，无法写入模板文件和模板的缓存文件！</font>";
			$continue = false;
		}
		',true);
$tpl->setVar('writable_upload_dir', '
		if(is_writable("upload"))
			echo "<img src=\"install/ok.png\"> upload目录可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">upload目录不可写，无法上传附件！</font>";
			$continue = false;
		}
		',true);
$tpl->setVar('writable_config_php', '
		if(is_writable("config.php"))
			echo "<img src=\"install/ok.png\"> config.php文件可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">config.php文件不可写，无法进行系统配置！</font>";
			$continue = false;
		}
		',true);
$tpl->setVar('writable_UpdateCms_dir', '
		if(is_writable("UpdateCms"))
			echo "<img src=\"install/ok.png\"> UpdateCms目录可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">UpdateCms目录不可写，安装可以继续，不过web可能无法直接进行更新升级操作。</font>";
		}
		',true);
$tpl->setVar('next_loc', '
		if($continue)
			echo \'install.php?action=install3\';
		else
			echo \'#\';
		',true);
$tpl->cache();
include $filecache;
?>