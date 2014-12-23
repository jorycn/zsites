<?php

if (!defined('IN_CONTEXT')) die('access violation error!');
class Form {
private $_form_action;
private $_form_name;
private $_func_validate;
private $_enc_type;
private $_validate_script;
public function __construct($form_action,$form_name,$func_validate = false) {
$this->_form_action = $form_action;
$this->_form_name = $form_name;
$this->_func_validate = $func_validate;
$this->_enc_type = false;
}
public function setEncType($enc_type) {
$this->_enc_type = $enc_type;
}
public function toJs($use_single_quote = false) {
$quote = '"';
if ($use_single_quote) {
$quote = '\'';
}
return 'document.forms['.$quote.$this->_form_name.$quote.']';
}
public function open() {
echo '<form name="'.$this->_form_name.'" id="'.$this->_form_name.'" ';
if ($this->_enc_type !== false)
{
echo 'enctype="'.$this->_enc_type.'" ';
}
if ($this->_func_validate !== false)
{
echo 'onsubmit="javascript:return '.$this->_func_validate.'(this);" ';
}
echo 'action="'.$this->_form_action.'" method="post">'."\n";
}
public function p_open($module,$action,$r_type = '_page') {
$this->open();
echo Html::input('hidden','_m',$module);
echo Html::input('hidden','_a',$action);
if ($r_type != '_page') {
$r_type = '_ajax';
}
echo Html::input('hidden','_r',$r_type);
}
public function close() {
echo '</form>'."\n";
}
public function addValidate($element_name,$validate_type,$alert_msg) {
if ($validate_type !== false) {
$validate_types = explode('|',$validate_type);
for ($i = 0;$i <count($validate_types);$i++) {
$func_name = 'gen'.$validate_types[$i];
$this->$func_name($element_name,$alert_msg);
}
}
}
public function writeValidateJs() {
if ($this->_func_validate !== false) {
echo '<script type="text/javascript" language="javascript">'."\n";
echo '<!--'."\n";
echo 'function '.$this->_func_validate.'(thisForm)'."\n";
echo '{'."\n";
echo $this->_validate_script;
echo 'return true;'."\n";
echo '}'."\n";
echo '-->'."\n";
echo '</script>'."\n";
}
}
public function genCompareValidate($element_name_1,$element_name_2,
$compare = '=',$alert_msg = '') {
if (empty($alert_msg)) {
$alert_msg = 'Target mismatch!';
}
switch ($compare) {
case '!=':
$compare = '==';
break;
case '>':
$compare = '<';
break;
case '<':
$compare = '>';
break;
default:
$compare = '!=';
break;
}
$this->_validate_script .= 'if (thisForm.elements["'.$element_name_1.'"].value '
.$compare.' thisForm.elements["'.$element_name_2.'"].value)'."\n"
.'{'."\n"
."\t".'alert("'.$alert_msg.'");'."\n"
."\t".'return false;'."\n"
.'}'."\n";
}
public function genRequireNum($element_name,$alert_msg = '') {
if (empty($alert_msg)) {
$alert_msg = 'Target format error!';
}
$this->_validate_script .= 'if (!/^[+-]{0,1}[0-9]+(\.[0-9]+){0,1}$/.test(thisForm.elements["'.$element_name.'"].value))'."\n"
.'{'."\n"
."\t".'alert("'.$alert_msg.'");'."\n"
."\t".'thisForm.elements["'.$element_name.'"].focus();'."\n"
."\t".'return false;'."\n"
.'}'."\n";
}
public function genRequiredTextbox($element_name,$alert_msg = '') {
if (empty($alert_msg)) {
$alert_msg = 'Missing text field!';
}
$this->_validate_script .= 'if (/^\s*$/.test(thisForm.elements["'.$element_name.'"].value))'."\n"
.'{'."\n"
."\t".'alert("'.$alert_msg.'");'."\n"
."\t".'thisForm.elements["'.$element_name.'"].focus();'."\n"
."\t".'return false;'."\n"
.'}'."\n";
}
public function genRequiredRichText($instance_name,$alert_msg = '') {
if (empty($alert_msg)) {
$alert_msg = 'Missing text field!';
}
$instance_var_name = str_replace(array('[',']'),array('_','_'),$instance_name);
$this->_validate_script .= 'var ed_'.$instance_var_name.' = FCKeditorAPI.GetInstance("'.$instance_name.'");'."\n"
.'var richtxt = ed_'.$instance_var_name.'.EditorDocument.body.innerHTML;'."\n"
.'if (/^\s*|(\&nbsp;)*$/.test(richtxt.replace(/<[^>]*>/g, "")))'."\n"
.'{'."\n"
."\t".'alert("'.$alert_msg.'");'."\n"
."\t".'return false;'."\n"
.'}'."\n";
}
public function genRequiredSelect($element_name,$alert_msg = '') {
if (empty($alert_msg)) {
$alert_msg = 'Empty selected value!';
}
$element_var_name = str_replace(array('[',']'),array('_','_'),$element_name);
$var_name = 'sel_'.$element_var_name;
$this->_validate_script .= 'var '.$var_name.' = thisForm.elements["'.$element_name.'"];'."\n"
.'if (/^\s*$/.test('.$var_name.'.options['.$var_name.'.selectedIndex].value))'."\n"
.'{'."\n"
."\t".'alert("'.$alert_msg.'");'."\n"
."\t".$var_name.'.focus();'."\n"
."\t".'return false;'."\n"
.'}'."\n";
}
public function genRequiredCheck($element_name,$alert_msg = '') {
if (empty($alert_msg)) {
$alert_msg = 'Missing option field!';
}
$element_var_name = str_replace(array('[',']'),array('_','_'),$element_name);
$var_name = 'ochk_'.$element_var_name;
$var_name_1 = 'chkd_'.$element_var_name;
$this->_validate_script .= 'var '.$var_name.' = thisForm.elements["'.$element_name.'"];'."\n"
.'var '.$var_name_1.' = false;'."\n"
.'if (typeof('.$var_name.'.length) == "undefined")'."\n"
.'{'."\n"
."\t".'if ('.$var_name.'.checked)'."\n"
."\t".'{'."\n"
."\t\t".$var_name_1.' = true;'."\n"
."\t".'}'."\n"
.'}'."\n"
.'else'."\n"
.'{'."\n"
."\t".'for (var i = 0; i < '.$var_name.'.length; i++)'."\n"
."\t".'{'."\n"
."\t\t".'if ('.$var_name.'[i].checked)'."\n"
."\t\t".'{'."\n"
."\t\t\t".$var_name_1.' = true;'."\n"
."\t\t\t".'break;'."\n"
."\t\t".'}'."\n"
."\t".'}'."\n"
.'}'."\n"
.'if (!'.$var_name_1.')'."\n"
.'{'."\n"
."\t".'alert("'.$alert_msg.'");'."\n"
."\t".'return false;'."\n"
.'}'."\n";
}
public function addCustValidationJs($js) {
$this->_validate_script .= $js;
}
}
class Html {
public static function input($type,$name,$value = '',$extra = '',
&$form = false,$validate_type = false,$alert_msg = '') {
$id = self::escSpecialChars($name);
$extra = ' '.$extra;
$html = '<input type="'.$type.'" name="'.$name.'" id="'.$id.'" value="'.$value.'"'.$extra.' />';
if ($form !== false &&$validate_type !== false) {
$form->addValidate($name,$validate_type,$alert_msg);
}
return $html;
}
public static function textarea($name,$value = '',$extra = '',
&$form = false,$validate_type = false,$alert_msg = '') {
$id = self::escSpecialChars($name);
$extra = ' '.$extra;
$html = '<textarea name="'.$name.'" id="'.$id.'"'.$extra.'>'.$value.'</textarea>';
if ($form !== false &&$validate_type !== false) {
$form->addValidate($name,$validate_type,$alert_msg);
}
return $html;
}
public static function select($name,$hash_entry,$selected_value = '',$extra = '',
&$form = false,$validate_type = false,$alert_msg = '') {
$id = self::escSpecialChars($name);
$extra = ' '.$extra;
$html = '';
if (sizeof($hash_entry) >= 1) {
$html = '<select name="'.$name.'" id="'.$id.'"'.$extra.'>';
foreach ($hash_entry as $key =>$value) {
$html .= '<option value="'.$key.'"';
if (is_array($selected_value) &&in_array($key,$selected_value)) {
$html .= ' selected="selected"';
}else 
if (strval($key) === strval($selected_value)) {
$html .= ' selected="selected"';
}
$html .= '>'.$value.'</option>';
}
$html .= '</select>';
if ($form !== false &&$validate_type !== false) {
$form->addValidate($name,$validate_type,$alert_msg);
}
}
return $html;
}
public static function multiselect($name,$hash_entry,$selected_values = array(),
$extra = '',&$form = false,$validate_type = false,$alert_msg = '') {
$id = self::escSpecialChars($name);
$extra = ' multiple="multiple" '.$extra;
$html = '';
if (sizeof($hash_entry) >= 1) {
$html = '<select name="'.$name.'" id="'.$id.'"'.$extra.'>';
foreach ($hash_entry as $key =>$value) {
$html .= '<option value="'.$key.'"';
if (in_array(strval($key),$selected_values,true)) {
$html .= ' selected="selected"';
}
$html .= '>'.$value.'</option>';
}
$html .= '</select>';
if ($form !== false &&$validate_type !== false) {
$form->addValidate($name,$validate_type,$alert_msg);
}
}
return $html;
}
public static function groupcheck($name,$hash_entry,$checked = array(),
$extra = '',$cols = 0,&$form = false,$validate_type = false,$alert_msg = '') {
$check_idx = 1;
$html = '';
if (sizeof($hash_entry) >= 1) {
foreach ($hash_entry as $key =>$value) {
$member_extra = $extra;
if (in_array(strval($key),$checked,true)) {
$member_extra .= ' checked="checked"';
}
$html .= Html::input('checkbox',$name,$key,$member_extra);
$html .= $value.'&nbsp;';
if ($cols != 0 &&$check_idx %$cols == 0) {
$html .= '<br />';
}
$member_extra = '';
$check_idx++;
}
if ($form !== false &&$validate_type !== false) {
$form->addValidate($name,$validate_type,$alert_msg);
}
}
return $html;
}
public static function groupradio($name,$hash_entry,$checked = false,
$extra = '',$cols = 0,&$form = false,$validate_type = false,$alert_msg = '') {
$check_idx = 1;
$html = '';
if (sizeof($hash_entry) >= 1) {
foreach ($hash_entry as $key =>$value) {
$member_extra = $extra;
if (strval($key) === strval($checked)) {
$member_extra .= ' checked="checked"';
}
$html .= Html::input('radio',$name,$key,$member_extra);
$html .= $value.'&nbsp;';
if ($cols != 0 &&$check_idx %$cols == 0) {
$html .= '<br />';
}
$member_extra = '';
$check_idx++;
}
if ($form !== false &&$validate_type !== false) {
$form->addValidate($name,$validate_type,$alert_msg);
}
}
return $html;
}
public static function uriquery($module,$action,$params = array(),$r_type = '_page') {
if(MOD_REWRITE == '2'){
$s_query=$module.'-'.$action;
if (!empty($params)) {
foreach($params as $key=>$val){
if (strpos($key,"_submit")) {
continue;
}else{
$s_query .= '-'.$key.'-'.$val;
}
}
}
$s_query = $s_query.'.html';
}else {
$s_query = 'index.php?_m='.urlencode($module).'&_a='.urlencode($action);
if ($r_type != '_page') {
$s_query .= '&_r=_ajax';
}
if (!empty($params)) {
foreach ($params as $key =>$val) {
$s_query .= '&'.$key.'='.urlencode($val);
}
}
}
return $s_query;
}
public static function xuriquery($module,$action,$params = array(),$r_type = '_page') {
$s_query = '_m='.urlencode($module).'&_a='.urlencode($action);
if ($r_type != '_page') {
$s_query .= '&_r=_ajax';
}
if (is_array($params)&&sizeof($params) >0) {
foreach ($params as $key =>$val) {
$s_query .= '&'.$key.'='.urlencode($val);
}
}
return $s_query;
}
public static function includeJs($file) {
echo '<script type="text/javascript" language="javascript" src="'.P_SCP.$file.'"></script>'."\n";
}
public static function includePopupJs() {
echo '<script type="text/javascript" language="javascript" src="'.P_SCP.'/popup/jquery.ui.custom.min.js"></script>'."\n";
}
public static function ddScript($pos_form_name = 'SAVE_POS') {
global $limited_pos;
if (SessionHolder::get('page/status','view') == 'edit') {
$s_containment = '';
$hidden_input = '';
$local = trim(SessionHolder::get('SS_LOCALE'));
foreach (TplInfo::$positions as $pos) {
if (in_array($pos,$limited_pos)) continue;
$s_containment .= ',#MODBLK_WRAPPER_'.$pos;
$hidden_input .= Html::input('hidden','SZ_'.$pos,'');
}
$s_containment = substr($s_containment,1);
$moveblockstr='';
if(!ACL::isAdminActionHasPermission('edit_block','process'))  $moveblockstr="\n cancel:\"div.mod_block\",";
$pos_form = new Form('index.php',$pos_form_name);
$pos_form->p_open('mod_tool','upd_position','_ajax');
echo $hidden_input;
$pos_form->close();
echo "<script type=\"text/javascript\" language=\"javascript\">
<!--
	var tmp_data = '';
	var tmp_data_ = '';
	var flag = false;
	function tojson(val){
		var json=\"\";
		var str=val.split(\"&\");
		for(var i=0;i<str.length;i++){
			var arr = str[i].split(\"=\");
			json+=\",\"+arr[1];
		}
		return json.substring(1);
	}
	$(function() {
		var count1 = 0;
		
		$(\"#modmenu1,#modmenu2,#modmenu3,#modmenu4,#modmenu5\").click(function(){
        	$(\".nav\").css('display','none');
       	});

        $(\".mod_toolbar\").each(function() {
            $(this).hide();
            $(this).parent().hover(
                function() {
                    var mypos = $(this).position();
                    $(this).children(\":first-child\").children('a').each(function(){
        				count1++;
        			});
        			// 2011/03/21 英文状态下移除按钮不显示用 >>
        			var tempwidth = (\"$local\" == 'en') ? 70 : 0;
        			// 2011/03/21 英文状态下移除按钮不显示用 <<
        			if(count1 == 3) {
        				$(this).children(\":first-child\").width(170+tempwidth);
        			} else if(count1 == 2) {
        				$(this).children(\":first-child\").width(110+tempwidth);
        			} else if(count1 == 1) {
        				$(this).children(\":first-child\").width(80+tempwidth);
        			}
                    count1 = 0;
                    $(this).children(\":first-child\").height(28);
                    if($(this).parent().attr('id') != 'nav') {
                    	$(this).children(\":first-child\").css({'position':'absolute','right':'2px','background':'#C7DEFC'});
                    }
                    $(this).children(\":first-child\").show();
                    if(($(this).parent().attr('id') == 'banner') || ($(this).parent().attr('id') == 'logo')) {
                    	$(this).css('position','relative');
                    	$(this).children(\":first-child\").width(80);
                    }
                    
                    if($(this).parent().attr('id') == 'nav') {
                    	var offset = $(\"#nav\").offset();//offset.left
                    	var nav_width = $('#nav').width();
                    	var left_margin = offset.left + nav_width - 90;
                    	var top_margin = offset.top;
                    	$(this).children(\":first-child\").css({'position':'absolute','left':left_margin,top:top_margin-28,'background':'#C7DEFC'});
                    	$(this).children(\":first-child\").width(80);
                    }
                    
                },
                function() {
                    $(this).children(\":first-child\").hide();
                }
            );
        });

		$(\"#modmenu1,#modmenu2,#modmenu3,#modmenu4,#modmenu5,{$s_containment}\").sortable({
			connectWith: [\"{$s_containment}\"],
			//containment: $(\"#mainmain\"),
			//handle: \".handle\",
			zIndex: 100,
			helper: \"clone\",
			opacity: 0.8,
			cursor: \"move\",{$moveblockstr}
			placeholder: \"sortable_placeholder\",
			//revert: true,
			tolerance: \"pointer\",
			start: function(event, ui) {
				if(ui.item.parent().attr('id') == 'modmenu1' || ui.item.parent().attr('id') == 'modmenu2' || ui.item.parent().attr('id') == 'modmenu3' || ui.item.parent().attr('id') == 'modmenu4' || ui.item.parent().attr('id') == 'modmenu5') {
					tmp_data = ui.item.parent();
					tmp_data_ = ui.item;
					ui.item.show();
				}
				$(\"#addModules\").css('display','block');
        		//$(\".mod_block,#sta_content,.artview,.art_list\").unbind('mouseenter mouseleave');//反绑定区域选取效果
        	},
        	stop: function(event, ui) {
        		$(\".mod_block,#sta_content,.artview,.art_list\").hover(function(){//首页、内页选取 static,article,news,bulletin,各种列表模块选取
		
//						border_style = $(this).css(\"border-style\");
//						border_color = $(this).css(\"border_color\");
//						border_width = $(this).css(\"border_width\");
//						
//						if($(this).parent().attr('id') == 'nav')
//						{
//							$(this).css({ \"border-style\": \"dashed\", \"border-color\": \"red\", \"border-width\": \"2px\", \"height\":  $(this).parent().css('height')});
//						}
//						else
//						{
							$(this).css({ \"border-style\": \"dashed\", \"border-color\": \"red\", \"border-width\": \"2px\" });
//						}
					},function(){
						$(this).css(\"border\", \"none\");
//						if($(this).parent().attr('id') == 'nav')
//						{
//							$(this).css({ \"border-style\": border_style, \"border-color\": border_color, \"border-width\": border_width });
//						}
//						else
//						{
//							$(this).css({ \"border-style\": border_style, \"border-color\": border_color, \"border-width\": border_width });
//						}
				});
				$(\".nav\").css(\"display\",\"none\");
				$(\".nav_two\").css(\"display\",\"none\");
				
				if(ui.item.attr('class') == 'modmenu_flag'){
					ui.item.remove();
					tmp_data.find('.modmenu_flag').each(function(index,domEle){
	        			if(parseInt($(domEle).attr('pos_num')) > parseInt(tmp_data_.attr('pos_num')))
	        			{
	        				$(domEle).before(tmp_data_);
	        				tmp_data_.css('background','none');
	        				flag = true;
	        				return false;
	        			}
	        		});
	        		
	        		if(!flag)
	        		{
	        			tmp_data.append(tmp_data_);
	        		}
					tmp_data_.attr('display','block');
				}
        	},
			update: function(event, ui) {
			
			    var pos = \"#SZ_\" + this.id.replace(/MODBLK_WRAPPER_/, \"\");
			    var s_data = $(this).sortable(\"serialize\");
			    $(pos).val(s_data);
				//$(\"#modmenu\").hide();
				//alert(\"xx=\"+ui.item.attr(\"class\")+this.id.replace(/MODBLK_WRAPPER_/, \"\"));
				
			},
			receive: function(event, ui) {
				if(ui.item.attr(\"class\")==\"modmenu_flag\"){
					popup_window('index.php?_m=mod_tool&_a=new_mblock_s2&widget=' + ui.item.attr(\"widget\") + '&currpos=' + this.id.replace(/MODBLK_WRAPPER_/, \"\")+ '&modblk=' + tojson($(\"#MODBLK_WRAPPER_\"+this.id.replace(/MODBLK_WRAPPER_/, \"\")).sortable(\"serialize\")),ui.item.text(),720,'',true);
				}
				$(\".nav_two\").css(\"display\",\"none\");
			},
			deactivate: function(event,ui) {
				$(\".nav\").css('display','none');//弹出框后使nav下拉框消失
			}
		}).disableSelection();
";
foreach (TplInfo::$positions as $pos) {
if (in_array($pos,$limited_pos)) continue;
echo "$(\"#SZ_{$pos}\").val($(\"#MODBLK_WRAPPER_{$pos}\").sortable(\"serialize\"));\n";
}
echo "	});
//-->
</script>
";
}
}
public static function adminBar() {
if(SessionHolder::get('page/status') == 'view') return null;
$o_admin_menu_item = new AdminMenuItem();
$content_entries =&$o_admin_menu_item->findAll("`level` <= ?",array(EZSITE_LEVEL),"ORDER BY `priority`, `text`");
if (strpos($_SERVER["PHP_SELF"],'/admin/') !== false) {
$admin_entries = array(
'Tools'=>array(
array('1','browser/tinybrowser.php?type=image','Image Manager'),
array('1','browser/tinybrowser.php?type=flash','Flash Manager'),
),
'System'=>array(
array('0','mod_database','admin_list','Database Managment')
)
);
include_once(P_TPL.'/common/admin_toolbar.php');
}else {
if (SessionHolder::get('page/status','view') == 'edit') {
$admin_entries = array();
include_once(P_INC.'/widgets.php');
$widgets_info = array();
if (!empty($widgets)) {
foreach($widgets as $w_module =>$w_actions) {
if (check_mod($w_module)) {
foreach($w_actions as $w_action =>$w_info ) {
$widgets_info[$w_module.'-'.$w_action] = __($w_info['name']);
}
}
}
}
include_once(P_INC.'/add_module_menu.php');
include_once(P_TPL_VIEW.'/view/common/admin_toolbar.php');
}
}
}
public static function escSpecialChars($in) {
$out = str_replace(array('[',']'),array('_','_'),$in);
return $out;
}
}
if (!defined('FCK_CREATE')) define('FCK_CREATE',801);
if (!defined('FCK_REPLACE_TEXTAREA')) define('FCK_REPLACE_TEXTAREA',802);
class RichTextbox {
public $instance_name;
public $width;
public $height;
public $toolbar_set;
public $value;
public function __construct($instance_name) {
$this->instance_name = $instance_name;
$this->width = '640';
$this->height = '320';
$this->toolbar_set = 'Default';
$this->basepath = 'fckeditor/';
$this->value = '';
}
public function create($create_mode = FCK_CREATE,
&$form = false,$validate_type = false,$alert_msg = '') {
$instance_var_name = str_replace(array('[',']'),array('_','_'),$this->instance_name);
$html = '';
$html .= '<script type="text/javascript" language="javascript">'."\n"
.'<!--'."\n"
."\t".'var fck_'.$instance_var_name.' =  new FCKeditor("'.$this->instance_name.'");'."\n"
."\t".'fck_'.$instance_var_name.'.Width = "'.$this->width.'";'."\n"
."\t".'fck_'.$instance_var_name.'.Height = "'.$this->height.'";'."\n"
."\t".'fck_'.$instance_var_name.'.ToolbarSet = "'.$this->toolbar_set.'";'."\n"
."\t".'fck_'.$instance_var_name.'.BasePath = "'.$this->basepath.'";'."\n"
."\t".'fck_'.$instance_var_name.'.Value = "'.$this->value.'";'."\n";
if ($create_mode == FCK_CREATE) {
$html .= "\t".'fck_'.$instance_var_name.'.Create();'."\n";
}else {
$html .= "\t".'fck_'.$instance_var_name.'.ReplaceTextarea();'."\n";
}
$html .= '-->'."\n"
.'</script>'."\n";
if ($form !== false &&$validate_type !== false) {
$form->addValidate($this->instance_name,$validate_type,$alert_msg);
}
return $html;
}
public static function jsinclude($script_path = 'fckeditor') {
echo '<script type="text/javascript" language="javascript" src="'.$script_path.'/fckeditor.js"></script>'."\n";
}
}
class DateTimeInput {
public static function create($name,&$form,$value = '',$extra = '',
$validate_type = false,$alert_msg = '') {
$html = Html::input('text',$name,$value,$extra,
$form,$validate_type,$alert_msg);
$id = Html::escSpecialChars($name);
$js = "<script type=\"text/javascript\" language=\"javascript\">
<!--
    $(function() {
        $('#{$id}').datepicker();
    });

//-->
</script>
";
return $html.$js;
}
public static function jsinclude() {
}
}