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
class tags{
	var $sql;
	var $page_size = 50;
	var $display_size = 7;
	function __construct($sql)
	{
		if(isset($sql))
		{
			$this->sql = $sql;
		}
	}
	function add($tags,$articleID)
	{
		$sql = $this->sql;
		$tablename = $sql->tables_prefix . 'tags';
		if(!(isset($tags) && is_string($tags) && $tags != ''))
			return null;
		$tag_array = explode(',', $tags);
		$tag_array = array_unique($tag_array);
		$sqlstr = "select * from $tablename where tag_name = '{$sql->escape_str($tag_array[0])}'";
		$tmp = array_shift($tag_array);
		foreach ($tag_array as $tag)
		{
			$tag = $sql->escape_str($tag);
			$tag = "'$tag'";
			$sqlstr .= " or tag_name = $tag";
		}
		$sql->query($sqlstr);
		array_unshift($tag_array,$tmp);
		$tmpsql = new sql('utf8');
		while($sql->parse_results())
		{
			$articles = $sql->row['articles'];
			$articles_array = explode(',', $articles);
			array_push($articles_array, $articleID);
			$articles_array = array_unique($articles_array);
			$count = count($articles_array);
			$articles = implode(',', $articles_array);
			$articles = $sql->escape_str($articles);
			$articles = "'$articles'";
			$tmpsql->query("update $tablename set count = $count , articles = $articles where tag_ID = '{$sql->row['tag_ID']}'");
			$unset_key = array_search($sql->row['tag_name'], $tag_array);
			if($unset_key === false)
				continue;
			else
				unset($tag_array[$unset_key]);
		}
		foreach ($tag_array as $tag)
		{
			$sql->insert('tags','tag_name,count,articles',$tag,1,$articleID);
		}
	}
	function find($articleID)
	{
		if(!is_numeric($articleID))
			new error('标签查询失败','无效的文章ID',true,true);
		$sql = $this->sql;
		$tablename = $sql->tables_prefix . 'tags';
		$sql->query("select * from $tablename where articles = '$articleID' or articles like '$articleID,%' or ".
					"articles like '%,$articleID,%' or articles like '%,$articleID'");
		$result=array();
		while($sql->parse_results())
		{
			$result[$sql->row['tag_name']]= $sql->row['tag_ID'];
		}
		return $result;
	}
	function query($startPage,$size)
	{
		global $rvar_tag;
		global $tag_startPage;
		if(!is_numeric($rvar_tag))
			new error('标签查询失败','无效的标签ID',true,true);
		$sql = $this->sql;
		$tablename = $sql->tables_prefix . 'tags';
		$sql->query("select * from $tablename where tag_ID = '$rvar_tag'");
		$sql->parse_results();
		$article_array = explode(',', $sql->row['articles']);
		$retstr = $sql->row['tag_name'];
		$sqlstr = "select * from {$sql->tables_prefix}articles where ";
		if(count($article_array) > 0)
		{
			$firstval = $article_array[0];
			foreach ($article_array as $val)
			{
				if($val == $firstval)
					$sqlstr .= "articleID = $val";
				else
					$sqlstr .= " or articleID = $val";
			}
			$sqlstr .= " order by time desc";
		}
		else
			$sqlstr .= "articleID = -1";
		if(is_numeric($startPage) && is_numeric($size))
			$sqlstr .= " limit $startPage,$size";
		$sql->query($sqlstr);
		return $retstr;
	}
	function update()
	{
		global $rvar_tags;
		global $rvar_articleID;
		if(!(isset($rvar_tags) && is_string($rvar_tags) && $rvar_tags != ''))
			return null;
		$sql = $this->sql;
		$tablename = $sql->tables_prefix . 'tags';
		$sql->query("select * from $tablename where articles = '$rvar_articleID' or articles like '$rvar_articleID,%' or ".
				"articles like '%,$rvar_articleID,%' or articles like '%,$rvar_articleID'");
		$tag_array = explode(',', $rvar_tags);
		$tag_array = array_unique($tag_array);
		$tmpsql = new sql('utf8');
		while($sql->parse_results())
		{
			if(in_array($sql->row['tag_name'],$tag_array))
			{
				$key = array_search($sql->row['tag_name'], $tag_array);
				if($key === false)
					continue;
				else
					unset($tag_array[$key]);
			}
			else
			{
				$articles = $sql->row['articles'];
				$article_array = array_unique(explode(',', $articles));
				if(($key = array_search($rvar_articleID,$article_array)) === false)
					continue;
				else
					unset($article_array[$key]);
				if(count($article_array) == 0)
					$tmpsql->query("delete from $tablename where tag_ID = {$sql->row['tag_ID']}");
				else
				{
					$articles = implode(',', $article_array);
					$tmpsql->query("update $tablename set articles = '$articles',count = '" . count($article_array) .
									"' where tag_ID = {$sql->row['tag_ID']}");
				}
			}
		}
		$this->add(implode(',', $tag_array), $rvar_articleID);
	}
	function getall()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme .
				'/class/tag_query_tpl_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/tag_query_tpl_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file tag_query_tpl_class.php does not exist!');
	}
	function getall_ajax()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme .
				'/class/tag_query_ajax_tpl_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/tag_query_ajax_tpl_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file tag_query_ajax_tpl_class.php does not exist!');
	}
	function get_some($num)
	{
		$sql = $this->sql;
		$tablename = $sql->tables_prefix . 'tags';
		$sql->query("select * from $tablename order by count desc limit 0,$num");
		$result=array();
		while($sql->parse_results())
		{
			$result[$sql->row['tag_name']]= $sql->row['tag_ID'];
		}
		return $result;
	}
	
}
?>