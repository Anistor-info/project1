// edit profile form validations
$('#CompanyEditForm').live('submit',function(){
	var name = $('#CompanyCompanyName');
	var company_address1 =  $('#CompanyAddress1');
	var contact =  $('#CompanyContactNumber');
	var country = $('#CompanyCountryId');
	var state = $('#CompanyStateId');
	var monitors = $('#CompanyMonitors');
	var error = 0;
	
	// remove class
	$('.error-message').remove();
	
	if(!is_valid_field(name.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		name.focus();
		name.after('<div class="error-message">Please enter company name.</div>');
		error = 1;
	}else if(!is_valid_char_length(name.val(),5,50)){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		name.focus();
		name.after('<div class="error-message">Company name should be from 5 to 50</div>');
		error = 1;
	}
	
	if(!is_valid_field(company_address1.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		company_address1.focus();
		company_address1.after('<div class="error-message">Please enter company address.</div>');
		error = 1;
	}
	
	if(!is_valid_field(contact.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		contact.focus();
		contact.after('<div class="error-message">Please enter contact.</div>');
		error = 1;
	}else if(!is_valid_char_length(contact.val(),10,12)){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		contact.focus();
		contact.after('<div class="error-message">Contact should be from 5 to 50.</div>');
		error = 1;
	}
	
	if(country.val() == 0){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		country.after('<div class="error-message">Please select the country.</div>');
		error = 1;
	}
	
	if(state.val() == 0){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		state.after('<div class="error-message">Please select the state.</div>');
		error = 1;
	}
	
	if(!is_valid_field(monitors.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		monitors.focus();
		monitors.after('<div class="error-message">Please enter the monitor.</div>');
		error = 1;
	}else if(isNaN(monitors.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		monitors.focus();
		monitors.after('<div class="error-message">Monitor should be number.</div>');
		error = 1;
	}
	
	if(error){
		update_frame_height();
		return false;
	}else{
		return true;
	}
})