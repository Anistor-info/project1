<div class="content_wrapper">
<div class="inner">

<?php echo $this->Html->script('add_monitor'); ?>
<?php echo $this->element('messages'); ?>
<?php echo $this->Global->get_bredcrum('add_monitor'); ?>

<div class="container_inner">
  <?php echo $this->Form->create('Monitor'); ?>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="form">
      <tbody><tr>
        <td valign="top" height="50" align="left" colspan="2"><h1><strong>Add Monitor</strong></h1></td>
      </tr>
      <tr>
        <td width="20%" height="45"><label for="textfield">Monitor Name</label></td>
        <td width="84%"><?php echo $this->Form->input('name',array('class'=>'roundborder_input','label'=>false)); ?></td>
        </tr>
      <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" height="20" align="left">
         <label for="textfield4">Location</label></td>
        <td valign="top" height="20" align="left">
         <?php echo $this->Form->input('location',array('class'=>'roundborder_input','label'=>false)); ?>
        </td>
        </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
       
        </tr>
      <tr>
        <td valign="top"><label for="textfield4">Assign Monitor</label></td>
        <td><?php echo $this->Form->input('assign_monitor', array('type' => 'select','class'=>'multiple_select','selected'=>$selected_user,'multiple'=>'true','options' => $under_users,'label'=>false));?></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
     
      <tr>
        <td>&nbsp;</td>
        <td><table width="330" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td>
            <span class="submit">
              <?php echo $this->Form->submit('Add Monitor',array('class'=>'buton_submit')); ?>
            </span>
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
   <?php echo $this->Form->end();?>
</div>
</div>
</div>