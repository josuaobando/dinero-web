<?php

require_once ('system/Startup.class.php');

$countryCode = $_REQUEST["code"];

$tblCountry = TblCountry::getInstance();
$states = $tblCountry->getStates($countryCode);

foreach($states as $state)
{
	$key = $state['Code'];
	$value = $state['Name'];
	echo "<option value=\"$key\">$value</option>";
}

?>