<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Add User</title>
<link href="images/favicon.ico" type="image/x-icon" rel="shortcut icon">
<link type="text/css" rel="stylesheet" href="style/style.css">

</head>
<?php echo $this->Html->script('create'); ?>
<body style="background-color:#e7e7e7">

<!--header starts here-->
<div class="header">
<div class="reg_header">
  <div class="reg_header_left">
  <span class="reg_header_logo">
  <a href="<?php echo Router::url('/', true); ?>">
  <?php echo $this->Html->image('reg_logo.png',array('width'=>"221",'height'=>"81")); ?>
  </a>
  </span></div>
  <div class="iconbox">
  <a href="<?php echo Router::url('/', true); ?>users/login">
  <?php echo $this->Html->image('icon-logout.png',array('width'=>"40",'height'=>"39")); ?>
  </a>
  <span><a href="<?php echo Router::url('/', true); ?>users/login">Login</a></span></div>
</div>
</div>
<!--header ends here-->

<!--container starts here-->
<div class="reg_container">
   <?php echo $this->Form->create(array('User','action'=>'create')); ?>
     <?php echo $this->Form->input('user_id',array('type'=>'hidden'));	?>
    <table width="95%" cellspacing="0" cellpadding="0" border="0" align="center" class="form_label">
      <tbody><tr>
        <td height="50" colspan="2"><div>
          <div> 
            <span>Don't have an account?</span> <br>
              <h2><strong>Register Here by filling out the form below</strong></h2>
          </div>
        </div></td>
      </tr>
      <tr>
        <td height="40" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="30%" height="30">
        <label for="textfield">Username:</label></td>
        <td><?php echo $this->Form->input('user_name',array('label'=>false)); ?></td>
      </tr>
      <tr>
        <td height="30">&nbsp;</td>
        <td valign="top" align="left" class="invalid">&nbsp;</td>
      </tr>
      <tr>
        <td height="30"><label for="textfield2">Password:</label></td>
        <td><?php  echo $this->Form->input('password',array('type'=>'password','label'=>false)); ?></td>
      </tr>
      <tr>
        <td height="30">&nbsp;</td>
        <td valign="top" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td height="30"><label for="textfield3">Confirm Password:</label></td>
        <td><?php echo $this->Form->input('confirm_password',array('type'=>'password','label'=>false)); ?></td>
      </tr>
      <tr>
        <td height="30">&nbsp;</td>
        <td valign="top" align="left"></td>
      </tr>
      <tr>
        <td height="30"><label for="textfield4">Email Address:</label></td>
        <td> <?php echo $this->Form->input('email',array('label'=>false)); ?></td>
      </tr>
      <tr>
        <td height="30">&nbsp;</td>
        <td valign="top" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td height="30"><label for="textfield2">First name:</label></td>
        <td><?php echo $this->Form->input('first_name',array('label'=>false));  ?></td>
      </tr>
       <tr>
        <td height="30">&nbsp;</td>
        <td valign="top" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td height="30"><label for="textfield2">Last name:</label></td>
        <td><?php echo $this->Form->input('last_name',array('label'=>false));  ?></td>
      </tr>
      <tr>
        <td height="40">&nbsp;</td>
        <td valign="middle" align="left"><input type="checkbox" id="checkbox" name="checkbox">
          <span style=" font-size:12px;">I have read and agree to the </span><span class="termlink"><a href="#">Terms of Service.</a></span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div class="submitwrapper">
         <?php echo $this->Form->input('token',array('type'=>'hidden')); ?>
          <input name="button" type="submit"  id="button" value=""  class="button"/>
		 <?php echo $this->Form->end();?>
        </div></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </tbody></table>
  </form>
</div>
<!--container ends here-->
<!--footer starts here-->

<div class="reg_footer">
<div class="reg_footer_wrapper">
<span class="left">Outbackmedia.tv&copy; 2012 </span>
<span class="right"><a href="#">About Us</a> <a href="#">Pricing</a> <a href="#">Apps</a> <a href="#">Developers</a> <a href="#">Jobs</a> <a href="#">Blog</a> <a href="#">Terms</a> <a href="#">Privacy</a> <a href="#">Policy Help</a><a href="#">Help</a></span>
</div>
</div>
<!--footer ends here-->


</body></html>