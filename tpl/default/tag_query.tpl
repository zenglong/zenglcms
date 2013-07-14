<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/style.css" media="screen">
<link rel="stylesheet" type="text/css" href="{zengl theme}/css/tag_query.css" media="screen">
<script type="text/javascript" src="{zengl theme}/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="{zengl theme}/js/jquery.paginate.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
		sec_pagenum = {zengl sec_PageNum};
		if(sec_pagenum > 0)
			$("#pages").paginate({ 
				        count    : {zengl sec_PageNum} {zengl sec_query}, 
				        start    : 1, 
				        display  : {zengl sec_DisplaySize}, 
				        //border                    : true, 
				        //border_color            : '#BEF8B8', 
				        //text_color              : '#79B5E3',
				        text_color				  : 'white',
				        //background_color        : '#E3F2E1',
				        background_color        : 'black',
				        //border_hover_color        : '#68BA64', 
				        //text_hover_color          : '#2573AF', 
				        //background_hover_color    : '#CAE6C6',
				        text_hover_color          : 'white', 
				        background_hover_color    : 'red',
				        images                    : false, 
				        mouse                    : 'press', 
				        onChange      : function(sec_page){ 
				        						if((value = encodeURI($('#query_word_hidden')[0].value)) != '')
				        							value = '&query_word=' + value;
				        						else
				        							value = '';
				        					   timestamp=new Date().getTime();
											   loadstr = "{zengl rootdir}tags_operate.php?action=getall_ajax" + 
												   			'&sec_page=' + sec_page + value + '&timestamp=' + timestamp;
											   $("#tag_content").load(loadstr, 
											                  function(response, status, xhr) { 
											                  	randomTag(); 
											                  	 });
												   		} 
	    				}); 
	    $("#tag_content").ajaxSend(function(event, request, settings){
        						$(this).html("<img src='images/loading.gif' /> 正在读取。。。");
        					});
        $("#query_btn").click(function(){
        						value = encodeURI($('#query_word')[0].value);
        						timestamp=new Date().getTime();
        						location.href = "{zengl rootdir}tags_operate.php?action=getall" + 
												   	"&query_word=" + value + '&timestamp=' + timestamp;
								return false;
        					});
        if($('#tag_content span a').length <=0)
        	$('#tag_content').html("<bold>Sorry,没有相关标签!</bold>");
	    function randomTag()
	    {
	    	 var x = 10,y = 24;
			 var color_arr=Array("#ff0000","#960","#F00","#03F","#0F0");
			 var a_count = $('#tag_content span a').size();
			 for(i=0;i < a_count;i++){
			   $("#tag_content span a:eq("+i+")").css("font-size",parseInt(Math.random()*(x-y+1)+y));
			   $("#tag_content span a:eq("+i+")").css("color",color_arr[Math.floor(Math.random()*color_arr.length)]);
			 }
	    }
	    randomTag();
	});
</script>
<title>{zengl title}</title>
</head>
<body>
<div id = "maindiv">
	<h2>{zengl title}</h2>
	<input type = 'text' id='query_word' value="{zengl query_word}" /> <a href='#' id='query_btn'>查询</a>
	<input type = 'hidden' id='query_word_hidden' value="{zengl query_word}" />
	<div id = 'tag_content'>
		{zengl for_tags}
			<span><a href={zengl tag_loc} target='_parent'>{zengl tag_name}</a>({zengl tag_count})</span>&nbsp;
		{zengl endfor}
	</div>
	<div id="pages"></div>
</div>
</body>
</html>