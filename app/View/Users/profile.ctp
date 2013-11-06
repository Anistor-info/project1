<div class="content_wrapper">
<div class="inner">

<?php echo $this->Html->script('edit_profile'); ?>
<?php echo $this->element('messages'); ?>
<?php echo $this->Global->get_bredcrum('profile'); ?>

<div class="container_inner">
   <?php echo $this->Form->create('User');?>
    <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" class="form">
      <tbody><tr>
        <td valign="top" height="50" align="left" colspan="2"><h1><strong>Edit Profile</strong></h1></td>
      </tr>
      <tr>
        <td width="20%" height="45"><label for="textfield">Email</label></td>
        <td> <?php echo $this->Form->input('email',array('label'=>false)); ?></td>
      </tr>
      <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="textfield2">First Name</label></td>
        <td><?php echo $this->Form->input('first_name',array('label'=>false)); ?></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="textfield3">Last Name</label></td>
        <td><?php echo $this->Form->input('last_name',array('label'=>false));?></td>
      </tr>
      
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="330" border="0" cellspacing="0" cellpadding="0">
          <tbody><tr>
            <td class="submit">
             <?php echo $this->Form->end(__('Save Profile'));?>
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