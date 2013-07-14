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
class db{
	var $sql;
	function __construct($use_sql)
	{
		if($use_sql)
		{
			$this->sql = $use_sql;
		}
	}
	function create_db_tables($rvar_command)
	{
		global $db_tables_prefix;
		global $zengl_cms_init_name;
		global $zengl_cms_init_pass;
		global $db_pass_suffix;
		global $permis_admin;
		global $permis_reg;
		global $permis_sec_root;
		
		if($this->sql == null)
			$this->sql = new sql('utf8');
		$sql = &$this->sql;
		if ($sql->db_type == MYSQL)
		{
			$sql->createTable('articles', 
							'articleID int NOT NULL AUTO_INCREMENT,
							PRIMARY KEY(articleID),
							title tinytext,
							author varchar(30),
							time int,
							content mediumtext,
							descript mediumtext,
							smimgpath mediumtext,
							scansCount int,
							sec_ID int,
							userID int,
							permis mediumtext', true);
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'articles 成功!<br/>';
			flush_buffers();
			$sql->createTable('user',
							'userID int NOT NULL AUTO_INCREMENT,
							PRIMARY KEY(userID),
							username varchar(60),
							password varchar(60),
							regtime int,
							lastlogtime int,
							level int,
							permis mediumtext', true);
			if($rvar_command != 'onlyinit')
				/* $sql->insert('user','username,password,regtime,lastlogtime,level','root','b1afd83a8f5889afbb196fa1626c216b',
								$time=time(),$time,USER_ADMIN);//该记录用于调试，发布时请注释掉或删除，密码admin */
				$sql->insert('user','username,password,regtime,lastlogtime,level',$zengl_cms_init_name,md5($zengl_cms_init_pass . $db_pass_suffix),
						$time=time(),$time,USER_ADMIN);//该记录用于调试，发布时请注释掉或删除，密码admin
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'user 成功!<br/>';
			flush_buffers();
			$sql->createTable('level',
					'levelID int NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(levelID),
					levelname varchar(60),
					permission mediumtext', true);
			if($rvar_command != 'onlyinit')
			{
				$sql->insert('level','levelname,permission','系统管理员',$permis_admin);
				$sql->insert('level','levelname,permission','高级用户',$permis_reg);
				$sql->insert('level','levelname,permission','中级用户',$permis_reg);
				$sql->insert('level','levelname,permission','初级注册用户',$permis_reg);
			}
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'level 成功!<br/>';
			flush_buffers();
			$sql->createTable('section',
					'sec_ID int NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(sec_ID),
					sec_name varchar(60),
					sec_dirname varchar(120),
					sec_parent_ID int,
					sec_content mediumtext,
					sec_weights int,
					permis mediumtext', true);
			if($rvar_command != 'onlyinit')
				$sql->insert('section','sec_name,sec_dirname,sec_parent_ID,sec_weights,permis','根栏目','genlanmu',0,50,$permis_sec_root);
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'section 成功!<br/>';
			flush_buffers();
			$sql->createTable('archives',
					'archive_ID int NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(archive_ID),
					title tinytext,
					path mediumtext,
					smimgpath mediumtext,
					time int,
					userID int,
					permis mediumtext', true);
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'archives 成功!<br/>';
			flush_buffers();
			$sql->createTable('tags',
					'tag_ID int NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(tag_ID),
					tag_name tinytext,
					time int,
					count int,
					articles mediumtext', true);
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'tags 成功!<br/>';
			flush_buffers();
			$sql->createTable('comment',
					'comment_ID int NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(comment_ID),
					username tinytext,
					showtime int,
					time int,
					content mediumtext,
					articleID int,
					uid int,
					ip_address tinytext,
					permis mediumtext', true);
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'comment 成功!<br/>';
			flush_buffers();
			$sql->createTable('CommentReply',
					'reply_ID int NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(reply_ID),
					username tinytext,
					time int,
					content mediumtext,
					commentID int,
					uid int,
					ip_address tinytext,
					permis mediumtext', true);
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'CommentReply 成功!<br/>';
			flush_buffers();
		}
		else if($sql->db_type == SQLITE)
		{
			$sql->createTable('articles', 
							'articleID INTEGER PRIMARY KEY,
							title,
							author,
							time INTEGER,
							content,
							descript,
							smimgpath,
							scansCount INTEGER,
							sec_ID INTEGER,
							userID INTEGER,
							permis', true);
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'articles 成功!<br/>';
			flush_buffers();
			$sql->createTable('user',
					'userID INTEGER PRIMARY KEY,
					username,
					password,
					regtime INTEGER,
					lastlogtime INTEGER,
					level INTEGER,
					permis', true);
			if($rvar_command != 'onlyinit')
				/* $sql->insert('user','username,password,regtime,lastlogtime,level','root','b1afd83a8f5889afbb196fa1626c216b',
						$time=time(),$time,USER_ADMIN);//该记录用于调试，发布时请注释掉或删除，密码admin */
				$sql->insert('user','username,password,regtime,lastlogtime,level',$zengl_cms_init_name,md5($zengl_cms_init_pass . $db_pass_suffix),
						$time=time(),$time,USER_ADMIN);//该记录用于调试，发布时请注释掉或删除，密码admin
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'user 成功!<br/>';
			flush_buffers();
			$sql->createTable('level',
					'levelID INTEGER PRIMARY KEY,
					levelname,
					permission', true);
			if($rvar_command != 'onlyinit')
			{
				$sql->insert('level','levelname,permission','系统管理员',$permis_admin);
				$sql->insert('level','levelname,permission','高级用户',$permis_reg);
				$sql->insert('level','levelname,permission','中级用户',$permis_reg);
				$sql->insert('level','levelname,permission','初级注册用户',$permis_reg);
			}
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'level 成功!<br/>';
			flush_buffers();
			$sql->createTable('section',
					'sec_ID INTEGER PRIMARY KEY,
					sec_name,
					sec_dirname,
					sec_parent_ID INTEGER,
					sec_content,
					sec_weights INTEGER,
					permis', true);
			if($rvar_command != 'onlyinit')
				$sql->insert('section','sec_name,sec_dirname,sec_parent_ID,sec_weights,permis','根栏目','genlanmu',0,50,$permis_sec_root);
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'section 成功!<br/>';
			flush_buffers();
			$sql->createTable('archives',
					'archive_ID INTEGER PRIMARY KEY,
					title,
					path,
					smimgpath,
					time INTEGER,
					userID INTEGER,
					permis', true);
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'archives 成功!<br/>';
			flush_buffers();
			$sql->createTable('tags',
					'tag_ID INTEGER PRIMARY KEY,
					tag_name,
					time INTEGER,
					count INTEGER,
					articles', true);
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'tags 成功!<br/>';
			flush_buffers();
			$sql->createTable('comment',
					'comment_ID INTEGER PRIMARY KEY,
					username ,
					showtime INTEGER,
					time INTEGER,
					content ,
					articleID INTEGER,
					uid INTEGER,
					ip_address,
					permis', true);
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'comment 成功!<br/>';
			flush_buffers();
			$sql->createTable('CommentReply',
					'reply_ID INTEGER PRIMARY KEY,
					username ,
					time INTEGER,
					content ,
					commentID INTEGER,
					uid INTEGER,
					ip_address ,
					permis ', true);
			if ($sql->err == SQL_SUCCESS)
				echo "创建表： $db_tables_prefix".'CommentReply 成功!<br/>';
			flush_buffers();
		}
	}
}
?>