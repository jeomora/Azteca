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
		var base_url = "<?php echo base_url("/") ?>";//No carga el archivo index
		var site_url = "<?php echo site_url("/") ?>";//Si carga el index 
		var user_name = "<?php echo strtoupper($usuario['username']) ?>";//
	</script>

	<!-- Bootstrap CSS-->
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/bootstrap.min.css') ?>" >

	<link rel="stylesheet" href="<?php echo base_url('/assets/fonts/css/font-awesome.css') ?>" >

	<link rel="stylesheet" href="<?php echo base_url('/assets/css/animate.css') ?>" >

	<link rel="stylesheet" href="<?php echo base_url('/assets/css/style.css') ?>" >
	<!-- Toastr style -->
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/plugins/toastr/toastr.min.css') ?>" >
	<!-- Favicon-->
	<link rel="shortcut icon" href="<?php echo base_url('/assets/img/abarrotes.png') ?>" >
	<?php if (isset($links) && $links): ?>
		<?php foreach ($links as $link): ?>
			<link rel="stylesheet" href="<?php echo base_url($link.'.css') ?>">
		<?php endforeach ?>
	<?php endif ?>
	<!-- Data Picker style -->
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/plugins/datapicker/datepicker3.css') ?>" >
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/plugins/colorpicker/bootstrap-colorpicker.min.css') ?>" >
	
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/plugins/chosen/chosen.css')?>" >
<!--Start of Zendesk Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="https://v2.zopim.com/?5srbeJgRLHkZUr7aKgWBFZ7qZPEKH0hW";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>
<!--End of Zendesk Chat Script-->
</head>

	<!-- Estructura de la ventana modal par insertar, modificar y eliminar datos -->
	<div class="modal inmodal fade" id="myModal" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content animated flipInY">

			</div>
		</div>
	</div>

<body class="top-navigation">

	<div id="cover" hidden="true">
		<div class="sk-spinner sk-spinner-cube-grid loader_cover"> </div>
		<span class="loaderText">
			<p style="font-size: 30px; font-weight: bold;"> <?php echo strtoupper($usuario['username']) ?> </p>
			<p style="font-size: 30px "> Espere mientras se carga la información...</p>
		</span>
	</div>
