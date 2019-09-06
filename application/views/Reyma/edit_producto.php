<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_producto_edit')); ?>
		<div class="row">
			<input type="text" name="id_producto" value="<?php echo $producto->id_producto ?>" style="display:none">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="descripcion">Descripción</label>
					<input type="text" name="descripcion" value="<?php echo $producto->nombre ?>" class="form-control" placeholder="descripción">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_proveedor">Familia</label>
					<select name="id_proveedor" class="form-control chosen-select" id="id_proveedor">
						<?php if ($familias):foreach ($familias as $key => $value): ?>
							<option value="<?php echo $producto->id_familia ?>" <?php echo $producto->id_familia == $value->id_familia ? 'selected' : '' ?>><?php echo $value->nombre ?></option>
						<?php endforeach; endif ?>
					</select>
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
					<label for="codigo">Código Pieza</label>
					<input type="text" name="codigo" value="<?php echo $producto->codpz ?>" class="form-control" placeholder="Código Pieza">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="caja">Código Caja</label>
					<input type="text" name="caja" value="<?php echo $producto->codcaja ?>" class="form-control" placeholder="Código Caja">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="proveedor">Código Proveedor</label>
					<input type="text" name="proveedor" value="<?php echo $producto->codprov ?>" class="form-control" placeholder="Código Proveedor">
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>