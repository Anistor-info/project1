// edit profile form validations
$('#UserForgetPasswordForm').live('submit',function(){
	var email = $('#UserEmail');
	var error = 0;
	
	// remove class
	$('.error-message').remove();
	
	if(!is_valid_field(email.val()) || email.val() == 'Email'){
		username.after('<div class="error-message">Please enter email.</div>');
		error = 1;
	}
	
	if(error){
		return false;
	}else{
		return true;
	}
})