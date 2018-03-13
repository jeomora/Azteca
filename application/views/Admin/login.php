<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/bootstrap.min.css') ?>" >
	<link rel="stylesheet" href="<?php echo base_url('/assets/fonts/css/font-awesome.css') ?>" >
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/animate.css') ?>" >
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/style.css') ?>" >
	<link rel="shortcut icon" href="<?php echo base_url('/assets/img/favicon_login.png') ?>" >
</head>
<style type="text/css">
	img {
		border-radius: 20px 0px 0px 20px;
		box-shadow: 0 0 15px #E0FFF0
		background: #E0FFF0;
	}
	@media screen and (max-width: 600px){
		.loginColumns{width: 100%;margin: 0px;}
		img{border-radius: 0px;width: 100%;height: 50%}
	}

</style>
<body class="blue-bg">

	<div class="loginColumns animated flipInY">
		<div class="row">
			<h3 class="col-md-6 col-md-offset-6" style="color:#990000"> <?php echo isset($message) ? $message : '' ?> </h3>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="logo_img">
					<img  src="<?php echo base_url('/assets/img/logo_abarrotes.jpg') ?>" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="ibox-content">
					<?php echo form_open("Welcome/login/",'class="m-t" role="form"');?>
						<div class="form-group">
							<input type="email" class="form-control" placeholder="ejemplo@email.com" name="email" required="" value="<?php echo set_value('email')?>" >
						</div>
						<div class="form-group">
							<input type="password" class="form-control" placeholder="********" name="password" required="">
						</div>
						<button type="submit" class="btn btn-success block full-width m-b">Entrar</button>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>

	<!-- Mainly scripts -->
	<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-2.1.1.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('/assets/js/bootstrap.min.js') ?>"></script>

</body>

</html>
