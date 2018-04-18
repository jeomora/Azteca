<style type="text/css" media="screen">
	tr:hover {background-color: #21b9bb !important;}
	tr:hover > td{color: white !important;}
	.modal-body{height: 65vh;overflow-y: scroll;}
</style>
<?php 
if(!$this->session->userdata("username")){
	redirect("Welcome/Login", "");
}
?>


<div class="col-md-12 ibox-content" style="padding: 4%">
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
	</div>
	<div class="row"></div>
	<div class="row">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="table_prov_cot">
				<thead>
					<tr>
						<th style="width: 5%">CÓDIGO</th>
						<th>DESCRIPCIÓN</th>
						<th>PRECIO</th>
						<th>PRECIO C/PROMOCIÓN</th>
						<th>OBSERVACIONES</th>
					</tr>
				</thead>
				<tbody class="cot-prov">
					
				</tbody>
			</table>
		</div>
	</div>
</div>