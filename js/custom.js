/**
 * jobando
 */

/**
 * Start Spinner
 */
function startSpinner() {
	$('.spinner').show();
}
/**
 * Stop Spinner
 */
function stopSpinner() {
	$('.spinner').hide();
}
startSpinner();

/**
 * Loading Spinner on form submit
 */
$("form").submit(function() {
	startSpinner();
});

/**
 * Document before unload
 */
window.onbeforeunload = function(e) {
	startSpinner();
};

/**
 * Documento ready load
 */
$(window).load(function() {
    stopSpinner();
});

/**
 * Export report transactions
 */
function exportReport(){
	startSpinner();

	var form = $('<form/>', { 'action': 'scripts/export.php', 'method': 'post', 'id': 'exportForm', 'name': 'exportForm', target:'_blank'});
	var filterStatus = $('<input>', { 'type': 'hidden', 'id': 'filterStatus', 'name': 'filterStatus', 'value': $("#filterStatus").val()});
	var filterType = $('<input>', { 'type': 'hidden', 'id': 'filterType', 'name': 'filterType', 'value': $("#filterType").val()});
	var filterAgencyType = $('<input>', { 'type': 'hidden', 'id': 'filterAgencyType', 'name': 'filterAgencyType', 'value': $("#filterAgencyType").val()});
	var filterBeginDate = $('<input>', { 'type': 'hidden', 'id': 'filterBeginDate', 'name': 'filterBeginDate', 'value': $("#filterBeginDate").val()});
	var filterEndDate = $('<input>', { 'type': 'hidden', 'id': 'filterEndDate', 'name': 'filterEndDate', 'value': $("#filterEndDate").val()});
	var filterMTCN = $('<input>', { 'type': 'hidden', 'id': 'filterMTCN', 'name': 'filterMTCN', 'value': $("#filterMTCN").val()});
	var filterUsername = $('<input>', { 'type': 'hidden', 'id': 'filterUsername', 'name': 'filterUsername', 'value': $("#filterUsername").val()});

	filterStatus.appendTo(form);
	filterType.appendTo(form);
	filterAgencyType.appendTo(form);
	filterBeginDate.appendTo(form);
	filterEndDate.appendTo(form);
	filterMTCN.appendTo(form);
	filterUsername.appendTo(form);
		
	form.appendTo($("body"));	
	$("#exportForm").submit();

	stopSpinner();
}

/**
 * Get new person
 * 
 * @param id
 */
function getNewPerson(id){
	startSpinner();
	
	$("#btnNewPerson"+id).attr("disabled", "disabled");
	
	$.get("scripts/transaction.php", {id: id},
			function(result){
				if(result.personId){
					
					$("#personName"+id).text(result.name);
					$("#typeId"+id).text(result.typeId);
					$("#personalId"+id).text(result.personalId);
					$("#expirationDateId"+id).text(result.expirationDateId);
					$("#birthDate"+id).text(result.birthDate);
					$("#maritalStatus"+id).text(result.maritalStatus);
					$("#gender"+id).text(result.gender);
					$("#profession"+id).text(result.profession);
					$("#phone"+id).text(result.phone);
					$("#address"+id).text(result.address);
					$("#city"+id).text(result.city);
					$("#location"+id).text(result.countryName+', '+result.stateName);

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
 * Date Range Element
 */
$('#dateRange .input-daterange').datepicker({
  	orientation: 'top',
  	autoclose: true,
  	format: 'yyyy-mm-dd',
  	todayHighlight: true
});