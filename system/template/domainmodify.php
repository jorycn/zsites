<?php include_once('header.php') ?>

    <div class="container g-wrap">
      <h1 class="tit">ZSites站群管理平台</h1>

      <div class="row">
        <div class="col-lg-3">
            <?php include_once('left.php');?>
        </div>
        <div class="col-lg-9">
            <h2>域名修改</h2>

            <form name="myform" action="index.php" method="post" role="form" class="form-horizontal">
                <div class="form-group">
                    <label for="domain" class="col-lg-2 control-label">域名</label>
                    <div class="col-lg-10">
                        <input type="text" name="domain" id="domain" class="form-control ipt" value="<?php echo $domain;?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="domaintemplate" class="col-lg-2 control-label">显示模板</label>
                    <div class="col-lg-10">
                      <select name="domaintemplate" class="form-control">
                        <?php
                        $array=getDir("../template");
                        for ($i=0;$i<sizeof($array);$i++){
                          $thisvalue=$array[$i];
                          if ($thisvalue){
                            echo "<option value='".$thisvalue."'".fc_compare($domaintemplate,$thisvalue," selected","").">".$thisvalue."</option>";
                          }
                        }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="mainkey" class="col-lg-2 control-label">主关键词</label>
                    <div class="col-lg-10">
                      <input type="text" name="mainkey" id="mainkey" class="form-control" value="<?php echo $mainkey;?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="domainkey" class="col-lg-2 control-label">转换关键词</label>
                    <div class="col-lg-10">
                        <textarea name="domainkey" cols="30" rows="5" class="form-control"/><?php echo $domainkey;?></textarea><br>格式如下<br>1:被替换关键词|替换后的关键词<br>2:被替换关键词<br>3:一行一个
                    </div>
                </div>
                <div class="form-group">
                    <label for="domainkey" class="col-lg-2 control-label">状态</label>
                    <div class="col-lg-10">
                        <input type="radio" name="status"  value="0"<?php echo fc_compare($status,0," checked","")?>/>暂停
                        <input type="radio" name="status"  value="1"<?php echo fc_compare($status,1," checked","")?>/>运行
                    </div>
                </div>

                <div class="form-group">
                    <input type="hidden" name="comeurl" value="<?php echo $comeurl;?>">
                    <input type="hidden" name="domainid" value="<?php echo $domainid;?>">
                    <input type="hidden" name="_a" value="savemodifydomain">
                    <input type="submit" name="submit" class="btn btn-primary btn-lg" value="保存" />
                </div>
            </form>

        </div>
    </div>

<?php include_once('foot.php');?>
