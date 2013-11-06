<div class="content_wrapper">
<div class="inner">

<?php echo $this->element('messages'); ?>
<?php echo $this->Global->get_bredcrum('list_monitor'); ?>

<div class="container_inner">
   <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0">
      <tbody><tr>
        <td valign="top" height="50" align="left"><h1><strong>Monitor List</strong></h1></td>
      </tr>
      <tr>
        <td valign="top" class="company_list"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="-webkit-border-radius: 8px;
-moz-border-radius: 8px;
border-radius: 8px;">
          <thead>
            <tr class="odd">
              <th width="13%" style="-webkit-border-top-left-radius: 8px;
-moz-border-radius-topleft: 8px;
border-top-left-radius: 8px;" scope="col" class="leftborder">Monitor</th>
              <th width="10%" style="-webkit-border-top-right-radius: 8px;
-moz-border-radius-topright: 8px;
border-top-right-radius: 8px;" scope="col" class="rightborder">Action</th>          
            </tr>
          </thead>
          <tfoot>
          </tfoot>
          <tbody>
          <?php if(count($monitors) < 1){ ?>
          <tr>
          <td> No result Found ! </td>
          </tr>
          <?php } ?>
          <?php $i= 0; foreach ($monitors as $monitor): 	?>
          <?php $td_class = ($i%2 == 0) ? 'even':'odd'; $i++; ?>
          <tr class="<?php echo $td_class; ?>">
           <td>
		   <?php 
           if($this->Global->has_access('monitors','timeline')){		
                echo $this->Html->link($monitor['Monitor']['name'], array('controller'=>'videos','action' => 'listVideo',0,$monitor['Monitor']['id']),array('escape' => false)); 
            }
		    ?>&nbsp;</td>
           <td>
                <?php 
                $delete_img = $this->Html->image('icon-cross.png');
                if($this->Global->has_access('monitors','add')){		
                echo $this->Html->link($this->Html->image("icon-edit.png"), array('controller'=>'monitors','action' => 'add', $monitor['Monitor']['id']),array('escape' => false)); 
                }
                ?>
                 <?php 
                if($this->Global->has_access('monitors','timeline')){		
                echo $this->Html->link(' View Timeline ', array('controller'=>'videos','action' => 'listVideo',0,$monitor['Monitor']['id']),array('escape' => false)); 
                }
                ?>   
            </td>
          </tr>
          <?php endforeach; ?>    
          </tbody>
        </table>
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
       <div class="pagination">
        <div class="right"> 
        <?php echo $this->Paginator->prev('<< Previous', null, null, array('class' => 'hide')); ?>
        <?php echo $this->Paginator->numbers(array('separator'=>'')); ?>
        <?php echo $this->Paginator->next('Next >>', null, null, array('class' => 'hide')); ?></div>
        </div>
       </div>
     <!--pagination ends here-->  
</td>
      </tr>
    </tbody></table>
</div>
</div>
</div>	