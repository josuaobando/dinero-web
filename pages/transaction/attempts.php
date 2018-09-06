<?php
//all script need to have this include except login.
include("../../header.php");

$wsRequest = new WSRequest($_REQUEST);

$totalAttempt = 0;
$userMessage = "Attempts Transactions";

try{
  $system = new System();
  $attempts = $system->transactionAttempts();
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
                <th>Date</th>
                <th>Customer</th>
                <th>Account</th>
                <th>First</th>
                <th>Last</th>
                <th>Attempts</th>
                <th>Amount</th>
                <th>Type</th>
                <th>ErrorMessage</th>
              </tr>
              </thead>
              <tbody>
              <?php
              foreach($attempts as $attempt){
                $type = $attempt['Type'];
                $typeId = $attempt['TypeId'];
                $attemptTrans = $attempt['Attempt'];
                $totalAttempt = $totalAttempt + $attemptTrans;
                $amounts = $attempt['Amount'];
                $customer = ucwords(strtolower($attempt['Customer']));
                $username = $attempt['Account'];
                $message = ucwords(strtolower($attempt['ErrorMessage']));
                //format date
                $createdDate = $attempt['Date'];
                $firstDate = $attempt['First'];
                $lastDate = $attempt['Last'];

                $createdDate = date('d F', strtotime($createdDate));
                $firstDate = date(Util::FORMAT_DATE_DISPLAY, strtotime($firstDate));
                $lastDate = date(Util::FORMAT_DATE_DISPLAY, strtotime($lastDate));

                $rowType = '';
                if($typeId == 1){
                  $rowType = 'danger';
                }elseif($typeId == 2){
                  $rowType = 'active';
                }elseif($typeId == 3){
                  $rowType = 'info';
                }

                echo "<tr class='$rowType'>
														<td>$createdDate</td>
														<td>$customer</td>
														<td>$username</td>
														<td>$firstDate</td>
														<td>$lastDate</td>
														<td>$attemptTrans</td>
														<td>$amounts</td>
														<td>$type</td>
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
      if($attempts && count($attempts) > 0){
      ?>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover wrap-table">
              <thead>
              <tr>
                <th>Unique</th>
                <th>Total</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td><?php echo count($attempts); ?></td>
                <td><?php echo $totalAttempt; ?></td>
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
