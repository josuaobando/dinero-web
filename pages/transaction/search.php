<?php
include("../../header.php");

$wsRequest = new WSRequest($_REQUEST);

try{
  $userMessage = "Search Transactions";
  $account = Session::getAccount();

  $system = new System($account);
  $transactionStatus = $system->transactionStatus();
}catch(Exception $ex){
  ExceptionManager::handleException($ex);
  $userMessage = $ex->getMessage();
  $userMessage = '<div class="alert alert-danger">' . $userMessage . '</div>';
}

try{
  // Change Status to Transaction
  $transactionId = $wsRequest->getParam("transactionId");
  if($transactionId){
    try{
      $providerTransaction = new ProviderTransaction($wsRequest);
      $update = $providerTransaction->transactionUpdate();
      if($update){
        $userMessage = '<div class="alert alert-success">Transaction has been Updated</div>';
      }
    }catch(Exception $exception){
      ExceptionManager::handleException($exception);
      $userMessage = $exception->getMessage();
      $userMessage = '<div class="alert alert-danger">' . $userMessage . '</div>';
    }
  }
}catch(Exception $ex){
  ExceptionManager::handleException($ex);
  $userMessage = $ex->getMessage();
  $userMessage = '<div class="alert alert-danger">' . $userMessage . '</div>';
}

try{
  $report = new Report($wsRequest, $account);
  $typeFilter = $wsRequest->getParam("btnSearch", 1);

  $filterStatus = $wsRequest->getParam("filterStatus", "3");
  $filterType = $wsRequest->getParam("filterType", "0");
  $filterAgencyType = $wsRequest->getParam("filterAgencyType", "0");
  $filterAgencyId = $wsRequest->getParam("filterAgencyId", "");
  $filterBeginDate = $wsRequest->getParam("filterBeginDate", "");
  $filterEndDate = $wsRequest->getParam("filterEndDate", "");

  $filterMTCN = $wsRequest->getParam("filterMTCN", "");
  $filterID = $wsRequest->getParam("filterID", "");
  $filterUsername = $wsRequest->getParam("filterUsername", "");
  $filterMerchantId = $wsRequest->getParam("filterReference", "");
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

      <!-- SEARCH -->
      <div class="panel panel-default">
        <div class="panel-body">

          <!-- Nav tabs -->
          <ul class="nav nav-tabs">
            <li role="presentation" class="<?= ($typeFilter != '2') ? 'active' : '' ?>">
              <a href="#tab-filter-general" data-toggle="tab" aria-expanded="<?= ($typeFilter != '2') ? 'true' : 'false' ?>">General</a>
            </li>
            <li role="presentation" class="<?= ($typeFilter == '2') ? 'active' : '' ?>">
              <a href="#tab-filter-specific" data-toggle="tab" aria-expanded="<?= ($typeFilter == '2') ? 'true' : 'false' ?>">Specific</a>
            </li>
          </ul>
          <!-- End Nav tabs -->
          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane fade <?= ($typeFilter != '2') ? 'active in' : '' ?>" id="tab-filter-general">
              <form role="form" data-toggle="validator" method="post" name="searchFormGeneral" action="search">
                <br>
                <!-- Optional filters -->
                <div class="row">
                  <!-- left -->
                  <div class="col-sm-6">
                    <div class="row">
                      <div class="col-sm-12">
                        <select name="filterStatus" id="filteredStatus" class="input-sm form-control">
                          <option value="-1" <?php echo $filterStatus == '-1' ? 'selected' : ''; ?>>
                            Transaction Status
                          </option>
                          <?php
                          foreach($transactionStatus as $tStatus){
                            $id = $tStatus['itemId'];
                            $value = $tStatus['itemValue'];
                            if($filterStatus == $id){
                              echo "<option value='$id' selected>$value</option>";
                            }else{
                              echo "<option value='$id'>$value</option>";
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <select name="filterType" id="filterType" class="input-sm form-control">
                          <option value="0" <?php echo $filterType == '0' ? 'selected' : ''; ?>>
                            Transaction Type
                          </option>
                          <option value="1" <?= ($filterType == Transaction::TYPE_RECEIVER) ? 'selected' : '' ?>>
                            Deposit (Receiver)
                          </option>
                          <option value="2" <?= ($filterType == Transaction::TYPE_SENDER) ? 'selected' : '' ?>>
                            Payout (Sender)
                          </option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <input class="input-sm form-control" type=text id="filterUsername" name="filterUsername" placeholder="Username" value="<?= $filterUsername ?>" pattern="<?= Util::REGEX_ALPHANIMERIC ?>">
                      </div>
                    </div>
                  </div>
                  <!-- right -->
                  <div class="col-sm-6">
                    <div class="row">
                      <div class="col-sm-12">
                        <select class="form-control input-sm" name="filterAgencyType" id="filterAgencyType"
                                onchange="changeFilter()">
                          <option value="0" <?php echo $filterAgencyType == '0' ? 'selected' : ''; ?>>
                            Agency Type
                          </option>
                          <option value="2" <?php echo $filterAgencyType == '2' ? 'selected' : ''; ?>>
                            MoneyGram
                          </option>
                          <option value="3" <?php echo $filterAgencyType == '3' ? 'selected' : ''; ?>>
                            Ria
                          </option>
                        </select>
                      </div>
                    </div>
                    <?php if($account->checkPermission('REPORT_FILTER_AGENCY')){ ?>
                      <div class="row">
                        <div class="col-sm-12">
                          <select class="form-control input-sm" name="filterAgencyId" id="filterAgencyId"
                                  onchange="changeFilter()">
                            <option value="">Agency</option>
                            <option value="1" <?php echo $filterAgencyId == '1' ? 'selected' : ''; ?>>
                              MG - Pavon
                            </option>
                            <option value="2" <?php echo $filterAgencyId == '2' ? 'selected' : ''; ?>>
                              MG - Cañas
                            </option>
                            <option value="100" <?php echo $filterAgencyId == '100' ? 'selected' : ''; ?>>
                              MG - Saturno
                            </option>
                            <option value="101" <?php echo $filterAgencyId == '101' ? 'selected' : ''; ?>>
                              MG - Nicaragua
                            </option>
                            <option value="4" <?php echo $filterAgencyId == '4' ? 'selected' : ''; ?>>
                              RIA - Pavon
                            </option>
                            <option value="5" <?php echo $filterAgencyId == '5' ? 'selected' : ''; ?>>
                              RIA - Cañas
                            </option>
                            <option value="102" <?php echo $filterAgencyId == '102' ? 'selected' : ''; ?>>
                              RIA - Saturno
                            </option>
                          </select>
                        </div>
                      </div>
                    <?php } ?>
                    <div class="row">
                      <div class="col-sm-12" id="dateRange">
                        <div class="input-daterange input-group" id="datepicker">
                          <input class="input-sm form-control" type="text" name="filterBeginDate" id="filterBeginDate" placeholder="Begin Date" readonly value="<?= $filterBeginDate ?>"/>
                          <span class="input-group-addon"></span>
                          <input class="input-sm form-control" type="text" name="filterEndDate" id="filterEndDate" placeholder="End Date" readonly value="<?= $filterEndDate ?>"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End Optional filters -->

                <div class="row" style="margin-top: 10px;">
                  <div class="col-lg-12">
                    <button name="btnSearch" type="submit" value="1" class="btn btn-success">Search transactions</button>
                  </div>
                </div>
            </div>
            <div class="tab-pane fade <?= ($typeFilter == '2') ? 'active in' : '' ?>" id="tab-filter-specific">
              <form role="form" data-toggle="validator" method="post" name="searchFormSpecific" action="search">
                <br>

                <!-- Specific filters -->
                <div class="row">
                  <div class="col-sm-4">
                    <input class="input-sm form-control" type="text" id="filterID" name="filterID" placeholder="ID" value="<?= $filterID ?>" pattern="<?= Util::REGEX_NUMERIC ?>">
                  </div>
                  <div class="col-sm-4">
                    <input class="input-sm form-control" type="text" id="filterMTCN" name="filterMTCN" placeholder="Control Number" value="<?= $filterMTCN ?>" minlength="8" maxlength="11" pattern="<?= Util::REGEX_NUMERIC ?>">
                  </div>
                  <div class="col-sm-4">
                    <input class="input-sm form-control" type=text id="filterUsername" name="filterReference" placeholder="Merchant Trans Id" value="<?= $filterMerchantId ?>" pattern="<?= Util::REGEX_ALPHANIMERIC ?>">
                  </div>
                </div>
                <!-- End Specific filters -->

                <div class="row" style="margin-top: 10px;">
                  <div class="col-lg-12">
                    <button name="btnSearch" type="submit" value="2" class="btn btn-success">Search transactions</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- Tab panes -->

        </div>
      </div>

      <!-- END SEARCH -->

      <div class="panel panel-default">
        <div class="panel-body">

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
            if($report->getTotal() > CoreConfig::PAGINATION_TABLE_MAX_ROWS){
              ?>
              <div class="panel-footer">
                <div class="btn-group" role="group">
                  <input type="hidden" id="typeFilter" name="typeFilter" value="<?= $typeFilter ?>">
                  <?php echo $report->getPaginationTable(); ?>
                </div>
              </div>
              <?php
            }
            ?>
            <!-- PAGINATION TABLE TRANSACTIONS -->
          </div>
          <!-- END TABLE TRANSACTIONS -->

          <!-- SUMMARY -->
          <?php
          if($report->getTotal()){
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
          if($report->getTotal() && $account->checkPermission('REPORT_EXPORT')){
            ?>
            <div>
              <div class="row">
                <div class="col-lg-12">
                  <input type="button" class="btn btn-default" value="Export" onclick="reportSearch(false, 0)">
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
            if($report->getTransactions()){
              foreach($report->getTransactions() as $transaction){
                $id = $transaction['Transaction_Id'];
                $apiTransactionId = $transaction['ApiTransactionId'];
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
                $customerName = $transaction['CustomerName'];
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

                $apiVerification = ucwords($transaction['Verification']);
                $apiVerificationId = $transaction['Verification_Id'];
                $apiAuthCode = $transaction['AuthCode'];

                $createdDate = $transaction['CreatedDate'];
                $modifiedDate = $transaction['ModifiedDate'];
                //format date
                $createdDate = date(Util::FORMAT_DATE_DISPLAY, strtotime($createdDate));
                $modifiedDate = date(Util::FORMAT_DATE_DISPLAY, strtotime($modifiedDate));
                $headerType = ($transactionTypeId == Transaction::TYPE_RECEIVER) ? 'modal-header-info' : 'modal-header-warning';

                $disabled = "";
                $readonly = "";
                if($statusId != Transaction::STATUS_APPROVED && $statusId != Transaction::STATUS_REJECTED){
                  $disabled = 'disabled';
                  $readonly = 'readonly';
                }
                ?>
                <div class="modal fade" id="myModal<?= $id ?>" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                      <div class="modal-header <?= $headerType ?>">
                        <h4 class="modal-title" id="myModalLabel"><?= $transactionType ?>
                          Transaction Details</h4>
                      </div>

                      <div class="modal-body">
                        <div class="panel-body">
                          <!-- Nav tabs -->
                          <ul class="nav nav-pills">
                            <?php if($account->checkPermission('TRANSACTION_UPDATE')){ ?>
                              <li class="active">
                                <a href="#tab-process<?= $id ?>" data-toggle="tab"
                                   aria-expanded="true">Re-Process
                                </a>
                              </li>
                              <li class="">
                                <a href="#tab-customer<?= $id ?>" data-toggle="tab"
                                   aria-expanded="false"><?= ($transactionTypeId == Transaction::TYPE_RECEIVER) ? 'Sender' : 'Receiver' ?></a>
                              </li>
                            <?php }else{ ?>
                              <li class="active">
                                <a href="#tab-customer<?= $id ?>" data-toggle="tab"
                                   aria-expanded="true"><?= ($transactionTypeId == Transaction::TYPE_RECEIVER) ? 'Sender' : 'Receiver' ?></a>
                              </li>
                            <?php } ?>
                            <li class="">
                              <a href="#tab-person<?= $id ?>" data-toggle="tab"
                                 aria-expanded="false"><?= ($transactionTypeId == Transaction::TYPE_SENDER) ? 'Sender' : 'Receiver' ?></a>
                            </li>
                          </ul>
                          <!-- Tab panes -->
                          <div class="tab-content">
                            <!-- Tab Re-Process -->
                            <?php if($account->checkPermission('TRANSACTION_UPDATE')){ ?>
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

                                  <div class="wrap-table">
                                    <table class="table">
                                      <thead>
                                      <tr class="active">
                                        <th>ID</th>
                                        <th>Agency</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <?php if($account->checkPermission('REPORT_TRANSACTION_VIEW_API_VERIFICATION')){ ?>
                                          <th>API Verification</th>
                                          <th>API Status</th>
                                          <th>API Code</th>
                                        <?php } ?>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      <tr>
                                        <td><?= $id ?></td>
                                        <td><?= $agency ?></td>
                                        <td><?= $agencyType ?></td>
                                        <td><?= $modifiedDate ?></td>
                                        <?php if($account->checkPermission('REPORT_TRANSACTION_VIEW_API_VERIFICATION')){ ?>
                                          <td><?= $apiVerificationId ?></td>
                                          <td><?= $apiVerification ?></td>
                                          <td><?= $apiAuthCode ?></td>
                                        <?php } ?>
                                      </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                  <br>

                                  <div class="form-group">
                                    <div class="row">
                                      <div class="col-lg-4">
                                        <label>Amount ($USD)</label>
                                        <input class="form-control input-sm <?= $disabled ?>"
                                               type="number" step="any" min="1"
                                               id="amount" name="amount"
                                               value="<?= $amount ?>" required>
                                      </div>
                                      <div class="col-lg-4">
                                        <label>Fee ($USD)</label>
                                        <input class="form-control input-sm <?= $disabled ?>"
                                               type="number" step="any" min="0"
                                               id="fee" name="fee"
                                               value="<?= $fee ?>" required>
                                      </div>
                                      <div class="col-lg-4">
                                        <label>Control Number</label>
                                        <input class="form-control input-sm <?= $disabled ?>"
                                               type="text" id="controlNumber"
                                               name="controlNumber"
                                               value="<?= $controlNumber ?>"
                                               minlength="8" maxlength="11"
                                               pattern="<?= Util::REGEX_NUMERIC ?>" <?php if($transactionTypeId == Transaction::TYPE_RECEIVER){
                                          echo 'required';
                                        } ?>>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <!-- Status -->
                                      <label>Status</label>
                                      <select class="form-control input-sm <?= $disabled ?>"
                                              id="status" name="status" required>
                                        <?php
                                        foreach($transactionStatus as $tStatus){
                                          $itemId = $tStatus['itemId'];
                                          $value = $tStatus['itemValue'];
                                          if($statusId == $itemId){
                                            echo "<option value='$itemId' selected>$value</option>";
                                          }else{
                                            echo "<option value='$itemId'>$value</option>";
                                          }
                                        }
                                        ?>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label>Reason (Only Numbers and Letters)</label>
                                    <input class="form-control input-sm" type="text"
                                           id="reason" name="reason"
                                           value="<?= $reason ?>"
                                           pattern="<?= Util::REGEX_ALPHANIMERIC ?>"
                                           required <?= $readonly ?>>
                                  </div>
                                  <div class="form-group">
                                    <label>Note</label>
                                    <input class="form-control input-sm" type="text"
                                           id="note" name="note" value="<?= $note ?>"
                                           pattern="<?= Util::REGEX_ALPHANIMERIC ?>">
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Close
                                    </button>
                                    <?php if($account->checkPermission('REPORT_TRANSACTION_SAVE')){ ?>
                                      <button type="submit"
                                              class="btn btn-danger <?= $disabled ?>">
                                        Save changes
                                      </button>
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
                                <?php if($apiTransactionId){ ?>
                                  <tr>
                                    <td>API ID</td>
                                    <td><?= $apiTransactionId ?></td>
                                  </tr>
                                <?php } ?>
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
                                <?php if($account->checkPermission('REPORT_TRANSACTION_VIEW_AGENCY')){ ?>
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
                                  <td id="location<?= $id ?>"><?= $personCountry . ', ' . $personState ?></td>
                                </tr>
                                </tbody>
                              </table>

                              <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Close
                                </button>
                                <?php if($transactionTypeId == Transaction::TYPE_SENDER && $statusId == Transaction::STATUS_REJECTED && $account->checkPermission('REPORT_TRANSACTION_GET_NEW_PERSON')){ ?>
                                  <button type="button" class="btn btn-danger"
                                          onclick="getNewPerson(<?= $id ?>)">Get New <?= $transactionType ?></button>
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

  <script type="application/javascript">
    $(window).load(function(){
      changeFilter()
    })
  </script>

  <!-- FOOTER -->
<?php include("../../footer.php"); ?>