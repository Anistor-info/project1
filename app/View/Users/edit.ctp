<div class="content_wrapper">
<div class="inner">

<?php echo $this->Html->script('edit_user'); ?>
<?php echo $this->element('messages'); ?>
<?php echo $this->Global->get_bredcrum('user_edit'); ?>

<div class="container_inner">
    <?php echo $this->Form->create('User');?>
    <?php echo $this->Form->input('user_id',array('type'=>'hidden'));?>
    <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" class="form">
      <tbody><tr>
        <td valign="top" height="50" align="left" colspan="2"><h1><strong>Edit User</strong></h1></td>
      </tr>
      <tr>
        <td width="20%" height="45"><label for="textfield">Email</label></td>
        <td width="84%"><?php echo $this->Form->input('email',array('label'=>false)); ?></td>
        </tr>
      <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" height="20" align="left">
        <label for="textfield4">first Name</label></td>
        <td valign="top" height="20" align="left"> <?php echo $this->Form->input('first_name',array('label'=>false)); ?></td>
        </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
        </tr>
      <tr>
        <td><label for="textfield2">Last Name</label></td>
        <td> <?php echo $this->Form->input('last_name',array('label'=>false));?></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      
      <?php
		    echo $this->Form->input('role_id',array('type'=>'hidden'));		 
			if($this->Global->has_access('users','edit') and ($login_user_id != $edit_user_id) and (@$role_id != 2)){
				
				$privilege_array = $this->Global->get_privilage($edit_user_id);
				$has_privilege_array = $this->Global->get_privilage($login_user_id);
			}
      ?>
      <tr>
        <td>&nbsp;</td>
        <td><table width="330" border="0" cellspacing="0" cellpadding="0">
          <tbody><tr>
            <td>
            <?php echo $this->Form->end(__('Edit User'));?>
            </td>
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