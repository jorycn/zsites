<?php include_once('header.php') ?>

    <div class="container g-wrap">
      <h1 class="tit">ZSites站群管理平台</h1>

      <div class="row">
        <div class="col-lg-3">
            <?php include_once('left.php');?>
        </div>
        <div class="col-lg-9">
            <h2>网站设置 - <?php echo $siteid;?></h2>

            <form name="myform" action="index.php" method="post" role="form" class="form-horizontal">
                <div class="form-group">
                    <label for="admin" class="col-lg-2 control-label">管理账号</label>
                    <div class="col-lg-10">
                        <input type="text" name="admin" id="admin" class="form-control ipt" value="<?php echo $admin;?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-lg-2 control-label">管理密码</label>
                    <div class="col-lg-10">
                      <input type="text" name="password" id="password" class="form-control" value="<?php echo $password;?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="sitename" class="col-lg-2 control-label">网站名称</label>
                    <div class="col-lg-10">
                      <input type="text" name="sitename" id="sitename" class="form-control" value="<?php echo $sitename;?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="sitedomain" class="col-lg-2 control-label">主域名</label>
                    <div class="col-lg-10">
                      <input type="text" name="sitedomain" id="sitedomain" class="form-control" value="<?php echo $sitedomain;?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="sitekey" class="col-lg-2 control-label">全局关键词</label>
                    <div class="col-lg-10">
                        <textarea name="sitekey" id="sitekey" cols="30" rows="5" class="form-control"/><?php echo $sitekey;?></textarea><br>1:输入关键词即可<br>2:一行一个<br>3:配合域名主关键词可以启动超级站群功能
                    </div>
                </div>
                <div class="form-group form-inline">
                    <label for="begindate" class="col-lg-2 control-label">有效期</label>
                    <div class="col-lg-10">
                      <input type="text" name="begindate" id="begindate" class="form-control j-date" value="<?php echo $begindate;?>" /> --
                      <input type="text" name="enddate" id="enddate" class="form-control j-date" value="<?php echo $enddate;?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">状态</label>
                    <div class="col-lg-10">
                      <input type="radio" name="status"  value="0"<?php echo fc_compare($status,0," checked","")?>/>试用
                      <input type="radio" name="status"  value="1"<?php echo fc_compare($status,1," checked","")?>/>运行
                      <input type="radio" name="status"  value="2"<?php echo fc_compare($status,2," checked","")?>/>停止
                    </div>
                </div>
                <div class="form-group form-inline">
                    <label for="showcheckcode" class="col-lg-2 control-label">验证码开关</label>
                    <div class="col-lg-10">
                      <input type="radio" name="showcheckcode"  value="0"<?php echo fc_compare($showcheckcode,0," checked","")?>/>关闭
                      <input type="radio" name="showcheckcode"  value="1"<?php echo fc_compare($showcheckcode,1," checked","")?>/>开启
                    </div>
                </div>
                <div class="form-group form-inline">
                    <label for="rewrite" class="col-lg-2 control-label">伪静态开关</label>
                    <div class="col-lg-10">
                      <input type="radio" name="rewrite"  value="1"<?php echo fc_compare($rewrite,1," checked","")?>/>关闭
                      <input type="radio" name="rewrite"  value="2"<?php echo fc_compare($rewrite,2," checked","")?>/>开启
                    </div>
                </div>
                <div class="form-group form-inline">
                    <label for="closeversion" class="col-lg-2 control-label">版权信息开关</label>
                    <div class="col-lg-10">
                        <input type="radio" name="closeversion"  value="0"<?php echo fc_compare($closeversion,0," checked","")?>/>开启
                        <input type="radio" name="closeversion"  value="1"<?php echo fc_compare($closeversion,1," checked","")?>/>关闭
                    </div>
                </div>
                <div class="form-group">
                    <input type="hidden" name="comeurl" value="<?php echo $comeurl;?>">
                    <input type="hidden" name="siteid" value="<?php echo $siteid;?>">
                    <input type="hidden" name="_a" value="savesite">
                    <input type="submit" name="submit" class="btn btn-primary btn-lg" value="保存" />
                </div>
            </form>

        </div>
    </div>

<?php include_once('foot.php');?>
