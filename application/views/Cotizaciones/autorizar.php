<style type="text/css" media="screen">
	tr:hover {background-color: #21b9bb !important;}
	tr:hover > td{color: white !important;}
	table#table_cot_admin {width: 96% !important;}
	.butProv{background:#5ebf3e;border:2px solid #5ebf3e;font-size:20px;padding:5px 41px;border-radius:2px;color:#FFF;}
	.butProv:hover{background:#FFF;color:#5ebf3e}
	.delCot{background:#fb5555;color:#FFF;border:2px solid #fb5555;font-size:18px;border-radius:20px;}
	.delCot:hover{background:#FFF;color:#fb5555;}
	.colorado{background:#fb5555 !important;}
	.cotiz{display:none}
</style>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<div class="col-lg-12" style="height:10rem;padding-top: 50px">
	<h1>AUTORIZAR COTIZACIONES POR PROVEEDOR</h1>
</div>
<div class="col-md-12 col-lg-12" style="padding-top: 20px">
	<div class="col-lg-3 col-md-3">
		<select name="id_pro" id="id_pro" class="form-control">
			<option value="nope">Seleccionar proveedor...</option>
				<?php foreach ($proveedores as $key => $value): ?>
					<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
				<?php endforeach ?>
		</select>
	</div>
</div>
<div class="col-lg-12 col-md-12" style="padding-top: 50px">
	<div class="col-md-6 col-lg-6">
		<button type="button" class="butProv">
			Agregar cotizaciones al sistema
		</button>
	</div>
</div>
<div class="col-lg-12 col-md-12 cotiz" style="padding-top: 50px">
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
						<th colspan="2">OBSERVACIONES</th>
						<th>ACCIÓN</th>
					</tr>
				</thead>
				<tbody class="cot-prov">

				</tbody>
			</table>
		</div>
	</div>
</div>

