<?php

require_once ('system/Startup.class.php');

session_start();
$countries = $_SESSION['countries'];

if(!$countries)
{
  $tblCountry = TblCountry::getInstance();
  $countries = $tblCountry->getCountries();
  $_SESSION['countries'] = $countries;
}

foreach($countries as $country)
{
	$key = $country['Code'];
	$value = $country['Name'];
	echo "<option value=\"$key\">$value</option>";
}
?>