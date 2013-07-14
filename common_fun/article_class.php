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
class article
{
	var $session;
	var $sql;
	var $tablename;
	var $error_str;
	var $all;
	var $sqlstr;
	var $secstr;
	var $array_file;
	var $permis;
	var $page_size = 20;
	var $display_size = 7;
	var $index_count = 10;
	var $index_sec_count = 6;
	var $progress;
	function __construct($use_session=false,$use_sql=false)
	{
		global $zengl_cms_filecache_dir;
		global $ZlCfg_IndexSecCount;
		global $ZlCfg_IndexArticleCount;
		global $ZlCfg_ListArticleCount;
		
		$this->index_sec_count = $ZlCfg_IndexSecCount;
		$this->index_count = $ZlCfg_IndexArticleCount;
		$this->page_size = $ZlCfg_ListArticleCount;
		
		$this->array_file = $zengl_cms_filecache_dir . 'all_sections_array.php';
		$this->permis = new permis();
		$this->progress = new progress();
		if($use_session)
		{
			$this->session = new session();
			$this->session->get_userinfo();
			$this->permis->set_sess(&$this->session);
		}
		if($use_sql)
		{
			$this->sql = new sql('utf8');
			$this->permis->sql = &$this->sql;
		}
	}
	function check_param($action)
	{
		global $rvar_title;
		global $rvar_author;
		global $rvar_content;
		if(!isset($rvar_title)) $rvar_title = '';
		if(!isset($rvar_author)) $rvar_author = '';
		if(!isset($rvar_content)) $rvar_content = '';
		//先检查参数
		if($action == 'add')
		{
			if( $rvar_title == '' || $rvar_author == '' || $rvar_content == '')
				return false;
			else 
				return true;
		}
		else if($action == 'ajax_save_to_draft')
		{
			if( $rvar_content == '')
				return false;
			else
				return true;
		}
		else if($action == 'edit')
		{
			global $rvar_articleID;
			if(isset($rvar_articleID) &&
				 $rvar_title!='' && $rvar_author!='' && $rvar_content!='')
				return true;
			else
				return false;
		}
		else if($action == 'del')
		{
			global $rvar_articleID;
			if(isset($rvar_articleID))
				return true;
			else 
				return false;
		}
		else if($action == 'gensechtml')
		{
			global $rvar_secID;
			if($rvar_secID != '')
				return true;
			else
				return false;
		}
		else
			return false;
	}
	function check_login()
	{
		if($this->session->userID == '' || $this->session->username == '' ||
			$this->session->userLevel == '')
			return false;
		else
			return true;
	}
	function check_perm($permis)
	{
		if($permis == ARTICLE_EDIT || $permis == ARTICLE_DEL)
		{
			global $rvar_articleID;
			$sql = &$this->sql;
			$this->tablename = $sql->tables_prefix . 'articles';
			$sql->query("select userID,permis from $this->tablename where articleID=" . $rvar_articleID);
			$sql->parse_results();
			if($this->permis->check_perm($permis, $sql->row['permis']))
				return true;
			else 
				return false;
		}
		else if($permis == ARTICLE_ADD || $permis == ARTICLE_ADMIN || $permis == ADMIN_HTML)
		{
			$sql = &$this->sql;
			$this->tablename = $sql->tables_prefix . 'articles';
			if($this->permis->check_perm($permis))
				return true;
			else
				return false;
		}
		else
			return false;
	}
	
	/*
	 * 删除文章生成的相关的html文件，包括分页的html文件*/
	function del_article_htmlfiles($base_path,$articleID)
	{
		$articlecache = $base_path . '-'. $articleID .'.html';
		if(file_exists($articlecache))
			unlink($articlecache);
		for($p=2;;$p++)
		{
			$articlecache = $base_path . '-'. $articleID .'-'.$p.'.html';
			if(file_exists($articlecache))
				unlink($articlecache);
			else
				break;
		}
	}
	
	function edit_article()
	{
		global $rvar_title;
		global $rvar_author;
		global $rvar_tags;
		global $rvar_content;
		global $rvar_smimgpath;
		global $rvar_isneed_getsmimg;
		global $rvar_checksmimg;
		global $rvar_descript;
		global $rvar_level;
		global $rvar_scans;
		global $rvar_sec_ID;
		global $rvar_articleID;
		global $rvar_time;
		global $rvar_addtime;
		global $rvar_tpl;
		global $zengl_cms_tpl_dir;
		global $article_descript_charnum;
		$rvar_time = isset($rvar_time) ? $rvar_time : '';
		if($rvar_time == '')
			$time = time();
		else
			$time = strtotime($rvar_time);
		if($rvar_addtime == '')
			$addtime = $time;
		else
			$addtime = strtotime($rvar_addtime);
		$sql = &$this->sql;
		$sql->query("select sec_ID from {$this->tablename} where articleID = $rvar_articleID");
		$sql->parse_results();
		$secID = $sql->row['sec_ID'];
		$author = $sql->escape_str($rvar_author);
		$title = $sql->escape_str($rvar_title);
		$tpl = $sql->escape_str($rvar_tpl);
		$level = $sql->escape_str($rvar_level);
		if($rvar_smimgpath == '' && $rvar_checksmimg == 1)
		{
			preg_match('/<img.*src="(.*)"\\s*.*>/iU',$rvar_content,$match);
			if(isset($match[1]) && $match[1]!= '')
			{
				$rvar_smimgpath = $sql->escape_str($match[1]);
				$sql->query("select * from {$sql->tables_prefix}archives where path = '{$rvar_smimgpath}'");
				$sql->parse_results();
				$rvar_smimgpath = $sql->row['smimgpath'];
			}
		}
		else if($rvar_smimgpath != '' && $rvar_isneed_getsmimg == 'yes')
		{
			$rvar_smimgpath = $sql->escape_str($rvar_smimgpath);
			$sql->query("select * from {$sql->tables_prefix}archives where path = '{$rvar_smimgpath}'");
			$sql->parse_results();
			if(isset($sql->row['smimgpath']) && $sql->row['smimgpath'] != '')
				$rvar_smimgpath = $sql->row['smimgpath'];
		}
		
		if($rvar_descript == '')
		{
			$rvar_descript = trim(subUTF8(strip_tags($rvar_content),$article_descript_charnum));
		}
		
		if(is_numeric($rvar_scans) && $rvar_scans!='')
			$scansCount = $rvar_scans;
		else
			$scansCount = 0;
		$content = $sql->escape_str($rvar_content);
		$descript = $sql->escape_str($rvar_descript);
		$smimgpath = $sql->escape_str($rvar_smimgpath);
		//$time = time();
		$tablename = $this->tablename;
		$userID = $this->session->userID;
		$sql->query("UPDATE $tablename SET title='$title',author='$author', " . 
				" time=$time,addtime=$addtime,content='$content',descript='$descript',smimgpath='$smimgpath',".
				" scansCount=$scansCount,sec_ID=$rvar_sec_ID,tpl='$tpl',level='$level' " . 
				" WHERE articleID=$rvar_articleID and userID=$userID");
		$tags = new tags(&$sql);
		$tags->update();
		if($sql->err == SQL_SUCCESS)
		{
			//$articlecache = $zengl_cms_tpl_dir . 'show_article_cache'. $rvar_articleID .'.php';
			$this->del_article_htmlfiles($this->GetSecDirFullPath($secID) . '/article', 
										$rvar_articleID);
			new success('提交情况：','编辑文章成功！',true,true);
		}
	}
	function show_edit_article()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/show_edit_article_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/show_edit_article_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file show_edit_article_class.php does not exist!');
	}
	function add_article()
	{
		global $rvar_title;
		global $rvar_author;
		global $rvar_tags;
		global $rvar_content;
		global $rvar_smimgpath;
		global $rvar_checksmimg;
		global $rvar_descript;
		global $rvar_level;
		global $rvar_scans;
		global $rvar_sec_ID;
		global $rvar_isneed_getsmimg;
		global $rvar_time;
		global $rvar_addtime;
		global $rvar_tpl;
		global $article_descript_charnum;
		$rvar_time = isset($rvar_time) ? $rvar_time : '';
		if($rvar_time == '')
			$time = time();
		else
			$time = strtotime($rvar_time);
		if($rvar_addtime == '')
			$addtime = $time;
		else
			$addtime = strtotime($rvar_addtime);
		$permis = &$this->permis;
		$permis->gen_cuid_permis(ARTICLE_EDIT, PER_ALLOW);
		$permis->gen_cuid_permis(ARTICLE_DEL, PER_ALLOW);
		$permis->gen_otheruid_permis(ARTICLE_EDIT, PER_DENY);
		$permis->gen_otheruid_permis(ARTICLE_DEL, PER_DENY);
		$sql = new sql('utf8');
		if($rvar_smimgpath == '' && $rvar_checksmimg == 1)
		{
			preg_match('/<img.*src="(.*)"\\s*.*>/iU',$rvar_content,$match);
			if($match[1]!= '')
			{
				$rvar_smimgpath = $sql->escape_str($match[1]);
				$sql->query("select * from {$sql->tables_prefix}archives where path = '{$rvar_smimgpath}'");
				$sql->parse_results();
				$rvar_smimgpath = $sql->row['smimgpath'];
			}
		}
		else if($rvar_smimgpath != '' && $rvar_isneed_getsmimg == 'yes')
		{
			$rvar_smimgpath = $sql->escape_str($rvar_smimgpath);
			$sql->query("select * from {$sql->tables_prefix}archives where path = '{$rvar_smimgpath}'");
			$sql->parse_results();
			if(isset($sql->row['smimgpath']) && $sql->row['smimgpath'] != '')
				$rvar_smimgpath = $sql->row['smimgpath'];
		}
		
		if($rvar_descript == '')
		{
			$rvar_descript = trim(subUTF8(strip_tags($rvar_content),$article_descript_charnum));
		}
		
		if(is_numeric($rvar_scans) && $rvar_scans!='')
			$scansCount = $rvar_scans;
		else
			$scansCount = 0;
		$sql->insert('articles','title,author,time,addtime,content,descript,smimgpath,scansCount,sec_ID,userID,tpl,level,permis',
				$rvar_title,$rvar_author,$time,$addtime,$rvar_content,$rvar_descript,
				$rvar_smimgpath,$scansCount,$rvar_sec_ID,$this->session->userID,
				$rvar_tpl,$rvar_level,$permis->gen_permis_str());
		$sql->query("select articleID from {$sql->tables_prefix}articles order by articleID desc limit 0,1");
		$sql->parse_results();
		$tag = new tags(&$sql);
		$tag->add($rvar_tags, $sql->row['articleID']);
		if($sql->err == SQL_SUCCESS)
			new success('ZENGLCMS文章提交情况：','文章添加成功！',true,true);
	}
	function del_article()
	{
		global $rvar_articleID;
		global $zengl_cms_tpl_dir;
		$sql = &$this->sql;
		$sql->query("select sec_ID from $this->tablename where articleID=$rvar_articleID");
		$sql->parse_results();
		$secID = $sql->row['sec_ID'];
		$sql->query("DELETE FROM $this->tablename WHERE articleID=$rvar_articleID");
		$tag = new tags(&$sql);
		$tag->update_for_del_articles($rvar_articleID); //清理tag标签
		if($sql->err== SQL_SUCCESS)
		{
			//$articlecache = $zengl_cms_tpl_dir . 'show_article_cache'. $rvar_articleID .'.php';
			$this->del_article_htmlfiles($this->GetSecDirFullPath($secID) . '/article',
										$rvar_articleID);
			new success('删除情况：','删除文章成功！',true,true);
		}
	}
	function recursive_show_options($id,$count,$select = '')
	{
		$secname = $this->all[$id]['sec_name'];
		$secname = "|--+$secname";
		for($i=0;$i<$count-4;$i++)
		{
			$secname = "&nbsp$secname";
		}
		if($select == $id)
			echo "<option value=$id selected='selected'>$secname</option>";
		else 
			echo "<option value=$id>$secname</option>";
		if($this->all[$id]['sec_content']!='')
		{
			$content = explode(',', $this->all[$id]['sec_content']);
			$count += 4;
			foreach ($content as $next)
				$this->recursive_show_options($next, $count,$select);
		}
	}
	function getallsections()
	{
		if(is_array($this->all))
			return;
		$section = new section(false,false);
		$section->sql = $section->permis->sql = &$this->sql;
		$section->getall();
		$section = null;
		if(file_exists($this->array_file))
			$this->all = unserialize(file_get_contents($this->array_file));
		else 
			new error('错误信息','栏目缓存不存在!',true,true);
	}
	function show_add_article()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/show_add_article_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/show_add_article_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file show_add_article_class.php does not exist!');
	}
	function recur_sec_sqlstr($sec_ID)
	{
		$this->sqlstr .= 'sec_ID=' . $sec_ID . ' or ';
		if($this->all[$sec_ID]['sec_content'] != '')
		{
			$content = explode(',', $this->all[$sec_ID]['sec_content']);
			foreach ($content as $next)
				$this->recur_sec_sqlstr($next);
		}
	}
	function list_articles()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/list_articles_class.php'))
			return include $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/list_articles_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			return include $zengl_theme_tpl_class;
		}
		else
			die('tpl class file list_articles_class.php does not exist!');
	}
	function index_articles()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_index = $zengl_cms_tpl_dir . $zengl_theme . '/class/index_articles_class.php'))
			include_once $zengl_theme_tpl_index;
		else if(file_exists($zengl_theme_tpl_index = $zengl_cms_tpl_dir . $zengl_old_theme . 
								'/class/index_articles_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_index;
		}
		else
			die('tpl class file index_articles_class.php does not exist!');
	}
	function header()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_index = $zengl_cms_tpl_dir . $zengl_theme . '/class/header_class.php'))
			include $zengl_theme_tpl_index;
		else if(file_exists($zengl_theme_tpl_index = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/header_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include $zengl_theme_tpl_index;
		}
		else
			die('tpl class file header_class.php does not exist!');
	}
	function footer()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_index = $zengl_cms_tpl_dir . $zengl_theme . '/class/footer_class.php'))
			include $zengl_theme_tpl_index;
		else if(file_exists($zengl_theme_tpl_index = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/footer_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include $zengl_theme_tpl_index;
		}
		else
			die('tpl class file footer_class.php does not exist!');
	}
	function show_article_sections($id,$count)
	{
		global $zengl_cms_rootdir;
		global $adminHtml_genhtml;
		$sec_name = $this->all[$id]['sec_name'];
		if($adminHtml_genhtml == 'yes')
		{
			if($this->all[$id]["sec_dirpath"] != "")
				$sec_dirpath = "html/" . $this->all[$id]["sec_dirpath"] . "/" .
								$this->all[$id]["sec_dirname"];
			else
				$sec_dirpath = "html/" . $this->all[$id]["sec_dirname"];
			$this->secstr = "<a href = '{$zengl_cms_rootdir}{$sec_dirpath}/'>" . $sec_name .
							"</a>" . '&nbsp;&gt;&nbsp;' . $this->secstr;
		}
		else
			$this->secstr = "<a href = '{$zengl_cms_rootdir}add_edit_del_show_list_article.php?hidden=list&
							sec_ID=$id&is_recur=yes'>" . $sec_name . 
							"</a>" . '&nbsp;&gt;&nbsp;' . $this->secstr;
		if($this->all[$id]['sec_parent_ID']!='0')
			$this->show_article_sections($this->all[$id]['sec_parent_ID'], $count+1);
		if($count==1)
		{
			$this->secstr = substr($this->secstr, 0,-16);
			if($adminHtml_genhtml == 'yes')
				$this->secstr = "<a href = '{$zengl_cms_rootdir}index.html'>主页" .
							"</a>" . '&nbsp;&gt;&nbsp;' . $this->secstr;
			else
				$this->secstr = "<a href = '{$zengl_cms_rootdir}index.php'>主页" .
								"</a>" . '&nbsp;&gt;&nbsp;' . $this->secstr;
			echo $this->secstr;
		}
	}
	function show_article()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/show_article_class.php'))
			include $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/show_article_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include $zengl_theme_tpl_class;
		}
		else
			die('tpl class file show_article_class.php does not exist!');
	}
	function getScansCount()
	{
		global $rvar_articleID;
		if(!isset($rvar_articleID))
			new error('获取计数发生错误',"无效的文章ID!",true,true);
		$sql = new sql('utf8');
		$sql->query("select scansCount from $sql->tables_prefix" . "articles where articleID = " . $rvar_articleID);
		$sql->parse_results();
		if(is_numeric($sql->row['scansCount']) && $sql->row['scansCount'] != '')
			$scansCount = $sql->row['scansCount'];
		else
			$scansCount = 0;
		$scansCount++;
		$sql->query("UPDATE {$sql->tables_prefix}articles SET scansCount=$scansCount " .
				" WHERE articleID=$rvar_articleID");
		echo $scansCount;
	}
	function admin_list()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/admin_list_article_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/admin_list_article_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file admin_list_article_class.php does not exist!');
	}
	function admin_multi_del_move()
	{
		global $rvar_id;
		global $rvar_action;
		global $rvar_sec_ID;
		global $zengl_cms_tpl_dir;
		//new success('测试结果：',"id=$rvar_id",true,true);
		if($rvar_id == '')
			new error('无效参数','请选择要操作的文章id',true,true);
		if($rvar_action == 'multimove' && $this->all == null)
		{
			$section = new section(true,true);
			$section->getall();
			$this->all =&$section->all;
		}
		$id_array = explode(',', $rvar_id);
		$id_str = '';
		foreach ($id_array as $key => $id)
		{
			if($key == 0)
				$id_str = 'articleID=' . $id;
			else
				$id_str .= ' or articleID='.$id;
		}
		$sql = &$this->sql;
		$tablename = $this->tablename;
		$sql->query("select *  from $tablename where $id_str");
		$del_str = '';
		while ($sql->parse_results()) {
			if(!$this->permis->check_perm(ARTICLE_DEL, $sql->row['permis']))
				new error('权限错误','用户无权操作文章：'. $sql->row['title'].' (id = '.$sql->row['articleID'].')',true,true);
			else
			{
				//$articlecache = $zengl_cms_tpl_dir . 'show_article_cache'. $id .'.php';
				$this->del_article_htmlfiles(
							$this->GetSecDirFullPath($sql->row['sec_ID']) . '/article', 
							$sql->row['articleID']);
				if($rvar_action == 'multidel')
					$del_str .= '删除文章：' . $sql->row['title'] . ' (id = ' . $sql->row['articleID'] . ') 成功!<br/>';
				else if($rvar_action == 'multimove')
					$del_str .= '移动文章：' . $sql->row['title'] . ' (id = ' . $sql->row['articleID'] . ')到'.
									$this->all[$rvar_sec_ID]['sec_name'].' (secid = ' . $rvar_sec_ID . ')'.' 成功!<br/>';
			}
		}
		if($rvar_action == 'multidel')
		{
			$sql->query("DELETE FROM $tablename WHERE $id_str");
			$tag = new tags(&$sql);
			$tag->update_for_del_articles($rvar_id); //清理tag标签
		}
		else if($rvar_action == 'multimove')
			$sql->query("UPDATE $tablename SET sec_ID = $rvar_sec_ID WHERE $id_str");
		if($sql->err== SQL_SUCCESS)
		{
			if($rvar_action == 'multidel')
				new success('删除情况：',$del_str,true,true);
			else if($rvar_action == 'multimove')
				new success('移动情况：',$del_str,true,true);
		}
	}
	/**
	 * 批量设置文章级别
	 * */
	function admin_multi_level_change()
	{
		global $rvar_id;
		global $rvar_level;
		if($rvar_id == '')
			new error('无效参数','请选择要操作的文章id',true,true);
		else if(!is_numeric($rvar_level))
			new error('无效参数','请选择要设置的级别',true,true);
		$id_array = explode(',', $rvar_id);
		$id_str = '';
		foreach ($id_array as $key => $id)
		{
			if($key == 0)
				$id_str = 'articleID=' . $id;
			else
				$id_str .= ' or articleID='.$id;
		}
		$sql = &$this->sql;
		$tablename = $this->tablename;
		$sql->query("UPDATE $tablename SET level = '$rvar_level' WHERE $id_str");
		if($sql->err== SQL_SUCCESS)
		{
			die('<script language="javascript" type="text/javascript">javascript:history.go(-1);</script>');
		}
	}
	function admin_multi_html()
	{
		global $rvar_id;
		global $adminHtml_genhtml;
		global $rvar_articleID;
		header( "Content-Type:   text/html;   charset=UTF-8 ");
		$adminHtml_genhtml = 'yes';
		if($rvar_id == '')
			new error('无效参数','请选择要操作的文章id',true,true);
		$id_array = explode(',', $rvar_id);
		$this->progress_begin("准备生成静态页面", count($id_array));
		foreach ($id_array as $id)
		{
			$rvar_articleID = $id;
			$this->show_article();
		}
		$this->progress_end("<a href='javascript:history.go(-1);'>生成完毕，点此返回</a>",false);
		$adminHtml_genhtml = 'no'; //sql对象撤销时用于释放数据库连接用的。
	}
	function OneKeyHtml()
	{
		set_time_limit(0);
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		//如果存在自定义的生成栏目静态页面的class处理程式，则调用该主题下的class.php
		if(file_exists($zengl_cms_tpl_dir . $zengl_theme . '/class/onekeyhtml_class.php'))
		{
			include $zengl_cms_tpl_dir . $zengl_theme . '/class/onekeyhtml_class.php';
			return;
		}
		
		global $zengl_cms_rootdir;
		global $rvar_sec_ID;
		global $rvar_sec_page;
		global $adminHtml_genhtml;
		global $rvar_is_recur;
		global $rvar_articleID;
		global $rvar_tpl_action;
		global $sec_allrownum;
		header( "Content-Type:   text/html;   charset=UTF-8 ");
		$adminHtml_genhtml = 'yes';
		$rvar_is_recur = 'yes';
		$this->sql->query("select * from {$this->sql->tables_prefix}articles");
		$totalrow_num = $this->sql->get_num();
		$totalsec_page = ceil($totalrow_num / $this->page_size);
		$this->progress_begin("准备生成静态页面", $totalsec_page + $totalrow_num + 1);
		$this->getallsections();
		$this->index_articles();
		foreach ($this->all as $secId => $array)
		{
			$rvar_sec_ID = $secId;
			$rvar_tpl_action = 'default';
			$this->list_articles();
			$pagecount = ceil($sec_allrownum/$this->page_size);
			$rvar_tpl_action = 'listajax';
			for($i=2;$i<=$pagecount;$i++)
			{
				$rvar_sec_page = $i;
				$this->list_articles();
			}
		}
		$rvar_tpl_action = '';
		$sql = new sql('utf8');
		$sql->query("select * from $sql->tables_prefix" . "articles");
		while ($sql->parse_results()) {
			$rvar_articleID = $sql->row['articleID'];
			$this->show_article();
		}
		$this->progress('生成完毕!');
		$this->progress_end("<a href='{$zengl_cms_rootdir}index.html' target='_blank'>" .
			  				"点此查看主页</a>",false);
		$adminHtml_genhtml = 'no'; //sql对象撤销时用于释放数据库连接用的。
	}
	function ShowGenHTMLforSec()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme .
				'/class/show_gen_html_forsec_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/show_gen_html_forsec_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file show_gen_html_forsec_class.php does not exist!');
	}
	function recur_sec_array($array,$secID)
	{
		array_push($array, $secID);
		if($this->all[$secID]['sec_content'] != '')
		{
			$content = explode(',', $this->all[$secID]['sec_content']);
			foreach ($content as $next)
				$this->recur_sec_array(&$array, $next);
		}
	}
	/*
	 * 输出进度条的开头
	 * 参数$title 标题和初始进度名
	 * 参数$total 总共需要操作的记录数
	 * */
	function progress_begin($title,$total)
	{
		$this->progress->begin($title, $total);
	}
	/*
	 * 输出进度条
	 * 参数$msg 进度条的消息
	 * */
	function progress($msg,$isNeedAddProgress = true)
	{
		$this->progress->step($msg,$isNeedAddProgress);
	}
	/*
	 * 输出进度条结束
	 * 参数$msg 进度条的消息*/
	function progress_end($msg,$isNeedAddLog = true)
	{
		$this->progress->end($msg,$isNeedAddLog);
	}
	function GenHTMLforSec()
	{
		set_time_limit(0);
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		//如果存在自定义的生成栏目静态页面的class处理程式，则调用该主题下的class.php
		if(file_exists($zengl_cms_tpl_dir . $zengl_theme . '/class/GenHtmlForSec_class.php'))
		{
			include $zengl_cms_tpl_dir . $zengl_theme . '/class/GenHtmlForSec_class.php';
			return;
		}
		
		global $zengl_cms_rootdir;
		global $rvar_sec_ID;
		global $rvar_sec_page;
		global $adminHtml_genhtml;
		global $rvar_is_recur;
		global $rvar_articleID;
		global $rvar_checkbox;
		global $rvar_secID;
		global $rvar_tpl_action;
		global $sec_allrownum;
		header( "Content-Type:   text/html;   charset=UTF-8 ");
		$is_gen_article = false;
		$is_recur_sec = false;
		if($rvar_secID <= 0)
		{
			$rvar_secID = 1;
			$is_gen_article = true;
			$is_recur_sec = true;
		}
		if($rvar_checkbox != null)
		{
			foreach ($rvar_checkbox as $value)
			{
				if($value == 'gen_article')
					$is_gen_article = true;
				if($value == 'is_recur')
					$is_recur_sec = true;
			}
		}
		$adminHtml_genhtml = 'yes';
		$rvar_is_recur = 'yes';
		$this->getallsections();
		$array = array();
		if($is_recur_sec)
		{
			$this->recur_sec_array(&$array,$rvar_secID);
			array_unique($array);
		}
		else 
			array_push($array, $rvar_secID);
		$this->progress_begin('准备生成静态页面',count($array) + 1);
		foreach ($array as $secId)
		{
			$rvar_sec_ID = $secId;
			$rvar_tpl_action = 'default';
			$this->list_articles();
			$pagecount = ceil($sec_allrownum/$this->page_size);
			$rvar_tpl_action = 'listajax';
			for($i=2;$i<=$pagecount;$i++)
			{
				$rvar_sec_page = $i;
				$this->list_articles();
			}
		}
		$rvar_tpl_action = '';
		if($is_gen_article)
		{
			$sql = new sql('utf8');
			if($is_recur_sec)
			{
				$this->sqlstr = "select *  from $sql->tables_prefix" . "articles where ";
				$this->recur_sec_sqlstr($rvar_secID);
				$this->sqlstr = substr($this->sqlstr, 0, -3);
			}
			else 
			{
				$this->sqlstr = "select *  from $sql->tables_prefix" . "articles where " . 
								  "sec_ID=$rvar_secID";
			}
			$sql->query($this->sqlstr);
			$this->progress->setNewTotalTask($sql->get_num()+1);
			while ($sql->parse_results()) {
				$rvar_articleID = $sql->row['articleID'];
				$this->show_article();
			}
		}
		$this->index_articles();
		$this->progress_end("<a href='{$zengl_cms_rootdir}index.html' target='_blank'>" .
							"点此查看主页</a>",false);
		$adminHtml_genhtml = 'no'; //sql对象撤销时用于释放数据库连接用的。
	}
	function OneKeyRM_HTML()
	{
		set_time_limit(0);
		header( "Content-Type:   text/html;   charset=UTF-8 ");
		$this->progress_begin('正在获取所有的文件个数...',15);
		$totalfiles = getdirs('html');
		$this->progress->setNewTotalTask($totalfiles + 3);
		rmdirs('html',&$this->progress);
		unlink('index.html');
		$this->progress_end('删除主页index.html');
	}
	function GetSecDirFullPath($secId)
	{
		if($this->all == null)
			$this->getallsections();
		if($this->all[$secId]["sec_dirpath"] != "")
			$sec_dirpath = "html/" . $this->all[$secId]["sec_dirpath"] . "/" .
							 $this->all[$secId]["sec_dirname"];
		else
			$sec_dirpath = "html/" . $this->all[$secId]["sec_dirname"];
		return $sec_dirpath;
	}
	/*添加编辑文章时，将文章保存为草稿*/
	function AjaxSaveToDraft()
	{
		global $rvar_content;
		global $rvar_isAutoSave;
		global $zengl_cms_filecache_dir;
		if(!isset($rvar_isAutoSave)) $rvar_isAutoSave = '';
		$draft_dir = $zengl_cms_filecache_dir . 'draft';
		mkdirs($draft_dir);
		$draft_file = $draft_dir . '/' .'article_draft_cache_uid_'.$this->session->userID.'.draft';
		if(get_magic_quotes_gpc())
			file_put_contents($draft_file,stripslashes($rvar_content));
		else
			file_put_contents($draft_file,$rvar_content);
		$time = date("Y年m月d日 H时i分s秒");
		if($rvar_isAutoSave == 'yes')
			$save_feedback = ' 系统自动保存内容到草稿中';
		else
			$save_feedback = ' 成功保存内容到草稿中';
		exit('服务端时间:'.$time.$save_feedback);
	}
	function AjaxGetDraft()
	{
		global $zengl_cms_filecache_dir;
		$draft_dir = $zengl_cms_filecache_dir . 'draft';
		$draft_file = $draft_dir . '/' .'article_draft_cache_uid_'.$this->session->userID.'.draft';
		if(!file_exists($draft_file))
		{
			$json_array['hasDraft'] = 'no';
			$json_array['time'] = '';
			$json_array['content'] = '';
			$json_encode_string = json_encode($json_array);
			exit($json_encode_string);
		}
		$content = file_get_contents($draft_file);
		$json_array['hasDraft'] = 'yes';
		$json_array['time'] = date("Y年m月d日 H时i分s秒",filemtime($draft_file));
		$json_array['content'] = $content;
		$json_encode_string = json_encode($json_array);
		exit($json_encode_string);
	}
}
?>