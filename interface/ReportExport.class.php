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
    $statusId = $wsRequest->getParam("filterStatus", "3");
    $statusId = ($statusId == "-1") ? "0" : $statusId;
    $filterType = $wsRequest->getParam("filterType", "0");
    $filterAgencyType = $wsRequest->getParam("filterAgencyType", "0");
    $filterAgencyId = $wsRequest->getParam("filterAgencyId", "0");

    $beginDate = $wsRequest->getParam("filterBeginDate", "");
    $endDate = $wsRequest->getParam("filterEndDate", "");
    $filterID = $wsRequest->getParam("filterID", "");
    $controlNumber = $wsRequest->getParam("filterMTCN", "");
    $filterReference = $wsRequest->getParam("filterReference", "");
    $filterUsername = $wsRequest->getParam("filterUsername", "");

    $pageSize = 0;
    $pageStart = 0;

    $this->tblSystem = TblSystem::getInstance();
    $dataReport = $this->tblSystem->getTransactionsReport($statusId, $filterType, $filterAgencyType, $filterAgencyId, $account->getAccountId(), $beginDate, $endDate, $controlNumber, $filterUsername, $filterID, $filterReference, $pageSize, $pageStart);

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
    $viewAPITransaction = $this->account->checkPermission('REPORT_TRANSACTION_VIEW_API_VERIFICATION');
    $viewCompany = $this->account->checkPermission('REPORT_TRANSACTION_VIEW_COMPANY');

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
    if($viewCompany)
    {
      $headers[] = 'Company';
    }
    $headers[] = 'MerchantId';
    if($viewAgency)
    {
      $headers[] = 'Agency';
    }
    $headers[] = 'Agency Type';
    if($viewAPITransaction)
    {
      $headers[] = 'API Verification';
      $headers[] = 'API Status';
      $headers[] = 'API Code';
    }
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
      if($viewCompany)
      {
        $row['Company'] = $transaction['Company'];
      }
      $row['MerchantId'] = $transaction['MerchantId'];
      if($viewAgency)
      {
        $row['Agency'] = $transaction['Agency'];
      }
      $row['AgencyType'] = $transaction['AgencyType'];
      if($viewAPITransaction)
      {
        $row['API Verification'] = $transaction['Verification_Id'];
        $row['API Status'] = ucwords($transaction['Verification']);
        $row['API Code'] = $transaction['AuthCode'];
      }
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