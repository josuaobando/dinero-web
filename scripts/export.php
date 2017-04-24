<?php
require_once ('system/Startup.class.php');
session_start();

$account = Session::getAccount();
$wsRequest = new WSRequest($_REQUEST);
$date = date('Y.m.d H.i.s');

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;
header("Content-Disposition: attachment;filename=Report-$date.xlsx");
header("Content-Transfer-Encoding: binary ");

$report = new ReportExport($wsRequest, $account);
$phpExcelWrite = $report->export("Report-$date");
$phpExcelWrite->save('php://output');
?>