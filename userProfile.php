<?php
//all script need to have this include except login.
include("header.php"); 

$wsRequest = new WSRequest($_REQUEST);
$userMessage = 'User Profile';

try
{
	$account = $_SESSION['account'];
	$account instanceof Account;
	if($account->isAuthenticated()){
	  
	  $currentPassword = trim($wsRequest->getParam('currentPassword', ''));
	  if(!empty($currentPassword)){
	    if($account->getPassword() == $currentPassword){
	      
	      $newPassword = trim($wsRequest->requireNotNullOrEmpty('newPassword'));
	      $newPasswordConfirm = trim($wsRequest->requireNotNullOrEmpty('newPasswordConfirm'));
	      
	      if($newPassword == $newPasswordConfirm){
	        if($account->changePassword($newPassword)){
	          $_SESSION['account'] = $account;
	          $userMessage = '<div class="alert alert-success">Account has been Updated</div>';
	        }else{
	          $userMessage = '<div class="alert alert-danger">Account could not be updated</div>';
	        }
	      }else{
	        $userMessage = '<div class="alert alert-warning">New password and Confirmation does not match</div>';
	      }
	      
	    }else{
	      $userMessage = '<div class="alert alert-warning">Current password does not match</div>';
	    }    
	  }
	  
	}
}
catch (Exception $ex)
{
	ExceptionManager::handleException($ex);
	$userMessage = $ex->getMessage();
	$userMessage = '<div class="alert alert-danger">'.$userMessage.'</div>';
}
?>

  <div id="page-wrapper">
  
    <div class="row">
      <div class="col-lg-12">
        <h3 class="page-header">
            <?=$userMessage?>
        </h3>
      </div>
    </div>

    <!-- /.row -->
    <div class="row">
      <div class="col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">Change Password</div>
          <div class="panel-body">
            <div class="row">
            	<div class="col-lg-12">
                <form role="form" data-toggle="validator" method="post" action="userProfile.php">
                  <fieldset>
    									<div class="form-group">
    									
    									  <input type="password" id="currentPassword" name="currentPassword" class="form-control input-sm" tabindex="1" autocomplete="off" placeholder="Current Password" required>
    		                <input type="password" id="newPassword" name="newPassword" class="form-control input-sm" tabindex="2" autocomplete="off" placeholder="New Password" minlength="8" maxlength="20" required>
    		                <input type="password" id="newPasswordConfirm" name="newPasswordConfirm" class="form-control input-sm" tabindex="3" autocomplete="off" placeholder="Confirm New Password" minlength="8" maxlength="20" required>
    										<button type="submit" class="btn btn-danger" tapindex="4">Save changes</button>
    										
    	                </div>
                    </fieldset>
                  </form>
                </div>           
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- FOOTER -->
<?php include("footer.php"); ?>
