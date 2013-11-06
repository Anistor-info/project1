<script>
var hash = '<?php echo $hash; ?>';
</script>
<?php 
echo $this->Html->css('uploadify'); 
echo $this->Html->script('jquery.uploadify-3.1.min'); 
echo $this->Html->script('uploadify');
?>

<div class="content_wrapper">
<div class="inner">

<?php echo $this->element('messages'); ?>
<?php echo $this->Global->get_bredcrum('add_monitor'); ?>

<div class="container_inner">
 	<?php 
	echo $this->Form->create('Video', array('enctype' => 'multipart/form-data')); 
	echo $this->Form->input('hash',array('type'=>'hidden','value'=>$hash));
	?>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="form">
      <tbody><tr>
        <td colspan="2"><h1><strong>Upload and Convert Video</strong></h1>
          <span class="greytxt">Use this form to upload video file types. Please allow Several minutes for the file to be processed</span></td>
      </tr>
      <tr>
        <td height="35" align="left" colspan="2">&nbsp;</td>
        </tr>
      <tr>
        <td width="20%" align="left"><label for="text">File Name </label></td>
        <td align="left"><?php echo $this->Form->input('name',array('class'=>'roundborder_input','label'=>false)); ?>  </td>
        </tr>
      <tr>
       <input type="hidden" id="upload_in_process" value="0"  />
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      
       <?php 
			if( (@$this->request->data['Video']['type'] == 1) or !isset($this->request->data['Video']['type'])){ 
				$show_uploadify = true;
				$show_interval = true;
				$show_images = true;
				$show_videos = false;
				$show_documents = false;		
			}else if($this->request->data['Video']['type'] == 2){
				$show_interval = true;
				$show_uploadify = false;
				$show_images = false;
				$show_videos = true;
				$show_documents = false;	
			}else if($this->request->data['Video']['type'] == 3){
				$show_interval = true;
				$show_uploadify = false;
				$show_images = false;
				$show_videos = false;
				$show_documents = true;
			}
			else{
			    $show_interval = false;
				$show_uploadify = false;	
			}
		?>	
        
      <tr>
        <td align="left"><label for="textfield2"> Select Type</label></td>
        <td align="left"><?php echo $this->Form->input('type',array('options'=>$options,'label'=>false)); ?> </td>
        </tr>
      <tr class="li_interval" <?php if(!$show_interval){ ?>style="display:none" <?php } ?> >
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      <tr class="li_interval" <?php if(!$show_interval){ ?>style="display:none" <?php } ?> >
        <td align="left"><label for="select2">Interval</label></td>
        <td align="left">
         <?php echo $this->Form->input('interval',array('options'=>$intervals,'class'=>'roundborder_input full_width','label'=>false)); ?>
        </td>
       </tr>
       
       <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
       </tr>
       
       <tr>
        <td valign="top" height="20" align="left">&nbsp;</td>
        <td valign="top" height="20" align="left"><table width="330" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
          
            <td id="image_uploads" width="387" class="buttons" <?php if(!$show_images){ ?>style="display:none" <?php } ?> >
             <?php echo $this->Form->file('file_upload',array('id'=>'file_upload','name'=>'file_upload')); ?>
             </td>
             
             <td id="video_uploads" <?php if(!$show_videos){ ?>style="display:none" <?php } ?> >
              <?php echo $this->Form->file('file_upload',array('id'=>'video_upload','name'=>'file_upload')); ?>
             </td>
             
              <td id="document_uploads" <?php if(!$show_documents){ ?>style="display:none" <?php } ?> >
              <?php echo $this->Form->file('file_upload',array('id'=>'document_upload','name'=>'file_upload')); ?>
             </td>
       
             <?php echo $this->Form->hidden('file_path'); ?>
            </tr>
          <tr>
            <td id="supported_format" valign="top" height="40" class="greytxt">Supported File Formats: (.jpg, .jpeg, .tiff) </td>
            </tr>
          </tbody></table></td>
      </tr>
        
      <tr>
        <td valign="top" height="20" align="left">&nbsp;</td>
        <td valign="top" height="20" align="left"><table width="330" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td width="387" class="buttons"> 
             <?php echo $this->Form->submit('Upload Video',array('class'=>'positive')); ?>
            </td>
            </tr>
          </tbody></table></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
        <td valign="top" align="left"><table width="330" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td height="26" align="left" class="submit">
            <?php echo $this->Form->end();?>
            </td>
          </tr>
          </tbody></table></td>
      </tr>
     
      <tr>
        <td align="right" class="select_wrap" colspan="2">&nbsp;</td>
      </tr>
    </tbody></table>
  </form>
</div>
</div>
</div>