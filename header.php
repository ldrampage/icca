<header class="main-header fixed">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <a href="index.php" class="logo">
      <span class="logo-mini"><b>AC</b></span>
      <span class="logo-lg"><b>ICCA</b> PANEL</span>
    </a>
    
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <i class="fas fa-bars"></i>
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!--<li class="dropdown notifications-menu">-->
          <!--    <?php $countN = 0; $count=0; ?>-->
          <!--  <a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
          <!--    <i class="fa fa-bell"></i>-->
          <!--    <span class="label label-warning"><?php echo $countN; ?></span>-->
          <!--  </a>-->
          <!--  <ul class="dropdown-menu">-->
          <!--    <li class="header">You have <?php echo $count; ?> notifications</li>-->
          <!--  </ul>-->
          <!--</li>-->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo HR_URL.$useri['photo']; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['login_user'] ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="<?php echo HR_URL.$useri['photo']; ?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo $_SESSION['login_user']; ?> (<?php echo $_SESSION['login_designation']; ?>)
                  <small><?php echo $_SESSION['login_department']; ?></small>
                </p>
              </li>
              <li class="user-footer">
                <!--<div class="pull-left">-->
                <!--  <a href="?page=profile&id=<?php echo $_SESSION['login_id']; ?>" class="btn btn-default btn-flat">Profile</a>-->
                <!--</div>-->
                <div class="pull-right">
                  <a href="?logout=1" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>