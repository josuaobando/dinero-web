<?php
//all script need to have this include except login.
include("../../header.php");

$wsRequest = new WSRequest($_REQUEST);

$totalAttempt = 0;
$userMessage = "Declined Transactions";

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
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover wrap-table">
              <thead>
              <tr>
                <th>Agency</th>
                <th>Type</th>
                <th>Timestamp</th>
                <th>Timespan</th>
                <th>Amount</th>
                <th>Account</th>
                <th>Customer</th>
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
														<td>$agencyType</td>
														<td>$requestType</td>
														<td>$timeStamp</td>
														<td>$timeSpan</td>
														<td>$$amount</td>
														<td>$username</td>
														<td>$customer</td>
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
      <!-- END TABLE -->

      <!-- SUMMARY -->
      <?php
      if($transactions && count($transactions) > 0){
      ?>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover wrap-table">
              <thead>
              <tr>
                <th>Total</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td><?php echo count($transactions); ?></td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php
    }
    ?>
    <!-- END SUMMARY -->

  </div>
</div>
</div>

<!-- FOOTER -->
<?php include("../../footer.php"); ?>
