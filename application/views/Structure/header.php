<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sistema</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="all,follow">

	<script type="text/javascript">
		/*window.onload =	function(){
			if(typeof history.pushState === "function"){
				history.pushState("jibberish", null, null);
				window.onpopstate = function () {
					history.pushState("newjibberish", null, null);
					//location.href="";
					console.log("Click atrás \n");
				};
			
				}else{
					var ignoreHashChange = true;
					window.onhashchange = function () {
						if (!ignoreHashChange) {
							ignoreHashChange = true;
							window.location.hash = Math.random();
							console.log("Aqui otra acción \n");
						}else{
							ignoreHashChange = false;
						}
					};
				}
			}*/

		var base_url = "<?php echo base_url("/") ?>";//No carga el archivo index
		var site_url = "<?php echo site_url("/") ?>";//Si carga el index 
		var user_name = "<?php echo strtoupper($usuario->username) ?>";//

	</script>

	<!-- Bootstrap CSS-->
	<link href="<?php echo base_url('/assets/css/bootstrap.min.css') ?>" rel="stylesheet" >

	<link href="<?php echo base_url('/assets/fonts/css/font-awesome.css') ?>" rel="stylesheet">

	<link href="<?php echo base_url('/assets/css/animate.css') ?>" rel="stylesheet">

	<link href="<?php echo base_url('/assets/css/style.css') ?>" rel="stylesheet">
	<!-- Toastr style -->
	<link href="<?php echo base_url('/assets/css/plugins/toastr/toastr.min.css') ?>" rel="stylesheet">
	<!-- Favicon-->
	<link href="<?php echo base_url('/assets/img/favicon.ico') ?>" rel="shortcut icon">
	<?php if (isset($links) && $links): ?>
		<?php foreach ($links as $link): ?>
			<link rel="stylesheet" href="<?php echo base_url($link.'.css') ?>">
		<?php endforeach ?>
	<?php endif ?>
	<!-- Data Picker style -->
	<link href="<?php echo base_url('/assets/css/plugins/datapicker/datepicker3.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('/assets/css/plugins/colorpicker/bootstrap-colorpicker.min.css') ?>" rel="stylesheet">

	<link href="<?php echo base_url('/assets/css/plugins/chosen/chosen.css')?>" rel="stylesheet">

</head>

	<!-- Estructura de la ventana modal par insertar, modificar y eliminar datos -->
	<div class="modal inmodal fade" id="myModal" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content animated flipInY">

			</div>
		</div>
	</div>

<body class="top-navigation">