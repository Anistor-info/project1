<div class="content_wrapper">
<div class="inner">

<?php echo $this->Html->script('add_company'); ?>
<?php echo $this->element('messages'); ?>
<?php echo $this->Global->get_bredcrum('add_company'); ?> 

<div class="container_inner">
  <?php echo $this->Form->create('Company'); ?>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="form">
      <tbody><tr>
        <td valign="top" height="50" align="left" colspan="2"><h1><strong>Add Company</strong></h1></td>
      </tr>
      <tr>
        <td width="20%" height="45"><label for="textfield">Company Name</label></td>
        <td> <?php echo $this->Form->input('company_name',array('class'=>'roundborder_input','label'=>false)); ?></td>
      </tr>
      <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="textfield2">Address 1</label></td>
        <td><?php  echo $this->Form->input('address1',array('class'=>'roundborder_input','label'=>false)); ?></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="textfield3">Address 2</label></td>
        <td> <?php  echo $this->Form->input('address2',array('class'=>'roundborder_input','label'=>false)); ?></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      
      <tr>
        <td><label for="textfield3">Contact Number</label></td>
        <td><?php  echo $this->Form->input('contact_number',array('class'=>'roundborder_input','label'=>false)); ?></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      
       <tr>
        <td><label for="textfield3">Country</label></td>
        <td><?php echo $this->Form->select('country_id',$countries_data,array('label'=>false,'empty'=>false));  ?></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
      
       <tr>
        <td><label for="textfield3">State</label></td>
        <td><?php echo $this->Form->select('state_id',$states_data,array('label'=>false,'empty'=>false));  ?></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
     
      <tr>
        <td><label for="textfield3">Monitor</label></td>
        <td><?php echo $this->Form->input('monitors',array('type'=>'text','label'=>false));  ?></td>
      </tr>
      <tr>
        <td valign="top" height="40" align="left">&nbsp;</td>
      </tr>
       
      <tr>
        <td>&nbsp;</td>
        <td><table width="330" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td class="submit">
              <?php echo $this->Form->Submit('Add Company');?>
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