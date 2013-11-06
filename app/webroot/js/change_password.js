// change password form validations
$('#UserChangePasswordForm').live('submit',function(){
	var old_password = $('#UserOldPassword');
	var new_password =  $('#UserPassword');
	var confirm_password =  $('#UserConfirmPassword');
	var error = 0;
	
	// remove class
	$('.error-message').remove();
	
	if(!is_valid_field(old_password.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		old_password.focus();
		old_password.after('<div class="error-message">Please enter password.</div>');
		error = 1;
	}
	
	if(!is_valid_field(new_password.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		new_password.focus();
		new_password.after('<div class="error-message">Please enter new password.</div>');
		error = 1;
	}
	
	if(confirm_password.val() != new_password.val()){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		confirm_password.focus();
		confirm_password.after('<div class="error-message">New password and confirm password are not matched.</div>');
		error = 1;
	}
	
	if(error){
		update_frame_height();
		return false;
	}else{
		return true;
	}
	
})
