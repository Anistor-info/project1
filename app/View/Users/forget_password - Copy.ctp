<?php /*echo $this->Html->css('style', 'stylesheet', array("media"=>"all" ), false); ?>
<div class="main-div">
<h2>Login</h2>
<?php
echo $this->Form->create('User',array('action'=>'login'));
echo $this->Form->input('username',array('div' => array( 'class' => 'glass-pill')));
echo $this->Form->input('password',array('div' => array( 'class' => 'glass-pill')));
echo $this->Form->end('Login');*/
?>
<!--</div>-->

<div class="login_page">
<div class="wraper">
<div class="admin_message white font12">Cu albucius disputationi sea, quo nobis quando ne. Ad eam prima timeam tincidunt. Feugait aliquando ad his, graeco animal has in.</div>
<br /><br /><br /><br /><br />

<div class="login_box">
<div class="login_inner">
<div class="heading">Welcome to <strong>Outback Media</strong></div>
<?php echo $this->Session->flash(); ?>
<br />
<div id="advancedCall">
<?php
echo $this->Form->create('User',array('action'=>'forgetPassword'));
?>
<div class="right font12 grey_dark label" style="color:#000"><?php echo $this->Html->link('Login',array('controller' => 'users', 'action'=>'login')); ?></div>
<?php
echo $this->Form->input('email',array('class' => 'login_input','label'=>false,'value'=>'Email'));
?>
<div style="overflow: hidden;" class="margintop25">
<div class="left"></div>
</div>
<br /><br />
<center>
<?php
$options = array('label'=>'login','class'=>'b_submit h_middle');
echo $this->Form->end($options);
?>
</center>
</div>
</div>
</div>
</div>
</div>
