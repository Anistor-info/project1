<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo "Outback Media Server"; ?> :
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('styles');
		echo $this->Html->css('form-styles');
		echo $this->Html->css('jquery.qtip.min');
		echo $this->Html->css('jquery.ui.all');
		echo $this->Html->script('jquery-1.7.2.min');
		echo $this->Html->script('forms-design');
		echo $this->Html->script('jquery.betterTooltip');
		echo $this->Html->script('jquery.ie9gradius');
		echo $this->Html->script('Highlighter');
		echo $this->Html->script('global');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		$user_id = @$this->Global->is_logined();
	?>
</head>
<?php if(!$user_id){ ?>
<body class="loginbg">
<?php }else{ ?>
<?php echo $this->Html->css('accordion'); ?>
<?php echo $this->Html->script('ddaccordion'); ?>
<body>
<?php } ?>
<script>
var baseUrl = '<?php echo Router::url('/', true); ?>';
</script>