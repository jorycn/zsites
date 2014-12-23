<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
class Content {

    public static function dispatch() {
        $module = strtolower(ParamHolder::get('_m', DEFAULT_MODULE));
        $action = strtolower(ParamHolder::get('_a', DEFAULT_ACTION));

        define('R_MOD', $module);
        define('R_ACT', $action);
        $request_type = strtolower(ParamHolder::get('_r', '_page'));
        define('R_TPE', $request_type);
        if (file_exists(P_MOD . '/' . $module . '.php')) {
            include_once (P_MOD . '/' . $module . '.php');
        } else {
            die("1" . PAGE_404);
            self::redirect(PAGE_404);
        }
        $module_name_part = explode('_', $module);
        $module_class_name = '';
        foreach ($module_name_part as $name_part) {
            $module_class_name.= ucfirst($name_part);
        }
        $module_class = new $module_class_name();
        if ($request_type == '_ajax') {
            @header('Content-Type: text/html; charset=' . __('charset'));
            $module_class->execute($action, false);
        } else if ($module_class_name == 'ModContent') {
            $module_class->execute('content', true);
        } else {
            $module_class->execute($action);
        }
    }

    public static function admin_dispatch() {
        $module = strtolower(ParamHolder::get('_m', 'frontpage'));
        $action = strtolower(ParamHolder::get('_a', 'index'));
        define('R_MOD', $module);
        define('R_ACT', $action);
        $request_type = strtolower(ParamHolder::get('_r', '_page'));
        define('R_TPE', $request_type);
        if (file_exists(P_MOD . '/' . $module . '.php')) {
            include_once (P_MOD . '/' . $module . '.php');
        } else {
            die("2" . PAGE_404);
            self::redirect(PAGE_404);
        }
        $module_name_part = explode('_', $module);
        $module_class_name = '';
        foreach ($module_name_part as $name_part) {
            $module_class_name.= ucfirst($name_part);
        }
        $module_class = new $module_class_name();
        if ($request_type == '_ajax') {
            @header('Content-Type: text/html; charset=' . __('charset'));
            $module_class->execute($action, false);
        } else {
            $module_class->execute($action);
        }
    }
    public static function render($module, $action, $params = array() , $block_id = 0) {
        if (Toolkit::getAgent() === 'agent') {
            $params['html'].= '';
        } else if (isset($params['block_pos']) && $params['block_pos'] == 'footer' && !Toolkit::getCorp()) {
            if (strpos($params['html'], 'http://www.jzbao.net/')) {
                $params['html'] = '';
            }
        }
        $module_name_part = explode('_', $module);
        $module_class_name = '';
        foreach ($module_name_part as $name_part) {
            $module_class_name.= ucfirst($name_part);
        }
        if (isset($params['block_pos']) && $params['block_pos'] == 'footer' && $params['html'] != '') {
            Toolkit::checkLicenceTimely();
        }
        if (isset($params['block_pos']) && $params['block_pos'] == 'banner') {
            if (empty($_GET) || (isset($_GET['_m']) && $_GET['_m'] == 'frontpage' && empty($_GET['_a'])) || (isset($_GET['_a']) && $_GET['_a'] == 'index' && empty($_GET['_m']))) {
                $url_addr = '_m=frontpage&_a=index';
            } else {
                $url_addr = '';
                foreach ($_GET as $k => $v) {
                    if ($k == '_l' || $k == '_v') continue;
                    $url_addr.= "{$k}={$v}&";
                }
                $url_addr = substr($url_addr, 0, strlen($url_addr) - 1);
            }
            ManualParamHolder::set('single_img_src', '');
            ManualParamHolder::set('img_src', '');
            ManualParamHolder::set('geshi', '');
            ManualParamHolder::set('flv_src', '');
            if (!empty($params)) {
                if (!empty($params[$url_addr]) && count($params[$url_addr]) > 0) {
                    foreach ($params[$url_addr] as $key => $value) {
                        ManualParamHolder::set($key, $value);
                    }
                    if (!empty($params['block_title'])) ManualParamHolder::set('block_title', $params['block_title']);
                    if (!empty($params['block_pos'])) ManualParamHolder::set('block_pos', $params['block_pos']);
                    if ($block_id > 0) {
                        ManualParamHolder::set("block_id", $block_id);
                    }
                    $module_class = new $module_class_name();
                    $module_class->execute($params[$url_addr]['action'], false);
                } else {
                    if (!empty($params['_all'])) {
                        foreach ($params['_all'] as $key => $value) {
                            ManualParamHolder::set($key, $value);
                        }
                    }
                    if (!empty($params['block_title'])) ManualParamHolder::set('block_title', $params['block_title']);
                    if (!empty($params['block_pos'])) ManualParamHolder::set('block_pos', $params['block_pos']);
                    if ($block_id > 0) {
                        ManualParamHolder::set("block_id", $block_id);
                    }
                    $module_class = new $module_class_name();
                    $module_class->execute($params['_all']['action'], false);
                }
            }
        } else {
            if (sizeof($params) > 0) {
                foreach ($params as $key => $value) {
                    ManualParamHolder::set($key, $value);
                }
            }
            if ($block_id > 0) {
                ManualParamHolder::set("block_id", $block_id);
            }
            $module_class = new $module_class_name();
            $module_class->execute($action, false);
        }
        return true;
    }
    public static function redirect($url) {
        @header('Location: ' . $url);
        exit();
    }
    private static function &_loadModules($position) {
        $o_mod_block = new ModuleBlock();
        $_flag = false;
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        $user_role1 = $user_role;
        if (ACL::isRoleAdmin($user_role)) $user_role1 = '{admin}';
        $mq_hash = Toolkit::calcMQHash($_SERVER['QUERY_STRING']);
        if ($position == 'right' && R_MOD == "frontpage") {
            $_postions = $o_mod_block->findAll("(`s_locale`=? OR `s_locale`='_ALL') AND " . "(`s_query_hash`=? OR `s_query_hash`='_ALL') AND for_roles LIKE ? AND (`module`<>? OR `action`<>?)", array(
                SessionHolder::get('_LOCALE', DEFAULT_LOCALE) ,
                $mq_hash,
                '%' . $user_role1 . '%',
                R_MOD,
                R_ACT
            ) , "ORDER BY `i_order`");
            $position = self::findPos($_postions);
            $_flag = true;
        }
        if (ACL::requireRoles(array(
            'admin'
        ))) {
            if ($_flag) {
                $blocks = & $o_mod_block->findAll("`s_pos` in ('" . $position . "') AND (`s_locale`=? OR `s_locale`='_ALL') AND " . "(`s_query_hash`=? OR `s_query_hash`='_ALL') AND (`module`<>? OR `action`<>?)", array(
                    SessionHolder::get('_LOCALE', DEFAULT_LOCALE) ,
                    $mq_hash,
                    R_MOD,
                    R_ACT
                ) , "ORDER BY `i_order`");
            } else {
                $blocks = & $o_mod_block->findAll("`s_pos` =? AND (`s_locale`=? OR `s_locale`='_ALL') AND " . "(`s_query_hash`=? OR `s_query_hash`='_ALL') AND (`module`<>? OR `action`<>?)", array(
                    $position,
                    SessionHolder::get('_LOCALE', DEFAULT_LOCALE) ,
                    $mq_hash,
                    R_MOD,
                    R_ACT
                ) , "ORDER BY `i_order`");
            }
        } else {
            if ($_flag) {
                $blocks = & $o_mod_block->findAll("`s_pos` in ('" . $position . "') AND (`s_locale`=? OR `s_locale`='_ALL') AND " . "(`s_query_hash`=? OR `s_query_hash`='_ALL') AND for_roles LIKE ? AND (`module`<>? OR `action`<>?)", array(
                    SessionHolder::get('_LOCALE', DEFAULT_LOCALE) ,
                    $mq_hash,
                    '%' . $user_role . '%',
                    R_MOD,
                    R_ACT
                ) , "ORDER BY `i_order`");
            } else {
                $blocks = & $o_mod_block->findAll("`s_pos` =? AND (`s_locale`=? OR `s_locale`='_ALL') AND " . "(`s_query_hash`=? OR `s_query_hash`='_ALL') AND for_roles LIKE ? AND (`module`<>? OR `action`<>?)", array(
                    $position,
                    SessionHolder::get('_LOCALE', DEFAULT_LOCALE) ,
                    $mq_hash,
                    '%' . $user_role . '%',
                    R_MOD,
                    R_ACT
                ) , "ORDER BY `i_order`");
            }
        }
        return $blocks;
    }
    public static function countModules($position) {
        $o_mod_block = new ModuleBlock();
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        $mq_hash = Toolkit::calcMQHash($_SERVER['QUERY_STRING']);
        if (ACL::requireRoles(array(
            'admin'
        ))) {
            $n_block = $o_mod_block->count("`s_pos`=? AND (`s_locale`=? OR `s_locale`='_ALL') AND " . "(`s_query_hash`=? OR `s_query_hash`='_ALL') AND (`module`<>? OR `action`<>?)", array(
                $position,
                SessionHolder::get('_LOCALE', DEFAULT_LOCALE) ,
                $mq_hash,
                R_MOD,
                R_ACT
            ) , "ORDER BY `i_order`");
        } else {
            $n_block = $o_mod_block->count("`s_pos`=? AND (`s_locale`=? OR `s_locale`='_ALL') AND " . "(`s_query_hash`=? OR `s_query_hash`='_ALL') AND for_roles LIKE ? AND (`module`<>? OR `action`<>?)", array(
                $position,
                SessionHolder::get('_LOCALE', DEFAULT_LOCALE) ,
                $mq_hash,
                '%' . $user_role . '%',
                R_MOD,
                R_ACT
            ) , "ORDER BY `i_order`");
        }
        return $n_block;
    }
    public static function loadModules($position) {
        global $limited_pos;
        $pos_blocks = & self::_loadModules($position);
        if (SessionHolder::get('page/status', 'view') == 'edit' && !in_array($position, $limited_pos)) {
            $add_mod_gif = 'add_mod.en.gif';
            $curr_locale = SessionHolder::get('_LOCALE', DEFAULT_LOCALE);
            if (file_exists(ROOT . '/images/add_mod.' . $curr_locale . '.gif')) {
                $add_mod_gif = 'add_mod.' . $curr_locale . '.gif';
            }
            echo '<div id="MODBLK_WRAPPER_' . $position . '" class="pos_wrapper">' . "\n";
        }
        if (sizeof($pos_blocks) > 0) {
            $content_edit = self::getEditContents();
            $arr = array_keys($content_edit);
            foreach ($pos_blocks as $block) {
                if (!check_mod($block->module)) continue;
                $is_mar = 0;
                if ($block->module == 'mod_marquee') {
                    $is_mar = 1;
                }
                $extra_css = ($block->module == 'mod_media') ? 'media_image ' : '';
                $_block_title = empty($block->title) ? '' : __($block->title);
                $content_edit_title = $_block_title . '&nbsp;&nbsp;' . __('Edit Content');
                $nopermissionstr = __('No Permission');
                $urllink = "alert('" . $nopermissionstr . "');return false;";
                if (in_array($block->module, $arr)) {
                    if (($block->module == 'mod_static') && (($block->s_pos != 'nav') && ($block->s_pos != 'footer'))) {
                        if (!in_array($block->action, array(
                            'custom_html',
                            'company_intro'
                        ))) continue;
                    }
                    if (!in_array($block->action, array(
                        'custom_html',
                        'company_intro'
                    ))) {
                        if ((($content_edit[$block->module]['action'] == 'admin_list') && ($content_edit[$block->module]['module'] == 'mod_category_a')) || (($content_edit[$block->module]['action'] == 'admin_list') && ($content_edit[$block->module]['module'] == 'mod_category_p')) || (($content_edit[$block->module]['action'] == 'admin_list') && ($content_edit[$block->module]['module'] == 'mod_product')) || (($content_edit[$block->module]['module'] == 'mod_article') && ($content_edit[$block->module]['action'] == 'admin_list'))) {
                            $content_mod = $content_edit[$block->module]['module'];
                            $content_action = $content_edit[$block->module]['action'];
                            if (ACL::isAdminActionHasPermission($content_mod, $content_action)) $urllink = 'popup_window(\'admin/index.php?_m=' . $content_edit[$block->module]['module'] . '&_a=' . $content_edit[$block->module]['action'] . '\',\'' . $content_edit_title . '\',\'\',\'500\',true);return false;';
                            $str_content_edit = '<a href="#" title="' . __('Edit Content') . '" onclick="' . $urllink . '"><img src="images/edit_content.gif" border="0" alt="' . __('Edit Content') . '"/>&nbsp;' . __('Edit Content') . '</a>';
                        } else {
                            $content_mod = $content_edit[$block->module]['module'];
                            $content_action = $content_edit[$block->module]['action'];
                            if (ACL::isAdminActionHasPermission($content_mod, $content_action)) {
                                if ($content_mod == 'mod_user' && $content_action == 'admin_list') {
                                    $urllink = 'popup_window(\'admin/index.php?_m=' . $content_edit[$block->module]['module'] . '&_a=' . $content_edit[$block->module]['action'] . '\',\'' . $content_edit_title . '\',\'884\',\'\',true);return false;';
                                } else {
                                    $urllink = 'popup_window(\'admin/index.php?_m=' . $content_edit[$block->module]['module'] . '&_a=' . $content_edit[$block->module]['action'] . '\',\'' . $content_edit_title . '\',\'\',\'\',true);return false;';
                                }
                            }
                            $str_content_edit = '<a href="#" title="' . __('Edit Content') . '" onclick="' . $urllink . '"><img src="images/edit_content.gif" border="0" alt="' . __('Edit Content') . '"/>&nbsp;' . __('Edit Content') . '</a>';
                        }
                    } else if ($block->action == 'company_intro') {
                        $content_mod = 'mod_media';
                        $content_action = 'admin_company_introduction';
                        if (ACL::isAdminActionHasPermission($content_mod, $content_action)) $urllink = 'popup_window(\'admin/index.php?_m=mod_media&_a=admin_company_introduction&sc_id=2\',\'' . $content_edit_title . '\',\'\',\'\',true);return false;';
                        $str_content_edit = '<a href="#" title="' . __('Edit Content') . '" onclick="' . $urllink . '"><img src="images/edit_content.gif" border="0" alt="' . __('Edit Content') . '"/>&nbsp;' . __('Edit Content') . '</a>';
                    } elseif (($block->action == 'custom_html') && ($block->alias == "mb_foot")) {
                        $_s_param = unserialize($block->s_param);
                        if (strpos($_s_param['html'], "www.jzbao.net") > 0) {
                            $str_content_edit = '';
                        } else {
                            $content_mod = $content_edit[$block->module]['module'];
                            $content_action = $content_edit[$block->module]['action'];
                            if (ACL::isAdminActionHasPermission($content_mod, $content_action)) $urllink = 'popup_window(\'admin/index.php?_m=' . $content_edit[$block->module]['module'] . '&_a=' . $content_edit[$block->module]['action'] . '\',\'' . $content_edit_title . '\',\'\',\'\',true);return false;';
                            $str_content_edit = '<a href="#" title="' . __('Edit Content') . '" onclick="' . $urllink . '"><img src="images/edit_content.gif" border="0" alt="' . __('Edit Content') . '"/>&nbsp;' . __('Edit Content') . '</a>';
                        }
                    } else {
                        $str_content_edit = '';
                    }
                    if (($block->module == 'mod_media') && (($block->s_pos != 'banner') && ($block->s_pos != 'logo'))) {
                        $str_content_edit = '';
                    } elseif (($block->module == 'mod_media') && ($block->s_pos == 'banner')) {
                        $content_edit_title = 'Banner&nbsp;&nbsp;' . __('Edit Content');
                        if (!empty($_GET)) {
                            if ((isset($_GET['_m']) && $_GET['_m'] == 'frontpage' && empty($_GET['_a'])) || (isset($_GET['_a']) && $_GET['_a'] == 'index' && empty($_GET['_m']))) {
                                $url_str1 = "_m=frontpage&_a=index";
                            } else {
                                $url_str1 = '';
                                foreach ($_GET as $k => $v) {
                                    if ($k == '_l' || $k == '_v') continue;
                                    $url_str1.= "{$k}={$v}&";
                                }
                                $url_str1 = substr($url_str1, 0, strlen($url_str1) - 1);
                            }
                        } else {
                            $url_str1 = "_m=frontpage&_a=index";
                        }
                        $url_str1 = urlencode($url_str1);
                        $content_mod = 'mod_media';
                        $content_action = 'admin_banner';
                        if (ACL::isAdminActionHasPermission($content_mod, $content_action)) $urllink = 'popup_window(\'admin/index.php?_m=mod_media&_a=admin_banner&_url=' . $url_str1 . '\',\'' . $content_edit_title . '\',\'\',\'\',true);return false;';
                        $str_content_edit = '<a href="#" title="' . __('Edit Content') . '" onclick="' . $urllink . '"><img src="images/edit_content.gif" border="0" alt="' . __('Edit Content') . '"/>&nbsp;' . __('Edit Content') . '</a>';
                    } elseif (($block->module == 'mod_media') && ($block->s_pos == 'logo')) {
                        $content_edit_title = 'Logo&nbsp;&nbsp;' . __('Edit Content');
                        $content_mod = 'mod_media';
                        $content_action = 'admin_logo';
                        if (ACL::isAdminActionHasPermission($content_mod, $content_action)) $urllink = 'popup_window(\'admin/index.php?_m=mod_media&_a=admin_logo\',\'' . $content_edit_title . '\',\'\',\'\',true);return false;';
                        $str_content_edit = '<a href="#" title="' . __('Edit Content') . '" onclick="' . $urllink . '"><img src="images/edit_content.gif" border="0" alt="' . __('Edit Content') . '"/>&nbsp;' . __('Edit Content') . '</a>';
                    }
                } else {
                    $str_content_edit = '';
                }
                $propurllink = "alert('" . $nopermissionstr . "');return false;";
                if (ACL::isAdminActionHasPermission('edit_block', 'process')) {
                    $propurllink = 'popup_window(\'index.php?' . Html::xuriquery('mod_tool', 'edit_prop', array(
                        'mb_id' => $block->id,
                        'mar' => $is_mar
                    )) . '\', ' . '\'' . $_block_title . ' ' . __('Properties') . '\', 720, \'\',true);return false;';
                }
                $str_property_remove = '';
                $str_remove_block = '';
                if (!in_array($position, $limited_pos)) {
                    $str_property_remove = '<a href="#" title="' . __('Properties') . '" onclick="' . $propurllink . '">' . '<img src="images/properties.gif" alt="' . __('Properties') . '" border="0" />&nbsp;' . __('Properties') . '</a>&nbsp;';
                    $removelink = "alert('" . $nopermissionstr . "');return false;";
                    if (ACL::isAdminActionHasPermission('edit_block', 'process')) $removelink = 'remove_block(\'' . $block->id . '\',\'' . DEFAULT_LOCALE . '\');return false;';
                    $str_remove_block = '<a href="#" title="' . __('Remove') . '" onclick="' . $removelink . '">' . '<img src="images/remove.gif" alt="' . __('Remove') . '" border="0" />&nbsp;' . __('Remove') . '</a>';
                }
                $drag_to_move = '';
                if (!ACL::isAdminActionHasPermission('edit_block', 'process') && SessionHolder::get('page/status') == 'edit' && !in_array($position, $limited_pos)) {
                    $drag_to_move = 'title="' . __('No permission to Move') . '"';
                }
                if (!in_array($position, $limited_pos) && SessionHolder::get('page/status') == 'edit' && ACL::isAdminActionHasPermission('edit_block', 'process')) {
                    $drag_to_move = 'title="' . __('Drag to Move') . '"';
                }
                echo '<div id="MODBLK_' . $block->id . '" class="mod_block ' . $extra_css . $block->alias . '_block"' . $drag_to_move . '>' . "\n";
                if (SessionHolder::get('page/status', 'view') == 'edit' && !strpos($block->s_param, "www.jzbao.net")) {
                    echo '<div id="tb_' . $block->alias . '" class="mod_toolbar" style="display:none;">' . "\n" . $str_content_edit . $str_property_remove . $str_remove_block . '</div>';
                }
                if (intval($block->show_title) == 1) {
                    echo '<h3 class="blk_t">' . $_block_title . '</h3>' . "\n";
                }
                if (strlen(trim($block->s_param)) > 0) {
                    self::render($block->module, $block->action, array_merge(unserialize($block->s_param) , array(
                        'block_title' => $_block_title,
                        'block_pos' => $block->s_pos
                    )) , $block->id);
                } else {
                    self::render($block->module, $block->action, array(
                        'block_title' => $_block_title
                    ) , $block->id);
                }
                if (SessionHolder::get('page/status', 'view') == 'edit' && !in_array($position, $limited_pos)) {
                    echo "\n" . '<div style="margin:0px;padding:0px;border:0px;clear:both;"></div>' . "\n";
                }
                echo "\n" . '</div>' . "\n";
            }
        }
        if (SessionHolder::get('page/status', 'view') == 'edit' && !in_array($position, $limited_pos)) {
            echo '<div class="placeholder"></div>' . "\n" . '</div>' . "\n";
        }
    }
    public static function loadModules_H($position) {
        $pos_blocks = & self::_loadModules($position);
        echo "    <table id=\"tbl_{$position}_modules\" class=\"tbl_module_wrapper\" cellspacing=\"0\">
			<tbody><tr>
	";
        if (sizeof($pos_blocks) > 0) {
            foreach ($pos_blocks as $block) {
                echo '<td>' . "\n" . '<div id="mod_' . $block->alias . '" class="mod_block ' . $block->alias . '_block">' . "\n";
                if (intval($block->show_title) == 1) {
                    echo '<h3>' . __($block->title) . '</h3>' . "\n";
                }
                if (strlen(trim($block->s_param)) > 0) {
                    self::render($block->module, $block->action, unserialize($block->s_param));
                } else {
                    self::render($block->module, $block->action, array());
                }
                echo "\n" . '</div>' . "\n" . '</td>' . "\n";
            }
        }
        echo "
			</tr></tbody>
		</table>";
    }
    private static function findPos($positions) {
        $ret_arr = array();
        foreach ($positions as $val) {
            if (!in_array($val->s_pos, $ret_arr)) {
                array_push($ret_arr, $val->s_pos);
            }
        }
        $poss = TplInfo::$positions;
        $_ret_arr = array_diff($ret_arr, $poss);
        if (sizeof($_ret_arr) > 0) {
            $ret_str = "";
            foreach ($_ret_arr as $val) {
                $ret_str = $ret_str . "','" . $val;
            }
            return "right" . $ret_str;
        } else {
            return 'right';
        }
    }
    public static function getEditContents() {
        $edit_contents = array(
            'mod_auth' => array(
                'module' => 'mod_user',
                'action' => 'admin_list'
            ) ,
            'mod_message' => array(
                'module' => 'mod_message',
                'action' => 'admin_list'
            ) ,
            'mod_friendlink' => array(
                'module' => 'mod_friendlink',
                'action' => 'admin_list'
            ) ,
            'mod_qq' => array(
                'module' => 'mod_qq',
                'action' => 'admin_list'
            ) ,
            'mod_download' => array(
                'module' => 'mod_download',
                'action' => 'admin_list'
            ) ,
            'mod_category_a' => array(
                'module' => 'mod_category_a',
                'action' => 'admin_list'
            ) ,
            'mod_article' => array(
                'module' => 'mod_article',
                'action' => 'admin_list'
            ) ,
            'mod_category_p' => array(
                'module' => 'mod_category_p',
                'action' => 'admin_list'
            ) ,
            'mod_product' => array(
                'module' => 'mod_product',
                'action' => 'admin_list'
            ) ,
            'company_intro' => array(
                'module' => '',
                'action' => ''
            ) ,
            'mod_bulletin' => array(
                'module' => 'mod_bulletin',
                'action' => 'admin_list'
            ) ,
            'mod_media' => array(
                'module' => '',
                'action' => ''
            ) ,
            'mod_static' => array(
                'module' => 'mod_media',
                'action' => 'admin_foot'
            ) ,
            'mod_menu' => array(
                'module' => 'mod_menu_item',
                'action' => 'admin_list'
            ) ,
        );
        return $edit_contents;
    }
}

