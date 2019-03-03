<?php
require_once ('system/Startup.class.php');
session_start();
try
{
  $account = Session::getAccount();
  if (!$account->isAuthenticated())
  {
    throw new InvalidStateException("User account is not logged");
  }
  $wsRequest = new WSRequest($_REQUEST);

  $function = $wsRequest->getParam('f');
  $transactionId = $wsRequest->requireNumericAndPositive('id');
}
catch (InvalidStateException $ex)
{
  $jsonContent = json_encode(array('error'=>$ex->getMessage()));
}

$header = 'Content-Type: application/json; charset=UTF-8';
header($header);
echo '{response: "null"}';

?>