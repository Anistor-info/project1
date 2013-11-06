<div class="content_wrapper">
    <div class="content_wrapper_inner">
    <div class="border_boxbtm ">
    <div class="content_wrapper_h4"><h2 class="marginleft15"><strong>Register User</strong></h2></div>
    <div class="content_wrapper_icon"><img alt="" src="/outbackmedia_streaming/img/user-01.png"></div>
    </div>
    <div class="border_boxtop marginbtm10">&nbsp;</div>
    <div class="form_wrapper">
     <?php echo $this->Form->create(array('User','action'=>'create')); ?>
     <?php echo $this->Form->input('user_id',array('type'=>'hidden'));	?>
                
     <div class="fieldbox_one">
          <div class="input text required">
          <?php  echo $this->Form->input('user_name'); ?>
         </div>       
     </div>
         
       <div class="fieldbox_two">
         
           <?php  echo $this->Form->input('password',array('type'=>'password')); ?>
            
       </div>
              
       <div class="fieldbox_one">
          <div class="input text required">
         <?php echo $this->Form->input('confirm_password',array('type'=>'password'));	 ?>
          </div>       
       </div>
       
       <div class="fieldbox_two">
          <div class="input text required">
          <?php echo $this->Form->input('email'); ?>
          </div>       
       </div>
       
       <div class="fieldbox_one">
          <div class="input text required">
           <?php echo $this->Form->input('first_name');  ?>
          </div>       
       </div>
       
       <div class="fieldbox_two">
          <div class="input text required">
           <?php echo $this->Form->input('last_name'); ?>
          </div>       
       </div>
           
       <div class="submitwrapper">
         <div class="submit">
         <?php echo $this->Form->input('token',array('type'=>'hidden')); ?>
		 <?php echo $this->Form->end(__('Submit'));?>
         </div>       
       </div>
       
    </div>
    </div>