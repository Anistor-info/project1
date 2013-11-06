// edit profile form validations
$('#UserCreateForm').live('submit',function(){
	var username = $('#UserUserName');
	var password = $('#UserPassword');
	var confirm_password = $('#UserConfirmPassword');
	var email = $('#UserEmail');
	var first_name = $('#UserFirstName');
	var last_name = $('#UserLastName');
	var error = 0;
	
	// remove class
	$('.error-message').remove();
	
	if(!is_valid_field(username.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		username.focus();
		username.after('<div class="error-message">Please enter Username.</div>');
		error = 1;
	}else if(!is_valid_char_length(username.val(),5,50)){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		username.focus();
		username.after('<div class="error-message">Username should be from 5 to 50</div>');
		error = 1;
	}
	
	if(!is_valid_field(password.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		password.focus();
		password.after('<div class="error-message">Please enter confirm password.</div>');
		error = 1;
	}
	
	if(!is_valid_field(confirm_password.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		confirm_password.focus();
		confirm_password.after('<div class="error-message">Please enter confirm password.</div>');
		error = 1;
	}
		
	if(!is_valid_email(email.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		email.focus();
		email.after('<div class="error-message">Please enter the valid email.</div>');
		error = 1;
	}
	
	if(!is_valid_field(first_name.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		first_name.focus();
		first_name.after('<div class="error-message">Please enter the first name.</div>');
		error = 1;
	}
	
	if(!is_valid_field(last_name.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		last_name.focus();
		last_name.after('<div class="error-message">Please enter the last name.</div>');
		error = 1;
	}
	
	if(error){
		return false;
	}else{
		return true;
	}
})