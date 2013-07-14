<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf8">
<title>{$title}</title>
<script language="JavaScript">
<!--
function updateProgress(sMsg, iWidth)
{ 
    document.getElementById("status").innerHTML = sMsg;
    document.getElementById("progress").style.width = iWidth + "px";
    document.getElementById("percent").innerHTML = parseInt(iWidth / {$width} * 100) + "%";
}
function addlog(sLog)
{
	var mytextarea = document.getElementById("text_log");
	mytextarea.value += "\n" + sLog;
	mytextarea.scrollTop = mytextarea.scrollHeight;
}
//-->
</script> 
</head>
<body>

<div style="margin: 4px; padding: 8px; border: 1px solid gray; background: #EAEAEA; width: {php echo $width+8;}px">
  <div><font color="gray">如下进度条的动态效果由服务器端 PHP 程序结合客户端 JavaScript 程序生成。</font></div>
  <div style="padding: 0; background-color: white; border: 1px solid navy; width: {$width}px">
    <div id="progress" style="padding: 0; background-color:#71d656; border: 0; width: 0px; text-align: center;  height: 16px"></div> 
  </div>
  <div id="status" style="height:55px;">&nbsp;</div>
  <div id="percent" style="position: relative; top: -30px; text-align: center; font-weight: bold; font-size: 8pt">0%</div>
  <div>
	日志：
	<textarea id="text_log" style="height:400px;width:{$width}px;font-size:13px;color:#777"></textarea>
  </div>
</div>
<script language="JavaScript">
updateProgress("{$title}", {php $progress+=$pix; echo min($width, intval($progress));});
</script>