<style type="text/css" media="screen">
	tr:hover {background-color: #21b9bb !important;}
	tr:hover > td{color: white !important;}
	.modal-body{height: 65vh;overflow-y: scroll;}
	.searchboxs{display: none}
	.top-navigation #page-wrapper {margin-left: 0;overflow-y: scroll;}
</style>
<?php
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>


<div class="col-md-12 ibox-content" style="padding: 4%">
	<div class="row">
		<h3>Seleccione un proveedor</h3>
	</div>
	<?php echo form_open("Cotizaciones/fill_excel_pro", array("id" => 'reporte_cotizaciones', "target" => '_blank')); ?>
	<div class="row">
		<div class="col-lg-3" style="margin-bottom: 1rem">
			<select name="id_pro" id="id_pro" class="form-control">
				<option value="nope">Seleccionar...</option>
					<?php foreach ($proveedores as $key => $value): ?>
						<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
					<?php endforeach ?>
			</select>
		</div>

	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="ibox float-e-margins" style="display: none">
				<div class="ibox-title">
					<h5>LISTADO DE COTIZACIONES</h5>
				</div>
				<div class="ibox-content" style="padding-top: 5rem">
					<div class="btn-group">
						<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_cotizacion" type="button">
							<i class="fa fa-plus"></i> Agregar Cotización
						</button>
					</div>
					<div class="btn-group">

							<button class="btn btn-primary" name="excel" data-toggle="tooltip" title="Exportar a Excel" type="submit">
								<i class="fa fa-file-excel-o"></i> Descargar formato cotizaciones
							</button>
					</div>
					<?php echo form_close(); ?>
					<div class="btn-group">
						<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;margin-top: -2rem;">
							Subir formato de cotizaciones
						</div>
						<?php echo form_open_multipart("", array('id' => 'upload_cotizaciones')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_otizaciones" name="file_otizaciones"/>
							</div>
						<?php echo form_close(); ?>
					</div>

					<div class="btn-group">
						<?php echo form_open_multipart("", array("id" => "end_cotizaciones")); ?>
						<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" id="end_cotizacion" type="button">
							<i class="fa fa-trash"></i> Eliminar Cotización Semana
						</button>
						<?php echo form_close(); ?>
					</div>

					<div class="btn-group searchboxs">
						<label>Buscar:<input class="form-control input-sm" type="text" id="myInput" onkeyup="myFunction()" placeholder="Nombre..."></label>
					</div>

					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_cot_proveedores">
							<thead>
								<tr>
									<th>DESCRIPCIÓN</th>
									<th>CODIGO</th>
									<th>FECHA REGISTRO</th>
									<th>PRECIO FACTURA</th>
									<th>PRECIO FACTURA C/PROMOCIÓN</th>
									<th>DESCUENTO ADICIONAL</th>
									<th colspan="2">PROMOCIÓN</th>
									<th>OBSERVACIONES</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody class="cot-prov">

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
