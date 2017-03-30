<?php
//all script need to have this include except login.
include("../../header.php");

$wsRequest = new WSRequest($_REQUEST);

try
{
  $btnNewTransaction = $wsRequest->getParam('btnNewTransaction');
  if($btnNewTransaction)
  {
    $account = $_SESSION['account'];
    $manager = new Manager($account);

    //Transaction Type
    $transactionTypeId = $wsRequest->requireNumericAndPositive('transactionTypeId');
    $wsResponse = $manager->startTransaction($wsRequest, $transactionTypeId);
    // transaction
    $transaction = $wsResponse->getElement("transaction");
    $transaction instanceof Transaction;
    // person
    if($transactionTypeId == Transaction::TYPE_RECEIVER)
    {
      $person = $wsResponse->getElement("receiver");
    }
    else
    {
      $person = $wsResponse->getElement("sender");
    }
    $person instanceof Person;

    if(!$transaction->getTransactionId())
    {
      $userMessage = '<div class="alert alert-danger">Transaction could not be created!</div>';
    }
    else
    {
      $userMessage = '<div class="alert alert-success">Please review the information</div>';
    }
  }
  else
  {
    $userMessage = 'New Transaction';
  }
}
catch(Exception $ex)
{
  ExceptionManager::handleException($ex);
  $userMessage = $ex->getMessage();
  $userMessage = '<div class="alert alert-danger">'.$userMessage.'</div>';
}
?>

<script type="text/javascript">
  $(document).ready(function(){
    load_country();
    $("#country").change(function(){
      $("#state").attr("disabled", true);
      load_state();
    });
  });

  function load_country(){
    startSpinner();
    $.get("scripts/load-country.php", function(result){
      if(result == false){
        alert('Countries not found!');
      }
      else{
        $('#country').append(result);
      }
      stopSpinner();
    });
  }
  function load_state(){
    startSpinner();
    var code = $("#country").val();
    $.get("scripts/load-state.php", {code: code},
      function(result){
        if(result == false){
          alert('States was not found for the selected Country!');
        }
        else{
          $("#state").attr("disabled", false);
          document.getElementById("state").options.length = 1;
          $('#state').append(result);
        }
        stopSpinner();
      }
    );
  }
</script>

<div id="page-wrapper">

  <div class="row">
    <div class="col-lg-12">
      <h3 class="page-header"><?= $userMessage ?></h3>
    </div>
  </div>

  <!-- ticket -->
  <?php if($transaction && $transaction->getTransactionId())
  { ?>
    <!-- Sender/Receiver -->
    <div class="row">
      <div class="col-lg-6">
        <div class="panel panel-default">

          <div class="panel-heading">
            <strong><?= ($transaction->getTransactionTypeId() == Transaction::TYPE_RECEIVER) ? 'Receiver' : 'Sender' ?> Information</strong>
          </div>

          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <table class="table">
                  <tbody>
                    <tr>
                      <td><?= ($transaction->getTransactionTypeId() == Transaction::TYPE_RECEIVER) ? 'Receiver' : 'Sender' ?></td>
                      <td><?= ucwords(strtolower($person->getName())) ?></td>
                    </tr>
                    <tr>
                      <td>Type Id</td>
                      <td><?= $person->getTypeId() ?></td>
                    </tr>
                    <tr>
                      <td>Personal Id</td>
                      <td><?= $person->getPersonalId() ?></td>
                    </tr>
                    <tr>
                      <td>Expiration Date Id</td>
                      <td><?= $person->getExpirationDateId() ?></td>
                    </tr>
                    <tr>
                      <td>Address</td>
                      <td><?= ucwords(strtolower($person->getAddress())) ?></td>
                    </tr>
                    <tr>
                      <td>City</td>
                      <td><?= ucwords(strtolower($person->getCity())) ?></td>
                    </tr>
                    <tr>
                      <td>Country/State</td>
                      <td><?= ucwords(strtolower($person->getFrom())) ?></td>
                    </tr>
                    <tr>
                      <td>Birth Date</td>
                      <td><?= $person->getBirthDate() ?></td>
                    </tr>
                    <tr>
                      <td>Marital Status</td>
                      <td><?= ucwords($person->getMaritalStatus()) ?></td>
                    </tr>
                    <tr>
                      <td>Gender</td>
                      <td><?= $person->getGender() ?></td>
                    </tr>
                    <tr>
                      <td>Profession</td>
                      <td><?= ucwords($person->getProfession()) ?></td>
                    </tr>
                    <tr>
                      <td>Phone</td>
                      <td><?= $person->getPhone() ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <!-- Sender/Receiver -->
  <?php }
  else
  { ?>
    <!-- New Transaction -->
    <div class="row">
      <div class="col-lg-6">
        <div class="panel panel-default">

          <div class="panel-heading">
            <strong>Customer Information</strong>
          </div>

          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">

                <form role="form" data-toggle="validator" method="post" action="transaction">
                  <fieldset>
                    <div class="form-group">
                      <select class="form-control input-sm" tabindex="1" name="transactionTypeId" id="transactionTypeId" required>
                        <option value="">Please Select Transaction Type</option>
                        <option value="1">Receiver</option>
                        <option value="2">Sender</option>
                      </select>
                      <input id="amount" name="amount" type="number" step="any" min="1" class="form-control input-sm" tabindex="2" autocomplete="off" placeholder="Amount" required>
                      <input id="uid" class="form-control input-sm" type="text" tabindex="3" autocomplete="off" placeholder="Username (Customer)" name="uid" required>
                      <input id="first_name" class="form-control input-sm" type="text" tabindex="4" autocomplete="off" placeholder="First Name" name="first_name" required>
                      <input id="last_name" class="form-control input-sm" type="text" tabindex="5" autocomplete="off" placeholder="Last Name" name="last_name" required>
                      <input id="phone" class="form-control input-sm" type="text" tabindex="6" autocomplete="off" placeholder="Phone" name="phone" required>
                      <!-- Country -->
                      <select class="form-control input-sm" tabindex="8" id="country" name="country" required>
                        <option value="">Please Select Country</option>
                      </select>
                      <!-- State -->
                      <select class="form-control input-sm" disabled tabindex="9" id="state" name="state" required>
                        <option value="">Please Select State</option>
                      </select>
                      <!-- Type -->
                      <select class="form-control input-sm" tabindex="10" name="type" id="type" required>
                        <option value="">Please Select Type</option>
                        <option value="2">MoneyGram</option>
                        <option value="3">Ria</option>
                      </select>
                      <button name="btnNewTransaction" type="submit" tabindex="11" value="true" class="btn btn-lg btn-primary btn-block">Add Transaction</button>
                    </div>
                  </fieldset>
                </form>

              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <!-- New Transaction -->
  <?php } ?>

</div>

<!-- FOOTER -->
<?php include("../../footer.php"); ?>
