<?php 
$current_date = date('Y-m-d h:i:s', time()); 
?>
<div class="content_wrapper">
<div class="inner">

<?php echo $this->Html->script('add_user'); ?>
<?php echo $this->element('messages'); ?>
<?php echo $this->Global->get_bredcrum('add_user'); ?>

<div class="container_inner">
  <?php echo $this->Form->create('User');?>
    <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" class="form">
      <tbody><tr>
        <td valign="top" height="50" align="left" colspan="2"><h1><strong>Add User</strong></h1></td>
      </tr>
      <tr>
        <td width="20%" height="45"><label for="textfield">First Name</label></td>
        <td width="84%"><?php echo $this->Form->input('first_name',array('label'=>false)); ?></td>
        </tr>
      <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
       
      </tr>
      
      <tr>
        <td valign="top" height="20" align="left">
        <label for="textfield4">Last Name</label></td>
        <td valign="top" height="20" align="left"><?php  echo $this->Form->input('last_name',array('label'=>false)); ?></td>
        </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      
       <tr>
        <td valign="top" height="20" align="left">
        <label for="textfield4">Email</label></td>
        <td valign="top" height="20" align="left"><?php  echo $this->Form->input('email',array('label'=>false)); ?></td>
        </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      
      <tr>
        <td valign="top" align="left"><label for="textarea">Welcome Message</label></td>
        <td valign="top" align="left">
          <?php echo $this->Form->input('welcome_message',array('label'=>false,'type'=>'textarea','value'=>'company #company_name# invited you to join #link#'));
		?></td>
        </tr>
      
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      
      <tr>
      
          <?php
        # SHOW ALL COMPANIES
		if(isset($company_id)){		
			echo $this->Form->input('company_id',array('type'=>'hidden','value'=>$company_id));
		} else {
			echo $this->Form->input('company_id',array('options'=>$company_names));									
		}		
       ?>
       
       <?php
	   # ROLES
		if(is_array($role_id)){ ?>
		 <div class="fieldbox_two">
         <td valign="top" height="40" align="left"><label for="Role">Role</label></td>
         <td valign="top" align="left">
		 <?php echo $this->Form->select('role_id',$role_id['options'],array('empty'=>'Select Role', 'selected'=>'1','label'=> false), array('label'=>false, 'div'=>false)); ?>
		          
		<?php 	
			# ACCESS PERMISSIONS 
			echo '<div id="access_permission" style="margin-top: 18px;">';
			if($this->Global->has_access('users','add') and (!$add_super_admin) and (!$add_company_user)){		
			echo $this->Form->checkbox('privilege',array('name'=>'privilege[]','class'=>'check','value' => ADD_USER,'hiddenField' => false));
			echo $this->Form->label('add user');
			
			if($this->Global->has_access('users','edit')){
			echo $this->Form->checkbox('privilege', array('name'=>'privilege[]','class'=>'check','value' => EDIT_USER,'hiddenField' => false));  
			echo $this->Form->label('edit user');
			}
			
			if($this->Global->has_access('users','delete')){	
			echo $this->Form->checkbox('privilege', array('name'=>'privilege[]','class'=>'check','value' => DELETE_USER,'hiddenField' => false));  
			echo $this->Form->label('delete user');
			}
				
			echo $this->Form->checkbox('privilege', array('name'=>'privilege[]','class'=>'allcheck','value' => ALL_ACCESS_USER,'hiddenField' => false));  
			echo $this->Form->label('all');
			}
			echo $this->Form->input('created_datetime',array('type'=>'hidden','value'=>$current_date));		
			echo '</div>';
			
		}else{
			echo $this->Form->input('role_id',array('type'=>'hidden','value'=>$role_id));	
		}
	   ?> 
       </td>
        </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="330" border="0" cellspacing="0" cellpadding="0">
          <tbody><tr>
            <td><span class="submit">
               <?php echo $this->Form->end(__('Add User'));?>
            </span></td>
          </tr>
        </tbody></table></td>
      </tr>
      <tr>
        <td class="submit" colspan="2">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </tbody></table>
</div>
</div>
</div>