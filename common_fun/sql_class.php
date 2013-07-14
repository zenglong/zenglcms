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
define('SQL_SUCCESS', 0);
define('SQL_ERROR',1);
class sql
{
	var $db_type;
	var $hostname;
	var $username;
	var $password;
	var $tables_prefix;
	var $dbname;
	var $charset;
	var $link;
	var $err = SQL_SUCCESS;
	var $errnum;
	var $errorstr;
	var $sql_desc;
	var $results;
	var $row;
	var $rownum;
	var $sqlite_lib_encode;
	var $progress;
	var $sqlite_bak_format_file = 'sqlite_bak_format.txt';
	var $mysql_bak_format_file = 'mysql_bak_format.txt';
	function __construct($charset)
	{
		global $db_type;
		global $db_hostname;
		global $db_username;
		global $db_password;
		global $db_database_name; 
		global $db_tables_prefix;
		$this->db_type = $db_type;
		$this->tables_prefix = $db_tables_prefix;
		if($this->db_type == MYSQL)
			$this->mysql_conn_db($db_hostname, $db_username, $db_password, $db_database_name, $charset);
		else if($this->db_type == SQLITE)
			$this->sqlite_conn_db($db_database_name, $charset);
		else
			new error('sql构造函数发生错误','无效的数据库类型！',true,true);
	}
	function __destruct()
	{
		global $adminHtml_genhtml;
		if($adminHtml_genhtml != 'yes')
		{
			if($this->link == null)
				return;
			if($this->db_type == MYSQL)
				mysql_close($this->link);
			else if($this->db_type == SQLITE)
				sqlite_close($this->link);
			else
				new error('sql对象发生错误','无效的数据库类型！',true,true);
			$this->link = null;
		}
	}
	function mysql_conn_db($db_hostname,$db_username,$db_password,$db_database_name,$charset)
	{
		$this->hostname = $db_hostname;
		$this->username = $db_username;
		$this->password = $db_password;
		$this->dbname = $db_database_name;
		$this->charset = $charset;
		$this->link = mysql_connect($this->hostname,$this->username,$this->password,true); //最后一个参数true表示始终创建新的连接，这样一个连接就对应一个sql对象
		if(!$this->link)
		{
			$this->fatalError('连接数据库失败，');
		}
		if(!mysql_select_db($this->dbname,$this->link))
		{
			$this->fatalError('选择数据库失败，');
		}
		if(!mysql_set_charset($this->charset,$this->link))
		{
			$this->fatalError('设置UTF8字符集失败，');
			
		}
	}
	function sqlite_conn_db($db_database_name,$charset)
	{
		$this->dbname = $db_database_name;
		$this->charset = $charset;
		$this->sqlite_lib_encode = sqlite_libencoding();
		$this->link = sqlite_open($this->dbname);
		if(!$this->link)
		{
			$this->fatalError('打开数据库失败，');
		}
	}
	function setError($errorstr)
	{
		$this->err = SQL_ERROR;
		if($this->db_type == MYSQL)
		{
			$this->errnum = mysql_errno();
			$this->errorstr = $errorstr . mysql_error();
		}
		else if($this->db_type == SQLITE)
		{
			$this->errnum = sqlite_last_error($this->link);
			$this->errorstr = $errorstr . sqlite_error_string($this->errnum);
		}
		else
		{
			$this->errnum = -1;
			$this->errorstr = $errorstr . "未知数据库类型，无法获取数据库错误！";
		}
	}
	function fatalError($errorstr)
	{
		$this->setError($errorstr);
		new error("数据库严重错误！", "错误原因：". $this->errorstr,true,true);
		/*$error = new error();
		$error->setVar('title', "数据库初始化失败！");
		$error->setVar('content', "失败原因：". $this->errorstr);
		$error->view_and_exit();*/
	}
	function mysql_createTable($tablename,$desc,$dropExists)
	{
		if($dropExists==true)
		{
			$tablename =  $this->tables_prefix . $tablename;
			$this->sql_desc = "DROP TABLE IF EXISTS " . $tablename;
			if(!mysql_query($this->sql_desc,$this->link))
			{
				$this->fatalError('删除存在的表'. $tablename . '失败，');
			}
		}
		$this->sql_desc = "CREATE TABLE ". $tablename . "( " . $desc . " ) DEFAULT CHARSET=utf8";
		if(!mysql_query($this->sql_desc,$this->link))
		{
			$this->fatalError('创建表'. $tablename . '失败，');
		}
	}
	function sqlite_createTable($tablename,$desc,$dropExists)
	{
		if($dropExists==true)
		{
			$tablename =  $this->tables_prefix . $tablename;
			$this->sql_desc = "DROP TABLE " . $tablename;
			sqlite_query($this->link,$this->sql_desc);
		}
		$this->sql_desc = "CREATE TABLE ". $tablename . "( " . $desc . " )";
		if(!sqlite_query($this->link,$this->sql_desc))
		{
			$this->fatalError('创建表'. $tablename . '失败，');
		}
	}
	function createTable($tablename,$desc,$dropExists)
	{
		if($this->db_type == MYSQL)
			$this->mysql_createTable($tablename, $desc, $dropExists);
		else if($this->db_type == SQLITE)
			$this->sqlite_createTable($tablename, $desc, $dropExists);
		else
			new error('创建数据库表失败','无效的数据库类型！',true,true);
	}
	/**
	 * 插入表数据，用法:
	 * insert( 表名(不含表前缀), 要插入的列如(username,password,regtime) , 每列对应的值 ... )
	 * 每列对应的值中每个值为一个参数，这是个可变参数的函数。
	 * @example $sql->insert('user','username,password,regtime,lastlogtime,level',
	 *			"'$this->username'","'$md5pass'",$time,$time,USER_REG); */
	function insert()
	{
		global $db_pass_suffix;
		$args = func_get_args();
		$tablename = $this->tables_prefix . array_shift($args);
		$cols = array_shift($args);
		$this->sql_desc = "INSERT INTO " . $tablename . "( " . $cols . " )" . 'values (';
		foreach ($args as $arg)
		{
			$arg = $this->escape_str($arg);
			$arg = "'$arg'";
			$this->sql_desc .= $arg . ',';
		}
		$this->sql_desc = substr($this->sql_desc, 0,-1);
		$this->sql_desc .= ')';
		if($this->db_type == MYSQL)
		{
			if(!mysql_query($this->sql_desc,$this->link))
			{
				$this->fatalError('往'. $tablename . '插入数据失败，');
			}
		}
		else if($this->db_type == SQLITE)
		{
			if(!sqlite_query($this->link,$this->sql_desc))
			{
				$this->fatalError('往'. $tablename . '插入数据失败，');
			}
		}
		else
			new error('插入数据库表失败','无效的数据库类型！',true,true);
	}
	/**
	 * 将每列对应的值以数组的形式插入数据库表。
	 */
	function insert_use_array($table,$cols,$val_array)
	{
		$tablename = $this->tables_prefix . $table;
		$this->sql_desc = "INSERT INTO " . $tablename . "( " . $cols . " )" . 'values (';
		foreach ($val_array as $arg)
		{
			$arg = $this->escape_str($arg);
			$arg = "'$arg'";
			$this->sql_desc .= $arg . ',';
		}
		$this->sql_desc = substr($this->sql_desc, 0,-1);
		$this->sql_desc .= ')';
		if($this->db_type == MYSQL)
		{
			if(!mysql_query($this->sql_desc,$this->link))
			{
				$this->fatalError('往'. $tablename . '插入数据失败，');
			}
		}
		else if($this->db_type == SQLITE)
		{
			if(!sqlite_query($this->link,$this->sql_desc))
			{
				$this->fatalError('往'. $tablename . '插入数据失败，');
			}
		}
		else
			new error('插入数据库表失败','无效的数据库类型！',true,true);
	}
	function query($sql)
	{
		$this->sql_desc = $sql;
		if($this->db_type == MYSQL)
		{
			if(!$this->results=mysql_query($this->sql_desc,$this->link))
			{
				$this->fatalError("执行 $this->sql_desc 语句失败，");
			}
		}
		else if($this->db_type == SQLITE)
		{
			if(!$this->results=sqlite_query($this->link,$this->sql_desc))
			{
				$this->fatalError("执行 $this->sql_desc 语句失败，");
			}
		}
		else
			new error('数据库操作失败','无效的数据库类型！',true,true);
	}
	function get_num()
	{
		if($this->db_type == MYSQL)
			return $this->rownum = mysql_num_rows($this->results);
		else if($this->db_type == SQLITE)
			return $this->rownum = sqlite_num_rows($this->results);
	}
	function parse_results()
	{
		if($this->db_type == MYSQL)
			return $this->row = mysql_fetch_array($this->results);
		else if($this->db_type == SQLITE)
			return $this->row = sqlite_fetch_array($this->results);
		else
			new error('数据库结果解析失败','无效的数据库类型！',true,true);
	}
	function escape_str($arg)
	{
		if($this->db_type == MYSQL)
		{
			return $arg = mysql_escape_string($arg);
		}
		else if($this->db_type == SQLITE)
		{
			return $arg = sqlite_escape_string($arg);
		}
		else
			new error('转义sql字符串失败','无效的数据库类型！',true,true);
	}
	function reset_row()
	{
		if($this->db_type == MYSQL)
			mysql_data_seek($this->results,0);
		else if($this->db_type == SQLITE)
			sqlite_rewind($this->results);
		else
			new error('重置数据库记录失败','无效的数据库类型！',true,true);
	}
	/*尝试取消PHP时间限制*/
	function cancel_time_limit()
	{
		set_time_limit(0);
	}
	/*备份数据库*/
	function bak_tables()
	{
		$this->cancel_time_limit();
		//$not_bak_array = array('articleID','userID','levelID','sec_ID','archive_ID','tag_ID');
		$keynum = 0;
		$recordnum;
		$allnum = 0;
		$args = func_get_args();
		$sqltype = array_pop($args);
		$pernum = array_pop($args);
		$count = 1; //文件名计数
		$num = 0; //行数
		$suffix = array_pop($args);
		$prefix = array_pop($args);
		$bakfile = $prefix . '_' . $count . ".$suffix";
		//$bakhandle = fopen($bakfile, 'w+');
		$bakhandle = null;
		$db_allnums=0;
		$dirname = dirname($prefix);
		if($sqltype == 'mysql')
		{
			if(file_exists($dirname . '/' .$this->sqlite_bak_format_file))
				unlink($dirname . '/' .$this->sqlite_bak_format_file);
			file_put_contents($dirname.'/'.$this->mysql_bak_format_file, "当前备份文件格式为{$sqltype}数据库格式");
		}
		elseif($sqltype == 'sqlite')
		{
			if(file_exists($dirname . '/' .$this->mysql_bak_format_file))
				unlink($dirname . '/' .$this->mysql_bak_format_file);
			file_put_contents($dirname.'/'.$this->sqlite_bak_format_file, "当前备份文件格式为{$sqltype}数据库格式");
		}
		$basename = basename($prefix);
		$pattern = "/".$basename."_[0-9]+.".$suffix."$/";
		$dirhandle = opendir($dirname);
		while ($file_bak = readdir($dirhandle))
		{
			if(preg_match($pattern, $file_bak))
			{
				unlink($dirname . '/' . $file_bak);
				$this->progress->step("删除 $dirname 目录的 $file_bak 文件",false);
			}
		}
		foreach ($args as $arg)
		{
			$this->query("select count(*) as all_num from {$this->tables_prefix}{$arg}");
			$this->parse_results();
			$db_allnums += $this->row['all_num'];
		}
		if($this->db_type == MYSQL)
			$this->progress->step("当前数据库为mysql，该数据库一共有" . $db_allnums . "条记录,下面要转为" . $sqltype . "格式",false);
		else if($this->db_type == SQLITE)
			$this->progress->step("当前数据库为sqlite，该数据库一共有" . $db_allnums . "条记录,下面要转为" . $sqltype . "格式",false);
		foreach ($args as $arg)
		{
			$this->query("select * from {$this->tables_prefix}{$arg}");
			$this->progress->step("正在备份{$this->tables_prefix}{$arg}表,该表有" . $this->get_num() . "条记录",false);
			flush_buffers();
			$recordnum = 0;
			while($this->parse_results())
			{
				$sqlstr = "INSERT INTO {$this->tables_prefix}{$arg} (";
				$valstr = '';
				foreach ($this->row as $key => $val)
				{
					if(is_string($key) && !(in_array($key, $not_bak_array) && $keynum == 0))
					{
						$sqlstr .= $key.',';
						if($sqltype == 'mysql')
							$val =  mysql_escape_string($val);
						elseif ($sqltype == 'sqlite')
							$val =  sqlite_escape_string($val);
						else 
							new error("错误",'必须指定mysql 或 sqlite类型',true,true);
						$val = "'$val'";
						$valstr .= $val.',';
						$keynum++;
					}
				}
				$keynum = 0;
				$sqlstr = substr($sqlstr, 0,-1);
				$sqlstr .= ') values(';
				$sqlstr .= substr($valstr,0,-1) . ')';
				if($bakhandle == null)
					$bakhandle = fopen($bakfile, 'w+');
				if($sqltype == 'sqlite')
					$sqlstr = addcslashes($sqlstr,"\r\n\t");
				//$sqlstr = rawurlencode($sqlstr);
				fwrite($bakhandle, $sqlstr . "\r\n");
				$recordnum++;
				$allnum++;
				$this->progress->step("({$allnum})备份完成表{$this->tables_prefix}{$arg} 第 $recordnum 条记录",false);
				flush_buffers();
				$num++;
				if($num == $pernum)
				{
					fclose($bakhandle);
					$bakhandle = null;
					$bakfile = $prefix . '_' . ++$count . ".$suffix";
					$num = 0;
				}
			}
		}
		if($bakhandle != null)
			fclose($bakhandle);
	}
	/*恢复数据库*/
	function restore_tables()
	{
		$this->cancel_time_limit();
		$args = func_get_args();
		$sqltype = array_pop($args);
		$suffix = array_pop($args);
		$prefix = array_pop($args);
		$count = 1; //文件名计数
		$allnum = 0;
		$bakfile = $prefix . '_' . $count . ".$suffix";
		$dirname = dirname($prefix);
		if($sqltype == 'mysql')
		{
			if(!file_exists($dirname . '/' .$this->mysql_bak_format_file))
			{
				$this->progress->end("错误：当前数据库是{$sqltype}类型，但是导出的备份文件不是{$sqltype}格式的，".
						  		 "请导出为{$sqltype}格式后再试!");
				die();
			}
		}
		elseif($sqltype == 'sqlite')
		{
			if(!file_exists($dirname . '/' .$this->sqlite_bak_format_file))
			{
				$this->progress->end("错误：当前数据库是{$sqltype}类型，但是导出的备份文件不是{$sqltype}格式的，".
						  		 "请导出为{$sqltype}格式后再试!");
				die();
			}
		}
		$this->progress->step("接下来采用事务方式来批量插入数据库！",false);
		flush_buffers();
		$this->begin(); //开启事务
		while(file_exists($bakfile))
		{
			$bakhandle = fopen($bakfile, 'r');
			while(!feof($bakhandle))
			{
				$sqlstr = fgets($bakhandle);
				//$sqlstr = rawurldecode($sqlstr);
				if ($sqltype == 'sqlite')
					$sqlstr = stripcslashes($sqlstr);
				else if($sqltype != 'mysql')
					new error("错误",'必须指定mysql 或 sqlite类型',true,true);
				if($sqlstr != '')
				{
					$this->query($sqlstr);
					$allnum++;
					$this->progress->step("$bakfile 加入插入队列 $allnum 条记录",false);
					flush_buffers();
				}
			}
			fclose($bakhandle);
			$bakfile = $prefix . '_' . ++$count . ".$suffix";
		}
		$this->progress->step("准备将插入队列导入到数据库中，请稍等。。。",false);
		flush_buffers();
		$this->commit(); //提交事务
	}
	function begin()
	{
		if($this->db_type == MYSQL)
		{
			if(!mysql_query('BEGIN',$this->link)) //或者mysql_query("START TRANSACTION");
			{
				$this->fatalError('开启mysql事务失败！');
			}
		}
		else if($this->db_type == SQLITE)
		{
			if(!sqlite_query($this->link,'begin'))
			{
				$this->fatalError('开启sqlite事务失败！');
			}
		}
		else
			new error('开启数据库事务失败','无效的数据库类型！',true,true);
	}
	function commit()
	{
		if($this->db_type == MYSQL)
		{
			if(!mysql_query('COMMIT',$this->link))
			{
				$this->fatalError('提交mysql事务失败！');
			}
			mysql_query("END",$this->link);
		}
		else if($this->db_type == SQLITE)
		{
			if(!sqlite_query($this->link,'commit'))
			{
				$this->fatalError('提交sqlite事务失败！');
			}
		}
		else
			new error('提交数据库事务失败','无效的数据库类型！',true,true);
	}
}
?>