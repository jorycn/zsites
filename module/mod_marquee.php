<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
class ModMarquee extends Module {
public function marquee() {
$block_id =  ParamHolder::get("block_id");
$mar_direc_id = trim(ParamHolder::get('mar_direc_id','left'));
$marquee_width = trim(ParamHolder::get('marquee_width','300'));
$marquee_speed = trim(ParamHolder::get('marquee_speed','2000'));
$marquee_class = trim(ParamHolder::get('marquee_class',''));
$curr_locale = trim(SessionHolder::get('_LOCALE'));
$o_marquee = new Marquee();
$curr_marquee = $o_marquee->findAll("module_id=? ",array($block_id)," order by id desc limit 0,12");
if($marquee_speed=="quick"){
$_marquee_speed=10;
}else if($marquee_speed=="general"){
$_marquee_speed=20;
}else if($marquee_speed=="slow"){
$_marquee_speed=30;
}
$this->assign("curr_marquee",$curr_marquee);
$this->assign("mar_direc_id",$mar_direc_id);
$this->assign("marquee_width",$marquee_width);
$this->assign("marquee_speed",$_marquee_speed);
$this->assign("block_id",$block_id);
$this->assign("marquee_class",$marquee_class);
$this->assign("randstr",ToolKit::randomStr());
}
public function del_prd(){
$prd_id = ParamHolder::get('prd_id','');
$mar = new Marquee(intval($prd_id));
$mar->delete();
}
}