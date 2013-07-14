<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/success.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jQuery.timers.js"></script>
<script type="text/javascript">
times = 7;
is_stop = false;
$(document).ready(function() {
		$('#timer').html(times--);
		$('#timer').everyTime(1000,'start',function(i){
				if(is_stop == false)
				{
					if(times <= 0)
					{
						if($('#content a:first')[0])
						{
							s = $('#content a:first')[0].href;
							s = s.substr(s.lastIndexOf('/')+1);
							if(isNaN(s) || s == '')
								location.href = $('#content a:first')[0].href;
							else
							{
								backnum = parseInt(s);
								backnum = -backnum;
								history.go(backnum);
							}
						}
						else
							history.go(-1);
					}
					else
						$(this).html(times--);
				}
		  });
		  $('#content a').mouseover(function(){
		  		//$('#timer').stopTime('start').html("跳转计时停止，请选择去向！");
		  		is_stop = true;
		  });
		  $('#content a').mouseout(function(){
		  		is_stop = false;
		  });
		  $('#timer').mouseover(function(){
		  		/*if(!is_stop)
		  		{
			  		$('#timer').stopTime('start').html("跳转计时停止，请选择去向:" + 
			  						"<a href = 'javascript:history.go(-1);'>返回前一页</a>&nbsp;&nbsp" + 
			  						"<a href='{zengl cms_root_dir}index.php'>goto 首页</a>");
			  		is_stop = true;
			  	}*/
			  	is_stop = true;
		  });
		   $('#timer').mouseout(function(){
		  		is_stop = false;
		  });
	});
</script>
<title>{zengl title}</title>
</head>
<body>
<div id = 'main'>
	<div id = "main_head">
	</div>
	<div id = "main_mid">
		<div id="title">
			{zengl title}
		</div>
		<div id = 'content'>{zengl content} <br/><br/>(鼠标划到下面链接上或秒数上可暂停数秒)<br/> <br/>
		{zengl jmp_locs} <br/><br/>
		<span id='timer'></span>秒后跳转到第一个链接或返回前页..
		</div>
	</div>
	<div id = "main_last">
	</div>
</div>
</body>
</html>