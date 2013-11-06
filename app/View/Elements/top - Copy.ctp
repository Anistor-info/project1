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
  
  <div class="topleft_wrapper">
    <div class="topleft_iconwrapper"> 
     <?php echo $this->Html->link($this->Html->image('admin-01.png', array('width' => 54,'height'=>54)),array('controller' => 'users', 'action'=>'home'),array('escape' => false))?>
    <?php echo $this->Html->link('Home',array('controller' => 'users', 'action'=>'home')); ?>
     <?php echo $this->Html->link($this->Html->image('profile-01.png', array('width' => 54,'height'=>54)),array('controller' => 'users', 'action'=>'profile'),array('escape' => false))?>
    <?php echo $this->Html->link('Edit Profile',array('controller' => 'users', 'action'=>'profile')); ?>
     <?php echo $this->Html->link($this->Html->image('help-01.png', array('width' => 54,'height'=>54)),array('controller' => 'users', 'action'=>'#'),array('escape' => false))?>
    <a href="#">Help</a> 
    <?php echo $this->Html->link($this->Html->image('logout-01.png', array('width' => 54,'height'=>54)),array('controller' => 'users', 'action'=>'logout'),array('escape' => false))?>
    <?php echo '<li>'.$this->Html->link('Logout',array('controller' => 'users', 'action'=>'logout')).'</li>'; ?>
    </div>
  </div>

<div class="logo">
<?php echo $this->Html->image('logo.png', array('width' => 201,'height'=>95))?>
</div>
</div>

<?php echo $this->Session->flash(); ?>
<!--header ends here-->
<?php } ?>


<script>
var baseUrl = '<?php echo Router::url('/', true); ?>';
</script>