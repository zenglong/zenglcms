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
function js_item($arr, $name)   
{   
     if ( is_array($arr) ) {   
         echo "{$name}=new Array();\n";   
     }   
   
     foreach($arr as $i => $data) {   
         if ( is_array($data) ) {   
             js_item($data, "{$name}[{$i}]");   
         } else {   
         	  $data = str_replace('"', '\"', $data);
             echo "{$name}['{$i}'] = \"{$data}\";\n";   
         }   
     }  
   
     return;   
}   
function js_array($arr, $name)    
{
    ob_start();   
    echo "<script type='text/javascript'>\n";   
    echo "var {$name};\n";   
    js_item($arr, $name);   
    echo "</script>\n";   
    $js = ob_get_contents();   
    ob_end_clean();   
    return $js;   
} 

$help_cur_array = array(
				'admin_err' => array(
									array('admin.php?arg=show','goto 管理界面'),
									array('index.php','goto 首页'),
								),
				'admin_nopermis_err' => array(
											array('login_out_register.php?action=login','转入登录界面'),
											array('index.php','goto 首页'),
										),
				'login' =>  array(
									array('back','返回来页'),
									array('admin.php?arg=show','goto 管理界面'),
									array('index.php','goto 首页'),
								),
				'login_err' =>  array(
										array('login_out_register.php?action=login','返回重新登录'),
										array('back','返回来页'),
										array('index.php','goto 首页'),
								),
				'logout' =>  array(
									array('back','返回来页'),
									array('index.php','goto 首页'),
									array('login_out_register.php?action=login','再次登录'),
								),
				'register' => array(
									array('login_out_register.php?action=login','转入登录界面'),
									array('index.php','goto 首页'),
								),
				'register_err' => array(
									array('login_out_register.php?action=register','转入重新注册界面'),
									array('index.php','goto 首页'),
									array('login_out_register.php?action=login','goto 登录'),
								),
				'admin_logout' => array(
										array('index.php','goto 首页'),
									 	array('login_out_register.php?action=login','再次登录'),
										),
				'other' => array(
									array('back','返回来页'),
								),
							);

$help_global_referer = '';

function help_setcookie_pre_url()
{
	global $help_global_referer;
	$not_save_array = array('?action=login','?action=register' ,'?action=logout'); 
	if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
			foreach ($not_save_array as $arg)
				if(strpos($referer, $arg))
					return ;
			setcookie('preurl',$referer);
			$help_global_referer = $referer;
	}
}

function help_get_pre_url()
{
	global $help_global_referer;
	if($help_global_referer != '')
		return $help_global_referer;
	else if($_COOKIE['preurl'])
		return $_COOKIE['preurl'];
}

function get_jmp_locs($cur)
{
	global $help_cur_array;
	global $zengl_cms_rootdir;
	echo "&nbsp;&nbsp;";

	if($cur != '')
	{
		$cur_array = $help_cur_array[$cur];
		foreach ($cur_array as $arg)
		{	
			if($arg[0] == 'back')
			{
				$url = help_get_pre_url();
				echo "<a href='$url'>". $arg[1] .'</a>&nbsp;&nbsp;';
			}
			else
				echo '<a href="'.$zengl_cms_rootdir .$arg[0].'">'. $arg[1] .'</a>&nbsp;&nbsp;';
		}
	}
	else
		echo '<a href="javascript:history.go(-1);">[返回]</a>&nbsp;&nbsp;';
}

function subUTF8($string, $length = 80, $etc = '...')
{
	$retstr = '';
	$tmpstr = '';
	$strLength = 0;
	$width = 0;
	$isneed = false;
	if(strlen($string) > $length) {
		$sl = mb_strlen($string,'utf-8');
		for($i = 0; $i < $sl; $i++) {
			if ( $width >= $length) {
				$isneed = true;
				break;
			}
			//当检测到一个中文字符时
			if(ord($tmpstr = mb_substr($string, $i, 1,'utf-8')) > 127) {
				$width     += 2;    //大概按一个汉字宽度相当于两个英文字符的宽度
			} else {
				$width     += 1;
			}
			$retstr .= $tmpstr;
		}
		if($isneed)
			return $retstr . $etc;
		else
			return $retstr;
	} else {
		return $string;
	}
}

function toUTF8($str){
	$encoding = mb_detect_encoding($str, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
	return mb_convert_encoding($str, 'utf-8', $encoding);
}

function flush_buffers(){
	ob_end_flush();
	ob_flush();
	flush();
	ob_start();
}

function getIP() //获取用户真实的IP地址
{
	static $realip;
	if (isset($_SERVER)){
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
			$realip = $_SERVER["HTTP_CLIENT_IP"];
		} else {
			$realip = $_SERVER["REMOTE_ADDR"];
		}
	} else {
		if (getenv("HTTP_X_FORWARDED_FOR")){
			$realip = getenv("HTTP_X_FORWARDED_FOR");
		} else if (getenv("HTTP_CLIENT_IP")) {
			$realip = getenv("HTTP_CLIENT_IP");
		} else {
			$realip = getenv("REMOTE_ADDR");
		}
	}
	return $realip;
}

/***************************************************************************
 * Pinyin.php 原文：http://hi.baidu.com/yezhoulife/blog/item/e683842d9fbcc23d359bf71f.html
* ------------------------------
* Date : Nov 7, 2006
* Copyright : 修改自网络代码,版权归原作者所有
* Mail :
* Desc. : 拼音转换
* History :
* Date :
* Author :
* Modif. :
* Usage Example :
***************************************************************************/

function Pinyin($_String, $_Code='gb2312')
{
	$_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha".
			"|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|".
			"cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er".
			"|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui".
			"|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang".
			"|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang".
			"|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue".
			"|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne".
			"|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen".
			"|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang".
			"|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|".
			"she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|".
			"tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu".
			"|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you".
			"|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|".
			"zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";

	$_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990".
			"|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725".
			"|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263".
			"|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003".
			"|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697".
			"|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211".
			"|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922".
			"|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468".
			"|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664".
			"|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407".
			"|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959".
			"|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652".
			"|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369".
			"|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128".
			"|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914".
			"|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645".
			"|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149".
			"|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087".
			"|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658".
			"|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340".
			"|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888".
			"|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585".
			"|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847".
			"|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055".
			"|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780".
			"|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274".
			"|-10270|-10262|-10260|-10256|-10254";
	$_TDataKey = explode('|', $_DataKey);
	$_TDataValue = explode('|', $_DataValue);

	$_Data = (PHP_VERSION>='5.0') ? array_combine($_TDataKey, $_TDataValue) : _Array_Combine($_TDataKey, $_TDataValue);
	arsort($_Data);
	reset($_Data);

	if($_Code != 'gb2312') $_String = _U2_Utf8_Gb($_String);
	$_Res = '';
	for($i=0; $i<strlen($_String); $i++)
	{
		$_P = ord(substr($_String, $i, 1));
		if($_P>160) 
		{
			$_Q = ord(substr($_String, ++$i, 1)); $_P = $_P*256 + $_Q - 65536;
		}
		$_Res .= _Pinyin($_P, $_Data);
	}
	return preg_replace("/[^a-z0-9A-Z]*/", '', $_Res);
}

function _Pinyin($_Num, $_Data)
{
	if ($_Num>0 && $_Num<160 ) 
		return chr($_Num);
	elseif($_Num<-20319 || $_Num>-10247) 
		return '';
	else 
	{
		foreach($_Data as $k=>$v)
		{
			if($v<=$_Num) break;
		}
		return $k;
	}
}

function _U2_Utf8_Gb($_C)
{
	$_String = '';
	if($_C < 0x80) 
		$_String .= $_C;
	elseif($_C < 0x800)
	{
		$_String .= chr(0xC0 | $_C>>6);
		$_String .= chr(0x80 | $_C & 0x3F);
	}
	elseif($_C < 0x10000)
	{
		$_String .= chr(0xE0 | $_C>>12);
		$_String .= chr(0x80 | $_C>>6 & 0x3F);
		$_String .= chr(0x80 | $_C & 0x3F);
	} 
	elseif($_C < 0x200000) 
	{
		$_String .= chr(0xF0 | $_C>>18);
		$_String .= chr(0x80 | $_C>>12 & 0x3F);
		$_String .= chr(0x80 | $_C>>6 & 0x3F);
		$_String .= chr(0x80 | $_C & 0x3F);
	}
	return iconv('UTF-8', 'GB2312', $_String);
}

function _Array_Combine($_Arr1, $_Arr2)
{
	for($i=0; $i<count($_Arr1); $i++) 
		$_Res[$_Arr1[$i]] = $_Arr2[$i];
	return $_Res;
}

function mkdirs($dir)
{
	if(!is_dir($dir))
	{
		if(!mkdirs(dirname($dir)))
		{
			return false;
		}
		if(!mkdir($dir,0777))
		{
			return false;
		}
	}
	return true;
}
function rmdirs($dir,$progress = null)
{
	$d = dir($dir);
	if(!is_dir($dir))
		return ;
	while (false !== ($child = $d->read()))
	{
		if($child != '.' && $child != '..')
		{
			if(is_dir($dir.'/'.$child))  
			{
				rmdirs($dir.'/'.$child,$progress);
			}
			else 
			{
				unlink($dir.'/'.$child);
				if($progress !== null)
					$progress->step('删除文件：'.$dir.'/'.$child);
				else
				{
					echo '删除文件：'.$dir.'/'.$child.'<br/>';
					flush_buffers();
				}
			}
		}
	}  
	$d->close();
	if(is_dir($dir))
	{
		rmdir($dir);
		if($progress !== null)
			$progress->step('删除目录：'.$dir);
		else 
		{
			echo '删除目录：'.$dir.'<br/>';
			flush_buffers();
		}
	}
}

function getdirs($dir)
{
	$count = 0;
	if(!is_dir($dir))
		return $count;
	$d = dir($dir);
	while (false !== ($child = $d->read()))
	{
		if($child != '.' && $child != '..')
		{
			if(is_dir($dir.'/'.$child))
				$count += getdirs($dir.'/'.$child);
			else
				$count++;
		}
	}
	if(is_dir($dir))
		$count++;
	return $count;
}
?>