<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<style>
	.buscale {width: 50vw;padding: 5px 10px;border: 2px solid #23c6c8;font-size: 16px;border-radius: 5px;}
	div#page-wrapper{background: #008b8b;}
	.top-navigation .nav>li>a{color:#000;background:#fff;}
	.white-bg .navbar-fixed-top, .white-bg .navbar-static-top{background: #fff;}
	.top-navigation .navbar-brand{background:#fff;color:#000;}
	#progress{color:#FFF !important;background:#008b8b !important;}
	.logo_img>img{border: 4px solid #008b8b;}
	.top-navigation .navbar-nav .dropdown-menu{background:#004479;color:#ffffff;}
	.top-navigation .wrapper.wrapper-content{padding:40px 0px !important;}
	td{font-family:sans-serif;font-size:14px}
	.table > thead > tr > th, .table > tbody > tr > th, .table > thead > tr > td, .table > tbody > tr > td{border: 1px solid #000000;}
	tr:hover {background: #c6e8ce !important;}
}
</style>
<div class="wrapper wrapper-content animated fadeInRight" style="padding-left: 0;padding-right: 0">
	<div class="row">
		<div class="col-lg-12" style="padding: 0">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>INGRESAR PEDIDOS PENDIENTES</h5>
				</div>
				<div class="ibox-content" style="padding-top: 4rem;">
					<div class="btn-group">
						<a href="<?php echo base_url('assets/uploads/cotizaciones/Pedidos Pendientes.xlsx'); ?>" target="_blank" data-toggle="tooltip" title="Decargar" class="btn btn-info" download><i class="fa fa-cloud-download"></i><span class="nav-label" download></span> Descargar Formato </a>
					</div>
					<?php echo form_close(); ?>
					<div class="btn-group">
						<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;margin-top: -2rem;">
							Subir Formato de Pedidos Pendientes
						</div>
						<?php echo form_open_multipart("", array('id' => 'upload_pendientes')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_pendientes" name="file_pendientes"/>
							</div>
						<?php echo form_close(); ?>
					</div>

					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_cot_v">
							<thead>
								<tr class="trpendientes">
									<th style="background-color: #000;color:#FFF;">CÓDIGO</th>
									<th style="background-color: #000;color:#FFF;">DESCRIPCIÓN</th>
									<th style="width: 80px;background-color: #C00000">CEDIS/SUPER</th>
									<th style="width: 80px;background-color: #01B0F0">ABARROTES</th>
									<th style="width: 80px;background-color: #FF0000">PEDREGAL</th>
									<th style="width: 80px;background-color: #E26C0B">TIENDA</th>
									<th style="width: 80px;background-color: #C5C5C5">ULTRA</th>
									<th style="width: 80px;background-color: #92D051">TRINCHERAS</th>
									<th style="width: 80px;background-color: #B1A0C7">AZT MERCADO</th>
									<th style="width: 80px;background-color: #DA9694">TENENCIA</th>
									<th style="width: 80px;background-color: #4CACC6">TIJERAS</th>
								</tr>
							</thead>
							<tbody class="tablePendv">

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
