<?php include_once('header.php') ?>

    <div class="container g-wrap">
      <h1 class="tit">ZSites站群管理平台</h1>
      
      <div class="row">
        <div class="col-lg-3">
          <?php include_once('left.php');?>
        </div>
        <div class="col-lg-9">
            <h1>UC整合设置</h1>
            <div class="row">
              <div class="col-lg-12 search">
                    <div class="panel panel-default">
                        <div class="panel-body">
                              <ol>
                                <li>会员管理：整合后，网站前后台的用户名和密码以uc数据为准</li>
                                <li>整合效果：所有网站都帮您积累会员基础。您的会员总数=网站数*单个网站会员数
                                <li>平台应用：实现旗下所有网站公用会员，打造属于自己的通行证</li>
                              </ol>
                        </div>
                    </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                  <form name="myform" action="index.php" method="post" role="form" class="form-horizontal">
                      <div class="form-group">
                          <label for="admin" class="col-lg-2 control-label">整合UC</label>
                          <div class="col-lg-10">
                              <input type="radio" name="ucopen"  value="0"<?php echo fc_compare(UC_OPEN,0," checked","")?>/>强制禁止
                              <input type="radio" name="ucopen"  value="1"<?php echo fc_compare(UC_OPEN,1," checked","")?>/>自由决定
                              <input type="radio" name="ucopen"  value="2"<?php echo fc_compare(UC_OPEN,2," checked","")?>/>强制整合
                              <br>整合后,所有网站后台管理员和前台会员都必须在UC有记录,<font color="#FF0000">此举可以大大增加主站会员数</font>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="admin" class="col-lg-2 control-label">UC信息</label>
                          <div class="col-lg-10">
                              <textarea name="ucconfig" cols="50" rows="10" class="form-control"><?php echo $ucconfig;?></textarea>
                              <br>请直接从UC应用管理中。复制应用的 UCenter 配置信息
                          </div>
                      </div>
                      <input type="hidden" name="_a" value="saveuc">
                      <input type="submit" name="submit" class="btn btn-primary btn-lg" value="保存" />
                  </form>
              </div>
            </div>
        
        </div>
      </div>
    </div>

<?php include_once('foot.php');?>
