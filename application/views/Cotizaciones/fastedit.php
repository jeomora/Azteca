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
		<h1>COTIZACIONES CON DIFERENCIA DEL 20 %</h1>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE COTIZACIONES</h5>
				</div>
				<div class="ibox-content" style="padding-top: 5rem">
					<div class="btn-group searchboxs">
						<label>Buscar:<input class="form-control input-sm" type="text" id="myInput" onkeyup="myFunction()" placeholder="Nombre..."></label>
					</div>
					
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_cot_proveedores">
							<thead>
								<tr>
									<th>CODIGO</th>
									<th>DESCRIPCIÓN</th>
									<th>PRECIO SISTEMA</th>
									<th>DIFERENCIA</th>
									<th>FECHA REGISTRO</th>
									<th>PROVEEDOR</th>
									<th>PRECIO FACTURA</th>
									<th>PRECIO FACTURA C/PROMOCIÓN</th>
									<th>DESCUENTO ADICIONAL</th>
									<th colspan="2">PROMOCIÓN</th>
									<th>OBSERVACIONES</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody class="cot-prov">
								<?php if($diferencias):foreach ($diferencias as $key => $value): ?>
									<tr>
										<td><?php echo $value->codigo ?></td>
										<td><?php echo $value->descrip ?></td>
										<td><?php echo '$ '.number_format($value->precio_sistema,2,'.',',') ?></td>
										<td style="background-color: #ffc1c1"><?php echo '$ '.number_format($value->diferencia,2,'.',',') ?></td>
										<td><?php echo date('d-m-Y', strtotime($value->fecha_registro)) ?> <input type="text" class="idprovs" name="idprovs" value="" style="display: none"> </td>
										<td><?php echo $value->nombre ?></td>
										<td><?php echo ($value->precio >0) ? '$ '.number_format($value->precio,2,'.',',') : '' ?></td>
										<td><?php echo ($value->precio_promocion >0) ? '$ '.number_format($value->precio_promocion,2,'.',',') : '' ?></td>
										<td><?php echo ($value->descuento > 0) ? number_format($value->descuento,0,'.',',').' %' : ''  ?></td>
										<td><?php echo $value->num_one ?></td>
										<td><?php echo $value->num_two ?></td>
										<td><?php echo $value->observaciones ?></td>
										<td>
											<button id="up_cotizacion" class="btn btn-info" data-toggle="tooltip" title="Editar" 
											data-id-cotizacion="<?php echo $value->id_cotizacion ?>">
												<i class="fa fa-pencil"></i>
											</button>
											<button id="del_cotizacion" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" 
											data-id-cotizacion="<?php echo $value->id_cotizacion ?>">
												<i class="fa fa-trash"></i>
											</button>
										</td>
									</tr>
								<?php endforeach;endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>