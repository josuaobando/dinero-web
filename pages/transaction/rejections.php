<?php
//all script need to have this include except login.
include("../../header.php");

$wsRequest = new WSRequest($_REQUEST);

$totalAttempt = 0;
$userMessage = "Declined Attempts";

try{
  $system = new System();
  $transactions = $system->transactionDeclined();
}catch(Exception $ex){
  ExceptionManager::handleException($ex);
  $userMessage = $ex->getMessage();
  $userMessage = '<div class="alert alert-danger">' . $userMessage . '</div>';
}
?>
<div id="page-wrapper">

  <div class="row">
    <div class="col-lg-12">
      <h3 class="page-header"><?= $userMessage ?></h3>
    </div>
  </div>

  <!-- /.row -->
  <div class="row">
    <div class="col-lg-12">

      <!-- TABLE -->
      <?php
      if($transactions && count($transactions) > 0){
      ?>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover wrap-table">
              <thead>
              <tr>
                <th>Timestamp</th>
                <th>Timespan</th>
                <th>Type</th>
                <th>Agency</th>
                <th>Amount</th>
                <th>Account</th>
                <th>Customer</th>
                <th>Id</th>
                <th>Control Number</th>
                <th>Error</th>
                <th>Message</th>
              </tr>
              </thead>
              <tbody>
              <?php
              foreach($transactions as $transaction){
                $type = $transaction['Type'];
                $agencyType = $transaction['AgencyType'];
                $requestType = $transaction['RequestType'];
                $timeStamp = $transaction['Timestamp'];
                $timeSpan = $transaction['Timespan'];
                $amount = $transaction['Amount'];
                $username = $transaction['Account'];
                $controlNumber = $transaction['ControlNumber'];
                $transactionId = $transaction['TransactionId'];
                $customer = ucwords(strtolower($transaction['Customer']));
                //format date
                $error = $transaction['Error'];
                $message = $transaction['Message'];

                $timeStamp = date(Util::FORMAT_DATE_DISPLAY, strtotime($timeStamp));

                $rowType = '';
                if($type == 'confirm'){
                  $rowType = 'danger';
                }

                echo "<tr class='$rowType'>
														<td>$timeStamp</td>
														<td>$timeSpan</td>
														<td>$requestType</td>
														<td>$agencyType</td>
														<td>$$amount</td>
														<td>$username</td>
														<td>$customer</td>
														<td>$transactionId</td>
														<td>$controlNumber</td>
														<td>$error</td>
														<td>$message</td>
													</tr>";
              }
              ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
        <?php
      }
      ?>
      <!-- END TABLE -->

      <!-- SUMMARY -->
      <?php
      if($transactions && count($transactions) > 0){
        ?>
        <div class="panel panel-default">
          <div class="panel-body">
            <strong>Total: <?php echo count($transactions); ?></strong>
          </div>
        </div>
        <?php
      }
      ?>
      <!-- END SUMMARY -->

      <!-- Error List -->
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover wrap-table">
              <thead>
              <tr>
                <th>Error Code</th>
                <th>Description</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td><?php echo RequestException::ERROR_TRANSACTION; ?></td>
                <td>General. E.g.: Invalid transaction Information, unmapped error</td>
              </tr>
              <tr>
                <td><?php echo RequestException::ERROR_CUSTOMER; ?></td>
                <td>Invalid customer information</td>
              </tr>
              <tr>
                <td><?php echo RequestException::ERROR_CUSTOMER_BLACKLIST; ?></td>
                <td>Customer is blacklisted</td>
              </tr>
              <tr>
                <td><?php echo RequestException::ERROR_PERSON; ?></td>
                <td>No names available</td>
              </tr>
              <tr>
                <td><?php echo RequestException::ERROR_LIMIT; ?></td>
                <td>Limits (min, max, reached)</td>
              </tr>
              <tr>
                <td><?php echo RequestException::ERROR_P2P; ?></td>
                <td>External filter validations</td>
              </tr>
              <tr>
                <td><?php echo RequestException::ERROR_API; ?></td>
                <td>Unassigned provider message</td>
              </tr>
              <tr>
                <td><?php echo RequestException::ERROR_API_BLACKLIST; ?></td>
                <td>Customer on the provider blacklist</td>
              </tr>
              <tr>
                <td><?php echo RequestException::ERROR_API_PERSON; ?></td>
                <td>No names available on the provider</td>
              </tr>
              <tr>
                <td><?php echo RequestException::ERROR_API_LIMIT; ?></td>
                <td>Limits reached on the provider</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- Error List -->
  </div>
</div>
<!-- FOOTER -->
<?php include("../../footer.php"); ?>
