<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE ARTÍCULOS</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_producto">
							<i class="fa fa-plus"></i>
						</button>
					</div>
					<div class="btn-group">
						<?php echo form_open("Productos/print_productos", array("id" => 'reporte_cotizaciones', "target" => '_blank')); ?>
							<button class="btn btn-info" name="excel" data-toggle="tooltip" title="Exportar a Excel" type="submit">
								<i class="fa fa-cloud-download"></i> Descargar Formato 
							</button>
						<?php echo form_close(); ?>
					</div>
					<div class="btn-group">
						<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;margin-top: -2rem;">
							Subir Varios Productos
						</div>
						<?php echo form_open_multipart("", array('id' => 'upload_productos')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_productos" name="file_productos" value="" size="20" />
							</div>
						<?php echo form_close(); ?>
					</div>
						<table class="table table-striped table-bordered table-hover" id="table_productos">
							<thead>
								<tr>
									<th>NO</th>
									<th>CÓDIGO</th>
									<th>NOMBRE</th>
									<th>FAMILIA</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
</div>