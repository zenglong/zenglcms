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

function convertToCache($filetpl)
{
	return str_replace('.tpl', '_cache.php', $filetpl);
}
?>