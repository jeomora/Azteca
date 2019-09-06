<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_producto_new')); ?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="descripcion">Descripción</label>
					<input type="text" name="descripcion" value="" class="form-control" placeholder="descripción">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_proveedor">Familia</label>
					<select name="id_proveedor" class="form-control chosen-select" id="id_proveedor">
						<?php if ($familias):foreach ($familias as $key => $value): ?>
							<option value="<?php echo $value->id_familia ?>"><?php echo $value->nombre ?></option>
						<?php endforeach; endif ?>
					</select>
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
					<label for="codigo">Código Pieza</label>
					<input type="text" name="codigo" value="" class="form-control" placeholder="Código Pieza">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="caja">Código Caja</label>
					<input type="text" name="caja" value="" class="form-control" placeholder="Código Caja">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="proveedor">Código Proveedor</label>
					<input type="text" name="proveedor" value="" class="form-control" placeholder="Código Proveedor">
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>