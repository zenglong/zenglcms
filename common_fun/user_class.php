<?php 
/*
	Copyright 2012 zenglong (made in china)
	
	For more information, please see www.zengl.com
	
	This file is part of zenglcms.
	
	zenglcms is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	zenglcms is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with zenglcms,the copy is in the file licence.txt.  If not,
	see <http://www.gnu.org/licenses/>.
*/
define('USER_ADMIN', 1); //管理员
define('USER_ADVANCE', 30); //高级用户
define('USER_MIDDLE', 60); //中级用户
define('USER_REG', 90); //初级注册用户
define('USER_TOURIST', 120); //游客

class user
{
	var $username;
	var $password;
	var $pass_suffix;
	var $session;
	function register_display()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . 
				'/class/register_tpl_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/register_tpl_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file register_tpl_class.php does not exist!');
	}
	function is_register($sql)
	{
		$username = $sql->escape_str($this->username);
		$sql->query('select username from ' . $sql->tables_prefix .
				"user where username = '$username'");
		$sql->parse_results();
		if($sql->row == null)
			return false;
		else
			return true;
	}
	function register()
	{
		global $rvar_username;
		global $rvar_password;
		global $db_pass_suffix;
		$this->username = $rvar_username;
		$this->password = $rvar_password;
		$this->pass_suffix = $db_pass_suffix;
		$md5pass = md5($this->password . $this->pass_suffix);
		$time = time();
		$sql = new sql('utf8');
		if($this->is_register(&$sql))
			new error('注册失败！','该用户已经注册过了！',true,true);
		$sql->query('select levelID from ' . $sql->tables_prefix . 
						"level where levelname = '初级注册用户'");
		$sql->parse_results();
		$sql->insert('user','username,password,regtime,lastlogtime,level',
				$this->username,$md5pass,$time,$time,$sql->row['levelID']);
		if($sql->err == SQL_SUCCESS)
			new success('注册情况：','恭喜，注册成功！',true);
	}
	function login_display()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . 
				'/class/login_tpl_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/login_tpl_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file login_tpl_class.php does not exist!');
	}
	function login()
	{
		global $rvar_username;
		global $rvar_password;
		global $db_pass_suffix;
		global $db_tables_prefix;
		$this->username = $rvar_username;
		$this->password = $rvar_password;
		$this->pass_suffix = $db_pass_suffix;
		$md5pass = md5($this->password . $this->pass_suffix);
		$sql = new sql('utf8');
		$tablename = $db_tables_prefix . 'user';
		$tablename_level = $db_tables_prefix . 'level';
		$username = $sql->escape_str($this->username);
		$sqldesc = "select $tablename.username as username, $tablename.password as password,
					   $tablename.userID as userID, $tablename.level as level,$tablename.permis as permis,
					   $tablename_level.permission as permission 
					   from $tablename,$tablename_level where $tablename.username='$username' 
						AND $tablename.level = $tablename_level.levelID";
		$sql->query($sqldesc);
		$sql->parse_results();
		if($sql->row['level'] == 1 && $_SESSION['adminloginflag'] != 'ImFromAdminLogin')
			new error('登录失败！','管理员不能从前台登录，请从后台登录！',true,true);
		if( ($this->username == $sql->row['username']) &&
						($md5pass == $sql->row['password']) )
		{
			$this->session = new session();
			$this->session->set_userinfo($sql->row['username'],$sql->row['userID'],$sql->row['level'],
												$sql->row['permis'],$sql->row['permission']);
			new success('登录成功！',"欢迎光临，$this->username 欢迎登录本站！",true,true);
		}
		else
			new error('登录失败！','用户名或密码不对',true,true);
	}
	function logout()
	{
		$this->session = new session();
		$this->session->unset_all();
		unset($_SESSION['adminloginflag']);
		new success('注销成功！','欢迎下次光临！',true,true);
	}
}  
?>