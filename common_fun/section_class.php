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
class section
{
	var $session;
	var $sql;
	var $error_str;
	var $all;
	var $array_file;
	var $permis;
	function __construct($use_session=false,$use_sql=false)
	{
		global $zengl_cms_filecache_dir;
		$this->array_file = $zengl_cms_filecache_dir . "all_sections_array.php";
		$this->permis = new permis();
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
	function check_perm($permis)
	{
		if($permis == SEC_DEL || $permis == SEC_EDIT)
		{
			global $rvar_delID;
			$this->getall();
			if($this->permis->check_perm($permis, $this->all[$rvar_delID]['permis']))
				return true;
			else
				return false;
		}
		else if($permis == SEC_ADD)
		{
			if($this->permis->check_perm($permis))
				return true;
			else
				return false;
		}
	}
	function check_param()
	{
		global $rvar_action;
		if($rvar_action == 'add')
		{
			global $rvar_secname;
			global $rvar_parentID;
			if($rvar_secname == null || $rvar_parentID == null)
				return false;
			else 
				return true;
		}
		else if($rvar_action == 'del' || $rvar_action == 'edit')
		{
			global $rvar_delID;
			global $rvar_afterdelID;
			if(isset($rvar_delID) && $rvar_delID == 0)
				new error('参数错误！','请您选择正确的栏目！',true,true);
			if($rvar_delID == null || $rvar_afterdelID == null)
				return false;
			if($rvar_delID !=null && $this->find_child($rvar_delID, $rvar_afterdelID))
				new error('参数错误！','所属ID和删除ID相同或者是删除ID的子ID，ID父子关系冲突！',true,true);
			return true;
		}
		else if($rvar_action == 'setmenu')
		{
			global $rvar_position;
			global $rvar_secID;
			if($rvar_position == null && $rvar_secID == null)
				return false;
			else if(!is_numeric($rvar_position))
				new error('参数错误！','请输入数字为位置！',true,true);
			else 
				return true;
		}
	}
	function find_child($id,$childID)
	{
		$this->getall();
		if($id==$childID)
			return true;
		if($this->all[$id]['sec_content']!='')
		{
			$content = explode(',', $this->all[$id]['sec_content']);
			foreach ($content as $next)
				if($this->find_child($next, $childID))
					return true;
		}
		return false;
	}
	function is_addsamesec()
	{
		global $rvar_secname;
		global $rvar_sec_dirname;
		global $rvar_parentID;
		$this->getall();
		foreach ($this->all as $key => $data)
		{
			if(($rvar_secname == $data['sec_name'] || $rvar_sec_dirname == $data['sec_dirname']) && 
				 $rvar_parentID == $data['sec_parent_ID'])
				return true;
		}
		return false;
	}
	function add($fun_call = false)
	{
		global $rvar_secname;
		global $rvar_sec_dirname;
		global $rvar_sec_weights;
		global $rvar_list_tpl;
		global $rvar_article_tpl;
		global $rvar_linkurl;
		global $rvar_keyword;
		global $rvar_description;
		global $rvar_sec_type;
		global $rvar_parentID;
		if($rvar_sec_dirname == '')
			$rvar_sec_dirname = Pinyin($rvar_secname,'utf8');
		if($rvar_sec_weights == '')
			$rvar_sec_weights = 50;
		if($rvar_sec_type == '')
			$rvar_sec_type = 'article';
		if($this->is_addsamesec())
			new error('栏目操作失败：','存在相同的栏目名或者静态目录名相同(目录名可能是栏目名的拼音)，'.
										'请重新设置栏目名或目录名！',true,true);
		$permis = &$this->permis;
		$permis->gen_cuid_permis(SEC_EDIT, PER_ALLOW);
		$permis->gen_cuid_permis(SEC_DEL, PER_ALLOW);
		$permis->gen_otheruid_permis(SEC_EDIT, PER_DENY);
		$permis->gen_otheruid_permis(SEC_DEL, PER_DENY);
		$sql = &$this->sql;
		$sql->insert('section','sec_name,sec_dirname,sec_parent_ID,sec_content,sec_weights,tpl,article_tpl,linkurl,keyword,sec_description,type,permis',
				$rvar_secname,$rvar_sec_dirname,$rvar_parentID,'',
				$rvar_sec_weights,$rvar_list_tpl,$rvar_article_tpl,
				$rvar_linkurl,$rvar_keyword,$rvar_description,$rvar_sec_type,$permis->gen_permis_str());
		$sql->query("select sec_content from $sql->tables_prefix" . "section where sec_ID = $rvar_parentID");
		$sql->parse_results();
		$newcontent=$sql->row['sec_content'];
		$sql->query("select sec_ID from $sql->tables_prefix" . "section where sec_name='$rvar_secname' and 
					  sec_parent_ID = $rvar_parentID");
		$sql->parse_results();
		$id = $sql->row['sec_ID'];
		if($newcontent == '')
		{
			$newcontent = $id;
		}
		else
		{
			$newcontent = explode(',', $newcontent);
			$newcontent[$id] = $id;
			$newcontent = implode(',', $newcontent);
			$newcontent = trim($newcontent);
			$newcontent = trim($newcontent,',');
		}
		$sql->query("update $sql->tables_prefix" . "section set sec_content = '$newcontent' 
				where sec_ID = $rvar_parentID");
		if(file_exists($this->array_file))
			unlink($this->array_file);
		if($sql->err == SQL_SUCCESS)
		{
			if($fun_call == false)
				new success('ZENGLCMS栏目提交情况：','栏目添加成功！',true,true);
			else if($fun_call == true) 
				return $id;
		}
	}
	function recur_del($id,$parentid)
	{
		$sql = $this->sql;
		if($this->all[$id]['sec_content']!='')
		{
			$content = explode(',', $this->all[$id]['sec_content']);
			foreach ($content as $next)
				$this->recur_del($next, $parentid);
		}
		$sql->query("delete from $sql->tables_prefix" . "section where sec_ID = $id");
		$sql->query("update $sql->tables_prefix" . "articles set sec_ID=$parentid where sec_ID = $id");
	}
	function del($fun_call = false)
	{
		global $rvar_delID;
		global $rvar_afterdelID;
		global $rvar_isrecur;
		$sql = &$this->sql;
		if($rvar_isrecur == 'no' || $rvar_isrecur == '')
		{
			$this->getall();
			$parentID = $this->all[$rvar_delID]['sec_parent_ID'];
			$sql->query("delete from $sql->tables_prefix" . "section where sec_ID = $rvar_delID");
			$sql->query("update $sql->tables_prefix" . "articles set sec_ID=$rvar_afterdelID where sec_ID = $rvar_delID");
			$sql->query("update $sql->tables_prefix" . "section set sec_parent_ID=$rvar_afterdelID 
					where sec_parent_ID = $rvar_delID");
			$parentContent = explode(',',$this->all[$parentID]['sec_content']);
			$array_key = array_search($rvar_delID, (array)$parentContent);
			if($array_key || $array_key==0) 
				unset($parentContent[$array_key]);
			$del_content = explode(',',$this->all[$rvar_delID]['sec_content']);
			if($parentID == $rvar_afterdelID)
				$sec_content = $parentContent;
			else
				$sec_content = explode(',',$this->all[$rvar_afterdelID]['sec_content']);
			//$sec_content = array_merge((array)$del_content,(array)$sec_content);
			$sec_content = array_merge((array)$sec_content,(array)$del_content);
			$sec_content = array_unique((array)$sec_content);
			$sec_content = $sql->escape_str(implode(',', $sec_content));
			$sec_content = trim($sec_content);
			$sec_content = trim($sec_content,',');
			if($parentID != $rvar_afterdelID)
			{
				$parentContent = implode(',', $parentContent);
				$parentContent = $sql->escape_str($parentContent);
				$sql->query("update $sql->tables_prefix" . "section set sec_content = '$parentContent'
						where sec_ID=$parentID");
			}
			$sql->query("update $sql->tables_prefix" . "section set sec_content = '$sec_content' 
					where sec_ID=$rvar_afterdelID");
		}
		else if($rvar_isrecur == 'yes')
		{
			$this->getall();
			$parentID = $this->all[$rvar_delID]['sec_parent_ID'];
			$this->recur_del($rvar_delID, $rvar_afterdelID);
			$parentContent = explode(',',$this->all[$parentID]['sec_content']);
			$array_key = array_search($rvar_delID, (array)$parentContent);
			if($array_key || $array_key==0)
				unset($parentContent[$array_key]);
			$parentContent = implode(',', $parentContent);
			$parentContent = trim($parentContent);
			$parentContent = trim($parentContent,',');
			$parentContent = $sql->escape_str($parentContent);
			$sql->query("update $sql->tables_prefix" . "section set sec_content = '$parentContent'
					where sec_ID=$parentID");
			
		}
		if(file_exists($this->array_file))
			unlink($this->array_file);
		if($sql->err == SQL_SUCCESS)
		{
			if($fun_call == false)
				new success('ZENGLCMS栏目提交情况：','栏目删除成功！',true,true);
			else if($fun_call == true)
				return true;
		}
	}
	function rename($rename)
	{
		global $rvar_delID;
		global $rvar_edit_dirname;
		global $rvar_edit_weights;
		global $rvar_edit_list_tpl;
		global $rvar_edit_article_tpl;
		global $rvar_edit_linkurl;
		global $rvar_edit_keyword;
		global $rvar_edit_description;
		global $rvar_sec_type;
		
		/*if(($this->all[$rvar_delID]['sec_dirname'] != '' && 
			$this->all[$rvar_delID]['sec_weights'] !='') 	&& 
			(($rename == '' && $rvar_edit_dirname == '' && $rvar_edit_weights == '') || 
			 ($rename == $this->all[$rvar_delID]['sec_name'] && 
			  $rvar_edit_dirname == $this->all[$rvar_delID]['sec_dirname']) &&
			  $rvar_edit_weights == $this->all[$rvar_delID]['sec_weights']))
			new success('ZENGLCMS栏目编辑情况：','栏目无须修改！',true,true);*/
		if($rename == '')
			$rename = $this->all[$rvar_delID]['sec_name'];
		if($rvar_edit_dirname == '')
			$rvar_edit_dirname = Pinyin($rename,'utf8');
		if($rvar_edit_weights == '')
		{
			if($this->all[$rvar_delID]['sec_weights'] !='')
				$rvar_edit_weights = $this->all[$rvar_delID]['sec_weights'];
			else
				$rvar_edit_weights = 50;
		}
		if($rvar_sec_type == '')
		{
			$rvar_sec_type = 'article';
		}
		$sql = &$this->sql;
		$rename = $sql->escape_str($rename);
		$dirname = $sql->escape_str($rvar_edit_dirname);
		$sec_weights = $sql->escape_str($rvar_edit_weights);
		$tpl = $sql->escape_str($rvar_edit_list_tpl);
		$article_tpl = $sql->escape_str($rvar_edit_article_tpl);
		$linkurl = $sql->escape_str($rvar_edit_linkurl);
		$type = $sql->escape_str($rvar_sec_type);
		$keyword = $sql->escape_str($rvar_edit_keyword);
		$description = $sql->escape_str($rvar_edit_description);
		$sql->query("update {$sql->tables_prefix}section set sec_name = '$rename', " . 
					  " sec_dirname = '$dirname', sec_weights = '$sec_weights', " .
					  " tpl = '$tpl', article_tpl = '$article_tpl', " .
					  " linkurl = '$linkurl', type = '$type' , keyword = '$keyword' , sec_description = '$description'" . 
					  " where sec_ID = $rvar_delID");
		if(file_exists($this->array_file))
			unlink($this->array_file);
		if($sql->err == SQL_SUCCESS)
			new success('ZENGLCMS栏目编辑情况：','栏目修改成功！',true,true);
	}
	function edit()
	{
		global $rvar_delID;
		global $rvar_afterdelID;
		global $rvar_edit_name;
		global $rvar_edit_dirname;
		if($this->all[$rvar_delID]['sec_parent_ID'] == $rvar_afterdelID)
			$this->rename($rvar_edit_name);
		else 
		{
			global $rvar_secname;
			global $rvar_sec_dirname;
			global $rvar_parentID;
			if($rvar_edit_name == '')
				$rvar_edit_name = $this->all[$rvar_delID]['sec_name'];
			if($rvar_edit_dirname == '')
				$rvar_edit_dirname = Pinyin($rvar_edit_name,'utf8');
			if($rvar_edit_weights == '')
				$rvar_edit_weights = $this->all[$rvar_delID]['sec_weights'];
			$rvar_secname = $rvar_edit_name;
			$rvar_sec_dirname = $rvar_edit_dirname;
			$rvar_sec_weights = $rvar_edit_weights;
			$rvar_parentID = $rvar_afterdelID;
			$rvar_afterdelID = $this->add(true);
			$orig_permis = $this->all[$rvar_delID]['permis'];
			/* $orig_weights = $this->all[$rvar_delID]['sec_weights'];
			$orig_weights = $this->sql->escape_str($orig_weights); */
			$orig_permis = $this->sql->escape_str($orig_permis);
			$this->sql->query("update {$this->sql->tables_prefix}section set permis = '$orig_permis'
								 where sec_ID = $rvar_afterdelID");
			global $rvar_isrecur;
			$rvar_isrecur = 'no';
			if($this->del(true))
				new success('栏目编辑情况：','栏目移动成功！',true,true);
		}
	}
	function recursive_show_options($id,$count,$select)
	{
		global $rvar_action;
		$secname = $this->all[$id]['sec_name'];
		$secname = "|--+$secname";
		if($id=='1' && ($select == '' || $select == '0') && 
		  $rvar_action!='del' && $rvar_action!='add')
			echo "<option value='0' selected='selected'>所有栏目文章</option>";
		else if($id=='1' && $rvar_action!='del' && $rvar_action!='add')
			echo "<option value='0'>所有栏目文章</option>";
		for($i=0;$i<$count-4;$i++)
		{
			$secname = "&nbsp;$secname";
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
	function getall()
	{
		if(is_array($this->all))
			return;
		else if(file_exists($this->array_file))
			$this->all = unserialize(file_get_contents($this->array_file));
		else 
		{
			$magic_quote = get_magic_quotes_gpc();
			$this->all = array();
			$sql = &$this->sql;
			$sql->query("select * from $sql->tables_prefix" . "section");
			while($sql->parse_results())
			{
				$this->all[$sql->row['sec_ID']] = array('sec_ID'=>$sql->row['sec_ID'] ,
						'sec_name'=>$sql->row['sec_name'],
						'sec_dirname'=>$sql->row['sec_dirname'],
						'sec_parent_ID'=>$sql->row['sec_parent_ID'],
						'sec_content'=>$sql->row['sec_content'],
						'sec_weights'=>$sql->row['sec_weights'],
						'tpl'=>$sql->row['tpl'],
						'article_tpl'=>$sql->row['article_tpl'],
						'linkurl'=>$sql->row['linkurl'],
						'type'=>$sql->row['type'],
						'permis'=>$sql->row['permis']);
				if(!empty($magic_quote))
				{
					$this->all[$sql->row['sec_ID']]['keyword'] = stripslashes($sql->row['keyword']);
					$this->all[$sql->row['sec_ID']]['sec_description'] = stripslashes($sql->row['sec_description']);
				}
				else
				{
					$this->all[$sql->row['sec_ID']]['keyword'] = $sql->row['keyword'];
					$this->all[$sql->row['sec_ID']]['sec_description'] = $sql->row['sec_description'];
				}
			}
			foreach ($this->all as $secId => $array)
			{
				$parentId = $array['sec_parent_ID'];
				if(!isset($patharray[$parentId]))
				{
					$path = '';
					$tmpid = $parentId;
					while($tmpid > 0)
					{
						if($path == '')
							$path = $this->all[$tmpid]['sec_dirname'];
						else
							$path = $this->all[$tmpid]['sec_dirname'] . '/' . $path;
						$tmpid = $this->all[$tmpid]['sec_parent_ID'];
					}
					$patharray[$parentId] = $path;
				}
				$this->all[$secId]['sec_dirpath'] = $patharray[$parentId];
			}
			file_put_contents($this->array_file, serialize($this->all));
		}
	}
	function show_add_section()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/show_add_section_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/show_add_section_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file show_add_section_class.php does not exist!');
	}
	function show_del_section()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/show_del_section_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/show_del_section_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file show_del_section_class.php does not exist!');
	}
	function list_sections($select)
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/list_section_class.php'))
			include $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/list_section_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include $zengl_theme_tpl_class;
		}
		else
			die('tpl class file list_section_class.php does not exist!');
	}
	function show_setmenu()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/section_setmenu_class.php'))
			include $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/section_setmenu_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include $zengl_theme_tpl_class;
		}
		else
			die('tpl class file section_setmenu_class.php does not exist!');
	}
	function real_setmenu()
	{
		global $rvar_position;
		global $rvar_secID;
		if($this->all == null)
			$this->getall();
		if(!is_numeric($rvar_secID) || $rvar_secID <= 1)
			new error('设置栏目位置失败','无效的栏目ID',true,true);
		$parentID = $this->all[$rvar_secID]['sec_parent_ID'];
		$parentContent = explode(',',$this->all[$parentID]['sec_content']);
		$count = count($parentContent);
		$array_key = array_search($rvar_secID, (array)$parentContent);
		if($array_key || $array_key==0)
			unset($parentContent[$array_key]);
		if($rvar_position <= 1)
			array_unshift($parentContent, $rvar_secID);
		else if($rvar_position >= $count)
			array_push($parentContent, $rvar_secID);
		else
			array_splice($parentContent, $rvar_position - 1 , 0 ,$rvar_secID);
		array_unique($parentContent);
		$parentContent = $this->sql->escape_str(implode(',', $parentContent));
		$this->sql->query("update {$this->sql->tables_prefix}section set 
				sec_content = '$parentContent'
				where sec_ID = $parentID");
		if(file_exists($this->array_file))
			unlink($this->array_file);
		if($this->sql->err == SQL_SUCCESS)
			new success('ZENGLCMS栏目调整情况：','栏目调整成功！',true,true);
	}
}
?>