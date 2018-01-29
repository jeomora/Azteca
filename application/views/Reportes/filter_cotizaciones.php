<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>CONSULTAR COTIZACIONES</h5>
				</div>
				<div class="ibox-content">
					<div class="panel-body">
						<?php echo form_open("Reportes/fill_reporte", array("id" => 'consultar_cotizaciones', "target" => '_blank')); ?>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="fecha_registro">Fecha consultar</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" name="fecha_registro" id="fecha_registro" class="form-control datepicker" value="" placeholder="00-00-0000">
								</div>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<label for="id_proveedor">Proveedores</label>
								<select name="id_proveedor" id="id_proveedor" class="form-control chosen-select">
									<option value="">Seleccionar...</option>
									<?php if ($proveedores): foreach ($proveedores as $key => $value): ?>
										<option value="<?php echo $value->id ?>"><?php echo strtoupper($value->username) ?></option>
									<?php endforeach; endif ?>
								</select>
							</div>
						</div>

						<!-- <div class="col-sm-12">
							<div class="pull-right">
								<button class="btn btn-danger" data-toggle="tooltip" name="pdf" title="PDF" type="submit">
									<i class="fa fa-file-pdf-o"></i>
								</button>
								<button class="btn btn-primary" data-toggle="tooltip" name="excel" title="Excel" type="submit">
									<i class="fa fa-file-excel-o"></i>
								</button>
							</div>
						</div> -->

						<input type="hidden" id="name_user" value="<?php echo strtoupper($usuario->username) ?>"/>

						<?php echo form_close(); ?>
						<div class="col-sm-2">
							<a title="Filtrar" id="filter_show" data-toggle="tooltip" class="btn btn-info" href="#" >
								<i class="fa fa-filter"></i>
							</a>
						</div>

						<div id="respuesta_show" class="row">

						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>