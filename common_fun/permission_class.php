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
define('PER_ALLOW', 'allow'); //允许
define('PER_DENY', 'deny'); //拒绝

define('ARTICLE_ADD', 'add_article'); //添加文章权限
define('ARTICLE_EDIT', 'edit_article'); //编辑文章权限
define('ARTICLE_DEL', 'del_article'); //删除文章权限
define('ARTICLE_ADMIN','admin_article'); //管理文章
define('SEC_ADD', 'add_section'); //添加栏目权限
define('SEC_EDIT','edit_section');//编辑栏目权限
define('SEC_DEL','del_section');//删除栏目
define('CLEAR_ALL_CACHE', 'clear_all_cache'); //清除所有缓存
define('ARCHIVE_UPLOAD','upload_archive'); //上传附件
define('ARCHIVE_LIST','list_archive');//附件浏览
define('ARCHIVE_EDIT','edit_archive');//编辑附件
define('ARCHIVE_DEL','del_archive');//删除附件
define('ADMIN_SHOW','show_admin');//显示进入管理界面
define('COMMENT_ADD','add_comment'); //发表评论
define('COMMENT_DEL','del_comment'); //删除评论
define('COMMENT_SHOW','show_comment'); //查看评论
define('COMMENT_ADMIN','admin_comment'); //管理评论
define('SET_CONFIG','set_config'); //设置config.php的权限
define('BAK_RESTORE_DB','bak_restore_database'); //备份恢复数据库
define('CMS_UPDATE','update_cms'); //升级CMS
define('ADMIN_HTML','admin_html'); //管理HTML静态页面
define('ADMIN_FILEMANAGE','admin_filemanage'); //使用文件管理器

$permis_admin = array(ARTICLE_ADD => PER_ALLOW , ARTICLE_EDIT => PER_ALLOW , ARTICLE_DEL => PER_ALLOW ,
						 SEC_ADD => PER_ALLOW , SEC_EDIT => PER_ALLOW , SEC_DEL => PER_ALLOW ,
						 CLEAR_ALL_CACHE => PER_ALLOW,ARCHIVE_UPLOAD => PER_ALLOW,ARCHIVE_LIST=>PER_ALLOW,
						 ARCHIVE_EDIT => PER_ALLOW,ARCHIVE_DEL => PER_ALLOW,
						 ADMIN_SHOW => PER_ALLOW,ARTICLE_ADMIN => PER_ALLOW,
						 COMMENT_ADD =>PER_ALLOW,COMMENT_DEL => PER_ALLOW,COMMENT_SHOW=>PER_ALLOW,
						 COMMENT_ADMIN => PER_ALLOW,SET_CONFIG=>PER_ALLOW,
						 BAK_RESTORE_DB => PER_ALLOW,CMS_UPDATE => PER_ALLOW,ADMIN_HTML=>PER_ALLOW,
						 ADMIN_FILEMANAGE=>PER_ALLOW,
						 );
$permis_admin = serialize($permis_admin);
$permis_reg = array(ARTICLE_ADD => PER_ALLOW , ARTICLE_EDIT => PER_ALLOW , ARTICLE_DEL => PER_ALLOW,
					   SEC_ADD => PER_DENY , SEC_EDIT => PER_DENY , SEC_DEL => PER_DENY , 
					   CLEAR_ALL_CACHE => PER_DENY,ARCHIVE_UPLOAD => PER_ALLOW,ARCHIVE_LIST=>PER_ALLOW,
					   ARCHIVE_EDIT => PER_ALLOW,ARCHIVE_DEL => PER_ALLOW,
					   ADMIN_SHOW => PER_ALLOW,ARTICLE_ADMIN => PER_ALLOW,
					   COMMENT_ADD =>PER_ALLOW,COMMENT_DEL => PER_DENY,COMMENT_SHOW=>PER_ALLOW,
					   COMMENT_ADMIN => PER_DENY,SET_CONFIG=>PER_DENY,
					   BAK_RESTORE_DB => PER_DENY,CMS_UPDATE => PER_DENY,ADMIN_HTML=>PER_DENY,
		 			   ADMIN_FILEMANAGE=>PER_DENY,
						);
$permis_reg = serialize($permis_reg);
$permis_tourist = array(ARTICLE_ADD => PER_DENY , ARTICLE_EDIT => PER_DENY , ARTICLE_DEL => PER_DENY ,
							SEC_ADD => PER_DENY , SEC_EDIT => PER_DENY , SEC_DEL => PER_DENY , 
							CLEAR_ALL_CACHE => PER_DENY,ARCHIVE_UPLOAD => PER_DENY,ARCHIVE_LIST=>PER_DENY,
						 	ARCHIVE_EDIT => PER_DENY,ARCHIVE_DEL => PER_DENY,
							ADMIN_SHOW => PER_DENY,ARTICLE_ADMIN => PER_DENY,
							COMMENT_ADD =>PER_ALLOW,COMMENT_DEL => PER_DENY,COMMENT_SHOW=>PER_ALLOW,
							COMMENT_ADMIN => PER_DENY,SET_CONFIG=>PER_DENY,
							BAK_RESTORE_DB => PER_DENY,CMS_UPDATE => PER_DENY,ADMIN_HTML=>PER_DENY,
							ADMIN_FILEMANAGE=>PER_DENY,
							);
$permis_sec_root = array('uid_1' => array(SEC_ADD => PER_ALLOW, SEC_EDIT => PER_ALLOW, SEC_DEL=>PER_ALLOW),
					'uid_other' => array(SEC_ADD=>PER_DENY, SEC_EDIT=>PER_DENY, SEC_DEL=>PER_DENY),
					);
$permis_sec_root = serialize($permis_sec_root);

class permis
{
	var $session;
	var $sql;
	var $isTourist = true;
	var $gen_permis;
	function __construct($use_session,$use_sql)
	{
		if($use_session)
		{
			$this->set_sess($use_session);
		}
		if($use_sql)
		{
			$this->sql = $use_sql;
		}
	}
	function set_sess($use_session)
	{
		$this->session = $use_session;
		if($this->session->userID == null ||
				$this->session->username == null)
			$this->isTourist = true;
		else
			$this->isTourist = false;
	}
	//生成current uid permis 当前uid的permis
	function gen_cuid_permis($permis,$action)
	{
		$this->gen_permis['uid_' . $this->session->userID][$permis] = $action;
	}
	function  gen_otheruid_permis($permis,$action)
	{
		$this->gen_permis['uid_other'][$permis] = $action;
	}
	function gen_permis_str()
	{
		return serialize($this->gen_permis);
	}
	function check_perm($permis,$dest)
	{
		if($this->session->userLevel == 1) //superuser超级管理员用户
			return true;
		global $permis_tourist;
		$sql = $this->sql;
		$isOK = false;
		if($this->isTourist && $permis_tourist[$permis] == PER_ALLOW)
			$isOK = true;
		else if($this->isTourist && $permis_tourist[$permis] == PER_DENY)
			$isOK = false;
		else if($this->session->userPermis)
		{
			$userpermis = unserialize($this->session->userPermis);
			if($userpermis[$permis] == PER_ALLOW)
				$isOK = true;
			else if($userpermis[$permis] == PER_DENY)
				$isOK = false;
		}
		else if($this->session->levelPermis)
		{
			$userpermis = unserialize($this->session->levelPermis);
			if($userpermis[$permis] == PER_ALLOW)
				$isOK = true;
			else if($userpermis[$permis] == PER_DENY)
				$isOK = false;
		}
		
		if($dest == null || $isOK == false)
			return $isOK;
		else
		{
			if(is_string($dest))
				$dest = unserialize($dest);
			if(is_array($dest))
			{
				$uid = 'uid_' . $this->session->userID;
				if($dest[$uid][$permis] == PER_ALLOW)
					$isOK = true;
				else if($dest[$uid][$permis] == PER_DENY)
					$isOK = false;
				else if($dest[$uid][$permis] == '' && $dest['uid_other'][$permis] == PER_ALLOW)
					$isOK = true;
				else if($dest[$uid][$permis] == '' && $dest['uid_other'][$permis] == PER_DENY)
					$isOK = false;
			}
			else
				$isOK = false;
			return $isOK;
		}
		return false;
	}
	function get_perms_array()
	{
		global $permis_tourist;
		if($this->isTourist)
			return $permis_tourist;
		if($this->session->userPermis)
			$userpermis = unserialize($this->session->userPermis);
		else if($this->session->levelPermis)
			$userpermis = unserialize($this->session->levelPermis);
		else
			$userpermis = array();
		return $userpermis;
	}
	function update_permis()
	{
		global $permis_admin;
		global $permis_reg;
		if($this->sql == null)
			$this->sql = new sql('utf8');
		$sql = &$this->sql;
		$orig_permis_admin = $permis_admin;
		$permis_admin = $sql->escape_str($permis_admin);
		$sql->query("update $sql->tables_prefix" . "level set permission = '$permis_admin'
					where levelname = '系统管理员'");
		$orig_permis_reg = $permis_reg;
		$permis_reg = $sql->escape_str($permis_reg);
		$sql->query("update $sql->tables_prefix" . "level set permission = '$permis_reg'
					where levelname = '高级用户' or levelname = '中级用户' or
					levelname = '初级注册用户'");
		if ($sql->err == SQL_SUCCESS)
		{
			if($this->session == null)
				$this->session = new session(true);
			if($this->session->userLevel == 1)
			{
				$_SESSION['userPermis'] = $orig_permis_admin;
				$_SESSION['levelPermis'] = $orig_permis_admin;
			}
			else
			{
				$_SESSION['userPermis'] = $orig_permis_reg;
				$_SESSION['levelPermis'] =$orig_permis_reg;
			}
			$this->session->get_userinfo();
			echo "<br/>更新权限成功！<br/>";
		}
		else
			die("未知错误，更新权限失败！");
	}
}
?>