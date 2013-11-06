<script type="text/javascript" >
var msg = '<?php echo str_replace(array("\r", "\n"), '', $this->Session->flash()); ?>';
window.parent.show(msg);
 
$(document).ready(function() {		
	var div_height = $('.content_wrapper').height();
	if(div_height){
		window.parent.set_frame_height(div_height);	
	}
});
</script>



