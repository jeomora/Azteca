<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_pedido_new')); ?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_proveedor">Proveedores</label>
					<select name="id_proveedor" id="id_proveedor" class="form-control">
						<option value="">Seleccionar...</option>
						<?php if ($proveedores): ?>
							<?php foreach ($proveedores as $key => $value): ?>
								<option value="<?php echo $value->id ?>"><?php echo strtoupper($value->first_name.'	'.$value->last_name) ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_sucursal">Sucursales</label>
					<select name="id_sucursal" id="id_sucursal" class="form-control">
						<option value="">Seleccionar...</option>
						<?php if ($sucursales): ?>
							<?php foreach ($sucursales as $key => $value): ?>
								<option value="<?php echo $value->id_sucursal ?>"><?php echo strtoupper($value->nombre) ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>NO</th>
							<th>NOMBRE</th>
							<th>PRECIO</th>
							<th>CANTIDAD</th>
							<th>IMPORTE</th>
						</tr>
					</thead>
					<tbody id="body_response">
						
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3"></td>
							<th>Subtotal</th>
							<td>
								<div class="input-group m-b">
									<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
									<input type="text" id="subtotal" value="" class="form-control numeric" placeholder="0.00" readonly="">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="3"></td>
							<th>IVA</th>
							<td>
								<div class="input-group m-b">
									<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
									<input type="text" id="iva" value="" class="form-control numeric" placeholder="0.00" readonly="">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="3"></td>
							<th>Total</th>
							<td>
								<div class="input-group m-b">
									<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
									<input type="text" id="total" name="total" value="" class="form-control numeric" placeholder="0.00" readonly="">
								</div>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<input type="hidden" id="name_user" value="<?php echo strtoupper($usuario->username) ?>"/>

		<?php echo form_close(); ?>
	</div>
</div>
