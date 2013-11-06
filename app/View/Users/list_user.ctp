<div class="content_wrapper">
<div class="inner">

<?php echo $this->element('messages'); ?>
<?php echo $this->Global->get_bredcrum('user_list'); ?>

<div class="container_inner">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
      <tbody><tr>
        <td valign="top" height="50" align="left"><h1><strong>User List</strong></h1></td>
      </tr>
      <tr>
        <td valign="top" class="company_list"><table width="100%" cellspacing="0" cellpadding="0" border="0" style="table-layout:fixed;-webkit-border-radius: 8px;
-moz-border-radius: 8px;
border-radius: 8px;">
          <thead>
            <tr class="odd">
              <th width="13%" style="-webkit-border-top-left-radius: 8px;
-moz-border-radius-topleft: 8px;
border-top-left-radius: 8px;" scope="col" class="leftborder">User Name</th>
              <th width="13%" scope="col">Email</th>
              <th width="13%" scope="col">Name</th>
              <th width="13%" scope="col">Role</th>
              <th width="13%" scope="col">Creation Date</th>
              <th width="16%" scope="col">Company</th>
              <th width="10%" style="-webkit-border-top-right-radius: 8px;
-moz-border-radius-topright: 8px;
border-top-right-radius: 8px;" scope="col" class="rightborder">Action</th>
            </tr>
          </thead>
          <tfoot>
          </tfoot>
          <tbody>
          <?php $i= 0; foreach ($users as $user): 	?>
          <?php $td_class = ($i%2 == 0) ? 'even':'odd'; $i++; ?>
          <tr class="<?php echo $td_class; ?>">
           <td><?php echo h($user['User']['user_name']); ?>&nbsp;</td>
            <td><?php echo h($user['User']['email']); ?>&nbsp;</td>
            <td><?php echo h($user['User']['first_name']) .' '.h($user['User']['last_name']); ?>&nbsp;</td>
           
            <td><?php echo h($user['Role']['role_name']); ?>&nbsp;</td>		
            <td><?php echo h($user['User']['created_datetime']); ?>&nbsp;</td>
            <td><?php echo h($user['Company']['company_name']); ?>&nbsp;</td>	
            <td class="rightborder">
                <?php 
                $delete_img = $this->Html->image('icon-cross.png');
                if($this->Global->has_access('users','edit')){		
                echo $this->Html->link($this->Html->image("icon-edit.png"), array('controller'=>'users','action' => 'edit', $user['User']['user_id']),array('escape' => false)); 
                }
                ?>
                <?php 
                if($this->Global->has_access('users','delete')){	
                echo $this->Form->postLink($delete_img, array('controller'=>'users','action' => 'delete',$user['User']['user_id']), array('escape' => false), sprintf(__('Are you sure you want to delete ?', true),$user['User']['user_id']));
                }
             ?>		
             </td>
          </tr>
           <?php endforeach; ?>
          </tbody>
        </table></td>
        </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td valign="top" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" align="left">
        
     <!--pagination starts here-->  
        <div class="out_pagination">
		<span class="">
		<?php echo $this->Paginator->prev('<< Previous', null, null, array('class' => 'hide')); ?>
        </span>
        <?php echo $this->Paginator->numbers(array('separator'=>'')); ?>
        <?php echo $this->Paginator->next('Next >>', null, null, array('class' => 'hide')); ?></div>
     <!--pagination ends here-->  
</td>
      </tr>
    </tbody></table>
</div>
</div>
</div>