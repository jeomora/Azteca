<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link href="<?php echo base_url('/assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('/assets/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('/assets/css/animate.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('/assets/css/style.css'); ?>" rel="stylesheet">

</head>

<body class="blue-bg">

	<div class="middle-box text-center loginscreen  animated fadeInDown">
		<div>
			<div>
				<img alt="image" width="350px;" style="margin:auto; padding: 5px;" class="img-responsive" src="<?php echo base_url("/assets/img/system_logo.png"); ?>" />
			</div>
			<h3>Administración</h3>
			<p>Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
				<!--Continually expanded and constantly improved Inspinia Admin Them (IN+)-->
			</p>
			<p>Login in. To see it in action.</p>
			<?php echo form_open("Auth/login",'class="m-t" role="form"');?>
				<div class="form-group">
					<input type="email" class="form-control" placeholder="ejemplo@email.com" name="identity" required="">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" placeholder="********" name="password" required="">
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b">Entrar</button>
			<?php echo form_close();?>
		</div>
	</div>

	<!-- Mainly scripts -->
	<script src="<?php echo base_url('/assets/js/jquery-2.1.1.js'); ?>"></script>
	<script src="<?php echo base_url('/assets/js/bootstrap.min.js'); ?>"></script>

</body>

</html>
