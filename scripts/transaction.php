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

  $manager = new Manager($account);
  if($function == 'information'){
    $wsRequest->putParam('transaction_id', $transactionId);
    $transaction = $manager->information($wsRequest, true);

    $jsonContent = json_encode($transaction->toArray());
  }else{
    $newPerson = $manager->getNewPerson($transactionId);
    $jsonContent = json_encode($newPerson->toArray2());
  }
}
catch (InvalidStateException $ex)
{
  $jsonContent = json_encode(array('error'=>$ex->getMessage()));
}

$header = 'Content-Type: application/json; charset=UTF-8';
header($header);
echo $jsonContent;

?>