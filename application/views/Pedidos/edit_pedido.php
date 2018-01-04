<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_pedido_edit')); ?>
		<input type="hidden" name="id_pedido" id="id_pedido" value="<?php echo $pedido->id_pedido ?>">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_proveedor">Proveedores</label>
					<select name="id_proveedor" id="id_proveedor" class="form-control">
						<option value="">Seleccionar...</option>
						<?php if ($proveedores): ?>
							<?php foreach ($proveedores as $key => $value): ?>
								<option value="<?php echo $value->id ?>" <?php echo $pedido->id_proveedor == $value->id ? 'selected' : '' ?> ><?php echo strtoupper($value->first_name.'	'.$value->last_name) ?></option>
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
								<option value="<?php echo $value->id_sucursal ?>" <?php echo $pedido->id_sucursal == $value->id_sucursal ? 'selected' : '' ?> ><?php echo strtoupper($value->nombre) ?></option>
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
					<tbody>
						<tr></tr>
					</tbody>

				</table>
		<?php echo form_close(); ?>
	</div>
</div>
