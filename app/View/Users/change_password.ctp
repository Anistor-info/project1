<div class="content_wrapper">
<div class="inner">

<?php echo $this->Html->script('change_password'); ?>
<?php echo $this->element('messages'); ?>
<?php echo $this->Global->get_bredcrum('change_password'); ?>

<div class="container_inner">
  <?php echo $this->Form->create('User');?>
    <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" class="form">
      <tbody><tr>
        <td valign="top" height="50" align="left" colspan="2"><h1><strong>Change Password</strong></h1></td>
      </tr>
      <tr>
        <td width="20%" height="45"><label for="textfield">Password</label></td>
        <td width="84%"><?php echo $this->Form->input('User.old_password',array('type'=>'password','label'=>false)); ?></td>
        </tr>
      <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" height="20" align="left">
        <label for="textfield4">New Password</label></td>
        <td valign="top" height="20" align="left"> <?php echo $this->Form->input('User.password',array('type'=>'password','label'=>false)); ?></td>
        </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
       
        </tr>
      <tr>
        <td><label for="textfield2">Confirm Password</label></td>
        <td> <?php echo $this->Form->input('User.confirm_password',array('type'=>'password','label'=>false)); ?></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="330" border="0" cellspacing="0" cellpadding="0">
          <tbody><tr>
            <td><span class="submit">
              <?php echo $this->Form->end(__('Change Password'));?>
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