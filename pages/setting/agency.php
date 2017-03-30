<?php
include("../../settingHeader.php");

$wsRequest = new WSRequest($_REQUEST);
try
{
  $account = $_SESSION['account'];
  $system = new System($account);
  $userMessage = "Agency";
}
catch(Exception $ex)
{
  ExceptionManager::handleException($ex);
  $userMessage = $ex->getMessage();
  $userMessage = '<div class="alert alert-danger">'.$userMessage.'</div>';
}

try
{
  $agencyId = $wsRequest->getParam("agencyId");
  if($agencyId)
  {
    $agencyNewName = $wsRequest->getParam("agencyNewName");
    $agencyNewStatus = $wsRequest->getParam("agencyNewStatus", 0);
    $r = $system->updateAgency($agencyId, $agencyNewName, $agencyNewStatus);
    if($r)
    {
      $userMessage = '<div class="alert alert-success">Agency has been Updated</div>';
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
  if($account->isAuthenticated())
  {
    $agencies = $system->getAgencies();
  }
}
catch(Exception $ex)
{
  ExceptionManager::handleException($ex);
  $userMessage = $ex->getMessage();
  $userMessage = '<div class="alert alert-danger">'.$userMessage.'</div>';
}
?>

<div id="page-wrapper">
  <!-- Header -->
  <div class="row">
    <div class="col-lg-12">
      <h3 class="page-header"><?= $userMessage ?></h3>
    </div>
  </div>
  <!-- End Header -->
  <!-- Body -->
  <div class="row">
    <div class="col-lg-12">

      <div class="panel panel-default">
        <div class="panel-heading">Agency List</div>
        <!-- Panel body -->
        <div class="panel-body">
          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover wrap-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Type</th>
                  <th>Status</th>
                  <th>Modify</th>
                  <th>Options</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach($agencies as $agency)
                {
                  $agencyId = $agency['Agency_Id'];
                  $agencyName = $agency['Name'];
                  $agencyType = $agency['AgencyType'];

                  //format date
                  $modifiedDate = $agency['Modified'];
                  $modifiedDate = date(Util::FORMAT_DATE_DISPLAY, strtotime($modifiedDate));

                  $agencyIsActive = $agency['IsActive'];
                  $status = ($agencyIsActive) ? 'Active' : 'Inactive';
                  $rowType = ($agencyIsActive) ? '' : 'danger';

                  echo "<tr class='$rowType'>
                      <td>$agencyName</td>
                      <td>$agencyType</td>
                      <td>$status</td>
                      <td>$modifiedDate</td>
                      <td>
                        <a class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#myModal$agencyId\">Edit</a>
                      </td>
                    </tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
          <!-- End Table -->
          <!-- Modal -->
          <div>
            <?php
            if($agencies)
            {
              foreach($agencies as $agency)
              {
                $agencyId = $agency['Agency_Id'];
                $agencyName = $agency['Name'];
                $agencyType = $agency['AgencyType'];

                //format date
                $modifiedDate = $agency['Modified'];
                $modifiedDate = date(Util::FORMAT_DATE_DISPLAY, strtotime($modifiedDate));

                $agencyIsActive = $agency['IsActive'];
                $status = ($agencyIsActive) ? 'Active' : 'Inactive';
                $headerType = ($agencyIsActive) ? 'modal-header-info' : 'modal-header-danger';

                ?>
                <div class="modal fade" id="myModal<?= $agencyId ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                  <div class="modal-dialog">
                    <div class="modal-content">

                      <div class="modal-header <?= $headerType ?>">
                        <h4 class="modal-title" id="myModalLabel">Agency Details (<?= $agencyName ?>)</h4>
                      </div>

                      <div class="modal-body">
                        <div class="panel-body">
                          <!-- Nav tabs -->
                          <ul class="nav nav-pills">
                            <li class="active">
                              <a href="#tab-agencyDetails<?= $agencyId ?>" data-toggle="tab" aria-expanded="true">Details</a>
                            </li>
                          </ul>
                          <!-- Tab panes -->
                          <div class="tab-content">
                            <!-- Tab Details -->
                            <div class="tab-pane fade active in" id="tab-agencyDetails<?= $agencyId ?>">
                              <br/>
                              <br/>
                              <form role="form" data-toggle="validator" method="post" id="myFormDetails<?= $agencyId ?>" name="myForm<?= $agencyId ?>">
                                <input type="hidden" id="agencyId" name="agencyId" value="<?= $agencyId ?>">
                                <input type="hidden" id="agencyName" name="agencyName" value="<?= $agencyName ?>">
                                <input type="hidden" id="agencyStatus" name="agencyStatus" value="<?= $agencyIsActive ?>">

                                <div class="form-group">
                                  <div class="form-group">
                                    <label>Name</label>
                                    <input class="form-control input-sm" type="text" id="agencyNewName" name="agencyNewName" pattern="<?= Util::REGEX_ALPHANIMERIC ?>" value="<?= $agencyName ?>" required>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <!-- Status -->
                                  <label>Status</label>
                                  <select class="form-control input-sm" id="agencyNewStatus" name="agencyNewStatus" required>
                                    <option value="1" <?php echo ($agencyIsActive == '1') ? 'selected' : '' ?>>Active</option>
                                    <option value="0" <?php echo ($agencyIsActive == '0') ? 'selected' : '' ?>>Inactive</option>
                                  </select>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-danger">Save changes</button>
                                </div>
                              </form>
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
          <!-- End Modal -->
        </div>
        <!-- End Panel body -->
      </div>

    </div>
  </div>
  <!-- End Body -->
</div>

<?php include("../../footer.php"); ?>

