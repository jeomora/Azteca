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
</div>
