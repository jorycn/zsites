<?php include_once('header.php') ?>

<div class="g-login">
    <div class="col-lg-12">
        <form name="myform" action="index.php" method="post">
            <div class="form-group">
                <label for="user">用户名</label>
                <input type="text" class="form-control" id="user" name="user">
            </div>
            <div class="form-group">
                <label for="password">登录密码</label>
                <input type="password" class="form-control" id="password" name="password"><a href="?_a=resetpassword"><font color="#FF0000">重设密码</font></a>
            </div>
            <input type="hidden" name="_a" value="check">
            <input type="submit" name="submit"  value="登录" class="btn btn-primary btn-lg btn-block"/>
        </form>
    </div>
</div>

