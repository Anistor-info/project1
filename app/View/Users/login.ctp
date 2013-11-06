<style>
.failure_message {
    background: none repeat scroll 0 0 #747474;
    border: 1px solid #5E5E5E;
    border-radius: 5px 5px 5px 5px;
    color: #FFFFFF;
    display: block;
    font-family: 'corbel',Arial,Helvetica,sans-serif;
    font-size: 16px;
    font-weight: inherit;
    left: 34.5%;
    margin: 0 auto;
    min-width: 30%;
    overflow: hidden;
    padding: 5px;
    position: absolute;
    top: 103px;
    z-index: 999999;
}
body{ background-color:#E7E7E7;}
</style>

<div class="login_page">
<div class="wraper">
<div class="admin_message white font12">Cu albucius disputationi sea, quo nobis quando ne. Ad eam prima timeam tincidunt. Feugait aliquando ad his, graeco animal has in.</div>
<br /><br /><br /><br /><br />

<div class="login_box">
<div class="login_inner">
<div class="heading">
<?php echo $this->Html->script('login'); ?>
<?php echo $this->Html->image('media-logo.png',array('height'=>67,'width'=>170)); ?>
<br />
Welcome to <strong>Outback Media</strong></div>
<?php echo $this->Session->flash(); ?>
<div id="advancedCall">

<?php
echo $this->Form->create('User',array('action'=>'login'));
echo $this->Form->input('username',array('class' => 'login_input','label'=>false,'value'=>'Username')); ?>
<div class="error-message" id="user-error"></div>
<?php
echo $this->Form->input('password',array('name'=>'password','value'=>'Password','class' => 'login_input','label'=>false));
?>
<div class="error-message" id="password-error"></div>
<div style="overflow: hidden;" class="margintop25">
<div class="left"></div>
<div class="right font12 grey_dark label" style="margin-top:10px; color:#000"><?php echo $this->Html->link('Forgot password ?',array('controller' => 'users', 'action'=>'forgetPassword')); ?></div>
</div>
<?php
$options = array('label'=>'login','class'=>'b_login');
echo $this->Form->end($options);
?>
</div>
</div>
</div>
</div>
</div>
