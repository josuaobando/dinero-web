<?php

/**
 * Gustavo Granados
 * code is poetry
 */

require_once('system/Startup.class.php');

session_start();
session_destroy();
$wsRequest = new WSRequest($_REQUEST);

try
{
  $login = $wsRequest->getParam('login');
  if($login)
  {
    $username = trim($wsRequest->requireNotNullOrEmpty('email'));
    $password = trim($wsRequest->requireNotNullOrEmpty('password'));

    $account = Session::getAccount($username);
    $account->authenticate($password);
    if($account->isAuthenticated())
    {
      header("Location:home");
    }
  }

}
catch(Exception $ex)
{
  ExceptionManager::handleException($ex);
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="DineroApp">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dinero Seguro - Login</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css<?= "?v=".CoreConfig::CACHE_VERSION ?>">
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

    <!-- JS's -->
    <script type="text/javascript" src="public/lib/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="public/lib/jquery/jquery-ui.min.js"></script>

  </head>

  <body>
    <div class="spinner">
      <img alt="loading" src="images/spinner.gif">
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-4 col-md-offset-4">
          <div class="login-panel panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Please Sign In</h3>
            </div>
            <div class="panel-body">
              <form role="form" method="post" action="login">
                <fieldset>
                  <div class="form-group">
                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                  </div>
                  <div class="form-group">
                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                  </div>
                  <input name="login" type="submit" value="Login" class="btn btn-lg btn-success btn-block">
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <hr>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Bootstrap Datepicker JavaScript -->
    <script src="bower_components/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

    <!-- Custom JavaScript -->
    <script src="js/custom.js<?= "?v=".CoreConfig::CACHE_VERSION ?>"></script>

  </body>

  <footer>
    <div align="center">
      <div class="row">
        <p>
          <strong>
            <i class="fa fa-copyright"></i>
            2017 - DineroSeguroHF.com - All Rights Reserved
          </strong>
        </p>
      </div>
    </div>
  </footer>

</html>
