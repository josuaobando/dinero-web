<?php
//all script need to have this include except login.
include("../../header.php");

if(!CoreConfig::DEV){
  header("Location:home");
  exit;
}

$wsRequest = new WSRequest($_REQUEST);

try{
  $btnNewTransaction = $wsRequest->getParam('btnNewTransaction');
  if($btnNewTransaction){
    $account = Session::getAccount();
    $manager = new Manager($account);

    //Transaction Type
    $transactionTypeId = $wsRequest->requireNumericAndPositive('transactionTypeId');
    if($transactionTypeId == Transaction::TYPE_SENDER){
      $wsResponse = $manager->sender($wsRequest);
    }else{
      $wsResponse = $manager->receiver($wsRequest);
    }

    if($wsResponse instanceof WSResponseOk){
      $userMessage = '<div class="alert alert-success">Please review the information</div>';
    }else{
      $userMessage = '<div class="alert alert-danger">Transaction could not be created!</div>';
    }
  }else{
    $userMessage = 'New Transaction';
  }
}catch(Exception $ex){
  ExceptionManager::handleException($ex);
  $userMessage = $ex->getMessage();
  $userMessage = '<div class="alert alert-danger">' . $userMessage . '</div>';
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
                    <select class="form-control input-sm" tabindex="10" name="type" id="type" required>
                      <option value="2">MoneyGram</option>
                      <option value="3">Ria</option>
                    </select>
                    <select class="form-control input-sm" tabindex="1" name="transactionTypeId" id="transactionTypeId"
                            required>
                      <option value="">Please Select Transaction Type</option>
                      <option value="1">Receiver</option>
                      <option value="2">Sender</option>
                    </select>
                    <input id="amount" name="amount" type="number" step="any" min="1" class="form-control input-sm"
                           tabindex="2" placeholder="Amount" required value="50">
                    <input id="uid" class="form-control input-sm" type="text" tabindex="3"
                           placeholder="Username (Customer)" name="uid" required value="JOSHTEST" readonly>
                    <input id="first_name" class="form-control input-sm" type="text" tabindex="4"
                           placeholder="First Name" name="first_name" required value="John Test">
                    <input id="last_name" class="form-control input-sm" type="text" tabindex="5" placeholder="Last Name"
                           name="last_name" required value="Smith">
                    <input id="phone" class="form-control input-sm" type="text" tabindex="6" placeholder="Phone"
                           name="phone" required value="5555500000">
                    <input id="country" class="form-control input-sm" type="text" tabindex="7" placeholder="Country"
                           name="country" required value="US" readonly>
                    <input id="state" class="form-control input-sm" type="text" tabindex="8" placeholder="State"
                           name="state" required value="FL" readonly>
                    <button name="btnNewTransaction" type="submit" tabindex="11" value="true"
                            class="btn btn-lg btn-primary btn-block">Add Transaction
                    </button>
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
</div>

<!-- FOOTER -->
<?php include("../../footer.php"); ?>
