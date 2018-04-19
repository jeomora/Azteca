<style type="text/css" media="screen">
	tr:hover {background-color: #21b9bb !important;}
	tr:hover > td{color: white !important;}
	.modal-body{height: 65vh;overflow-y: scroll;}
	.searchboxs{display: none}
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
		<div class="col-lg-5"></div>
		<div class="col-lg-4 searchboxs">
			<label>Buscar:<input class="form-control input-sm" type="text" id="myInput" onkeyup="myFunction()" placeholder="Nombre..."></label>
		</div>
	</div>
	
	<div class="row">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="table_prov_cots">
				<thead>
					<tr>
						<th>DESCRIPCIÓN</th>
						<th style="width: 5%">CÓDIGO</th>
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