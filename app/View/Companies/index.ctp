<div class="content_wrapper">
<div class="inner">

<?php echo $this->element('messages'); ?>
<?php echo $this->Global->get_bredcrum('list_company'); ?> 

<div class="container_inner">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
      <tbody><tr>
        <td valign="top" height="50" align="left"><h1><strong>Company List</strong></h1></td>
      </tr>
      <tr>
        <td valign="top" class="company_list"><table width="100%" cellspacing="0" cellpadding="0" border="0" style="-webkit-border-radius: 8px;
-moz-border-radius: 8px;
border-radius: 8px;">
          <thead>
            <tr class="odd">
              <th width="13%" style="-webkit-border-top-left-radius: 8px;
-moz-border-radius-topleft: 8px;
border-top-left-radius: 8px;" scope="col" class="leftborder">Company Name</th>
              <th width="13%" scope="col">Address 1</th>
              <th width="13%" scope="col">Address 2</th>
              <th width="13%" scope="col">Contact Number</th>
              <th width="13%" scope="col">Manage Admin</th>
              <th class="rightborder" width="16%" scope="col" style="border-radius:0px 8px 0px 0px;">Manage Company</th>
            </tr>
          </thead>
          <tfoot>
          </tfoot>
          <tbody>
           <?php $i= 0; foreach ($companies as $company): ?>
       	   <?php $td_class = ($i%2 == 0) ? 'even':'odd'; $i++; ?>
            <tr class="<?php echo $td_class; ?>">
              <td><?php echo h($company['Company']['company_name']); ?></td>
              <td><?php echo h($company['Company']['address1']); ?></td>
              <td><?php echo h($company['Company']['address2']); ?></td>
              <td><?php echo h($company['Company']['contact_number']); ?></td>
              <td>
               <?php
				$delete_img = $this->Html->image('icon-cross.png');
				if($this->Global->has_access('users','add')){
				 if((!$company['User']['user_id'])){
				  echo $this->Html->link($this->Html->image("icon-add.png"),array('controller'=>'users','action' => 'add',$company['Company']['company_id'],COMPANY_SUPER_ADMIN),array('escape' => false));
				 }
				?>
				<?php 
				if($this->Global->has_access('users','edit')){	
				if(($company['User']['user_id'])){	   
				 echo $this->Html->link($this->Html->image("icon-edit.png"),array('controller'=>'users','action' => 'edit',$company['User']['user_id']),array('escape' => false));
				}
				}
				?>
				<?php 
				if($this->Global->has_access('users','delete')){	
				 if(($company['User']['user_id']))	
				 echo $this->Form->postLink($delete_img, array('controller'=>'users','action' => 'delete',$company['User']['user_id']), array('escape' => false), sprintf(__('Are you sure you want to delete ?', true),$company['User']['user_id']));
				 }
				}
			  ?>
              </td>
              <td class="rightborder"> 
			  <?php 
				if($this->Global->has_access('companies','edit')){	
				echo $this->Html->link($this->Html->image("icon-edit.png"), array('action' => 'edit', $company['Company']['company_id']),array('escape' => false)); 
				}
				?>
				<?php 
				if($this->Global->has_access('companies','delete')){	
				echo $this->Form->postLink($delete_img, array('controller'=>'companies','action' => 'delete',$company['Company']['company_id']), array('escape' => false), sprintf(__('Are you sure you want to delete ?', true),$company['Company']['company_id']));
				}
				?>
             </td>
            </tr>
            <?php endforeach; ?>  
          
    </tbody></table>
    
    <!--pagination starts here-->  
        <div class="out_pagination">
		<span>
		<?php echo $this->Paginator->prev('<< Previous', null, null, array('class' => 'hide')); ?>
        </span>
        <?php echo $this->Paginator->numbers(array('separator'=>'')); ?>
        <?php echo $this->Paginator->next('Next >>', null, null, array('class' => 'hide')); ?></div>
     <!--pagination ends here-->  
</div>
</div>
</div>