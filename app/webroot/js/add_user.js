// JavaScript Document

// add user form validations
$('#UserAddForm').live('submit',function(){
	var first_name = $('#UserFirstName');
	var last_name = $('#UserLastName');
	var email = $('#UserEmail');
	var welcome_msg = $('#UserWelcomeMessage');
	var role_id = $('#UserRoleId');
	var error = 0;
	
	// remove class
	$('.error-message').remove();
		
	if(!is_valid_field(first_name.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		first_name.after('<div class="error-message">Please enter first name.</div>');
		first_name.focus();
		error = 1;
	}
	
	if(!is_valid_field(last_name.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		last_name.after('<div class="error-message">Please enter last name.</div>');
		last_name.focus();
		error = 1;
	}
	
	if(!is_valid_email(email.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		email.after('<div class="error-message">Please enter valid email.</div>');
		email.focus();
		error = 1;
	}
	
	if(!is_valid_field(welcome_msg.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		welcome_msg.after('<div class="error-message">Please enter welcome message.</div>');
		welcome_msg.focus();
		error = 1;
	}
	
	if(role_id.val() == 0){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		role_id.after('<div class="error-message">Please select the role.</div>');
		error = 1;
	}
	
	if(error){
		update_frame_height();
		return false;
	}else{
		return true;
	}
	
})