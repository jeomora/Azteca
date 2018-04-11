<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight" id="welcome_container">
	<div class="row col-lg-12">
		<div class="col-lg-4">
			<div class="ibox">
				<div class="ibox-content ">
					<h5 class="m-b-md">ARTICULOS</h5>
					<h2 class="text-navy">
						<i class="fa fa-play fa-rotate-45"></i> <?php echo empty($productos) ? 0 : number_format(sizeof($productos),2,'.',',') ?>
					</h2>
					<small>EN EL SISTEMA</small>
				</div> 
			</div>
		</div>
		<div class="col-lg-4">
			<div class="ibox">
				<div class="ibox-content">
					<h5 class="m-b-md">FAMILIAS</h5>
					<h2 class="text-navy">
						<i class="fa fa-play fa-rotate-45"></i> <?php echo empty($familias) ? 0 : number_format(sizeof($familias),2,'.',',') ?>
					</h2>
					<small>EN EL SISTEMA</small>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="ibox">
				<div class="ibox-content">
					<h5 class="m-b-md">PROVEEDORES</h5>
					<h2 class="text-navy">
						<i class="fa fa-play fa-rotate-45"></i> <?php echo empty($proveedores) ? 0 : number_format(sizeof($proveedores),2,'.',',') ?>
					</h2>
					<small>EN EL SISTEMA</small>
				</div>
			</div>
		</div>
	</div>
	<div class="row col-lg-12">
		<?php if ($ides <> 2): ?>
			<div class="col-md-4 cotizados">
				<div class="ibox-content" style="border-color:#3f47cc">
					<h2 class="text-navy" style="color:#3f47cc">
						<i class="fa fa-cubes"></i> <strong>PRODUCTOS</strong> <br> SIN COTIZACIÓN
					</h2>
					<button id="no_cotizados" class="btn btn-success" data-toggle="tooltip" title="VER" data-id-producto="'.$id_producto.'">
						<i class="fa fa-eye"></i>
					</button>
				</div>
			</div>
			<div class="col-md-4 cotizados">
				<div class="ibox-content" style="border-color:#3f47cc">
					<h2 class="text-navy" style="color:#3f47cc">
						<i class="fa fa-group"></i> <strong>PROVEEDORES</strong> <br> SIN COTIZAR
					</h2>
					<button id="no_cotizo" class="btn btn-success" data-toggle="tooltip" title="VER" data-id-producto="'.$id_producto.'">
						<i class="fa fa-eye"></i>
					</button>
				</div>
			</div>
		<?php endif; ?>
	</div>
	
</div>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-2.1.1.js') ?>"></script>

<script src="../scripts/admin.js" type="text/javascript"></script>