$(document).ready(function() {
$('#file_upload').uploadify({
'removeCompleted' : false,
'auto'    		  : true,
'buttonImage'     : baseUrl+'app/webroot/img/upload.png',
'width'           : 168,
'height'		  : 49,
'swf'             : baseUrl+'app/webroot/uploadify/uploadify.swf',
'uploader'        : baseUrl + 'videos/uploadify/' + hash +'/image',
'scriptData'      : {'sending':'images'},
'onSelect'        : function(){
	$('#upload_in_process').val(2);
},
'onUploadSuccess' : function(file, data, response) {
	update_frame_height();	
	var result = jQuery.parseJSON(data);
	if(!result.response){
		alert(result.msg);
		$('#'+file.id).fadeOut(1600, "linear");
	}
	$('#upload_in_process').val(1);
},
'onCancel' : function(file) {
	//alert('The file ' + file.name + ' was cancelled.');
	return;
}
});

$('#video_upload').uploadify({
'removeCompleted' : false,
'auto'    		  : true,
'buttonImage'     : baseUrl+'app/webroot/img/upload.png',
'width'           : 168,
'height'		  : 49,
'uploadLimit'     : 1,
'swf'             : baseUrl+'app/webroot/uploadify/uploadify.swf',
'uploader'        : baseUrl + 'videos/uploadify/' + hash +'/video',
'onSelect'        : function(){
	$('#upload_in_process').val(2);
},
'onUploadSuccess' : function(file, data, response) {
	update_frame_height();	
	var result = jQuery.parseJSON(data);
	
	if(!result.response){
		alert(result.msg);
		$('#'+file.id).fadeOut(1600, "linear");
	}
	$('#upload_in_process').val(1);
	$('#VideoFilePath').val(result.file_path);
	
},
'onCancel' : function(file) {
	//alert('The file ' + file.name + ' was cancelled.');
	return;
}
});

$('#document_upload').uploadify({
'removeCompleted' : false,
'auto'    		  : true,
'buttonImage'     : baseUrl+'app/webroot/img/upload.png',
'width'           : 168,
'height'		  : 49,
'uploadLimit'     : 1,
'swf'             : baseUrl+'app/webroot/uploadify/uploadify.swf',
'uploader'        : baseUrl + 'videos/uploadify/' + hash +'/document',
'onSelect'        : function(){
	$('#upload_in_process').val(2);
},
'onUploadSuccess' : function(file, data, response) {
	update_frame_height();	
	var result = jQuery.parseJSON(data);
	
	if(!result.response){
		alert(result.msg);
		$('#'+file.id).fadeOut(1600, "linear");
	}
	$('#upload_in_process').val(1);
	$('#VideoFilePath').val(result.file_path);
},
'onCancel' : function(file) {
	//alert('The file ' + file.name + ' was cancelled.');
	return;
}
});

$('#VideoAddForm').submit(function() {
  var upload_status = $('#upload_in_process').val();
  var name = $('#VideoName');
  var video_type = $('#VideoType');
  var video_interval = $('#VideoInterval');
  var error = 0;
	
   // remove class
   $('.error-message').remove();
  
  // check name
  if(!$.trim(name.val())){
	if(window.parent.hide_loader) {
		window.parent.hide_loader();
	}
	name.after('<div class="error-message">Please enter the name.</div>');
	name.focus();
	error = 1;
  }
  
  // check interval selected
  if(video_type.val() != 2){
	  
	 if(video_interval.val() == 0){
		if(window.parent.hide_loader) {
			window.parent.hide_loader();
		}
		video_interval.after('<div class="error-message">Please select the interval.</div>');
		error = 1;
	 }
  }
  
  if(error){
		update_frame_height();
		return false;
   }
 
  // check upload status  
  if(upload_status == 0){
  	alert('Please upload file first');
	if(window.parent.hide_loader) {
		window.parent.hide_loader();
	}
	return false;
  }else if(upload_status == 1){
	return true;  
  }else if(upload_status == 2){
  	alert('Please wait uploading is in process.');
	if(window.parent.hide_loader) {
		window.parent.hide_loader();
	}
	return false;  
  }
   
});
});

function update_frame_height(){
	window.parent.set_frame_height($('.content_wrapper').height());
}