<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_producto_edit')); ?>
		<input type="hidden" name="codigos" id="codigos" value="<?php echo $producto->codigo ?>">
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label for="codigo">Código</label>
					<input type="text" name="codigo" value="<?php echo $producto->codigo ?>" class="form-control" placeholder="Código del producto">
				</div>
			</div>
			<div class="col-sm-8">
				<div class="form-group">
					<label for="descripcion">Descripción</label>
					<input type="text" name="descripcion" value="<?php echo $producto->descripcion ?>" class="form-control" placeholder="descripción">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="unidad Medida">Unidad Medida</label>
					<input type="text" name="unidad" value="<?php echo $producto->unidad ?>" class="form-control numeric" placeholder="Número de prods">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="sistema">Precio Sistema</label>
					<input type="text" name="sistema" value="<?php echo $producto->sistema ?>" class="form-control numeric" placeholder="Precio del Sistema">
				</div>
			</div>
			<div class="col-sm-5">
				<div class="form-group">
					<label for="observaciones">Observaciones</label>
					<input type="text" name="observaciones" value="<?php echo $producto->observaciones ?>" class="form-control" placeholder="Promoción">
				</div>
			</div>
			<div class="col-sm-8">
				<div class="form-group">
					<label for="id_proveedor">Proveedor Asignado</label>
					<select name="id_proveedor" class="form-control chosen-select" id="id_proveedor">
						<option value="-1">Seleccionar...</option>
						<?php if ($proveedores):foreach ($proveedores as $key => $value): ?>
							<option value="<?php echo $value->id_proveedor ?>" <?php echo $producto->id_proveedor == $value->id_proveedor ? 'selected' : '' ?>><?php echo $value->nombre ?></option>
						<?php endforeach; endif ?>
					</select>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="sistema">Precio Proveedor</label>
					<input type="text" name="precio" value="<?php echo $producto->precio ?>" class="form-control numeric" placeholder="Precio del Proveedor">
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>