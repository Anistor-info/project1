<?php echo $this->element('top'); ?>

<div class="container" align="center"> 
<div class="toplink_wrapper">
<div class="loglink"><?php echo $this->Global->get_current_username(); ?><a href="<?php echo Router::url('/', true);?>users/logout">logout</a>
</div>
</div>

<script type="text/javascript">
function set_frame_height(height){
//mainframe
    //alert(height)
	document.getElementById('mainframe').style.height = height + 10 + "px";
	document.getElementById('frame-loader-id').style.height = height + "px";
}
function show_loader(){
	$('.frame-loader').show();
}
function hide_loader(){
	$('.frame-loader').fadeOut(500);
	$('.body-loader').hide();
	$('#hide-loader').hide();
}
</script>

<?php $last_url = $this->Global->get_last_url();  ?>
<iframe onload="hide_loader()" height="500" id="mainframe" align="center" frameborder="0"  width="95%" scrolling="no"  src="<?php echo $last_url;?>"></iframe>

<div id="frame-loader-id" class="frame-loader" style="display:none"></div>
<div id="hide-loader"><div class="body-loader"></div></div>

<?php echo $this->element('footer'); ?>
</div>