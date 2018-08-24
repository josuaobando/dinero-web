<?php
//all script need to have this include except login.
include("../../header.php");

$wsRequest = new WSRequest($_REQUEST);

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
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover wrap-table">
              <thead>
              <tr>
                <th>Type</th>
                <th>Date</th>
                <th>Attempt</th>
                <th>First</th>
                <th>Last</th>
                <th>Amount</th>
                <th>Customer</th>
                <th>Account</th>
                <th>ErrorMessage</th>
              </tr>
              </thead>
              <tbody>
              <?php
              foreach($attempts as $attempt){
                $type = $attempt['Type'];
                $attemptTrans = $attempt['Attempt'];
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
                if($type == 'P2P Controller'){
                  $rowType = 'active';
                }elseif($type == 'Saturno Dep'){
                  $rowType = 'danger';
                }elseif($type == 'Saturno Pay'){
                  $rowType = 'info';
                }

                echo "<tr class='$rowType'>
														<td>$type</td>
														<td>$createdDate</td>
														<td>$attemptTrans</td>
														<td>$firstDate</td>
														<td>$lastDate</td>
														<td>$amounts</td>
														<td>$customer</td>
														<td>$username</td>
														<td>$message</td>
													</tr>";
              }
              ?>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<?php include("../../footer.php"); ?>
