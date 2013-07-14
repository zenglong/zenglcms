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
class session
{
	var $authnum;
	var $username;
	var $userID;
	var $userLevel;
	var $userPermis;
	var $levelPermis;
	function __construct($bool_getinfo=false)
	{
		session_start();
		if($bool_getinfo)
			$this->get_userinfo();
	}
	function set_authnum($authnum)
	{
		$this->authnum = $authnum;
		$_SESSION['authnum'] = $this->authnum;
	}
	function get_authnum()
	{
		$this->authnum = $_SESSION['authnum'];
		return $this->authnum;
	}
	function set_userinfo($username,$userID,$userLevel,$userPermis,$levelPermis)
	{
		$this->username = $username;
		$this->userID = $userID;
		$this->userLevel = $userLevel;
		$this->userPermis = $userPermis;
		$this->levelPermis = $levelPermis;
		$_SESSION['username'] = $this->username;
		$_SESSION['userID'] = $this->userID;
		$_SESSION['userLevel'] = $this->userLevel;
		$_SESSION['userPermis'] = $userPermis;
		$_SESSION['levelPermis'] = $levelPermis;
	}
	function get_userinfo()
	{
		$this->username = $_SESSION['username'];
		$this->userID = $_SESSION['userID'];
		$this->userLevel = $_SESSION['userLevel'];
		$this->userPermis = $_SESSION['userPermis'];
		$this->levelPermis = $_SESSION['levelPermis'];
	}
	function unset_all()
	{
		unset($_SESSION['authnum']);
		unset($_SESSION['username']);
		unset($_SESSION['userID']);
		unset($_SESSION['userLevel']);
		unset($_SESSION['userPermis']);
		unset($_SESSION['levelPermis']);
	}
}
?>