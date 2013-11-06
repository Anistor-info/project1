// edit profile form validations
$('#UserProfileForm').live('submit',function(){
	var email = $('#UserEmail');
	var first_name =  $('#UserFirstName');
	var last_name =  $('#UserLastName');
	var error = 0;
	
	// remove class
	$('.error-message').remove();
	
	if(!is_valid_email(email.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		email.after('<div class="error-message">Please enter valid email.</div>');
		email.focus();
		error = 1;
	}
	
	if(!is_valid_field(first_name.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		first_name.focus();
		first_name.after('<div class="error-message">Please enter first name.</div>');
		error = 1;
	}
	
	if(!is_valid_field(last_name.val())){
		if(window.parent.hide_loader){
			window.parent.hide_loader();
		}
		last_name.focus();
		last_name.after('<div class="error-message">Please enter last name.</div>');
		error = 1;
	}
	
	if(error){
		update_frame_height();
		return false;
	}else{
		return true;
	}
	
	})