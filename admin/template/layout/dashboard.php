<?php if (!defined('IN_CONTEXT')) die('access violation error!');?>
<!DOCTYPE html>
<html lang="Zh-cn">
<head>
   <!-- Meta-->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
   <meta name="description" content="">
   <meta name="keywords" content="">
   <meta name="author" content="">
   <title>47Admin - Bootstrap Admin Skin</title>
   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
   <!--[if lt IE 9]><script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script><script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script><![endif]-->
   <!-- Bootstrap CSS-->
   <link rel="stylesheet" href="./static/bootstrap.css">
   <link rel="stylesheet" href="./static/app.css">
   <link rel="stylesheet" type="text/css" href="../static/css/font-awesome.min.css">
   <!-- Modernizr JS Script-->
</head>

<body>
   <!-- START Main wrapper-->
   <section class="wrapper">
      <!-- START Top Navbar-->
      <nav role="navigation" class="navbar navbar-default navbar-top navbar-fixed-top">
         <!-- START navbar header-->
         <div class="navbar-header">
            <a href="#" class="navbar-brand">
               <div class="brand-logo">47Admin</div>
               <div class="brand-logo-collapsed">47</div>
            </a>
         </div>
         <!-- END navbar header-->
         <!-- START Nav wrapper-->
         <div class="nav-wrapper">
            <!-- START Left navbar-->
            <ul class="nav navbar-nav">
               <li>
                  <a href="#" data-toggle="aside">
                     <em class="fa fa-align-left"></em>
                  </a>
               </li>
            </ul>
            <!-- END Left navbar-->
            <!-- START Right Navbar-->
            <ul class="nav navbar-nav navbar-right">
            	<li>
            		<a href="../" >
                     	<em class="fa fa-home"></em>
                  	</a>
            	</li>
                <li class="dropdown">
               		
                  <a href="#" data-toggle="dropdown" data-play="bounceIn" class="dropdown-toggle">
                     <em class="fa fa-user"></em>
                  </a>
                  <!-- START Dropdown menu-->
                  <ul class="dropdown-menu">
                     <li>
                        <div class="p">
                           <p>Overall progress</p>
                           <div class="progress progress-striped progress-xs m0">
                              <div role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;" class="progress-bar progress-bar-success">
                                 <span class="sr-only">80% Complete</span>
                              </div>
                           </div>
                        </div>
                     </li>
                     <li class="divider"></li>
                     <li><a href="#">Profile</a>
                     </li>
                     <li><a href="#">Settings</a>
                     </li>
                     <li><a href="#">Notifications<div class="label label-info pull-right">5</div></a>
                     </li>
                     <li><a href="#">Messages<div class="label label-danger pull-right">10</div></a>
                     </li>
                     <li><a href="#">Logout</a>
                     </li>
                  </ul>
                  <!-- END Dropdown menu-->
               </li>
            </ul>
            <!-- END Right Navbar-->
         </div>
         <!-- END Nav wrapper-->
      </nav>
      <!-- END Top Navbar-->
      <!-- START aside-->
      <aside class="aside">
         <!-- START Sidebar (left)-->
         <nav class="sidebar">
            <ul class="nav">
               <!-- END user info-->
               <!-- START Menu-->
               <li class="active">
                  <a href='main.php' target='main' title="Dashboard">
                     <em class="fa fa-dashboard"></em>
                     <span class="item-text">Home</span>
                  </a>
                  <!-- END SubMenu item-->
               </li>
                <?php include_once('menu.php'); ?>
                <?php foreach ($menus as $v):?>
	               <li>
	                  	<a href="#" title="Charts" data-toggle="collapse-next" class="has-submenu">
	                     	<em class="fa fa-bar-chart-o"></em>
	                     	<span class="item-text"><?php echo $v['title']?></span>
	                  	</a>
		                <?php if($v['child']):?>
		                  	<ul class="nav collapse ">
		                  		<?php foreach($v['child'] as $child):?>
			                    <li>
			                        <a href="<?php echo $child[1]?>" title="Flot" data-toggle="" class="no-submenu" target="main">
			                           <span class="item-text"><?php echo $child[0]?></span>
			                        </a>
			                    </li>
		                     	<?php endforeach;?>
		                  	</ul>
	              		<?php endif;?>
	               </li>
           		<?php endforeach;?>
               <!-- END Menu-->
               <!-- Sidebar footer    -->
               <li class="nav-footer">
                  <div class="nav-footer-divider"></div>
                  <!-- START button group-->
                  <div class="btn-group text-center">
                     <button type="button" data-toggle="tooltip" data-title="Add Contact" class="btn btn-link">
                        <em class="fa fa-user text-muted"><sup class="fa fa-plus"></sup>
                        </em>
                     </button>
                     <button type="button" data-toggle="tooltip" data-title="Settings" class="btn btn-link">
                        <em class="fa fa-cog text-muted"></em>
                     </button>
                     <button type="button" data-toggle="tooltip" data-title="Logout" class="btn btn-link">
                        <em class="fa fa-sign-out text-muted"></em>
                     </button>
                  </div>
                  <!-- END button group-->
               </li>
            </ul>
         </nav>
         <!-- END Sidebar (left)-->
      </aside>
      <!-- End aside-->
      <!-- START aside-->
      <!-- END aside-->
      <!-- START Main section-->
      <section>
         <!-- START Page content-->
         <section class="main-content">
            <iframe src="main.php" id="main" name="main" width="100%" height="700px" frameborder=false scrolling="auto" onload="document.all['main'].style.height=main.document.body.scrollHeight"></iframe>
         </section>
         <!-- END Page content-->
      </section>
      <!-- END Main section-->
   </section>
   <!-- END Main wrapper-->
   <!-- START Scripts-->
   <!-- Main vendor Scripts-->
   <script src="../static/js/jquery-2.0.2.min.js"></script>
   <script src="../static/js/bootstrap.min.js"></script>
   <!-- Sparklines-->
   <script src="./static/jquery.sparkline.min.js"></script>
   <!-- Slimscroll-->

   <!-- Plugins-->
   <!-- App Main-->
   <script src="./static/app.js"></script>
   <!-- END Scripts-->
</body>

</html>