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
class archive
{
	var $session;
	var $sql;
	var $uploadfile;
	var $permis;
	function __construct($use_session=false,$use_sql=false)
	{
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
		if($permis == ARCHIVE_EDIT || $permis == ARCHIVE_DEL)
		{
			global $rvar_archiveID;
			if($rvar_archiveID == null)
				new error('禁止访问','文章ID为空，无法编辑',true,true);
			$sql = &$this->sql;
			$sql->query("select userID,permis from {$sql->tables_prefix}archives where archive_ID in (" .  
						$rvar_archiveID.")");
			while($sql->parse_results())
			{
				if(!$this->permis->check_perm($permis, $sql->row['permis']))
					return false;
			}
			return true;
		}
		else if($permis == ARCHIVE_UPLOAD || $permis == ARCHIVE_LIST)
		{
			if($this->permis->check_perm($permis))
				return true;
			else
				return false;
		}
	}
	function check_param($action)
	{
		if($action == 'upload')
		{
			if($_FILES['upload']['name'] == '')
				return false;
			else
				return true;	
		}
		elseif ($action == 'list')
			return true;
		elseif ($action == 'del' || $action == 'multi_del')
		{
			global $rvar_archiveID;
			if(isset($rvar_archiveID))
				return true;
			else
				return false;
		}
		elseif ($action == 'edit' )
		{
			global $rvar_archiveID;
			global $rvar_title;
			global $rvar_submit;
			if(!isset($rvar_archiveID))
				new error('无法执行编辑','文章ID参数没有提交！',true,true);
			if(($rvar_title != '' || $_FILES['upload']['name'] != '') && $rvar_submit == "提交")
				return true;
			else
				return false;
		}
		else
			return false;
	}
	function result($url,$message)
	{
		$funcNum = $_GET['CKEditorFuncNum'] ;
		exit("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');".
			  "</script>");
	}
	function ImageResize($srcFile,$toW,$toH,$toFile="",$isinEdit = false)
	{
		if($toFile==""){ $toFile = $srcFile; }
		$info = "";
		$data = GetImageSize($srcFile,$info);
		switch ($data[2])
		{
		case 1:
			if(!function_exists("imagecreatefromgif")){
				if($isinEdit)
					new error('生成缩略图失败！','不好意思，你的GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式！',true,true);
				else
	       		$this->result('', "你的GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式！") ;
	      	}
	       $im = ImageCreateFromGIF($srcFile);
	       break;
		case 2:
	       if(!function_exists("imagecreatefromjpeg")){
	       	if($isinEdit)
	       		new error('生成缩略图失败！','不好意思，你的GD库不能使用jpeg格式的图片，请使用其它格式的图片！',true,true);
	       	else
	       		$this->result('',"你的GD库不能使用jpeg格式的图片，请使用其它格式的图片！");
	      	}
	       $im = ImageCreateFromJpeg($srcFile);   
	       break;
		case 3:
	       $im = ImageCreateFromPNG($srcFile);   
	       break;
		}
		$srcW=ImageSX($im);
		$srcH=ImageSY($im);
		if($srcW <= $toW && $srcH <= $toH){
			$ftoW = $srcW;
			$ftoH = $srcH;
		}
		else{
	       $ftoW=$toW;
	       $ftoH=$toH;
		} 
		if(function_exists("imagecreatetruecolor"))
		{
        	@$ni = ImageCreateTrueColor($ftoW,$ftoH);
        	if($ni) 
        		ImageCopyResampled($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
        	else
        	{
         		$ni=ImageCreate($ftoW,$ftoH);
          		ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
        	}
	    }
	   else
		{
	       $ni=ImageCreate($ftoW,$ftoH);
	       ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
	    }
		if(function_exists('imagejpeg')) 
			ImageJpeg($ni,$toFile);
		else 
			ImagePNG($ni,$toFile);
		ImageDestroy($ni);
	   ImageDestroy($im);
	   return $toFile;
	}
	function upload()
	{
		global $zengl_upload_dir;
		global $archive_smimg_width;
		global $archive_smimg_height;
		global $archive_smimg_dirname;
		global $rvar_isNeedWaterMark; //是否需要水印
		header("Content-type: text/html; charset=utf-8");
		$rvar_isNeedWaterMark = isset($rvar_isNeedWaterMark) ? $rvar_isNeedWaterMark : '';
		$array = array('jpg','gif','png','jpeg');
		$title = trim(basename(' ' . $_FILES['upload']['name'])); //加入空格可以修复basename处理中文字符时的BUG。
		$file_type = end(explode(".",$title)); //获取文件后缀名
		$title = substr($title,0,-(strlen($file_type)+1));
		$file_name = Pinyin($title,'utf8') . '.' .$file_type;
		$this->uploadfile = $zengl_upload_dir . $file_name;
		$smimgtmp =  $zengl_upload_dir . $archive_smimg_dirname . '/' . $file_name;
		$time = time();
		$timestr = date('YmdHis',$time);
		$tmparray = explode('.',$this->uploadfile);
		$smimgpath = ''; 
		if(($tmpcount = count($tmparray)) >= 2)
		{
			$tmparray[$tmpcount-2] .= '_' . $timestr;
			$this->uploadfile = implode('.', $tmparray);
		}
		else
			$this->uploadfile .= '_'.$timestr;
		//$this->uploadfile = str_replace('.','_'.$timestr.'.' , $this->uploadfile);
		if(!move_uploaded_file($_FILES['upload']['tmp_name'], $this->uploadfile))
		{
			$url = '';
			$message = '不好意思，上传文件失败！请稍候重传！';
		}
		else
		{
			if($rvar_isNeedWaterMark == 'yes')
			{
				$watermark = new WaterMark($this->uploadfile,$rvar_isNeedWaterMark,true,$_GET['CKEditorFuncNum']);
				$watermark->output();
			}
			$tmparray = explode('.',$smimgtmp);
			if(($tmpcount = count($tmparray)) >= 2)
			{
				if(in_array($tmparray[$tmpcount - 1],$array))
				{
					if(is_dir($zengl_upload_dir . $archive_smimg_dirname) == false)
						mkdir($zengl_upload_dir . $archive_smimg_dirname);
					$tmparray[$tmpcount-2] .= '_smimg_' . $timestr;
					$smimgpath = implode('.', $tmparray);
					$this->ImageResize($this->uploadfile, $archive_smimg_width, $archive_smimg_height,
										  				$smimgpath);
				}
			}
			$permis = &$this->permis;
			$permis->gen_cuid_permis(ARCHIVE_EDIT, PER_ALLOW);
			$permis->gen_cuid_permis(ARCHIVE_DEL, PER_ALLOW);
			$permis->gen_otheruid_permis(ARCHIVE_EDIT, PER_DENY);
			$permis->gen_otheruid_permis(ARCHIVE_DEL, PER_DENY);
			if(!isset($this->sql))
				$this->sql = new sql('utf8');
			else 
				$sql = &$this->sql;
			$sql->insert('archives','title,path,smimgpath,time,userID,permis',
					$title,$this->uploadfile,$smimgpath,$time,$this->session->userID,$permis->gen_permis_str());
			if($sql->err != SQL_SUCCESS)
				new error('ZENGLCMS附件上传情况：','附件数据库添加时失败！',true,true);
			$url = $this->uploadfile;
			$message = '上传成功！';
		}
		$funcNum = $_GET['CKEditorFuncNum'] ;
		echo "<script type='text/javascript'>window.parent.setimgsmimg_ineditor('{$smimgpath}');window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
	}
	function upload_forWaterMark()
	{
		header("Content-type: text/html; charset=utf-8");
		$array = array('jpg','gif','png','jpeg');
		$title = trim(basename(' ' . $_FILES['upload']['name'])); //加入空格可以修复basename处理中文字符时的BUG。
		$file_type = end(explode(".",$title)); //获取文件后缀名
		if(!in_array($file_type, $array))
		{
			die('上传失败:图片后缀名不对，必须是jpg,jpeg,gif,png为后缀的图片格式 ');
		}
		$filename = 'watermark.'.$file_type;
		$this->uploadfile = 'images/'.$filename;
		if(!move_uploaded_file($_FILES['upload']['tmp_name'], $this->uploadfile))
		{
			die('不好意思，上传文件失败！请稍候重传！');
		}
		else
		{
			echo $filename;
		}
	}
	function list_uploads()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/list_uploads_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/list_uploads_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file list_uploads_class.php does not exist!');
	}
	function list_uploads_smimg()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . '/class/list_uploads_smimg_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/list_uploads_smimg_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file list_uploads_smimg_class.php does not exist!');
	}
	function del_archive()
	{
		global $rvar_archiveID;
		$sql = &$this->sql;
		$sql->query("select path,smimgpath from {$sql->tables_prefix}archives where archive_ID=" .
				$rvar_archiveID);
		$sql->parse_results();
		$path = $sql->row['path'];
		$smimgpath = $sql->row['smimgpath'];
		$sql->query("DELETE FROM {$sql->tables_prefix}archives WHERE archive_ID=$rvar_archiveID");
		if($sql->err== SQL_SUCCESS)
		{
			if(file_exists($path))
				unlink($path);
			else
				new success('删除情况：','指定的附件之前已经被删除了！',true,true);
			if($smimgpath != '' && file_exists($smimgpath))
				unlink($smimgpath);
			new success('删除情况：','删除指定附件成功！',true,true);
		}
	}
	function multi_del_archives()
	{
		global $rvar_archiveID;
		$sql = &$this->sql;
		$sql->query("select archive_ID,path,smimgpath from {$sql->tables_prefix}archives where archive_ID in (" .
					$rvar_archiveID . ")");
		$tmpsql = new sql('utf8');
		while($sql->parse_results())
		{
			$path = $sql->row['path'];
			$smimgpath = $sql->row['smimgpath'];
			$tmpsql->query("DELETE FROM {$sql->tables_prefix}archives WHERE archive_ID={$sql->row['archive_ID']}");
			if($sql->err== SQL_SUCCESS)
			{
				if(file_exists($path))
					unlink($path);
				if($smimgpath != '' && file_exists($smimgpath))
					unlink($smimgpath);
			}
		}
		new success('删除情况：','删除指定附件成功！',true,true);
	}
	function show_edit_archive()
	{
		global $zengl_cms_tpl_dir;
		global $zengl_theme;
		global $zengl_old_theme;
		if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_theme . 
				'/class/show_edit_archive_class.php'))
			include_once $zengl_theme_tpl_class;
		else if(file_exists($zengl_theme_tpl_class = $zengl_cms_tpl_dir . $zengl_old_theme .
				'/class/show_edit_archive_class.php'))
		{
			$zengl_theme = $zengl_old_theme;
			include_once $zengl_theme_tpl_class;
		}
		else
			die('tpl class file show_edit_archive_class.php does not exist!');
	}
	function edit_archive()
	{
		global $rvar_archiveID;
		global $rvar_title;
		global $rvar_isautoname;
		global $zengl_upload_dir;
		global $archive_smimg_width,$archive_smimg_height;
		if($_FILES['upload']['name']!='')
		{
			if(!isset($this->sql))
				$this->sql = new sql('utf8');
			else
				$sql = &$this->sql;
			$userID = $this->session->userID;
			$sql->query("select path,smimgpath from {$sql->tables_prefix}archives
			WHERE archive_ID=$rvar_archiveID and userID=$userID");
			$sql->parse_results();
			if(file_exists($sql->row['path']))
				unlink($sql->row['path']);
			$title = basename($_FILES['upload']['name']);
			//$this->uploadfile = $zengl_upload_dir . $title;
			$this->uploadfile = $sql->row['path'];
			$smimgpath = $sql->row['smimgpath'];
			$time = time();
			/*$timestr = date('YmdHis',$time);
			$this->uploadfile = str_replace('.','_'.$timestr.'.' , $this->uploadfile); */
			if(!move_uploaded_file($_FILES['upload']['tmp_name'], $this->uploadfile))
			{
				$url = '';
				new error('上传失败！','不好意思，上传文件失败！请稍候重传！',true,true);
			}
			if($smimgpath != '')
				$this->ImageResize($this->uploadfile, $archive_smimg_width, $archive_smimg_height,
										$smimgpath);
			if($rvar_title != '')
			{
				$title = $rvar_title;
				$title = $sql->escape_str($title);
				$title = "title='$title',";
			}
			elseif ($rvar_isautoname == 'yes')
			{
				$title = $sql->escape_str($title);
				$title = "title='$title',";
			}
			else
				$title = '';
			$this->uploadfile = $sql->escape_str($this->uploadfile);
			$smimgpath = $sql->escape_str($smimgpath);
			$timestr = $sql->escape_str($timestr);
			$sql->query("UPDATE {$sql->tables_prefix}archives SET $title
							path='{$this->uploadfile}' , smimgpath='{$smimgpath}' , time= $time   
							WHERE archive_ID=$rvar_archiveID and userID=$userID");
		}
		elseif ($rvar_title != '')
		{
			if(!isset($this->sql))
				$this->sql = new sql('utf8');
			else
				$sql = &$this->sql;
			$userID = $this->session->userID;
			$title = $rvar_title;
			$title = $sql->escape_str($title);
			$title = "title='$title'";
			$sql->query("UPDATE {$sql->tables_prefix}archives SET $title 
						WHERE archive_ID=$rvar_archiveID and userID=$userID");
		}
		else 
			new error('编辑失败','无效的编辑参数！',true,true);
		if($sql->err == SQL_SUCCESS)
			new success('附件编辑情况：','附件编辑更新成功！',true,true);
	}
}
?>