<?php
$url = Router::url('/', true);
//getting user_info from db for user authentication checking
$user_data = $this->Session->read('user.data');	
$role = $this->Global->get_current_role_id();	
$role_name = $this->Global->get_current_role_name();
$user_name = $this->Global->get_current_username();
$user_id = $this->Global->is_logined();		
?>

<?php if($user_id){ ?>

<!--header starts here-->
<div class="header">
  
 	<div class="header">
	<div class="inner">
    <div class="logo">
    <a class="menu" href="#" link="<?php echo Router::url('/', true); ?>">
    <?php echo $this->Html->image('logo.png', array('width' => 233,'height'=>69)); ?>
    </a>
 	</div>
    <div class="topright_wrapper">
	<div class="icon_wrap">
      <ul>
      <?php if( ($this->Global->get_current_role_id() == WEBMASTER) ){ ?>	
        <li>
		<a class="menu" href="#" link="<?php echo Router::url('/', true); ?>companies">
        <?php echo $this->Html->image('icon-company.png',array('height'=>"54",'width'=>"54")); ?>
        <span>Company</span></a>       
        </li>
      <?php }else{ ?>
      	<li>
		<a class="menu" href="#" link="<?php echo Router::url('/', true); ?>monitors">
        <?php echo $this->Html->image('icon-monitor.png',array('height'=>"54",'width'=>"54")); ?>
        <span>Monitors</span></a>       
        </li>
      <?php } ?>
        <li><!--<a href="#"><img width="42" height="46" src="images/icon-user.png"><span>Users</span></a>-->
         <a class="menu" href="#" link="<?php echo Router::url('/', true); ?>users/listUser">
         <?php echo $this->Html->image('icon-user.png',array('height'=>"54",'width'=>"54")); ?> 
         <span>Users</span></a>        
        </li>
        <?php if( ($this->Global->get_current_role_id() != WEBMASTER) ){ ?>	
        <li>
        <a class="menu" href="#" link="<?php echo Router::url('/', true); ?>videos/add">
        <?php echo $this->Html->image('icon-video1.png',array('height'=>"54",'width'=>"54")); ?> 
        <span>Videos</span></a>       
        </li>
        <?php } ?>
      </ul>
    </div>
    </div>
    </div>
    </div>
    </div>
    
<?php //echo $this->Session->flash(); ?>
<!--header ends here-->
<?php } ?>

<span id="msg"></span>

