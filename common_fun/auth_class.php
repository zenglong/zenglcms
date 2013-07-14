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
class rndnum
{
	function rnd(){
		srand((double)microtime()*1000000);
		$rnd_number=array(
				1=>'1',
				2=>'2',
				3=>'3',
				4=>'4',
				5=>'5',
				6=>'6',
				7=>'7',
				8=>'8',
				9=>'9',
				10=>'a',
				11=>'b',
				12=>'c',
				13=>'d',
				14=>'e',
				15=>'f',
				16=>'g',
				17=>'h',
				18=>'i',
				19=>'j',
				20=>'k',
				21=>'l',
				22=>'m',
				23=>'n',
				24=>'o',
				25=>'p',
				26=>'q',
				27=>'r',
				28=>'s',
				29=>'t',
				30=>'u',
				31=>'v',
				32=>'w',
				33=>'x',
				34=>'y',
				35=>'z',
				36=>'0'
		);
		$result=array_rand($rnd_number,6);
		$j=count($result);
		for ($i=0;$i<$j;$i++) {
			$re.=$rnd_number[$result[$i]];
		}
		//$re=$rnd_number[$result[1]].$rnd_number[$result[2]].$rnd_number[$result[3]].$rnd_number[$result[4]].$rnd_number[$result[5]].$rnd_number[$result[6]].$rnd_number[$result[7]];
		//return array_keys($result);
		return $re;
	}
}
class authImg
{
	var $authnum;
	var $img_width=72;
	var $img_height=20;
	var $img_font=5;
	var $image;
	var $background;
	var $foreground;
	function __construct()
	{
		$rndnum=new rndnum();
		$this->authnum = $rndnum->rnd();
	}
	function mkpng()
	{
		$this->header_ImgType();
		$session = new session();
		$session->set_authnum($this->authnum);
		$this->createImg();
		$this->setBackColor(200, 200, 200);
		$this->setForeColor(0, 0, 0);
		$this->fillImg(0, 0);
		$this->draw_authnum(10, 3);
		$this->draw_points();
		imagepng($this->image);
		imagedestroy($this->image);
	}
	function header_ImgType()
	{
		Header("Content-type: image/PNG");
	}
	function createImg()
	{
		$this->image = imagecreate($this->img_width, $this->img_height);
	}
	function setBackColor($red,$green,$blue)
	{
		$this->background = imagecolorallocate($this->image, $red, $green, $blue);
	}
	function setForeColor($red,$green,$blue)
	{
		$this->foreground = imagecolorallocate($this->image,$red, $green, $blue);
	}
	function fillImg($x,$y)
	{
		imagefill($this->image, $x, $y, $this->background);
	}
	function draw_authnum($x,$y)
	{
		imagestring($this->image, $this->img_font, $x, $y, $this->authnum, $this->foreground);
	}
	function draw_points()
	{
		for($i=0;$i<200;$i++)
		{
			$randcolor = imagecolorallocate($this->image,rand(0,255),rand(0,255),rand(0,255));
			imagesetpixel($this->image, rand()%70,  rand()%30, $randcolor);
		}
	}
}
?>