<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_producto_new')); ?>
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label for="codigo">Código</label>
					<input type="text" name="codigo" value="" class="form-control" placeholder="Código del producto">
				</div>
			</div>
			<div class="col-sm-8">
				<div class="form-group">
					<label for="descripcion">Descripción</label>
					<input type="text" name="descripcion" value="" class="form-control" placeholder="descripción">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="unidad Medida">Unidad Medida</label>
					<input type="text" name="unidad" value="" class="form-control numeric" placeholder="Número de prods">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="sistema">Precio Sistema</label>
					<input type="text" name="sistema" value="" class="form-control numeric" placeholder="Precio del Sistema">
				</div>
			</div>
			<div class="col-sm-5">
				<div class="form-group">
					<label for="observaciones">Observaciones</label>
					<input type="text" name="observaciones" value="" class="form-control" placeholder="Promoción">
				</div>
			</div>
			<div class="col-sm-8">
				<div class="form-group">
					<label for="id_proveedor">Proveedor Asignado</label>
					<select name="id_proveedor" class="form-control chosen-select" id="id_proveedor">
						<option value="-1">Seleccionar...</option>
						<?php if ($proveedores):foreach ($proveedores as $key => $value): ?>
							<option value="<?php echo $value->id_proveedor ?>"><?php echo $value->nombre ?></option>
						<?php endforeach; endif ?>
					</select>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="sistema">Precio Proveedor</label>
					<input type="text" name="precio" value="" class="form-control numeric" placeholder="Precio del Proveedor">
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>