<?php include_once('header.php') ?>

    <div class="container g-wrap">
      <h1 class="tit">ZSites站群管理平台</h1>
      
      <div class="row">
        <div class="col-lg-3">
          <?php include_once('left.php');?>
        </div>
        <div class="col-lg-9">
            <div class="row">
              <div class="col-lg-12"><h1>网站设置</h1></div>
            </div>
            <div class="row sub-tab">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="?_a=setting">基本设置</a></li>
                        <li role="presentation"><a href="?_a=domain">泛域名设置</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form name="myform" action="index.php" method="post" role="form" class="form-horizontal">
                        <div class="form-group">
                            <label for="user" class="col-lg-2 control-label">管理员</label>
                            <div class="col-lg-10">
                                <input type="text" name="user" id="user" class="form-control ipt" value="<?php echo $user;?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-lg-2 control-label">管理密码</label>
                            <div class="col-lg-10">
                                <input type="text" name="password" id="password" class="form-control ipt" value="<?php echo $password;?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="defaultsiteid" class="col-lg-2 control-label">缺省站点ID</label>
                            <div class="col-lg-10">
                                <input type="text" name="defaultsiteid" id="defaultsiteid" class="form-control ipt" value="<?php echo DEFAULTSITEID ?>" />当域名非正常绑定时,显示缺省站点
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="defaultqq" class="col-lg-2 control-label">默认客服QQ</label>
                            <div class="col-lg-10">
                                <input type="text" name="defaultqq" id="defaultqq" class="form-control ipt" value="<?php echo DEFAULTQQ ?>" />开通网站后默认显示的QQ
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="helpurl" class="col-lg-2 control-label">客户帮助链接</label>
                            <div class="col-lg-10">
                                <input type="text" name="helpurl" id="helpurl" class="form-control ipt" value="<?php echo HELPURL ?>" />开通网站后默认显示的QQ
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="defaultpicurl" class="col-lg-2 control-label">模板图片与演示地址</label>
                            <div class="col-lg-10">
                                <input type="text" name="defaultpicurl" id="defaultpicurl" class="form-control ipt" value="<?php echo DEFAULTPICURL ?>" />网站模板列表默认图片以及演示地址
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="apikey" class="col-lg-2 control-label">通讯密钥</label>
                            <div class="col-lg-10">
                                <input type="text" name="apikey" id="apikey" class="form-control ipt" value="<?php echo APIKEY ?>" />api(一分钟)在线开通网站唯一编码,不建议修改
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="showright" class="col-lg-2 control-label">显示版权</label>
                            <div class="col-lg-10">
                                <input type="radio" name="showright"  value="0"<?php echo fc_compare(SHOWRIGHT,0," checked","")?>/>系统决定
                                <input type="radio" name="showright"  value="1"<?php echo fc_compare(SHOWRIGHT,1," checked","")?>/>强制显示
                            </div>
                        </div>
                        <input type="hidden" name="_a" value="saveset">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>

<?php include_once('foot.php');?>
