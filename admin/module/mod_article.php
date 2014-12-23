<?php // 
if (!defined('IN_CONTEXT')) die('access violation error!');
class ModArticle extends Module {
protected $_filters = array(
'check_admin'=>''
);
public function admin_batch_create() {
$file_allow_ext_pat = '/\.(csv)$/i';
$file_info =&ParamHolder::get('batch_file',array(),PS_FILES);
if (empty($file_info)) {
Notice::set('mod_article/msg',__('Invalid post file data!'));
Content::redirect(Html::uriquery('mod_article','admin_batch'));
}
if(!preg_match($file_allow_ext_pat,$file_info["name"])) {
Notice::set('mod_article/msg',__('File type error!'));
Content::redirect(Html::uriquery('mod_article','admin_batch'));
}
$file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
if (!$this->_savetplFile($file_info)) {
Notice::set('mod_article/msg',__('Uploading file failed!'));
Content::redirect(Html::uriquery('mod_article','admin_batch'));
}
$curr_locale = trim(SessionHolder::get('_LOCALE'));
$article_info = array();
$article_info['pub_start_time'] = -1;
$article_info['pub_end_time'] = -1;
$article_info['published'] = '1';
$article_info['for_roles'] = '{member}{admin}{guest}';
$article_info['create_time'] = time();
$article_info['v_num'] = 0;
$article_info['s_locale'] = $curr_locale;
$handle = fopen(ROOT.'/file/'.THISSITE.'/file/'.$file_info["name"],"r");
$row = 1;
while ($data = fgetcsv($handle)) {
if($row == 1){
$row++;
continue;
}
$num = count($data);
$row++;
$o_article = new Article();
$article_info['i_order'] = Article::getMaxOrder(1) +1;
$article_info['title'] = iconv('gb2312','utf-8',strip_tags($data[0]));
$article_info['author'] = iconv('gb2312','utf-8',strip_tags($data[1]));
$article_class = iconv('gb2312','utf-8',strip_tags($data[2]));
$o_article_class = new ArticleCategory();
$article_arr = $o_article_class->findAll("name='".$article_class."'");
$article_info['article_category_id'] = $article_arr[0]->id;
$article_info['source'] = iconv('gb2312','utf-8',strip_tags($data[3]));
$article_info['tags'] = iconv('gb2312','utf-8',strip_tags($data[4]));
$article_info['intro'] = iconv('gb2312','utf-8',strip_tags($data[5]));
$article_info['content'] = str_replace( array("/r/n","/r","/n"),array("\r\n","\r","\n"),iconv('gb2312','utf-8',$data[6]) );
$o_article->set($article_info);
$o_article->save();
}
fclose($handle);
@unlink(ROOT.'/file/'.THISSITE.'/file/'.$file_info["name"]);
Notice::set('mod_article/msg',__('Article order added successfully!'));
Content::redirect(Html::uriquery('mod_article','admin_list'));
}
public function admin_batch() {
die('Access deny');
$this->_layout = 'content';
$curr_locale = trim(SessionHolder::get('_LOCALE'));
$mod_locale = trim(SessionHolder::get('mod_article/_LOCALE',$curr_locale));
$this->assign('content_title',__('Batch Import'));
$this->assign('next_action','admin_batch_create');
$this->assign('mod_locale',$mod_locale);
$this->assign('langs',Toolkit::loadAllLangs());
$this->assign('roles',Toolkit::loadAllRoles());
}
public function admin_list() {
$this->_layout = 'content';
$curr_locale = trim(SessionHolder::get('_LOCALE'));
$mod_locale = trim(SessionHolder::get('mod_article/_LOCALE',$curr_locale));
$lang_sw = trim(ParamHolder::get('lang_sw',$curr_locale));
SessionHolder::set('mod_article/_LOCALE',$lang_sw);
$keyword = trim(ParamHolder::get('hidkeyword','',PS_POST))?Toolkit::baseEncode(trim(ParamHolder::get('hidkeyword','',PS_POST))):trim(ParamHolder::get('hidkeyword','',PS_GET));
$keyword = Toolkit::baseDecode($keyword);
$where = "s_locale=?";
$params = array($lang_sw);
$caa_sw = trim(ParamHolder::get('caa_sw','-'));
$all_categories =&ArticleCategory::listCategories(0,"s_locale=?",array($lang_sw));
if (is_numeric($caa_sw)) {
$childids = '';
$childids = $this->getCategoryChildIds( $caa_sw,$curr_locale );
$catids = !empty($childids) ?$childids.$caa_sw : $caa_sw;
if ($caa_sw==0) {
$where .= " AND article_category_id=0";
}else{
$where .= " AND article_category_id IN(".$catids.")";
}
}
$where .=  " AND article_category_id <> 2";
if( trim($keyword) ) $where .=  " AND title LIKE '%{$keyword}%'";
$article_data =&
Pager::pageByObject('Article',$where,$params,
"ORDER BY `create_time` DESC");
$this->assign('default_lang',trim(SessionHolder::get('_LOCALE')));
$this->assign('next_action','admin_order');
$this->assign('articles',$article_data['data']);
$this->assign('pager',$article_data['pager']);
$this->assign('page_mod',$article_data['mod']);
$this->assign('page_act',$article_data['act']);
$this->assign('page_extUrl',$article_data['extUrl']);
$this->assign('lang_sw',$lang_sw);
$this->assign('caa_sw',$caa_sw);
$this->assign('keyword',$keyword);
$this->assign('langs',Toolkit::loadAllLangs());
$this->assign('roles',Toolkit::loadAllRoles());
$select_categories = array();
ArticleCategory::toSelectArray($all_categories,$select_categories,
0,array(),array('-'=>__('View All'),'0'=>__('Uncategorised')));
$act = trim(ParamHolder::get('act',''));
if( $act == '9999') {
$articles = $rows = array();
$obj = new Article();
$articles =&$obj->findAll($where,$params,"ORDER BY `create_time` DESC");
$rows[] = array(__('Title'),__('Author'),__('Category'),__('Source'),__('Tags'),__('Abstract'),__('Article Content'),__('Add Date'));
foreach ($articles as $article) {
$article->loadRelatedObjects(REL_PARENT,array('ArticleCategory'));
$rows[] = array($article->title,$article->author,$article->masters['ArticleCategory']->name,$article->source,
$article->tags,$article->intro,$article->content,date('Y-m-d H:i:s',$article->create_time));
}
include_once P_LIB."/Excel/export.class.php";
$csv = new Export_CSV($rows,'articles.csv');
$csv->Export();
}
$this->assign('select_categories',$select_categories);
}
public function admin_add() {
$this->_layout = 'content';
$curr_locale = trim(SessionHolder::get('_LOCALE'));
$mod_locale = trim(SessionHolder::get('mod_article/_LOCALE',$curr_locale));
$this->assign('article_title',__('New Article'));
$this->assign('next_action','admin_create');
$all_categories =&ArticleCategory::listCategories(0,"s_locale=?",array($mod_locale));
$select_categories = array();
ArticleCategory::toSelectArray($all_categories,$select_categories,
0,array(),array('0'=>__('Uncategorised')));
$this->assign('select_categories',$select_categories);
$this->assign('mod_locale',$mod_locale);
$this->assign('language_info',$mod_locale);
$this->assign('langs',Toolkit::loadAllLangs());
$this->assign('roles',Toolkit::loadAllRoles());
$this->assign('act','add');
return '_form';
}
public function admin_mi_quick_add() {
$curr_locale = trim(SessionHolder::get('_LOCALE'));
$mod_locale = trim(SessionHolder::get('mod_menu_item/_LOCALE',$curr_locale));
$this->assign('article_title',__('New Article'));
$this->assign('next_action','admin_create');
$all_categories =&ArticleCategory::listCategories(0,"s_locale=?",array($mod_locale));
$select_categories = array();
ArticleCategory::toSelectArray($all_categories,$select_categories,
0,array(),array('0'=>__('Uncategorised')));
$this->assign('select_categories',$select_categories);
$this->assign('mod_locale',$mod_locale);
$this->assign('langs',Toolkit::loadAllLangs());
$this->assign('roles',Toolkit::loadAllRoles());
$link_type_text = trim(ParamHolder::get('txt'));
$this->assign('type_text',$link_type_text);
$this->_layout = 'clean';
return '_mi_quick_add_form';
}
public function admin_create() {
$article_info =&ParamHolder::get('article',array());
if (sizeof($article_info) <= 0) {
$this->assign('json',Toolkit::jsonERR(__('Missing article information!')));
return '_result';
}
if($article_info['article_category_id'] == -1) {
$article_info['article_category_id'] = 0;
}
$is_member_only = ParamHolder::get('ismemonly','0');
try {
$article_info['pub_start_time'] = -1;
$article_info['pub_end_time'] = -1;
$article_info['published'] = '1';
$article_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
$article_info['create_time'] = strtotime($article_info['create_time']);
$article_info['v_num'] = 0;
$article_info['intro'] =strip_tags($article_info['intro'])?strip_tags($article_info['intro']):mb_substr(strip_tags($article_info['content']),0,120,'utf-8');
$article_info['tags'] = strip_tags($article_info['tags']);
$article_info['source'] = strip_tags($article_info['source']);
$article_info['author'] = strip_tags($article_info['author']);
$article_info['title'] = strip_tags($article_info['title']);
$article_info['i_order'] =
Article::getMaxOrder($article_info['article_category_id']) +1;
$article_info['is_seo'] = $article_info['is_seo'];
$article_info['description'] = strip_tags($article_info['description']);
$o_article = new Article();
$o_article->set($article_info);
$o_article->save();
}catch (Exception $ex) {
$this->assign('json',Toolkit::jsonERR($ex->getMessage()));
return '_result';
}
$this->assign('json',Toolkit::jsonOK(array('forward'=>Html::uriquery('mod_article','admin_list'),
'id'=>$o_article->id,'title'=>$o_article->title)));
return '_result';
}
public function admin_order() {
$order_info =&ParamHolder::get('i_order',array());
if (!is_array($order_info)) {
$this->assign('json',Toolkit::jsonERR(__('Missing article order information!')));
return '_result';
}
try {
foreach($order_info as $key =>$val) {
$article_info['i_order'] = $val;
$o_article = new Article($key);
$o_article->set($article_info);
$o_article->save();
}
}catch (Exception $ex) {
$this->assign('json',Toolkit::jsonERR($ex->getMessage()));
return '_result';
}
Notice::set('mod_article/msg',__('Article order added successfully!'));
Content::redirect(Html::uriquery('mod_article','admin_list'));
}
public function admin_edit() {
$this->_layout = 'content';
$article_id = ParamHolder::get('article_id','0');
if (intval($article_id) == 0) {
$this->assign('json',Toolkit::jsonERR(__('Invalid ID!')));
return '_error';
}
try {
$curr_article = new Article($article_id);
$this->assign('curr_article',$curr_article);
$this->assign('language_info',$curr_article->s_locale);
$all_categories =&ArticleCategory::listCategories(0,"s_locale=?",
array($curr_article->s_locale));
$select_categories = array();
ArticleCategory::toSelectArray($all_categories,$select_categories,
0,array($curr_article->id),array('0'=>__('Uncategorised')));
$this->assign('select_categories',$select_categories);
}catch (Exception $ex) {
$this->assign('json',Toolkit::jsonERR($ex->getMessage()));
return '_error';
}
$this->assign('article_title',__('Edit Article'));
$this->assign('next_action','admin_update');
$this->assign('langs',Toolkit::loadAllLangs());
$this->assign('roles',Toolkit::loadAllRoles());
return '_form';
}
public function admin_update() {
$article_info =&ParamHolder::get('article',array());
if (sizeof($article_info) <= 0) {
$this->assign('json',Toolkit::jsonERR(__('Missing article information!')));
return '_result';
}
$is_member_only = ParamHolder::get('ismemonly','0');
try {
$article_info['pub_start_time'] = -1;
$article_info['pub_end_time'] = -1;
$article_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
$article_info['intro'] = strip_tags($article_info['intro']);
$article_info['tags'] = strip_tags($article_info['tags']);
$article_info['source'] = strip_tags($article_info['source']);
$article_info['author'] = strip_tags($article_info['author']);
$article_info['title'] = strip_tags($article_info['title']);
$article_info['create_time'] = strtotime($article_info['create_time']);
$o_article = new Article($article_info['id']);
$pos = strpos($_SERVER['PHP_SELF'],'/admin/index.php');
$path = substr($_SERVER['PHP_SELF'],0,$pos)=='/'?'':substr($_SERVER['PHP_SELF'],0,$pos);
$article_info['content'] = str_replace($path,"",$article_info['content']);
$o_article->set($article_info);
$o_article->save();
}catch (Exception $ex) {
$this->assign('json',Toolkit::jsonERR($ex->getMessage()));
return '_result';
}
$this->assign('json',Toolkit::jsonOK(array('forward'=>Html::uriquery('mod_article','admin_list'))));
return '_result';
}
public function admin_delete() {
$article_id = trim(ParamHolder::get('article_id','0'));
if (intval($article_id) == 0) {
$this->assign('json',Toolkit::jsonERR(__('Invalid ID!')));
return '_result';
}
try {
if (strpos($article_id,'_') >0) {
$tmp_arr = explode('_',$article_id);
$len = sizeof($tmp_arr) -1;
for ($i = 0;$i<$len;$i++){
$curr_article = new Article($tmp_arr[$i]);
$curr_article->delete();
}
}else {
$curr_article = new Article($article_id);
$curr_article->delete();
}
}catch (Exception $ex) {
$this->assign('json',Toolkit::jsonERR($ex->getMessage()));
return ('_result');
}
$this->assign('json',Toolkit::jsonOK());
return '_result';
}
public function admin_pic()
{
$article_info = array();
$article_id = trim(ParamHolder::get('_id',''));
if(!empty($article_id))
{
$o_article = new Article($article_id);
if($o_article->published == 1)
{
$article_info['published'] = '0';
$o_article->set($article_info);
$o_article->save();
die('0');
}
elseif($o_article->published == 0)
{
$article_info['published'] = '1';
$o_article->set($article_info);
$o_article->save();
die('1');
}
}
}
public function admin_dashboard() {
$this->_layout = 'default';
}
private function getCategoryChildIds( $cur_classid,$curr_locale ) 
{
$childids = '';
$article_childcategories = array();
$article_category = new ArticleCategory();
$article_childcategories = $article_category->findAll("article_category_id = '{$cur_classid}' AND s_locale = '{$curr_locale}'");
if ( count($article_childcategories) >0 ) {
foreach( $article_childcategories as $val ) 
{
$childids .= $val->id.',';
$childids .= $this->getCategoryChildIds( $val->id,$curr_locale );
}
}
return $childids;
}
private function _savetplFile($struct_file) {
$struct_file['name'] = iconv("UTF-8","gb2312",$struct_file['name']);
move_uploaded_file($struct_file['tmp_name'],ROOT.'/file/'.THISSITE.'/file/'.$struct_file['name']);
return ParamParser::fire_virus(ROOT.'/file/'.THISSITE.'/file/'.$struct_file['name']);
}
}