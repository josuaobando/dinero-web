<?php
//all script need to have this include except login.
include("../../header.php");

$wsRequest = new WSRequest($_REQUEST);

try{
  $userMessage = "Processing Transactions";
  $account = Session::getAccount();

  $transactionId = $wsRequest->getParam("transactionId");
  if($transactionId){
    $manager = new Manager($account);
    $update = $manager->transactionUpdate($wsRequest);
    if($update){
      $userMessage = '<div class="alert alert-success">Transaction processed successfully</div>';
    }
  }

  $system = new System();
  $transactions = $system->transactions(Transaction::STATUS_SUBMITTED, $account->getAccountId());
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
            <table class="table table-striped table-bordered table-hover">
              <thead>
              <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Fee</th>
                <th>Username</th>
                <th>Customer</th>
                <th>Person</th>
                <th>MTCN</th>
                <th>Agency</th>
                <th>Type</th>
                <th>Modified</th>
              </tr>
              </thead>
              <tbody>
              <?php
              foreach($transactions as $transaction){
                $id = $transaction['Transaction_Id'];
                $type = $transaction['TransactionType_Id'];
                $customer = $transaction['Username'];
                $customerName = ucwords(strtolower($transaction['CustomerName']));
                $amount = $transaction['Amount'];
                $fee = $transaction['Fee'];
                $personName = ucwords(strtolower($transaction['PersonName']));
                $controlNumber = $transaction['ControlNumber'];
                $agency = $transaction['Agency'];
                $agencyType = $transaction['AgencyType'];
                $modifiedDate = $transaction['ModifiedDate'];
                //format date
                $modifiedDate = date(Util::FORMAT_DATE_DISPLAY, strtotime($modifiedDate));
                $rowType = ($type == Transaction::TYPE_RECEIVER) ? '' : 'warning';

                echo "<tr class='$rowType'>
														<td>
														  <a title='Open' class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#myModal$id\">$id</a>
														</td>
														<td>$$amount</td>
														<td>$$fee</td>
														<td>$customer</td>
														<td>$customerName</td>
														<td>$personName</td>
														<td>$controlNumber</td>
														<td>$agency</td>
														<td>$agencyType</td>
														<td>$modifiedDate</td>
													</tr>";
              }
              ?>
              </tbody>
            </table>

            <!-- SUMMARY -->
            <?php
            if($transactions && count($transactions) > 0){
              ?>
              <div class="panel panel-default">
                <div class="panel-heading"><strong>Total: <?php echo count($transactions); ?></strong></div>
              </div>
              <?php
            }
            ?>
            <!-- END SUMMARY -->

            <!-- MODAL -->
            <?php
            foreach($transactions as $transaction){
              $id = $transaction['Transaction_Id'];
              $apiTransactionId = $transaction['ApiTransactionId'];
              $statusId = $transaction['TransactionStatus_Id'];
              $transactionTypeId = $transaction['TransactionType_Id'];
              $transactionType = $transaction['TransactionType'];
              $amount = $transaction['Amount'];
              $reason = $transaction['Reason'];
              $note = $transaction['Note'];
              $controlNumber = $transaction['ControlNumber'];
              $agency = $transaction['Agency'];
              $agencyType = $transaction['AgencyType'];
              $agencyId = $transaction['Agency_Id'];

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
              <div class="modal fade" id="myModal<?= $id ?>" tabindex="-1" role="dialog"
                   aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">

                    <div class="modal-header <?= $headerType ?>">
                      <h4 class="modal-title" id="myModalLabel">Please review
                        the <?= $transactionType ?> information</h4>
                    </div>
                    <div class="modal-body">

                      <div class="panel-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills">
                          <li class="active">
                            <a href="#tab-process<?= $id ?>" data-toggle="tab"
                               aria-expanded="true">Process</a>
                          </li>
                          <li class="">
                            <a href="#tab-customer<?= $id ?>" data-toggle="tab"
                               aria-expanded="false"><?= ($transactionTypeId == Transaction::TYPE_RECEIVER) ? 'Sender' : 'Receiver' ?></a>
                          </li>
                          <li class="">
                            <a href="#tab-person<?= $id ?>" data-toggle="tab"
                               aria-expanded="false"><?= ($transactionTypeId == Transaction::TYPE_SENDER) ? 'Sender' : 'Receiver' ?></a>
                          </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                          <!-- Tab Process -->
                          <div class="tab-pane fade active in" id="tab-process<?= $id ?>">
                            <br/>
                            <form role="form" data-toggle="validator" method="post"
                                  id="myForm<?= $id ?>" name="myForm<?= $id ?>"
                                  action="processing">
                              <input type="hidden" id="transactionId" name="transactionId"
                                     value="<?= $id ?>">
                              <input type="hidden" id="transactionTypeId"
                                     name="transactionTypeId"
                                     value="<?= $transactionTypeId ?>">

                              <div class="form-group">
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-lg-4">
                                      <label>Amount ($USD)</label>
                                      <?php if($account->checkPermission('BOARD_PROCESSING_CHANGE_AMOUNT')){ ?>
                                        <input class="form-control input-sm" type="number"
                                               step="any" min="1" id="amount" name="amount"
                                               value="<?= $amount ?>" required>
                                      <?php }else{ ?>
                                        <input class="form-control input-sm disabled"
                                               type="number" id="amount" name="amount"
                                               value="<?= $amount ?>">
                                      <?php } ?>
                                    </div>
                                    <div class="col-lg-4">
                                      <label>Fee ($USD)</label>
                                      <?php if($account->checkPermission('BOARD_PROCESSING_CHANGE_FEE')){ ?>
                                        <input class="form-control input-sm" type="number"
                                               step="any" min="0" id="fee" name="fee"
                                               value="<?= $fee ?>" required>
                                      <?php }else{ ?>
                                        <input class="form-control input-sm disabled"
                                               type="number" id="fee" name="fee"
                                               value="<?= $fee ?>">
                                      <?php } ?>
                                    </div>
                                    <div class="col-lg-4">
                                      <label>Control Number</label>
                                      <input class="form-control input-sm" type="text"
                                             id="controlNumber" name="controlNumber"
                                             value="<?= $controlNumber ?>" minlength="8"
                                             maxlength="11"
                                             pattern="<?= Util::REGEX_NUMERIC ?>" <?php if($transactionTypeId == Transaction::TYPE_RECEIVER){
                                        echo 'required';
                                      } ?>>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <!-- Status -->
                                  <label>Status</label>
                                  <select class="form-control input-sm" id="status"
                                          name="status" required>
                                    <option value="">Please Select Status</option>
                                    <option value="3" <?php if($statusId == 3){
                                      echo 'selected="selected"';
                                    } ?>>Approved
                                    </option>
                                    <option value="4" <?php if($statusId == 4){
                                      echo 'selected="selected"';
                                    } ?>>Rejected
                                    </option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group">
                                <label>Reason (Only Numbers and Letters)</label>
                                <input class="form-control input-sm" type="text"
                                       id="reason" name="reason"
                                       pattern="<?= Util::REGEX_ALPHANIMERIC ?>" required>
                              </div>
                              <?php if($account->checkPermission('BOARD_PROCESSING_NOTE')){ ?>
                                <div class="form-group">
                                  <label>Note</label>
                                  <input class="form-control input-sm" type="text"
                                         id="note" name="note"
                                         pattern="<?= Util::REGEX_ALPHANIMERIC ?>">
                                </div>
                              <?php } ?>
                              <div class="btn-group pull-right">
                                <?php if($agencyId == CoreConfig::AGENCY_ID_SATURNO){ ?>
                                  <button type="button" class="btn btn-info" id="btnCheckStatus<?= $id ?>"
                                          onclick="getStatus(<?= $id ?>)">Check Status
                                  </button>
                                <?php } ?>
                                <?php if($account->checkPermission('BOARD_PROCESSING_SAVE')){ ?>
                                  <button type="submit" class="btn btn-danger" id="btnSave<?= $id ?>">Save changes
                                  </button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </form>
                          </div>
                          <!-- Tab Sender -->
                          <div class="tab-pane fade" id="tab-customer<?= $id ?>">
                            <br/>
                            <table class="table">
                              <tbody>
                              <tr>
                                <td>Transaction ID</td>
                                <td><?= $id ?></td>
                              </tr>
                              <?php if($apiTransactionId){ ?>
                                <tr>
                                  <td>API ID</td>
                                  <td><?= $apiTransactionId ?></td>
                                </tr>
                              <?php } ?>
                              <tr>
                                <td>Amount</td>
                                <td>$<?= $amount ?></td>
                              </tr>
                              <tr>
                                <td>Fee</td>
                                <td>$<?= $fee ?></td>
                              </tr>
                              <tr>
                                <td>Username</td>
                                <td><?= $customer ?></td>
                              </tr>
                              <tr>
                                <td>Customer</td>
                                <td><?= $customerName ?></td>
                              </tr>
                              <tr>
                                <td>State</td>
                                <td><?= $stateName ?></td>
                              </tr>
                              <tr>
                                <td>Country</td>
                                <td><?= $countryName ?></td>
                              </tr>
                              <tr>
                                <td>Control Number</td>
                                <td><?= $controlNumber ?></td>
                              </tr>
                              <tr>
                                <td>Agency</td>
                                <td><?= $agency ?></td>
                              </tr>
                              <tr>
                                <td>Type</td>
                                <td><?= $agencyType ?></td>
                              </tr>
                              <tr>
                                <td>Created Date</td>
                                <td><?= $createdDate ?></td>
                              </tr>
                              <tr>
                                <td>Modified Date</td>
                                <td><?= $modifiedDate ?></td>
                              </tr>
                              </tbody>
                            </table>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-default"
                                      data-dismiss="modal">Close
                              </button>
                            </div>
                          </div>
                          <!-- Tab Receiver -->
                          <div class="tab-pane fade" id="tab-person<?= $id ?>">
                            <br/>
                            <table class="table">
                              <tbody>
                              <tr>
                                <td><?= $transactionType ?></td>
                                <td id="personName<?= $id ?>"><?= $personName ?></td>
                              </tr>
                              <tr>
                                <td>Type Id</td>
                                <td id="typeId<?= $id ?>"><?= $personTypeId ?></td>
                              </tr>
                              <tr>
                                <td>Personal Id</td>
                                <td id="personalId<?= $id ?>"><?= $personPersonalId ?></td>
                              </tr>
                              <tr>
                                <td>Expiration Date Id</td>
                                <td id="expirationDateId<?= $id ?>"><?= $personExpirationDateId ?></td>
                              </tr>
                              <tr>
                                <td>Birth Date</td>
                                <td id="birthDate<?= $id ?>"><?= $personBirthDate ?></td>
                              </tr>
                              <tr>
                                <td>Marital Status</td>
                                <td id="maritalStatus<?= $id ?>"><?= $personMaritalStatus ?></td>
                              </tr>
                              <tr>
                                <td>Gender</td>
                                <td id="gender<?= $id ?>"><?= $personGender ?></td>
                              </tr>
                              <tr>
                                <td>Profession</td>
                                <td id="profession<?= $id ?>"><?= $personProfession ?></td>
                              </tr>
                              <tr>
                                <td>Phone</td>
                                <td id="phone<?= $id ?>"><?= $personPhone ?></td>
                              </tr>
                              <tr>
                                <td>Address</td>
                                <td id="address<?= $id ?>"><?= $personAddress ?></td>
                              </tr>
                              <tr>
                                <td>City</td>
                                <td id="city<?= $id ?>"><?= $personCity ?></td>
                              </tr>
                              <tr>
                                <td>Country/State</td>
                                <td id="location<?= $id ?>"><?= $personCountry . ', ' . $personState ?></td>
                              </tr>
                              </tbody>
                            </table>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-default"
                                      data-dismiss="modal">Close
                              </button>
                              <?php if($transactionTypeId == Transaction::TYPE_SENDER && $account->checkPermission('BOARD_PROCESSING_TRANSACTION_GET_NEW_PERSON')){ ?>
                                <button type="button" id="btnNewPerson<?= $id ?>"
                                        class="btn btn-danger"
                                        onclick="getNewPerson(<?= $id ?>)">Get
                                  New <?= $transactionType ?></button>
                              <?php } ?>
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
            <!-- END MODAL -->

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<?php include("../../footer.php"); ?>
