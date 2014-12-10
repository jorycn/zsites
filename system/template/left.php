<ul class="nav nav-pills nav-stacked" role="tablist">
	<li <?php if(!isset($_GET['_a'])):?>class="active"<?php endif;?>><a href="index.php">站群管理</a></li>
	<li <?php if($_GET['_a'] == 'add'):?>class="active"<?php endif;?>><a href="?_a=add">添加站点</a></li>
	<li <?php if($_GET['_a'] == 'setting'):?>class="active"<?php endif;?>><a href="?_a=setting">站群设置</a></li>
	<li <?php if($_GET['_a'] == 'uc'):?>class="active"<?php endif;?>><a href="?_a=uc">UCenter整合</a></li>
	<li <?php if($_GET['_a'] == 'loginout'):?>class="active"<?php endif;?>><a href="?_a=loginout">安全退出</a></li>
</ul>