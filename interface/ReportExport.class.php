<?php

/**
 * @author Josua
 */
class ReportExport extends Report
{

  /**
   * @see Report::__construct()
   *
   * @param WSRequest $wsRequest
   * @param Account $account
   */
  public function __construct($wsRequest, $account)
  {

    $wsRequest instanceof WSRequest;

    $statusId = $wsRequest->getParam("filterStatus", "3");
    $statusId = ($statusId == "-1") ? "0" : $statusId;

    $filterType = $wsRequest->getParam("filterType", "0");
    $filterAgencyType = $wsRequest->getParam("filterAgencyType", "0");

    $beginDate = $wsRequest->getParam("filterBeginDate", "");
    $endDate = $wsRequest->getParam("filterEndDate", "");
    $controlNumber = $wsRequest->getParam("filterMTCN", "");
    $filterUsername = $wsRequest->getParam("filterUsername", "");

    $pageSize = 0;
    $pageStart = 0;

    $this->tblSystem = TblSystem::getInstance();
    $dataReport = $this->tblSystem->getTransactionsReport($statusId, $filterType, $filterAgencyType, $account->getAccountId(), $beginDate, $endDate, $controlNumber, $filterUsername, $pageStart, $pageSize);

    $this->account = $account;
    $this->transactions = $dataReport['transactions'];
    $this->summary = $dataReport['summary'];
    $this->total = $dataReport['total'][0]['total'];

  }

  /**
   * @param string $filename
   *
   * @return PHPExcel_Writer_Excel2007
   */
  public function export($filename)
  {

    $viewAgency = $this->account->checkPermission('REPORT_TRANSACTION_VIEW_AGENCY');

    $headers = array();
    $headers[] = 'Transaction Id';
    $headers[] = 'Transaction Type';
    $headers[] = 'Status';
    $headers[] = 'Amount';
    $headers[] = 'Fee';
    $headers[] = 'Username';
    $headers[] = 'Customer';
    $headers[] = 'Person';
    $headers[] = 'MTCN';
    if($viewAgency)
    {
      $headers[] = 'Agency';
    }
    $headers[] = 'Agency Type';
    $headers[] = 'Created';
    $headers[] = 'Modified';
    $headers[] = 'Notes';

    $data = array();
    foreach($this->transactions as $transaction)
    {
      $row = array();
      $row['ID'] = $transaction['Transaction_Id'];
      $row['TransactionType'] = $transaction['TransactionType'];
      $row['Status'] = $transaction['Status'];
      $row['Amount'] = $transaction['Amount'];
      $row['Fee'] = $transaction['Fee'];
      $row['Username'] = $transaction['Customer'];
      $row['Customer'] = ucwords(strtolower($transaction['CustomerName']));
      $row['Person'] = ucwords(strtolower($transaction['PersonName']));
      $row['MTCN'] = $transaction['ControlNumber'];
      if($viewAgency)
      {
        $row['Agency'] = $transaction['Agency'];
      }
      $row['AgencyType'] = $transaction['AgencyType'];
      $createdDate = $transaction['CreatedDate'];
      $modifiedDate = $transaction['ModifiedDate'];
      $row['CreatedDate'] = date(Util::FORMAT_DATE_DISPLAY, strtotime($createdDate));
      $row['ModifiedDate'] = date(Util::FORMAT_DATE_DISPLAY, strtotime($modifiedDate));
      $row['Reason'] = $transaction['Reason'];
      $data[] = $row;
    }

    $format = array();
    $format['Amount'] = array('DataType' => Export::FIELD_DATA_TYPE_NUMERIC, 'Format' => '0.00');
    $format['Fee'] = array('DataType' => Export::FIELD_DATA_TYPE_NUMERIC, 'Format' => '0.00');

    $export = new ExportXSLX($filename, $data, $headers, 'Report', $format);

    return $export->export();
  }

}

?>