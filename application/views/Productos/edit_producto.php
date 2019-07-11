<style type="text/css" media="screen">
	.datepicker, #mapa{
		z-index: 9999 !important;
	}
</style>

<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_producto_edit')); ?>
		<input type="hidden" name="id_producto" id="id_producto" value="<?php echo $producto->id_producto ?>">
		<div class="row">
			<div class="col-sm-6" style="display:none">
				<div class="form-group">
					<label for="codigo">Código</label>
					<input type="text" name="codigo" value="<?php echo $producto->codigo ?>" class="form-control" placeholder="7500000000">
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="<?php echo $producto->nombre ?>" class="form-control" placeholder="Nombre">
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<label for="estatus">Tipo Producto</label>
					<select name="estatus" class="form-control chosen-select">
						<option value="1" <?php echo $producto->estatus == 1 ? 'selected' : '' ?> >NORMAL</option>
						<option value="2" <?php echo $producto->estatus == 2 ? 'selected' : '' ?> >VOLUMEN</option>
						<option value="3" <?php echo $producto->estatus == 3 ? 'selected' : '' ?> >AMARILLO</option>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="estatus">Conversión</label>
					<select name="colorp" class="form-control chosen-select">
						<option value="0" <?php echo $producto->colorp == 0 ? 'selected' : '' ?> >No</option>
						<option value="1" <?php echo $producto->colorp == 1 ? 'selected' : '' ?> >Si</option>
					</select>
				</div>
			</div>
			<!-- <div class="col-sm-6">
				<div class="form-group">
					<label for="precio">Precio</label>
					<input type="text" name="precio" value="<?php echo $producto->precio ?>" class="form-control" placeholder="0.00">
				</div>
			</div> -->

			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_familia">Familias</label>
					<select name="id_familia" class="form-control chosen-select">
						<option value="-1">Seleccionar...</option>
						<?php if ($familias):foreach ($familias as $key => $value): ?>
						<option value="<?php echo $value->id_familia ?>" <?php echo $producto->id_familia == $value->id_familia ? 'selected' : '' ?> ><?php echo $value->nombre ?></option>
						<?php endforeach; endif ?>
					</select>
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>