<?php 
echo $this->Html->css('uploadify'); 
echo $this->Html->script('jquery.uploadify-3.1.min'); 
echo $this->Html->script('uploadify');
?>

<div class="mainbox_right_panel">
  <h1>Video Settings</h1>
  <div class="h_line_grey"></div>
  <div class="h_line_white"></div>
  <div class="main_box">
    <?php echo $this->Form->create('Video', array('enctype' => 'multipart/form-data')); ?>
	  <div class="add_comp_legend">Upload and Convert Video</div>
	  <div class="useform"> Use this form to upload video file types. Please allow several minutes for the file to be processed</div>
	  <div class="overflow_hidden">
		<ul class="add_comp_form">
         <li>
         
		  </li>
          <li>
          <?php echo $this->Form->input('name',array('class'=>'roundborder_input')); ?>
		  </li>
          <li class="li_interval">
          </li>
		  <li style="position: relative;">
			<label>
            File<span id="content_label" class="label_explain"> (.flv, .mpg, .mpeg, .mp4, .avi, .mov)</span></label>
			<div class="NFFile">
             <?php echo $this->Form->file('Document.submittedfile',array('class'=>"NFhidden")); ?>
			  <div class="NFFileNew">
			  <?php echo $this->Html->image('0.png', array('alt' => 'CakePHP', 'class' => 'NFTextLeft'))?>
				<div class="NFTextCenter"></div>
				<?php echo $this->Html->image('0.png', array('alt' => 'CakePHP', 'class' => 'NFFileButton'))?>
				</div>
               
			</div> <span style="visibility: hidden;" class="input_error">error message will go here</span>
		  </li>
           <li style="position: relative;">
			 <input type="file" name="file_upload" id="file_upload" />
             <a href="javascript:$('#file_upload').uploadify('upload')">Upload Files</a>
		  </li>
		  <li class="full">   
            <?php echo $this->Form->submit('submit',array('class'=>'buton_submit')); ?>
		  </li>
		</ul>
	  </div>
	<?php echo $this->Form->end();?>
  </div>
</div>