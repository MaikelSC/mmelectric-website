$(document).ready(function(){
	/*alert('aa');*/
	var messageErr;
	var errorGral = $('div.alert.alert-danger').removeClass('alert alert-danger');
	var errorItem = $('span.help-block').clone().addClass('alert-danger');
	if(errorGral.length > 0){
		messageErr = errorGral;
		var options ={
			pMessage : messageErr
		} 
		$.fn.messagePopup(options);
	}
	if(errorItem.length > 0){ 
		messageErr = errorItem;
		var options ={
			pMessage : messageErr
		} 
		$.fn.messagePopup(options);
	}
	
});