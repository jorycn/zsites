<?php
$menus = array(
	array(
		'title' => '效果查看',
		'child' => array(
			array('订单查看','index.php?_m=mod_order&_a=admin_list'),
			array('留言查看','index.php?_m=mod_message&_a=admin_list'),
			array('会员查看','index.php?_m=mod_user&_a=admin_list')
		)
	),
	array(
		'title' => '内容管理',
		'child'	=> array(
			array('产品管理', 'index.php?_m=mod_product&_a=admin_list'),
			array('文章管理', 'index.php?_m=mod_article&_a=admin_list'),
			array('下载管理', 'index.php?_m=mod_download&_a=admin_list'),
			array('公告管理', 'index.php?_m=mod_bulletin&_a=admin_list')
		)
	),
	array(
		'title' => '系统设置',
		'child'	=> array(
			array('基本设置', 'index.php?_m=mod_site&_a=admin_list&rurl=1'),
			array('SEO设置', 'index.php?_m=mod_site&_a=admin_seo&rurl=1'),
			array('多语言站点', 'index.php?_m=mod_lang&_a=admin_list'),
			array('在线支付', 'index.php?_m=mod_payaccount&_a=admin_list'),
			array('网站统计', 'index.php?_m=mod_statistics&_a=admin_list'),
			array('数据备份', 'index.php?_m=mod_backup&_a=admin_list')
		)
	)
);

