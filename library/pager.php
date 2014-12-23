<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
class Pager {
    public static $mod;
    public static $act;
    public static $extUrl;
    public static $status;
    
    public static function &pageByObject_old($object_name, $where = false, $params = false, $more_sql = false, $page_param = 'p') {
        $result = array(
            'pager' => false,
            'data' => false
        );
        try {
            $obj = new $object_name();
            $obj_cnt = $obj->count($where, $params);
            $curr_page = & ParamHolder::get($page_param, 1);
            $result['pager'] = & self::_genPagerLinks($curr_page, $obj_cnt, $page_param);
            $start_index = intval(PAGE_SIZE) * ($curr_page - 1);
            if ($more_sql == false) {
                $more_sql = '';
            }
            $more_sql.= " LIMIT " . $start_index . ", " . PAGE_SIZE;
            $result['data'] = & $obj->findAll($where, $params, $more_sql);
        }
        catch(RecordException $ex) {
            throw new Exception($ex->getMessage());
        }
        echo $result;
        return $result;
    }
    public static function &pageByText($content, $urlparams = array() , $splitstr = '', $page_param = 'p') {
        $defaultparam = array(
            '_m' => R_MOD,
            '_a' => R_ACT
        );
        $defaultsplitstr = '#p#·ÖÒ³·û#e#';
        $urlparams = array_merge($defaultparam, $urlparams);
        $exturl = $urlparams;
        unset($exturl['_m']);
        unset($exturl['_a']);
        if (empty($splitstr)) $splitstr = $defaultsplitstr;
        $pagecontents = explode($splitstr, $content);
        $totalpagenums = count($pagecontents);
        $curr_page = & ParamHolder::get($page_param, 1);
        if (!preg_match('/^\d+$/', $curr_page) || intval($curr_page) == 0) {
            $curr_page = 1;
        } elseif (intval($curr_page) > $totalpagenums) {
            $curr_page = $totalpagenums;
        }
        $splitcontent = $pagecontents[$curr_page - 1];
        $result = array(
            'data' => $splitcontent,
            'mod' => $urlparams['_m'],
            'cur_page' => $curr_page,
            'act' => $urlparams['_a'],
            'extUrl' => $exturl,
            'total' => $totalpagenums
        );
        return $result;
    }
    public static function &pageByObject($object_name, $where = false, $params = false, $more_sql = false, $page_param = 'p', $type = '') {
        $result = array(
            'pager' => false,
            'data' => false
        );
        Pager::$status = $type;
        try {
            $obj = new $object_name();
            $obj_cnt = $obj->count($where, $params);
            $curr_page = & ParamHolder::get($page_param, 1);
            $result['pager'] = & self::_genPagerLinks($curr_page, $obj_cnt, $page_param, 5);
            $start_index = intval(PAGE_SIZE) * ($curr_page - 1);
            if ($more_sql == false) {
                $more_sql = '';
            }
            $more_sql.= " LIMIT " . $start_index . ", " . PAGE_SIZE;
            $result['data'] = & $obj->findAll($where, $params, $more_sql);
            $result['mod'] = Pager::$mod;
            $result['act'] = Pager::$act;
            $result['extUrl'] = Pager::$extUrl;
        }
        catch(RecordException $ex) {
            throw new Exception($ex->getMessage());
        }
        return $result;
    }
    public static function &pageBySql($sql, $params = false, $more_sql = false, $page_param = 'p') {
        $db = & MysqlConnection::get();
        $result = array(
            'pager' => false,
            'data' => false
        );
        try {
            $sql_part = spliti('from', $sql);
            $count_sql = "SELECT COUNT(*) FROM" . $sql_part[1];
            $rs = & $db->query($count_sql, $params);
            $row = & $rs->fetchRow(MYSQL_NUM);
            $rec_cnt = $row[0];
            $rs->free();
            $curr_page = & ParamHolder::get($page_param, 1);
            $result['pager'] = & self::_genPagerLinks($curr_page, $rec_cnt, $page_param, 5);
            $start_index = intval(PAGE_SIZE) * ($curr_page - 1);
            if ($more_sql !== false) {
                $sql.= " " . $more_sql;
            }
            $sql.= " LIMIT " . $start_index . ", " . PAGE_SIZE;
            $rs_o = & $db->query($sql, $params);
            $result['data'] = & $rs_o->fetchObjects();
            $rs_o->free();
        }
        catch(MysqlException $ex) {
            throw new Exception('Failed loading records!' . "\n" . $ex->getMessage());
        }
        return $result;
    }
    public static function &pageBySql_wCount($count_sql, $count_params = false, $sql, $params = false, $more_sql = false, $page_param = 'p') {
        $db = & MysqlConnection::get();
        $result = array(
            'pager' => false,
            'data' => false
        );
        try {
            $rs = & $db->query($count_sql, $count_params);
            $row = & $rs->fetchRow(MYSQL_NUM);
            $rec_cnt = $row[0];
            $rs->free();
            $curr_page = & ParamHolder::get($page_param, 1);
            $result['pager'] = & self::_genPagerLinks($curr_page, $rec_cnt, $page_param);
            $start_index = intval(PAGE_SIZE) * ($curr_page - 1);
            if ($more_sql !== false) {
                $sql.= " " . $more_sql;
            }
            $sql.= " LIMIT " . $start_index . ", " . PAGE_SIZE;
            $rs_o = & $db->query($sql, $params);
            $result['data'] = & $rs_o->fetchObjects();
            $rs_o->free();
        }
        catch(MysqlException $ex) {
            throw new Exception('Failed loading records!' . "\n" . $ex->getMessage());
        }
        return $result;
    }
    private static function &_genPagerLinks($curr_page, $rec_cnt, $page_param, $show_num) {
        $pager = false;
        $page_list = '';
        $pages = ceil($rec_cnt / intval(PAGE_SIZE));
        if ($pages < 1) $pages = 1;
        if ($curr_page > $pages) $curr_page = $pages;
        if ($curr_page < 1) $curr_page = 1;
        $prev_page = ($curr_page == 1) ? 1 : $curr_page - 1;
        $next_page = ($curr_page == $pages) ? $pages : $curr_page + 1;
        $pager['first'] = self::_genPagerUri(1, $page_param);
        $page_list.= $curr_page == 1 ? '&nbsp;' . __("First") . '&nbsp;' : '<a href="' . $pager['first'] . '" class="page_word">' . __("First") . '</a>&nbsp;';
        $pager['prev'] = self::_genPagerUri($prev_page, $page_param);
        $page_list.= $curr_page == 1 ? '&nbsp;' . __("Previous") . '&nbsp;' : '&nbsp;<a href="' . $pager['prev'] . '" class="page_word" >' . __("Previous") . '</a>&nbsp;';
        $startPage = $curr_page - ceil($show_num / 2) + 1 > 0 ? ($curr_page - ceil($show_num / 2)) + 1 : 1;
        $endPage = $startPage + $show_num;
        if ($endPage > ($pages + 1)) {
            $endPage = $pages + 1;
        }
        if ($startPage + 1 >= $show_num) {
            $page_list.= '&nbsp;<a href="' . $pager['first'] . '" class="page_square" >1</a>...&nbsp;';
        }
        for ($i = $startPage; $i < $endPage; $i++) {
            if ($i == $curr_page) {
                $page_list.= '<a href="' . self::_genPagerUri($i, $page_param) . '" class="page_square_bg"><font color="white">' . $i . '</font></a>';
            } else {
                $page_list.= '<a href="' . self::_genPagerUri($i, $page_param) . '" class="page_square">' . $i . '</a>';
            }
        }
        if ($endPage < ($pages + 1)) {
            $page_list.= "...";
            $pager['last'] = self::_genPagerUri($pages, $page_param);
            $page_list.= '<a href="' . $pager['last'] . '"  class="page_square">' . $pages . '</a>';
        }
        $pager['next'] = self::_genPagerUri($next_page, $page_param);
        $page_list.= $pages == $curr_page ? '&nbsp;' . __("Next") . '&nbsp;' : '&nbsp;<a href="' . $pager['next'] . '" class="page_word"  >' . __("Next") . '</a>&nbsp;';
        $pager['last'] = self::_genPagerUri($pages, $page_param);
        $page_list.= $pages == $curr_page ? '&nbsp;' . __("Last") . '&nbsp;' : '&nbsp;<a href="' . $pager['last'] . '" class="page_word"  >' . __("Last") . '</a>&nbsp;';
        $page_list.= '&nbsp;&nbsp;&nbsp;&nbsp;' . __("Skip to") . '£º';
        $page_list.= Html::input('text', 'p', '', 'class="pageinput"');
        $page_list.= "&nbsp;" . __("pagePage") . "&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' class='page_sure' onclick='pageLocation()'>&nbsp;" . __("page sure") . "&nbsp;</a>";
        $pager['curr'] = $curr_page;
        $pager['total'] = $pages;
        $ret_page_list = '';
        if ($pages != 1) {
            $ret_page_list = $page_list;
        } else {
            $ret_page_list = '';
        }
        return $ret_page_list;
    }
    private static function _genPagerUri($page, $page_param) {
        $uri = 'index.php?' . $page_param . '=' . $page;
        $extra_o = '';
        if (isset($_GET)) {
            foreach ($_GET as $key => $value) {
                if ($key != $page_param) {
                    if (strpos($key, "keyword")) {
                        $uri.= '&amp;' . $key . '=' . Toolkit::baseDecode($value);
                    } else {
                        $uri.= '&amp;' . $key . '=' . htmlspecialchars($value);
                    }
                }
            }
        }
        if (isset($_POST)) {
            foreach ($_POST as $key => $value) {
                if ($key != $page_param) {
                    $uri.= '&amp;' . $key . '=' . htmlspecialchars($value);
                }
            }
        }
        $temp = $extra = array();
        $temp = explode('&amp;', $uri);
        $ln = sizeof($temp);
        if ($ln) {
            if ($page != 1) {
                $extra = array(
                    $page_param => $page
                );
            } else {
                $extra = array();
            }
            for ($i = 1; $i < $ln; $i++) {
                $ky = substr($temp[$i], 0, strrpos($temp[$i], '='));
                $vl = substr($temp[$i], strrpos($temp[$i], '=') + 1);
                switch ($ky) {
                    case '_m':
                        $_m = $vl;
                        break;

                    case '_a':
                        $_a = $vl;
                        break;

                    default:
                        if (!empty($ky)) {
                            if ($ky == 'txt') {
                                continue;
                            }
                            if ($ky != $page_param && $key != "prdsearch_submit") {
                                if (strpos($ky, "keyword")) {
                                    $extra_o[$ky] = Toolkit::baseEncode($vl);
                                } elseif (strpos($ky, "_sw")) {
                                    if ($vl == "") continue;
                                    else $extra_o[$ky] = $vl;
                                } else {
                                    $extra_o[$ky] = $vl;
                                }
                            }
                            if (strpos($ky, "keyword")) {
                                $extra[$ky] = Toolkit::baseEncode($vl);
                            } elseif (strpos($ky, "_sw")) {
                                if (empty($vl) || $vl == '-') continue;
                                else $extra[$ky] = $vl;
                            } else {
                                $extra[$ky] = $vl;
                            }
                        }
                        break;
                    }
                }
            } else {
                $_m = DEFAULT_MODULE;
                $_a = DEFAULT_ACTION;
            }
            Pager::$mod = $_m;
            Pager::$act = $_a;
            Pager::$extUrl = $extra_o;
            if (Pager::$status == 'popupwin') {
                $uri = 'index.php?' . Html::xuriquery($_m, $_a, $extra);
            } else {
                $uri = Html::uriquery($_m, $_a, $extra);
            }
            return $uri;
        }
        public static function &genPagerLinks($curr_page, $rec_cnt, $page_param, $show_num) {
            $pager = & self::_genPagerLinks($curr_page, $rec_cnt, $page_param, $show_num);
            return $pager;
        }
}

