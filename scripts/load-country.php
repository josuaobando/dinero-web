<?php

require_once ('system/Startup.class.php');

session_start();
$countries = Session::getCountries();
foreach($countries as $country)
{
	$key = $country['Code'];
	$value = $country['Name'];
	echo "<option value=\"$key\">$value</option>";
}
?>