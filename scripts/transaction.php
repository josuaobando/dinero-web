<?php
require_once ('system/Startup.class.php');
session_start();

try
{
  $account = $_SESSION['account'];
  if (!$account)
  {
    throw new InvalidStateException("User account is not logged");
  }
  $wsRequest = new WSRequest($_REQUEST);
  $transactionId = $wsRequest->requireNumericAndPositive('id');
  
  $manager = new Manager($account);
  $newPerson = $manager->getNewPerson($transactionId);

  $jsonContent = json_encode($newPerson->toArray2());
}
catch (InvalidStateException $ex)
{
  $jsonContent = json_encode(array('error'=>$ex->getMessage()));
}

$header = 'Content-Type: application/json; charset=UTF-8';
header($header);
echo $jsonContent;

?>