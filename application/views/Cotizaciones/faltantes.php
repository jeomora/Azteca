<style type="text/css" media="screen">
	tr:hover {background-color: #21b9bb !important;}
	tr:hover > td{color: black !important;}
	.modal-body{height: 65vh;overflow-y: scroll;}
	.searchboxs{display: none}
	.col-md-12.ibox-content {
	    overflow-y: scroll;
	    height: 88vh;
	}
</style>
<?php
if(!$this->session->userdata("username")){
	redirect("Welcome/Login", "");
}
?>
<?php echo form_open_multipart("", array('id' => 'upload_faltantes')); ?>
<div class="col-md-12 ibox-content" style="padding: 4%;padding-top: 2%">
	<div class="row">
		<h3>Seleccione un proveedor</h3>
	</div>

	<div class="row">
		<div class="col-lg-3" style="margin-bottom: 1rem">
			<select name="id_pro" id="id_pro" class="form-control">
				<option value="nope">Seleccionar...</option>
					<?php foreach ($proveedores as $key => $value): ?>
						<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre.' '.$value->apellido ?></option>
					<?php endforeach ?>
			</select>
		</div>
		<div class="col-lg-3" style="margin-bottom: 1rem">
			<button class="btn btn-info" name="camb" data-toggle="tooltip" title="Cambiar" type="button" id="camb" style="display: none">
				<i class="fa fa-refresh"></i> Cambiar Proveedor
			</button>
		</div>

	</div>

	<div class="contenids">
		<div class="col-lg-12" style="height: 2rem;border-top:1px solid #FF6805">

		</div>
		<div class="row">
			<div class="col-lg-2" >
				<button class="btn btn-primary" name="nuevo_fal" data-toggle="tooltip" title="Agregar Faltante" type="button" id="nuevo_fal" style="display: none">
					<i class="fa fa-plus"></i> Agregar Faltante
				</button>
			</div>
			<div class="col-lg-3 btn-group" style="display: none" id="falts">
					<div class="col-sm-4 faltsc">
						<input class="btn btn-info" type="file" id="file_faltantes" name="file_faltantes" value="" size="20" />
					</div>
				<?php echo form_close(); ?>
				<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;">
					Subir formato de faltantes
				</div>
			</div>
			<div class="col-lg-4"></div>
			<div class="col-lg-3 searchboxs">
				<label>Buscar:<input class="form-control input-sm" type="text" id="myInput" onkeyup="myFunction()" placeholder="Nombre..."></label>
			</div>
		</div>

		<div class="row">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="table_prov_cots">
					<thead>
						<tr>
							<th>CÓDIGO</th>
							<th>DESCRIPCIÓN</th>
							<th>SEMANAS FALTANTES</th>
							<th>FECHA TERMINO</th>
							<!--<th>ACCIONES</th>-->
						</tr>
					</thead>
					<tbody class="cot-prov">

					</tbody>
				</table>
			</div>
		</div>

	</div>
</div>

<?php echo form_close(); ?>
