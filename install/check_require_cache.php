<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<style type="text/css" media=screen>
<!--
	#main{
		width:700px;
		margin:0 auto;
	}
	#content{
		width:698px;
		border: 1px solid green;
	}
-->
</style>
<title><?php echo $title ?></title>
</head>
<body>
<div id='main'>
<h2><?php echo $title ?></h2>
	<div id='content'>
		系统环境检测：<br/>
		PHP版本：<?php 
		if($this->check_version($serverinfo->GetPhpVersion(),"5.2.3"))
			echo "<img src=\"install/ok.png\">" . $serverinfo->GetPhpVersion() . " >= 5.2.3";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">" . $serverinfo->GetPhpVersion() . " < 5.2.3 (mysql_set_charset等函数出现在5.2.3的版本)</font>";
			$continue = false;
		}
		 ?> <br/>
		MYSQL版本：<?php 
		if(extension_loaded("mysql"))
			echo "<img src=\"install/ok.png\"> 支持mysql数据库";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\"> 不支持mysql数据库，安装可以继续，不过无法使用mysql数据库的功能。如果能用sqlite就只能用sqlite。 </font>";
			$mysql_flag = false;
		}
		 ?><br/>
		图形处理 GD Library的支持：<?php 
		if(extension_loaded("gd"))
			echo "<img src=\"install/ok.png\"> 支持gd库";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">不支持gd库,验证码等图形功能无法使用！</font>";
			$continue = false;
		}
		 ?><br/>
		sqlite的支持：<?php 
		if(extension_loaded("sqlite"))
			echo "<img src=\"install/ok.png\"> 支持sqlite数据库";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">不支持sqlite数据库,安装可以继续，如果mysql也不能用，就无法安装了!</font>";
			if(!$mysql_flag)
				$continue = false;
		}
		 ?><br/>
		网站CMS所在目录的可写性：<?php 
		if(is_writable(dirname("config.php")))
			echo "<img src=\"install/ok.png\"> CMS目录可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">CMS目录不可写，无法生成静态页面！</font>";
			$continue = false;
		}
		 ?><br/>
		common_fun目录可写性：<?php 
		if(is_writable("common_fun"))
			echo "<img src=\"install/ok.png\"> common_fun目录可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">common_fun目录不可写，安装可以继续，不过web无法直接操作common_fun目录，某些主题可能会写该目录</font>";
		}
		 ?><br/>
		file_cache目录可写性：<?php 
		if(is_writable("file_cache"))
			echo "<img src=\"install/ok.png\"> file_cache目录可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">file_cache目录不可写，无法生成缓存文件！</font>";
			$continue = false;
		}
		 ?><br/>
		tpl目录可写性：<?php 
		if(is_writable("tpl"))
			echo "<img src=\"install/ok.png\"> tpl目录可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">tpl目录不可写，无法写入模板文件和模板的缓存文件！</font>";
			$continue = false;
		}
		 ?><br/>
		upload目录可写性：<?php 
		if(is_writable("upload"))
			echo "<img src=\"install/ok.png\"> upload目录可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">upload目录不可写，无法上传附件！</font>";
			$continue = false;
		}
		 ?><br/>
		UpdateCms目录可写性：<?php 
		if(is_writable("UpdateCms"))
			echo "<img src=\"install/ok.png\"> UpdateCms目录可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">UpdateCms目录不可写，安装可以继续，不过web可能无法直接进行更新升级操作。</font>";
		}
		 ?><br/>
		config.php配置文件可写性：<?php 
		if(is_writable("config.php"))
			echo "<img src=\"install/ok.png\"> config.php文件可写";
		else
		{
			echo "<img src=\"install/no.jpg\"><font color=\"red\">config.php文件不可写，无法进行系统配置！</font>";
			$continue = false;
		}
		 ?><br/>
	</div>
	<p align='center'><a href='<?php 
		if($continue)
			echo 'install.php?action=install3';
		else
			echo '#';
		 ?>'>下一步</a></p>
</div>
</body>
</html>