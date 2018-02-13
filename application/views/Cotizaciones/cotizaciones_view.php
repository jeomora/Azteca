<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css" media="screen">
	.preciomas{
		width: 8.2rem;
	    height: 13.2rem;
	    background-color: #ea9696;
	    color: red;
	    font-size: 1.5rem;
	    line-height: 13.2rem;
	    font-weight: bold;
	    margin: -8px;
	    text-align: center;
	}
	.preciomenos{
		width: 8.2rem;
	    height: 13.2rem;
	    background-color: #96eaa8;
	    color: green;
	    font-size: 1.5rem;
	    line-height: 13.2rem;
	    font-weight: bold;
	    margin: -8px;
	    text-align: center;
	}
</style>
<div class="wrapper wrapper-content animated fadeInRight" style="padding-left: 0;padding-right: 0">
	<div class="row">
		<div class="col-lg-12" style="padding: 0">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE COTIZACIONES</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<div class="col-sm-2">
							<a href="<?php echo base_url('./assets/uploads/Formato_precios.xlsx'); ?>" target="_blank" data-toggle="tooltip" title="Decargar Formato Precios" class="btn btn-info"><i class="fa fa-cloud-download"></i><span class="nav-label" download></span> </a>
						</div>
					</div>
					<div class="btn-group">
						<?php echo form_open_multipart("", array('id' => 'upload_precios')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_precios" name="file_precios" value=""/>
							</div>
						<?php echo form_close(); ?>
					</div>
					<div class="btn-group">
						<?php echo form_open("Cotizaciones/fill_excel", array("id" => 'reporte_cotizaciones', "target" => '_blank')); ?>
							<button class="btn btn-primary" name="excel" data-toggle="tooltip" title="Exportar a Excel" type="submit">
								<i class="fa fa-file-excel-o"></i>
							</button>
						<?php echo form_close(); ?>
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
							<thead>
								<tr>
									<th>FAMILIAS</th>
									<th>CÓDIGO</th>
									<th>DESCRIPCIÓN</th>
									<th>SISTEMA</th>
									<th>PRECIO 4</th>
									<th>PRECIO MAXIMO</th>
									<th>PRECIO PROMEDIO</th>
									<th>PROVEEDOR</th>
									<th>PRECIO MENOR</th>
									<th>OBSERVACIÓN</th>
									<th>2DO PROVEEDOR</th>
									<th>PRECIO 2</th>
									<th>OBSERVACIÓN</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<!-- Aqui cargamos el contenido -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>