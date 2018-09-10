/**
 * jobando
 */

/**
 * Start Spinner
 */
function startSpinner(){
  $('.spinner').show();
}
/**
 * Stop Spinner
 */
function stopSpinner(){
  $('.spinner').hide();
}
startSpinner();

/**
 * Loading Spinner on form submit
 */
$("form").submit(function(){
  startSpinner();
});

/**
 * Document before unload
 */
window.onbeforeunload = function(e){
  startSpinner();
};

/**
 * Documento ready load
 */
$(window).load(function(){
  stopSpinner();
});

/**
 * Export report transactions
 */
function reportSearch(search, pageId){
  startSpinner();
  
  var filterStatus = $('<input>', {'type': 'hidden', 'id': 'filterStatus', 'name': 'filterStatus', 'value': $("#filterStatus").val()});
  var filterType = $('<input>', {'type': 'hidden', 'id': 'filterType', 'name': 'filterType', 'value': $("#filterType").val()});
  var filterAgencyType = $('<input>', {'type': 'hidden', 'id': 'filterAgencyType', 'name': 'filterAgencyType', 'value': $("#filterAgencyType").val()});
  var filterBeginDate = $('<input>', {'type': 'hidden', 'id': 'filterBeginDate', 'name': 'filterBeginDate', 'value': $("#filterBeginDate").val()});
  var filterEndDate = $('<input>', {'type': 'hidden', 'id': 'filterEndDate', 'name': 'filterEndDate', 'value': $("#filterEndDate").val()});
  var filterMTCN = $('<input>', {'type': 'hidden', 'id': 'filterMTCN', 'name': 'filterMTCN', 'value': $("#filterMTCN").val()});
  var filterUsername = $('<input>', {'type': 'hidden', 'id': 'filterUsername', 'name': 'filterUsername', 'value': $("#filterUsername").val()});
  var filterAgencyId = $('<input>', {'type': 'hidden', 'id': 'filterAgencyId', 'name': 'filterAgencyId', 'value': $("#filterAgencyId").val()});
  var filterID = $('<input>', {'type': 'hidden', 'id': 'filterID', 'name': 'filterID', 'value': $("#filterID").val()});
  var filterReference = $('<input>', {'type': 'hidden', 'id': 'filterReference', 'name': 'filterReference', 'value': $("#filterReference").val()});
  var filterPage = $('<input>', {'type': 'hidden', 'id': 'filterPage', 'name': 'filterPage', 'value': pageId});
  
  var form = $('<form/>', {'action': 'search', 'method': 'post', 'id': 'searchForm', 'name': 'searchForm'});
  if(!search){
    form = $('<form/>', {'action': 'scripts/export.php', 'method': 'post', 'id': 'searchForm', 'name': 'searchForm', target: '_blank'});
  }
  
  filterStatus.appendTo(form);
  filterType.appendTo(form);
  filterAgencyType.appendTo(form);
  filterBeginDate.appendTo(form);
  filterEndDate.appendTo(form);
  filterMTCN.appendTo(form);
  filterUsername.appendTo(form);
  filterAgencyId.appendTo(form);
  filterID.appendTo(form);
  filterReference.appendTo(form);
  filterPage.appendTo(form);
  
  form.appendTo($("body"));
  $("#searchForm").submit();
  
  stopSpinner();
}

/**
 * Get new person
 *
 * @param id
 */
function getNewPerson(id){
  startSpinner();
  
  $("#btnNewPerson" + id).attr("disabled", "disabled");
  
  $.get("scripts/transaction.php", {f: 'newName', id: id},
    function(result){
      if(result.personId){
        
        $("#personName" + id).text(result.name);
        $("#typeId" + id).text(result.typeId);
        $("#personalId" + id).text(result.personalId);
        $("#expirationDateId" + id).text(result.expirationDateId);
        $("#birthDate" + id).text(result.birthDate);
        $("#maritalStatus" + id).text(result.maritalStatus);
        $("#gender" + id).text(result.gender);
        $("#profession" + id).text(result.profession);
        $("#phone" + id).text(result.phone);
        $("#address" + id).text(result.address);
        $("#city" + id).text(result.city);
        $("#location" + id).text(result.countryName + ', ' + result.stateName);
        
        setTimeout(function(){
          $("#searchForm").submit();
        }, 5000);
        
      }else{
        if(result.error){
          alert('The operation could not be completed: ' + result.error);
        }else{
          alert('The operation could not be completed!');
        }
      }
      
      stopSpinner();
    }
  );
}

/**
 * Get new person
 *
 * @param id
 */
function getStatus(id){
  startSpinner();
  
  $("#btnCheckStatus" + id).attr("disabled", "disabled");
  
  $.get("scripts/transaction.php", {f: 'information', id: id, XDEBUG_SESSION_START: 'ECLIPSE_DBGP'},
    function(transaction){
      if(transaction){
        
        if(transaction.status_id == 3){
          alert('The transaction has been Approved. Please refresh!');
        }else if(transaction.status_id == 4){
          alert('The transaction has been Rejected (' + transaction.notes + '). Please refresh!');
        }else{
          alert('The transaction has not changed');
        }
        $("#btnSave" + id).attr("disabled", "disabled");
      }else{
        $("#btnCheckStatus" + id).removeAttr("disabled");
        if(transaction.error){
          alert('The operation could not be completed: ' + transaction.error);
        }else{
          alert('The operation could not be completed!');
        }
      }
      
      stopSpinner();
    }
  );
}

/**
 *
 */
function changeFilter(){
  var agencyTypeId = $("#filterAgencyType").val();
  if(agencyTypeId > 0){
    $("#filterAgencyId").attr("disabled", "disabled");
  }else{
    $("#filterAgencyId").removeAttr("disabled");
  }
  var agencyId = $("#filterAgencyId").val();
  if(agencyId > 0){
    $("#filterAgencyType").attr("disabled", "disabled");
  }else{
    $("#filterAgencyType").removeAttr("disabled");
  }
}
/**
 *
 */

/**
 * Date Range Element
 */
$('#dateRange .input-daterange').datepicker({
  orientation: 'top',
  autoclose: true,
  format: 'yyyy-mm-dd',
  todayHighlight: true
});