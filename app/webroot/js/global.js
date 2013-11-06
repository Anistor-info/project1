// JavaScript Document

// check all functionality
$('.allcheck').live('click',function(){
	$('.check').each(function() {
                        $(this).attr('checked',!!$('.allcheck').attr('checked'));
                    });
	})

$(document).ready(function () {

/*Calling your own functions through Form Highlighter*/
$("#advancedCall").formHighlighter({
	classFocus: 'demoFocus',
	classBlur: 'demoBlur',
	classKeyDown: 'demoKeydown',
	classNotEqualToDefault: 'demoNotEqualToDefault',
	clearField:false,
	onBlur: function () { demoBlur(); },
	onFocus: function () { demoFocus($(this)); }
});

/*Your own function that will be called from Form Highlighter*/
function demoBlur() {
	$("#advancedCall input.demoBlur:not(input:hidden)").stop(0, 1).fadeTo(500, 1.0);
}

function demoFocus(element) {
	$("#advancedCall input.demoBlur:not(input:hidden)").stop(0, 1).fadeTo(500, 0.5);
}
});

$(document).ready(function(){
//tooltips popup
$('.tTip').betterTooltip({speed: 150, delay: 100});
// admin settings popup
$('li.dropdown').hover(function(){
$(".toprightlinks li ul").animate({top:'25px'},{duration:250});
}, function(){
$(".toprightlinks li ul").animate({top:'30px'},{duration:250});
});
});

// ROLE MANAGEMENT
$('#UserRoleId').live('change',function(){
	var selected_option = $('#UserRoleId').val();		// get selected option
		if(selected_option == 3){
			$('#access_permission').show();				// show the accpermision div
		}else{
			$('#access_permission').hide();				// hide the accpermision div
			$('.check').attr('checked',false);
			$('.allcheck').attr('checked',false);
		}
	});


// VIDEO MANAGEMENT
$('#VideoType').live('change',function(){
	
		var option_value = $('#VideoType').val();
		// images
		if(option_value == 1){
			$('#supported_format').text('Supported File Formats: (.jpg, .jpeg, .tiff)');
		 	$('#image_uploads').show();
			$('.li_interval').show();
			$('#document_uploads').hide();
			$('#video_uploads').hide();
		// videos	
		}else if(option_value == 2){
			$('#supported_format').text('Supported File Formats: (.mp4, .avi, .flv)');
			$('#image_uploads').hide();
			$('.li_interval').hide();
			$('#document_uploads').hide();
			$('#video_uploads').show();
			
		// document
		}else if(option_value == 3){
			$('#supported_format').text('Supported File Formats: (.pdf, .doc, .ppt)');
			$('#image_uploads').hide();
			$('#video_uploads').hide();
			$('.li_interval').show();
			$('#document_uploads').show();
		}
	});

$(document).ready(function(){
// auto deletion after 5 second
//$('#flashMessage').delay(5000).fadeOut(1600, "linear");

$('.close_me').live('click',function(){
	$('#flashMessage').fadeOut(1000);
	});
});

$('#CompanyCountryId').live('change',function(){
	var country_id = this.value;
	$.ajax({
		  url: baseUrl + '/Countries/GetStatesAjax',
		  type: "POST",
		  data: {country_id: country_id},
		  async: false,
		  success: function(result){
			 //alert(result);
			 $('#CompanyStateId').html(result);
		  }
		});
	});
	
// MENUS
$('.menu').live('click',function(){	
	$('#mainframe').attr('src',$(this).attr('link'));
});

function show(a){
	$('#msg').html(a);
}


$('.content_wrapper a').live('click',function(){
	if($(this).attr('href') != '#'){
		if(window.parent.show_loader){
			window.parent.show_loader();
		}
	}
})

$('.menu').live('click',function(){
	 if(window.parent.show_loader){
	 	window.parent.show_loader();
	 }
})

$("input[type='submit']").live('click',function() {
	 if(window.parent.show_loader){
	 	window.parent.show_loader();
	 }
});


// validation functions for forms
function is_valid_field(str){
	if( !$.trim(str) ){
		return false;
	}
	return true;
}

function is_valid_char_length(str,minimum,maximum){
	if (($.trim(str).length < minimum) || ($.trim(str).length > maximum) ) {
            return false
    } 	
	return true;
}

function is_valid_email(email){
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   	if(reg.test(email) == false) {
      return false;
   	}
	return true;
}

function update_frame_height(){
	window.parent.set_frame_height($('.content_wrapper').height());
}
	