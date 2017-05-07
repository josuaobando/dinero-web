<?php
include("../../header.php");

$wsRequest = new WSRequest($_REQUEST);

try
{
  $userMessage = "Search Transactions";
  $account = Session::getAccount();

  $system = new System($account);
  $transactionStatus = $system->transactionStatus();
}
catch(Exception $ex)
{
  ExceptionManager::handleException($ex);
  $userMessage = $ex->getMessage();
  $userMessage = '<div class="alert alert-danger">'.$userMessage.'</div>';
}

try
{
  // Change Status to Transaction
  $transactionId = $wsRequest->getParam("transactionId");
  if($transactionId)
  {
    $manager = new Manager($account);
    $r = $manager->transactionUpdate($wsRequest);
    if($r)
    {
      $userMessage = '<div class="alert alert-success">Transaction has been Updated</div>';
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
  $report = new Report($wsRequest, $account);

  $filterStatus = $wsRequest->getParam("filterStatus", "3");
  $filterType = $wsRequest->getParam("filterType", "0");
  $filterAgencyType = $wsRequest->getParam("filterAgencyType", "0");

  $filterBeginDate = $wsRequest->getParam("filterBeginDate", "");
  $filterEndDate = $wsRequest->getParam("filterEndDate", "");
  $filterMTCN = $wsRequest->getParam("filterMTCN", "");
  $filterUsername = $wsRequest->getParam("filterUsername", "");
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
            <form role="form" data-toggle="validator" method="post" name="searchForm" action="search">
              <!-- Optional filters -->
              <div class="row">
                <div class="col-sm-4">
                  <select name="filterStatus" id="filteredStatus" class="input-sm form-control">
                    <option value="-1" <?php echo $filterStatus == '-1' ? 'selected' : ''; ?>>Transaction Status</option>
                    <?php
                    foreach($transactionStatus as $tStatus)
                    {
                      $id = $tStatus['itemId'];
                      $value = $tStatus['itemValue'];
                      if($filterStatus == $id)
                      {
                        echo "<option value='$id' selected>$value</option>";
                      }
                      else
                      {
                        echo "<option value='$id'>$value</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
                <div class="col-sm-4">
                  <select name="filterType" id="filterType" class="input-sm form-control">
                    <option value="0" <?php echo $filterType == '0' ? 'selected' : ''; ?>>Transaction Type</option>
                    <option value="1" <?= ($filterType == Transaction::TYPE_RECEIVER) ? 'selected' : '' ?>>Receiver</option>
                    <option value="2" <?= ($filterType == Transaction::TYPE_SENDER) ? 'selected' : '' ?>>Sender</option>
                  </select>
                </div>
                <div class="col-sm-4">
                  <select class="form-control input-sm" name="filterAgencyType" id="filterAgencyType" required>
                    <option value="0" <?php echo $filterAgencyType == '0' ? 'selected' : ''; ?>>Agency Type</option>
                    <option value="2" <?php echo $filterAgencyType == '2' ? 'selected' : ''; ?>>MoneyGram</option>
                    <option value="3" <?php echo $filterAgencyType == '3' ? 'selected' : ''; ?>>Ria</option>
                  </select>
                </div>
              </div>

              <!-- Specific filters -->
              <div class="row">
                <div class="col-sm-4" id="dateRange">
                  <div class="input-daterange input-group" id="datepicker">
                    <input class="input-sm form-control" type="text" name="filterBeginDate" id="filterBeginDate" placeholder="Begin Date" readonly value="<?= $filterBeginDate ?>"/>
                    <span class="input-group-addon"></span>
                    <input class="input-sm form-control" type="text" name="filterEndDate" id="filterEndDate" placeholder="End Date" readonly value="<?= $filterEndDate ?>"/>
                  </div>
                </div>
                <div class="col-sm-4">
                  <input class="input-sm form-control" type=text id="filterUsername" name="filterUsername" placeholder="Username" value="<?= $filterUsername ?>" pattern="<?= Util::REGEX_ALPHANIMERIC ?>">
                </div>
                <div class="col-sm-4">
                  <input class="input-sm form-control" type="text" id="filterMTCN" name="filterMTCN" placeholder="Control Number" value="<?= $filterMTCN ?>" minlength="8" maxlength="10" pattern="<?= Util::REGEX_NUMERIC ?>">
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <button name="btnSearch" type="submit" tabindex="11" value="true" class="btn btn-success">Search transactions</button>
                </div>
              </div>

              <br/>

              <!-- TABLE TRANSACTIONS -->
              <div class="panel panel-default">
                <div class="panel-heading">Search Result</div>
                <div class="panel-body">

                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover wrap-table">
                      <?php echo $report->getReportTable(); ?>
                    </table>
                  </div>
                </div>
                <!-- PAGINATION TABLE TRANSACTIONS -->
                <?php
                if($report->getTotal() > CoreConfig::PAGINATION_TABLE_MAX_ROWS)
                {
                  ?>
                  <div class="panel-footer">
                    <div class="btn-group" role="group">
                      <?php echo $report->getPaginationTable(); ?>
                    </div>
                  </div>
                  <?php
                }
                ?>
                <!-- PAGINATION TABLE TRANSACTIONS -->
              </div>
              <!-- END TABLE TRANSACTIONS -->
            </form>

            <!-- SUMMARY -->
            <?php
            if($report->getTotal())
            {
              ?>
              <div class="panel panel-default">
                <div class="panel-heading">Summary</div>
                <div class="panel-body">
                  <!-- Table -->
                  <div class="table-responsive">
                    <table class="table table-hover table-condensed">
                      <?php echo $report->getReportSummaryTable(); ?>
                    </table>
                  </div>
                </div>
              </div>
              <?php
            }
            ?>
            <!-- END SUMMARY -->

            <!-- EXPORT -->
            <?php
            if($report->getTotal() && $account->checkPermission('REPORT_EXPORT'))
            {
              ?>
              <div>
                <div class="row">
                  <div class="col-lg-12">
                    <input type="button" class="btn btn-default" value="Export" onclick="exportReport()">
                  </div>
                </div>
              </div>
              <?php
            }
            ?>
            <!-- END EXPORT -->

            <!-- MODAL -->
            <div>
              <?php
              if($report->getTransactions())
              {
                foreach($report->getTransactions() as $transaction)
                {
                  $id = $transaction['Transaction_Id'];
                  $statusId = $transaction['TransactionStatus_Id'];
                  $transactionTypeId = $transaction['TransactionType_Id'];
                  $transactionType = $transaction['TransactionType'];
                  $amount = $transaction['Amount'];
                  $fee = $transaction['Fee'];
                  $reason = $transaction['Reason'];
                  $note = $transaction['Note'];
                  $controlNumber = $transaction['ControlNumber'];
                  $agency = $transaction['Agency'];
                  $agencyType = $transaction['AgencyType'];

                  $customer = $transaction['Customer'];
                  $customerName = ucwords(strtolower($transaction['CustomerName']));
                  $country = $transaction['Country'];
                  $state = $transaction['State'];

                  $personPersonalId = $transaction['PersonPersonalId'];
                  $personName = ucwords(strtolower($transaction['PersonName']));
                  $personTypeId = ucwords(strtolower($transaction['PersonTypeId']));
                  $personExpirationDateId = $transaction['PersonExpirationDateId'];
                  $personBirthDate = $transaction['PersonBirthDate'];
                  $personMaritalStatus = ucwords($transaction['PersonMaritalStatus']);
                  $personGender = $transaction['PersonGender'];
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

                  $disabled = "";
                  $readonly = "";
                  if($statusId != Transaction::STATUS_APPROVED && $statusId != Transaction::STATUS_REJECTED)
                  {
                    $disabled = 'disabled';
                    $readonly = 'readonly';
                  }
                  ?>
                  <div class="modal fade" id="myModal<?= $id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                      <div class="modal-content">

                        <div class="modal-header <?= $headerType ?>">
                          <h4 class="modal-title" id="myModalLabel"><?= $transactionType ?> Transaction Details</h4>
                        </div>

                        <div class="modal-body">
                          <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills">
                              <?php if($account->checkPermission('TRANSACTION_UPDATE'))
                              { ?>
                                <li class="active">
                                  <a href="#tab-process<?= $id ?>" data-toggle="tab" aria-expanded="true">Re-Process</a>
                                </li>
                                <li class="">
                                  <a href="#tab-customer<?= $id ?>" data-toggle="tab" aria-expanded="false"><?= ($transactionTypeId == Transaction::TYPE_RECEIVER) ? 'Sender' : 'Receiver' ?></a>
                                </li>
                              <?php }
                              else
                              { ?>
                                <li class="active">
                                  <a href="#tab-customer<?= $id ?>" data-toggle="tab" aria-expanded="true"><?= ($transactionTypeId == Transaction::TYPE_RECEIVER) ? 'Sender' : 'Receiver' ?></a>
                                </li>
                              <?php } ?>
                              <li class="">
                                <a href="#tab-person<?= $id ?>" data-toggle="tab" aria-expanded="false"><?= ($transactionTypeId == Transaction::TYPE_SENDER) ? 'Sender' : 'Receiver' ?></a>
                              </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                              <!-- Tab Re-Process -->
                              <?php if($account->checkPermission('TRANSACTION_UPDATE'))
                              { ?>
                                <div class="tab-pane fade active in" id="tab-process<?= $id ?>">
                                  <br/>
                                  <form role="form" data-toggle="validator" method="post" id="myForm<?= $id ?>" name="myForm<?= $id ?>" action="search">
                                    <input type="hidden" id="filterStatus" name="filterStatus" value="<?= $filterStatus ?>">
                                    <input type="hidden" id="filterType" name="filterType" value="<?= $filterType ?>">
                                    <input type="hidden" id="filterAgencyType" name="filterAgencyType" value="<?= $filterAgencyType ?>">
                                    <input type="hidden" id="filterBeginDate" name="filterBeginDate" value="<?= $filterBeginDate ?>">
                                    <input type="hidden" id="filterEndDate" name="filterEndDate" value="<?= $filterEndDate ?>">
                                    <input type="hidden" id="filterUsername" name="filterUsername" value="<?= $filterUsername ?>">
                                    <input type="hidden" id="filterMTCN" name="filterMTCN" value="<?= $filterMTCN ?>">
                                    <input type="hidden" id="transactionId" name="transactionId" value="<?= $id ?>">
                                    <input type="hidden" id="transactionTypeId" name="transactionTypeId" value="<?= $transactionTypeId ?>">

                                    <div class="form-group">
                                      <div class="form-group">
                                        <label>Amount ($USD)</label>
                                        <input class="form-control input-sm <?= $disabled ?>" type="number" step="any" min="1" id="amount" name="amount" value="<?= $amount ?>" required>
                                      </div>
                                      <div class="form-group">
                                        <label>Fee ($USD)</label>
                                        <input class="form-control input-sm <?= $disabled ?>" type="number" step="any" min="0" id="fee" name="fee" value="<?= $fee ?>" required>
                                      </div>
                                      <div class="form-group">
                                        <!-- Status -->
                                        <label>Status</label>
                                        <select class="form-control input-sm <?= $disabled ?>" id="status" name="status" required>
                                          <?php
                                          foreach($transactionStatus as $tStatus)
                                          {
                                            $itemId = $tStatus['itemId'];
                                            $value = $tStatus['itemValue'];
                                            if($statusId == $itemId)
                                            {
                                              echo "<option value='$itemId' selected>$value</option>";
                                            }
                                            else
                                            {
                                              echo "<option value='$itemId'>$value</option>";
                                            }
                                          }
                                          ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label>Control Number</label>
                                      <input class="form-control input-sm <?= $disabled ?>" type="text" id="controlNumber" name="controlNumber" value="<?= $controlNumber ?>" minlength="8" maxlength="11" pattern="<?= Util::REGEX_NUMERIC ?>" <?php if($transactionTypeId == Transaction::TYPE_RECEIVER)
                                      {
                                        echo 'required';
                                      } ?>>
                                    </div>
                                    <div class="form-group">
                                      <label>Reason (Only Numbers and Letters)</label>
                                      <input class="form-control input-sm" type="text" id="reason" name="reason" value="<?= $reason ?>" pattern="<?= Util::REGEX_ALPHANIMERIC ?>" required <?= $readonly ?>>
                                    </div>
                                    <div class="form-group">
                                      <label>Note</label>
                                      <input class="form-control input-sm" type="text" id="note" name="note" value="<?= $note ?>" pattern="<?= Util::REGEX_ALPHANIMERIC ?>">
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                      <?php if($account->checkPermission('REPORT_TRANSACTION_SAVE'))
                                      { ?>
                                        <button type="submit" class="btn btn-danger <?= $disabled ?>">Save changes</button>
                                      <?php } ?>
                                    </div>
                                  </form>
                                </div>
                              <?php } ?>
                              <!-- Tab Customer -->
                              <div class="tab-pane fade <?= (!$account->checkPermission("TRANSACTION_UPDATE") ? 'active in' : '') ?>" id="tab-customer<?= $id ?>">
                                <br/>
                                <table class="table">
                                  <tbody>
                                    <tr>
                                      <td>Transaction ID</td>
                                      <td><?= $id ?></td>
                                    </tr>
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
                                      <td><?= $state ?></td>
                                    </tr>
                                    <tr>
                                      <td>Country</td>
                                      <td><?= $country ?></td>
                                    </tr>
                                    <tr>
                                      <td>Control Number</td>
                                      <td><?= $controlNumber ?></td>
                                    </tr>
                                    <?php if($account->checkPermission('REPORT_TRANSACTION_VIEW_AGENCY'))
                                    { ?>
                                      <tr>
                                        <td>Agency</td>
                                        <td><?= $agency ?></td>
                                      </tr>
                                    <?php } ?>
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
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                              <!-- Tab Person -->
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
                                      <td id="location<?= $id ?>"><?= $personCountry.', '.$personState ?></td>
                                    </tr>
                                  </tbody>
                                </table>

                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  <?php if($transactionTypeId == Transaction::TYPE_SENDER && $statusId == Transaction::STATUS_REJECTED && $account->checkPermission('REPORT_TRANSACTION_GET_NEW_PERSON'))
                                  { ?>
                                    <button type="button" class="btn btn-danger" onclick="getNewPerson(<?= $id ?>)">Get New <?= $transactionType ?></button>
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