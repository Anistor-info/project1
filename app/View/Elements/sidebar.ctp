<?php 
if($this->Global->is_logined()){ ?>	
<div class="sidebar">
  <div class="border_boxbtm">&nbsp;</div>
  <div class="arrowlistmenu">
<h3 style="cursor: pointer;" class="menuheader">
<span>
<?php echo $this->Html->image('home-icon.png', array('height' => 18,'width'=>18))?>
</span><?php echo $this->Html->link('Home',array('controller' => 'users', 'action'=>'home')); ?></h3>
<h3 class="menuheader expandable openheader" headerindex="0h">
<span class="accordprefix"></span>
<?php echo $this->Html->image('profile.png', array('height' => 18,'width'=>18))?>User Profile<span class="accordsuffix"></span></h3>
<ul class="categoryitems" contentindex="0c" style="display: block;">
<li><?php echo $this->Html->link('Edit Profile',array('controller' => 'users', 'action'=>'profile')); ?></li>
<li><?php echo $this->Html->link('Change Password',array('controller' => 'users', 'action'=>'ChangePassword ')); ?></li>
</ul>

<?php if( ($this->Global->get_current_role_id() != COMPANY_USER) ){ ?>	
<h3 class="menuheader expandable" headerindex="1h"><span class="accordprefix"></span>
<?php echo $this->Html->image('icon-users.png', array('height' => 18,'width'=>18))?>
User<span class="accordsuffix"></span></h3>
<ul class="categoryitems" contentindex="1c" style="display: none;">
<li><?php echo $this->Html->link('All Users',array('controller' => 'users', 'action'=>'index')); ?></li>
<?php if($this->Global->has_access('users','add') and ($this->Global->get_current_role_id() != WEBMASTER) ){ ?>	
<li><?php echo $this->Html->link('Add New Users',array('controller' => 'users', 'action'=>'add')); ?></li>
<?php } ?>
</ul>
<?php } ?>

<?php if( ($this->Global->get_current_role_id() == WEBMASTER) ){ ?>	
<h3 class="menuheader expandable" headerindex="1h"><span class="accordprefix"></span>
<?php echo $this->Html->image('icon-users.png', array('height' => 18,'width'=>18))?>
Company<span class="accordsuffix"></span></h3>
<ul class="categoryitems" contentindex="1c" style="display: none;">
<li><?php echo $this->Html->link('All Companies',array('controller' => 'companies', 'action'=>'index')); ?></li>
<li><?php echo $this->Html->link('Add New company',array('controller' => 'companies', 'action'=>'add')); ?></li>
</ul>
<?php } ?>

<h3 class="menuheader expandable" headerindex="2h"><span class="accordprefix"></span>
<?php echo $this->Html->image('media-bins.png', array('height' => 18,'width'=>18))?>
Videos<span class="accordsuffix"></span></h3>
<ul class="categoryitems" contentindex="2c" style="display: none;">
<li><?php echo $this->Html->link('Add Video',array('controller' => 'videos', 'action'=>'add')); ?></li>
<!--<li><?php echo $this->Html->link('All Video',array('controller' => 'videos', 'action'=>'listVideo')); ?></li>-->
</ul>

<?php if( ($this->Global->get_current_role_id() == COMPANY_SUPER_ADMIN) or ($this->Global->get_current_role_id() == COMPANY_ADMIN) or ($this->Global->get_current_role_id() == COMPANY_USER) ){ ?>	
<h3 class="menuheader expandable" headerindex="1h">
<span class="accordprefix"></span>
<?php echo $this->Html->image('monitors.png',array('width'=>"28",'height'=>"28")); ?>
Monitors
<span class="accordsuffix"></span>
</h3>
<ul class="categoryitems" contentindex="0c" style="display: block;">
<li><?php echo $this->Html->link('List Monitors',array('controller' => 'monitors', 'action'=>'index')); ?></li>
<?php if( ($this->Global->get_current_role_id() == COMPANY_SUPER_ADMIN) or ($this->Global->get_current_role_id() == COMPANY_ADMIN)){ ?>
<li><?php echo $this->Html->link('Add Monitor',array('controller' => 'monitors', 'action'=>'add ')); ?></li>
<?php } ?>
</ul>
<?php } ?>

</div>
<div class="border_boxtop">&nbsp;</div>
</div>
<?php } ?>
