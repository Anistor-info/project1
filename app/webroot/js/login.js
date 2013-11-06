// edit profile form validations
$('#UserLoginForm').live('submit',function(){
	var username = $('#UserUsername');
	var password =  $('#UserPassword');
	var error = 0;
	
	// remove class
	//$('.error-message').remove();
	
	if(!is_valid_field(username.val()) || username.val() == 'Username'){
		$('#user-error').html('<div class="error-message">Please enter username.</div>');
		error = 1;
	}
	
	if(!is_valid_field(password.val()) || password.val() == 'Password'){
		$('#password-error').html('<div class="error-message">Please enter password.</div>');
		error = 1;
	}
	
	if(error){
		return false;
	}else{
		return true;
	}
})