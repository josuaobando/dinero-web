<?php

require_once('system/Startup.class.php');

session_start();
$page = $_SERVER["REQUEST_URI"];
if(strpos($page, "login") === false){
  $account = $_SESSION['account'];
  $account instanceof Account;
  if(!$account){
    header("Location:login");
  }
}

?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dinero Seguro</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap DatePicker CSS -->
    <link href="bower_components/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="dist/css/timeline.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- JQUERY JS -->
    <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>

  </head>

  <body>
    <div class="spinner">
      <img alt="loading" src="images/spinner.gif">
    </div>

    <div id="wrapper">

      <!-- Navigation -->
      <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="home">Dinero Seguro</a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="">
              <i class="fa fa-bars fa-fw"></i>
              <?= $account->getUsername() ?>
              <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
              <li>
                <a href="home">
                  <i class="fa fa-home fa-fw"></i>
                  Home
                </a>
              </li>
              <li class="divider"></li>
              <!-- user -->
              <?php if($account->checkPermission('SETTING_USER_PROFILE')){ ?>
                <?php if($account->checkPermission('SETTING_USER_PROFILE_CHANGE_PASSWORD')){ ?>
                <li>
                  <a href="userProfile">
                    <i class="fa fa-user fa-fw"></i>
                    User Profile
                  </a>
                </li>
                <?php } ?>
                <li class="divider"></li>
              <?php } ?>
              <li>
                <a href="logout.php">
                  <i class="fa fa-sign-out fa-fw"></i>
                  Logout
                </a>
              </li>
            </ul>
            <!-- /.dropdown-user -->
          </li>
        </ul>
        <!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
          <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
              <li>
                <a href="settings">
                  <i class="fa fa-cogs fa-fw"></i>
                  Setting
                </a>
              </li>
              <?php if ($account->checkPermission('SETTING_TOOLS_AGENCY')){ ?>
                <li>
                  <a href="agency">
                    <i class="fa fa-university fa-fw"></i>
                    Agency
                  </a>
                </li>
              <?php }?>
              <?php if ($account->checkPermission('SETTING_TOOLS_SCHEDULE')){ ?>
                <li>
                  <a href="schedule">
                    <i class="fa fa-calendar fa-fw"></i>
                    Schedule
                  </a>
                </li>
              <?php }?>
            </ul>
          </div>
          <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
      </nav>
        