<?php
//all script need to have this include except login.
include("../../header.php");

$wsRequest = new WSRequest($_REQUEST);

try
{
  $userMessage = "Pending Transactions";
  $account = Session::getAccount();
}
catch(Exception $ex)
{
  ExceptionManager::handleException($ex);
  $userMessage = $ex->getMessage();
  $userMessage = '<div class="alert alert-danger">'.$userMessage.'</div>';
}

try
{
  $transactionId = $wsRequest->getParam("transaction_id");
  if($transactionId)
  {
    $manager = new Manager($account);
    $r = $manager->confirm($wsRequest);
    if($r)
    {
      $userMessage = '<div class="alert alert-success">Transaction has been confirmed</div>';
    }
  }
}
catch(Exception $ex)
{
  ExceptionManager::handleException($ex);
  $userMessage = $ex->getMessage();
  $userMessage = '<div class="alert alert-danger">'.$userMessage.'</div>';
}

try
{
  $system = new System();
  $transactions = $system->transactions(Transaction::STATUS_REQUESTED, $account->getAccountId());
}
catch(Exception $ex)
{
  ExceptionManager::handleException($ex);
  $userMessage = $ex->getMessage();
  $userMessage = '<div class="alert alert-danger">'.$userMessage.'</div>';
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
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Amount</th>
                  <th>Username</th>
                  <th>Username</th>
                  <th>Person</th>
                  <th>Agency</th>
                  <th>Type</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach($transactions as $transaction)
                {
                  $id = $transaction['Transaction_Id'];
                  $type = $transaction['TransactionType_Id'];
                  $amount = $transaction['Amount'];
                  $agency = $transaction['Agency'];
                  $agencyType = $transaction['AgencyType'];

                  $customer = $transaction['Username'];
                  $customerName = ucwords(strtolower($transaction['CustomerName']));
                  $personName = ucwords(strtolower($transaction['PersonName']));

                  //format date
                  $createdDate = $transaction['CreatedDate'];
                  $modifiedDate = $transaction['ModifiedDate'];
                  $createdDate = date(Util::FORMAT_DATE_DISPLAY, strtotime($createdDate));
                  $modifiedDate = date(Util::FORMAT_DATE_DISPLAY, strtotime($modifiedDate));
                  $rowType = ($type == Transaction::TYPE_RECEIVER) ? '' : 'warning';

                  echo "<tr class='$rowType'>
														<td>
														  <a class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#myModal$id\">$id</a>
														</td>
														<td>$$amount</td>
														<td>$customer</td>
														<td>$customerName</td>
														<td>$personName</td>
														<td>$agency</td>
														<td>$agencyType</td>
														<td>$modifiedDate</td>
													</tr>";
                }
                ?>
              </tbody>
            </table>

            <?php
            foreach($transactions as $transaction)
            {
              $id = $transaction['Transaction_Id'];
              $statusId = $transaction['TransactionStatus_Id'];
              $transactionTypeId = $transaction['TransactionType_Id'];
              $transactionType = $transaction['TransactionType'];
              $amount = $transaction['Amount'];
              $notes = $transaction['Notes'];
              $controlNumber = $transaction['ControlNumber'];
              $agency = $transaction['Agency'];
              $agencyType = $transaction['AgencyType'];

              $customer = $transaction['Username'];
              $customerName = ucwords(strtolower($transaction['CustomerName']));
              $firstName = ucwords($transaction['FirstName']);
              $lastName = ucwords($transaction['LastName']);
              $phone = $transaction['Phone'];
              $country = $transaction['Country'];
              $countryName = $transaction['CountryName'];
              $state = $transaction['State'];
              $stateName = $transaction['StateName'];

              $personPersonalId = $transaction['PersonPersonalId'];
              $personName = ucwords(strtolower($transaction['PersonName']));
              $personTypeId = ucwords(strtolower($transaction['PersonTypeId']));
              $personExpirationDateId = $transaction['PersonExpirationDateId'];
              $personBirthDate = $transaction['PersonBirthDate'];
              $personMaritalStatus = ucwords($transaction['PersonMaritalStatus']);
              $personGender = ucwords($transaction['PersonGender']);
              $personProfession = ucwords($transaction['PersonProfession']);
              $personPhone = $transaction['PersonPhone'];
              $personAddress = ucwords(strtolower($transaction['PersonAddress']));
              $personCity = ucwords(strtolower($transaction['PersonCity']));
              $personState = $transaction['PersonState'];
              $personCountry = $transaction['PersonCountry'];

              $createdDate = $transaction['CreatedDate'];
              $modifiedDate = $transaction['ModifiedDate'];
              //format date
              $createdDate = date(Util::FORMAT_DATE_DISPLAY, strtotime($createdDate));
              $modifiedDate = date(Util::FORMAT_DATE_DISPLAY, strtotime($modifiedDate));
              $headerType = ($transactionTypeId == Transaction::TYPE_RECEIVER) ? 'modal-header-info' : 'modal-header-warning';
              ?>
              <div class="modal fade" id="myModal<?= $id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">

                    <div class="modal-header <?= $headerType ?>">
                      <h4 class="modal-title" id="myModalLabel">Please review the <?= $transactionType ?> information</h4>
                    </div>

                    <div class="modal-body">
                      <div class="panel-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills">
                          <li class="active">
                            <a href="#tab-process<?= $id ?>" data-toggle="tab" aria-expanded="true">Process</a>
                          </li>
                          <li class="">
                            <a href="#tab-person<?= $id ?>" data-toggle="tab" aria-expanded="false"><?= ($transactionTypeId == Transaction::TYPE_SENDER) ? 'Sender' : 'Receiver' ?></a>
                          </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                          <!-- Tab Process -->
                          <div class="tab-pane fade active in" id="tab-process<?= $id ?>">
                            <form role="form" data-toggle="validator" method="post" id="myForm<?= $id ?>" name="myForm<?= $id ?>">
                              <table class="table">
                                <thead>
                                  <tr>
                                    <th>ID</th>
                                    <th>Agency</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><?= $id ?></td>
                                    <td><?= $agency ?></td>
                                    <td><?= $agencyType ?></td>
                                    <td><?= $modifiedDate ?></td>
                                  </tr>
                                </tbody>
                              </table>

                              <input type="hidden" id="transaction_id" name="transaction_id" value="<?= $id ?>">
                              <input type="hidden" id="country" name="country" value="<?= $country ?>">
                              <input type="hidden" id="state" name="state" value="<?= $state ?>">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-6">
                                    <label>Amount ($USD)</label>
                                    <input class="form-control input-sm" type="number" id="amount" name="amount" step="any" min="1" value="<?= $amount ?>" required>
                                  </div>
                                  <div class="col-lg-6">
                                    <label>Control Number</label>
                                    <input class="form-control input-sm" type="text" id="control_number" name="control_number" value="<?= $controlNumber ?>" minlength="8" maxlength="11" pattern="<?= Util::REGEX_NUMERIC ?>" required>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <label>Username (Customer)</label>
                                <input class="form-control input-sm" type="text" id="uid" name="uid" value="<?= $customer ?>" required>
                              </div>
                              <div class="form-group">
                                <label>First Name</label>
                                <input class="form-control input-sm" type="text" id="first_name" name="first_name" value="<?= $firstName ?>" required>
                              </div>
                              <div class="form-group">
                                <label>Last Name</label>
                                <input class="form-control input-sm" type="text" id="last_name" name="last_name" value="<?= $lastName ?>" required>
                              </div>
                              <div class="form-group">
                                <label>Phone</label>
                                <input class="form-control input-sm" type="text" id="phone" name="phone" value="<?= $phone ?>" required>
                              </div>
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-6">
                                    <label>Country</label>
                                    <input class="form-control input-sm" type="text" id="countryName" name="countryName" value="<?= $countryName ?>" readonly>
                                  </div>
                                  <div class="col-lg-6">
                                    <label>State</label>
                                    <input class="form-control input-sm" type="text" id="stateName" name="stateName" value="<?= $stateName ?>" readonly>
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <?php if($account->checkPermission('BOARD_PENDING_SAVE'))
                                { ?>
                                  <button type="submit" class="btn btn-danger">Confirm MTCN</button>
                                <?php } ?>
                              </div>
                            </form>
                          </div>
                          <!-- Tab Sender/Receiver -->
                          <div class="tab-pane fade" id="tab-person<?= $id ?>">
                            <br/>
                            <table class="table">
                              <tbody>
                                <tr>
                                  <td><?= $transactionType ?></td>
                                  <td><?= $personName ?></td>
                                </tr>
                                <tr>
                                  <td>Type Id</td>
                                  <td><?= $personTypeId ?></td>
                                </tr>
                                <tr>
                                  <td>Personal Id</td>
                                  <td><?= $personPersonalId ?></td>
                                </tr>
                                <tr>
                                  <td>Expiration Date Id</td>
                                  <td><?= $personExpirationDateId ?></td>
                                </tr>
                                <tr>
                                  <td>Birth Date</td>
                                  <td><?= $personBirthDate ?></td>
                                </tr>
                                <tr>
                                  <td>Marital Status</td>
                                  <td><?= $personMaritalStatus ?></td>
                                </tr>
                                <tr>
                                  <td>Gender</td>
                                  <td><?= $personGender ?></td>
                                </tr>
                                <tr>
                                  <td>Profession</td>
                                  <td><?= $personProfession ?></td>
                                </tr>
                                <tr>
                                  <td>Phone</td>
                                  <td><?= $personPhone ?></td>
                                </tr>
                                <tr>
                                  <td>Address</td>
                                  <td><?= $personAddress ?></td>
                                </tr>
                                <tr>
                                  <td>City</td>
                                  <td><?= $personCity ?></td>
                                </tr>
                                <tr>
                                  <td>Country/State</td>
                                  <td><?= $personCountry.', '.$personState ?></td>
                                </tr>
                              </tbody>
                            </table>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                          </div>

                        </div>
                      </div>

                    </div>

                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <?php
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<?php include("../../footer.php"); ?>
