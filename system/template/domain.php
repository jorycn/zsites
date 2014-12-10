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
                        <li role="presentation"><a href="?_a=setting">基本设置</a></li>
                        <li role="presentation" class="active"><a href="?_a=domain">泛域名设置</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">泛域名说明</div>
                        <div class="panel-body">
                              新获得的网站自动获得域名的条件<br>
                              1、建站宝必须在主机头为空的IIS下<br>
                              2、请将域名泛解析到服务器上<br>
                              3、设置泛域名规则<br>
                              4、如果您无法满足以上条件。请为每个网站人工绑定域名，并且分析是否可以正常访问
                        </div>
                    </div>

                    <form name="myform" action="index.php" method="post" role="form" class="form-horizontal form-inline">
                        <div class="form-group">
                            <label for="defaultdomain" class="col-lg-3 control-label">泛域名</label>
                            <div class="col-lg-7">
                                <input type="text" name="defaultdomain" id="defaultdomain" class="form-control" value="<?php echo DEFAULTDOMAIN;?>" />
                            </div>
                            <label class="col-lg-2">
                                <input type="hidden" name="_a" value="savedomain">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </label>
                        </div>
                    </form>

                    <?php if(DEFAULTDOMAIN!=""):?>
                    <div class="row">
                        <div class="col-lg-12">
                            <h3>示例域名</h3>
                            <ul class="list-unstyled">
                                <?php for ($i=1; $i < 6; $i++):?>
                                  <li><a href='<?php echo gettrueurl(0,$i.DEFAULTDOMAIN)?>' target='_blank'><?php echo $i.DEFAULTDOMAIN?></a></li>
                                <?php endfor;?>
                            </ul>
                        </div>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>

<?php include_once('foot.php');?>
