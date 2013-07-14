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
function template_addquote($var) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}

class tpl
{
	var $outstring;
	var $filetpl;
	var $filecache;
	function __construct($filetpl,$filecache)
	{
		$this->filetpl = $filetpl;
		if($filecache != '')
			$this->filecache = $filecache;
		else
			$this->filecache = str_replace('.tpl', '_cache.php', $filetpl);
		$this->outstring = file_get_contents($this->filetpl);
	}
	function template_parse() {
		$str = $this->outstring;
		$str = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $str);
		$str = preg_replace("/\{template\s+([^\}]+)\}/", "<?php include template(\\1);?>", $str);
		$str = preg_replace("/\{php\s+(.+)\}/", "<?php \\1?>", $str);
		$str = preg_replace("/\{if\s+(.+?)\}/", "<?php if(\\1) { ?>", $str);
		$str = preg_replace("/\{else\}/", "<?php } else { ?>", $str);
		$str = preg_replace("/\{elseif\s+(.+?)\}/", "<?php } else if(\\1) { ?>", $str);
		$str = preg_replace("/\{\/if\}/", "<?php } ?>", $str);
		$str = preg_replace("/\{loop\s+(\S+)\s+(\S+)\}/", "<?php if(is_array(\\1)) { foreach(\\1 as \\2) { ?>", $str);
		$str = preg_replace("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php if(is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>", $str);
		$str = preg_replace("/\{\/loop\}/", "<?php } } ?>", $str);
		$str = preg_replace("/\{while\s+(.+?)\}/", "<?php while(\\1) { ?>", $str);
		$str = preg_replace("/\{\/while\}/", "<?php } ?>", $str);
		$str = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str);
		$str = preg_replace("/<\?php([^\?]+)\?>/es", "template_addquote('<?php\\1?>')", $str);
		$str = preg_replace("/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/", "<?php echo \\1;?>", $str);
		$str = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\->\$\x7f-\xff]+)\}/es", "template_addquote('<?php echo \\1;?>')", $str);
		$str = preg_replace("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>", $str);
		$str = preg_replace("/\'([A-Za-z]+)\[\'([A-Za-z\.]+)\'\](.?)\'/s", "'\\1[\\2]\\3'", $str);
		$str = preg_replace("/(\r?\n)\\1+/", "\\1", $str);
		$str = str_replace("\t", '', $str);
		$this->outstring = $str;
		return $str;
	}
	function setVar($tagname,$tagvar,$boolphp = false)
	{
		if($boolphp == true)
			$tagvar = '<?php '.$tagvar.' ?>';
		$this->outstring = str_replace("{zengl ". $tagname ."}", $tagvar, $this->outstring);
	}
	function cache()
	{
		file_put_contents($this->filecache, $this->outstring);
	}
}

function template($file)
{
	global $zengl_cms_tpl_dir , $zengl_theme;
	$file = $zengl_cms_tpl_dir.$zengl_theme.'/'.$file.'.tpl';
	$file_cache = str_replace('.tpl', '_cache.php', $file);
	$file_cache = str_replace($zengl_theme, 'cache', $file_cache);
	if(!file_exists($file))
		return '';
	if(file_exists($file_cache) && ( filemtime($file_cache) > filemtime($file) ) &&
	  (filemtime($file_cache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	{
		return $file_cache;
		exit();
	}
	$tpl = new tpl($file,$file_cache);
	$tpl->template_parse();
	$tpl->cache();
	return $file_cache;
}

function convertToCache($filetpl)
{
	return str_replace('.tpl', '_cache.php', $filetpl);
}
?>