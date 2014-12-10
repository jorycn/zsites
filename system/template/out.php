<?php include_once('header.php') ?>

    <div class="container g-wrap">
      <h1 class="tit">ZSites站群管理平台</h1>
      
      <div class="row">
        <div class="col-lg-3">
          <?php include_once('left.php');?>
        </div>
        <div class="col-lg-9">
            <h1>分离站点-<?php echo $sitename;?></h1>
            <div class="row">
              <div class="col-lg-12 search">
                    <div class="panel panel-default">
                        <div class="panel-body">
                              <ol class="list-unstyled">
                                  <li>第一步 在新的虚拟主机,全新安装本程序</li>
                                  <li>第二步 登陆要导出的站点<?php echo $siteid?>后台,备份数据库</li>
                                  <li>第三步 将file/<?php echo $siteid?>,打包下载,并且上传到新的虚拟主机中</li>
                                  <li>第四步 将file/<?php echo $siteid?>/backup中的最新数据库文件还原到新的数据库中</li>
                                  <li>第五步 登陆新的站点的站群管理系统,点开站群设置,将缺省站点修改为:<?php echo $siteid?></li>
                              </ol>
                        </div>
                    </div>
              </div>
            </div>
        </div>
      </div>
    </div>

<?php include_once('foot.php');?>
