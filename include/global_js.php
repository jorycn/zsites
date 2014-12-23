<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$locale = str_replace('_','-',SessionHolder::get('_LOCALE'));
$script_path = P_SCP;
Html::includeJs('/popup/jquery-1.4.3.min.js');
Html::includeJs('/droppy.js');
Html::includeJs('/thickbox.js');
Html::includeJs('/helper.js');
Html::includeJs('/png.js');
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$script_path}/thickbox.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"{$script_path}/popup/theme/jquery.ui.core.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"{$script_path}/popup/theme/jquery.ui.dialog.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"{$script_path}/popup/theme/jquery.ui.theme.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"{$script_path}/popup/theme/jquery.ui.resizable.css\" />";
$type = ADVERT_STATUS;
if( isset($type) &&!empty($type) )
{
switch( $type ) {
case '1': 
$js = '/popup.js';
$func = "swf( '#pp', 'data/adtool/popup.swf', 300, 200 );";
$wrap = '<div id="pp" style="position:absolute;right:0;bottom:0;display:none;z-index:999;width:300px;height:200px;overflow:hidden;"></div>';
break;
case '2': 
$js = '/float.js';
$func = "swf( '#pp', 'data/adtool/popup.swf', 100, 100 );";
$wrap = '<div id="pp" style="width:100px;height:100px;overflow:hidden;z-index:999;"></div>   
				 	 <script type="text/javascript">   
				 	 var m=new AdMove("pp");    
			     	 m.Run();    
				 	 </script>';
break;
case '3': 
$js = '/couplet.js';
$func = "$('#pp').jFloat({
					 	position:'left',top:100,height:300,width:100,left:20,allowClose:false
					 });
					 swf( '#pp', 'data/adtool/popup.swf', 100, 300 );
					 
					 $('#cp').jFloat({
		        		position:'right',top:100,height:300,width:100,right:20,allowClose:false
					 });
		        	 swf( '#cp', 'data/adtool/couplet1.swf', 100, 300 );";
$wrap = '<div id="cp" style="z-index:999"></div><div id="pp" style="z-index:999"></div>';
break;
default:
$js = '';
$func = '';
break;
}
Html::includeJs('/jquery.flash.js');
if( !empty($js) ) Html::includeJs("{$js}");
echo "<style type=\"text/css\">
	.close {
		background-color:#FCABCF;
		color:#FFF;
		cursor:pointer;
		display:inline-block;
		float:right;
		font-size:12px;
		margin-bottom:-25px;
		padding:0 2px;
		position:relative;
		z-index:2000;
		height:15px;
		line-height:15px;
	}
</style>";
echo "<script type=\"text/javascript\" language=\"javascript\">
<!--
$(function(){
	$func
		
    $('.close').click(function(){
		$(this).parent().hide();
	});
	
	function swf( element, src, _x, _y ){
		$(element).flash(
			{
			  src: src,
			  width: _x,
			  height: _y,
			  wmode: 'transparent'
			},
			{ expressInstall: true }
		).prepend('<span class=\"close\">×</span>');	
	}
});
//-->
</script>";
}
if(SessionHolder::get('page/status','view') == 'edit')
{
echo "<script type=\"text/javascript\" language=\"javascript\">
	var border_style;//保存原来样式，鼠标划出恢复原来样式
	var border_color;
	var border_width;
	$(document).ready(function(){
		$(\".mod_block,.artview,.art_list\").hover(function(){//首页、内页选取 static,article,news,bulletin,各种列表模块选取
		
			border_style = $(this).css(\"border-style\");
			border_color = $(this).css(\"border_color\");
			border_width = $(this).css(\"border_width\");
            
			
			if($(this).parent().attr('id') == 'nav')
			{
				$(this).css({ \"border-style\": \"dashed\", \"border-color\": \"red\", \"border-width\": \"2px\", \"height\":  $(this).parent().css('height')});
			}
			else
			{
				if($(this).children(\"#com_con_sq\").size()!=1){
					$(this).css({ \"border-style\": \"dashed\", \"border-color\": \"red\", \"border-width\": \"2px\",\"cursor\":\"pointer\" });
				}
			}
		},function(){
			if($(this).parent().attr('id') == 'nav')
			{
				$(this).css({ \"border-style\": border_style, \"border-color\": border_color, \"border-width\": border_width });
			}
			else
			{
				$(this).css({ \"border-style\": border_style, \"border-color\": border_color, \"border-width\": border_width });
			}
		});
	});
</script>";
}