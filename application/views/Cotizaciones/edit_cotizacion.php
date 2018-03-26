<style type="text/css" media="screen">
	.datepicker{ z-index: 9999 !important; }
	.modal-lg {width: 1220px !important;}
	table#table_cot_admin {width: 1140px !important;}
</style>

<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_cotizacion_edit')); ?>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
				<thead>
					<tr>
						<th>EDITAR</th>
						<th>PROVEEDOR</th>
						<th>FACTURA</th>
						<th>FACTURA C/PROMOCIÓN</th>
						<th colspan="2"># CAJAS EN LA COMPRA DE # CAJAS</th>
						<th>% DESCUENTO</th>			
						<th>OBSERVACIONES</th>
						<th>DESHABILITAR</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($cots as $key => $value): ?>
						<tr>
							<td><input type='checkbox' value="<?php echo $value->id_cotizacion ?>" class='id_cotz'></td>
							<td style="text-align: center;"><?php echo $value->nomb ?></td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
									<input type='text' value='<?php echo $value->precio ?>' class='form-control precio numeric' readonly=''>
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
									<?php if($value->precio_promocion > 0): ?> 
										<input type='text' value='<?php echo $value->precio_promocion ?>' class='form-control precio_promocion numeric' readonly=''>
									<?php else: ?>
										<input type='text' value='<?php echo $value->precio ?>' class='form-control precio_promocion numeric' readonly=''>
									<?php endif ?>
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-slack'></i></span>
									<input type='text' value='<?php echo $value->num_one ?>' class='form-control num_one numeric' readonly=''>
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-slack'></i></span>
									<input type='text' value='<?php echo $value->num_two ?>' class='form-control num_two numeric' readonly=''>
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'>%</i></span>
									<input type='text' value='<?php echo $value->descuento ?>' class='form-control descuento numeric' readonly=''>
								</div>
							</td>
							<td><input type='text' value='<?php echo $value->observaciones ?>' class='form-control observaciones' readonly=''></td>
							<td><input type='checkbox' value="<?php echo $value->id_cotizacion ?>" class='id_cotz'></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<input type="hidden" name="id_cotizacion" id="id_cotizacion" value="<?php echo $cotizacion->id_cotizacion ?>">
		<?php echo form_close(); ?>
	</div>


	<div class="row">
		<?php echo form_open("", array("id"=>'form_cotizacion_edit')); ?>
		<h1>Deshabilitados</h1>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
				<thead>
					<tr>
						<th>EDITAR</th>
						<th>PROVEEDOR</th>
						<th>FACTURA</th>
						<th>FACTURA C/PROMOCIÓN</th>
						<th colspan="2"># CAJAS EN LA COMPRA DE # CAJAS</th>
						<th>% DESCUENTO</th>			
						<th>OBSERVACIONES</th>
						<th>HABILITAR</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($cotss as $key => $value): ?>
						<tr>
							<td><input type='checkbox' value="<?php echo $value->id_cotizacion ?>" class='id_cotz'></td>
							<td style="text-align: center;"><?php echo $value->nomb ?></td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
									<input type='text' value='<?php echo $value->precio ?>' class='form-control precio numeric' readonly=''>
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
									<?php if($value->precio_promocion > 0): ?> 
										<input type='text' value='<?php echo $value->precio_promocion ?>' class='form-control precio_promocion numeric' readonly=''>
									<?php else: ?>
										<input type='text' value='<?php echo $value->precio ?>' class='form-control precio_promocion numeric' readonly=''>
									<?php endif ?>
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-slack'></i></span>
									<input type='text' value='<?php echo $value->num_one ?>' class='form-control num_one numeric' readonly=''>
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-slack'></i></span>
									<input type='text' value='<?php echo $value->num_two ?>' class='form-control num_two numeric' readonly=''>
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'>%</i></span>
									<input type='text' value='<?php echo $value->descuento ?>' class='form-control descuento numeric' readonly=''>
								</div>
							</td>
							<td><input type='text' value='<?php echo $value->observaciones ?>' class='form-control observaciones' readonly=''></td>
							<td><input type='checkbox' value="<?php echo $value->id_cotizacion ?>" class='id_cotz'></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<input type="hidden" name="id_cotizacion" id="id_cotizacion" value="<?php echo $cotizacion->id_cotizacion ?>">
		<?php echo form_close(); ?>
	</div>
</div>