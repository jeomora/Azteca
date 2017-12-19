<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link href="<?php echo base_url('/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('/assets/font-awesome/css/font-awesome.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('/assets/css/animate.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('/assets/css/style.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('/assets/img/favicon_login.png') ?>" rel="shortcut icon">


</head>
<style type="text/css">
	img {
		border-radius: 20px 0px 0px 20px;
		box-shadow: 0 0 15px #E0FFF0
		background: #E0FFF0;
	}
</style>
<body class="blue-bg">

	<div class="loginColumns animated fadeInDown">
		<div class="row">

			<div class="col-md-6">
				<div class="logo_img">
					<img  src="<?php echo base_url('/assets/img/logo_abarrotes.jpg') ?>" />
				</div>
			</div>

			<div class="col-md-6">
				<div class="ibox-content">
					<?php echo form_open("Auth/login",'class="m-t" role="form"');?>
						<div class="form-group">
							<input type="email" class="form-control" placeholder="ejemplo@email.com" name="identity" required="">
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
	<script src="<?php echo base_url('/assets/js/jquery-2.1.1.js') ?>"></script>
	<script src="<?php echo base_url('/assets/js/bootstrap.min.js') ?>"></script>

</body>

</html>
