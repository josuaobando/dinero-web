<?php
include("../../settingHeader.php");

$wsRequest = new WSRequest($_REQUEST);
try
{
  $account = Session::getAccount();
  if($account->isAuthenticated())
  {

  }
}
catch(Exception $ex)
{
  ExceptionManager::handleException($ex);
  $userMessage = $ex->getMessage();
  $userMessage = '<div class="alert alert-danger">'.$userMessage.'</div>';
}
?>

<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <h3 class="page-header">
        Schedule
      </h3>
    </div>
  </div>
</div>

<?php include("../../footer.php"); ?>

