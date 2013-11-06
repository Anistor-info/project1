// JavaScript Document

// add monitor form validations
$('#MonitorAddForm').live('submit',function(){
	var monitor_name = $('#MonitorName');
	var monitor_location =  $('#MonitorLocation');
	var error = 0;
	
	// remove class
	$('.error-message').remove();
			
	if(!is_valid_field(monitor_name.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		monitor_name.after('<div class="error-message">Please enter monitor name.</div>');
		monitor_name.focus();
		error = 1;
	}
	
	if(!is_valid_field(monitor_location.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		monitor_location.after('<div class="error-message">Please enter location name.</div>');
		monitor_location.focus();
		error = 1;
	}
	
	if(error){
		update_frame_height();
		return false;
	}else{
		return true;
	}
})