<?php

/**
 * @author Josua
 */
class Report
{

  /**
   * TblSystem reference
   *
   * @var TblSystem
   */
  protected $tblSystem;

  /**
   * Account reference
   *
   * @var Account
   */
  protected $account;

  /**
   * Transactions
   *
   * @var array
   */
  protected $transactions = array();

  /**
   * Summary
   *
   * @var array
   */
  protected $summary = array();

  /**
   * Total
   *
   * @var int
   */
  protected $total = 0;

  /**
   * Current Page
   *
   * @var int
   */
  protected $currentPage = 0;

  /**
   * report constructor
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

    $this->currentPage = $wsRequest->getParam("filterPage", "1");

    $system = new System();
    $dataReport = $system->transactionsReport($statusId, $filterType, $filterAgencyType, $account->getAccountId(), $beginDate, $endDate, $controlNumber, $filterUsername, $filterID, $filterReference, $this->currentPage);

    $this->account = $account;
    $this->transactions = $dataReport['transactions'];
    $this->summary = $dataReport['summary'];
    $this->total = $dataReport['total'][0]['total'];
  }

  /**
   * @return the $transactions
   */
  public function getTransactions()
  {
    return $this->transactions;
  }

  /**
   * @return the $summary
   */
  public function getSummary()
  {
    return $this->summary;
  }

  /**
   * @return the $total
   */
  public function getTotal()
  {
    return $this->total;
  }

  /**
   * @return the $currentPage
   */
  public function getCurrentPage()
  {
    return $this->currentPage;
  }

  /**
   * Return Table Transactions
   *
   * @return array
   */
  public function getReportTable()
  {
    $viewAgency = $this->account->checkPermission('REPORT_TRANSACTION_VIEW_AGENCY');
    $viewAgencyNote = $this->account->checkPermission('REPORT_TRANSACTION_VIEW_AGENCY_NOTE');

    $table = "<thead>
                 <tr>
                  <th>ID</th>
                  <th>Status</th>
                  <th>Amount</th>
                  <th>Fee</th>
                  <th>Username</th>
                  <th>Customer</th>
                  <th>Person</th>
                  <th>MTCN</th>
                  <th>Date</th>
                  <th>Reference</th>
                  ".($viewAgency ? '<th>Agency</th>' : '')."
                  <th>Type</th>
                  <th>Reason</th>
                  ".($viewAgencyNote ? '<th>Note</th>' : '')."
                </tr>
              </thead>";

    $table .= "<tbody>";

    if(count($this->transactions) > 0)
    {
      foreach($this->transactions as $transaction)
      {
        $id = $transaction['Transaction_Id'];
        $type = $transaction['TransactionType_Id'];
        $status = $transaction['Status'];
        $customer = $transaction['Customer'];
        $senderName = ucwords(strtolower($transaction['CustomerName']));
        $amount = $transaction['Amount'];
        $fee = $transaction['Fee'];
        $receiverName = ucwords(strtolower($transaction['PersonName']));
        $controlNumber = $transaction['ControlNumber'];
        $reason = $transaction['Reason'];
        $note = $transaction['Note'];
        $agency = $transaction['Agency'];
        $agencyType = $transaction['AgencyType'];
        $modifiedDate = $transaction['ModifiedDate'];
        $modifiedDate = date(Util::FORMAT_DATE_DISPLAY, strtotime($modifiedDate));
        $reference = $transaction['Reference'];
        $rowType = ($type == Transaction::TYPE_RECEIVER) ? '' : 'warning';

        $row = "<tr class='$rowType'>
          <td>
            <a title='Open' class='btn btn-primary btn-xs' data-toggle='modal' data-target=\"#myModal$id\">$id</a>
          </td>
          <td>$status</td>
          <td>$$amount</td>
          <td>$$fee</td>
          <td>$customer</td>
          <td>$senderName</td>
          <td>$receiverName</td>
          <td>$controlNumber</td>
          <td>$modifiedDate</td>
          <td>$reference</td>
          ".($viewAgency ? "<td>$agency</td>" : "")."
          <td>$agencyType</td>
          <td>$reason</td>
          ".($viewAgencyNote ? "<td>$note</td>" : "")."
        </tr>";

        $table .= $row;
      }
    }
    else
    {
      $table .= "<td colspan='14'>No Records!</td>";
    }

    $table .= "</tbody>";

    return $table;
  }

  /**
   * Return the summary transactions
   *
   * @return array
   */
  public function getReportSummaryTable()
  {

    $table = "<thead>
                <tr>
                  <th>Status</th>
                  <th>Records</th>
                  <th>Amount</th>
                  <th>Fee</th>
                  <th>Total</th>
                </tr>
              </thead>";

    $table .= "<tbody>";

    foreach($this->summary as $summary)
    {

      $status = $summary['Status'];
      $records = $summary['Records'];
      $amount = number_format($summary['Amount'], 2);
      $fee = number_format($summary['Fee'], 2);
      $total = number_format($summary['Total'], 2);

      $statusId = $summary['TransactionStatus_Id'];

      switch($statusId)
      {
        case Transaction::STATUS_REQUESTED:
          $row = "<tr class='active'>";
          break;
        case Transaction::STATUS_SUBMITTED:
          $row = "<tr class='info'>";
          break;
        case Transaction::STATUS_APPROVED:
          $row = "<tr class='success'>";
          break;
        case Transaction::STATUS_REJECTED:
          $row = "<tr class='danger'>";
          break;
        case Transaction::STATUS_CANCELED:
          $row = "<tr class='warning'>";
          break;
        default:
          $row = "<tr>";
          break;
      }

      $row .= "
                <td>$status</td>
                <td>$records</td>
                <td>$$amount</td>
                <td>$$fee</td>
                <td>$$total</td>
              </tr>";

      $table .= $row;
    }
    $table .= "</tbody>";

    return $table;
  }

  /**
   * Return the pagination table
   *
   * @return string
   */
  public function getPaginationTable()
  {
    if($this->total > CoreConfig::PAGINATION_TABLE_MAX_ROWS)
    {

      $totalPages = (int)($this->total / CoreConfig::PAGINATION_TABLE_MAX_ROWS) + ((($this->total % CoreConfig::PAGINATION_TABLE_MAX_ROWS) > 0) ? 1 : 0);
      if($totalPages > 1)
      {
        $pagination = "<ul>";
        for($id = 1; $id <= $totalPages; $id++)
        {
          $class = ($this->currentPage == $id) ? 'btn-info disabled' : 'btn-default';
          $pagination .= '<input type="submit" class="btn '.$class.'" data-toggle="tooltip" data-placement="bottom" title="Page '.$id.'" name="filterPage" id="filterPage" value="'.$id.'">';
        }
        $pagination .= "</ul>";

      }
    }

    return $pagination;
  }

}

?>