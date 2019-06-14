<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_producto_new')); ?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="codigo">Código</label>
					<input type="text" name="codigo" value="" class="form-control" placeholder="7500000000">
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="" class="form-control" placeholder="Nombre">
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<label for="estatus">Tipo Producto</label>
					<select name="estatus" class="form-control chosen-select">
						<option value="1">NORMAL</option>
						<option value="2">VOLUMEN</option>
						<option value="3">AMARILLO</option>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="estatus">Conversión</label>
					<select name="colorp" class="form-control chosen-select">
						<option value="0">No</option>
						<option value="1">Si</option>
					</select>
				</div>
			</div>
			
			<!-- <div class="col-sm-6">
				<div class="form-group">
					<label for="precio">Precio</label>
					<input type="text" name="precio" value="" class="form-control" placeholder="0.00">
				</div>
			</div> -->

			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_familia">Familias</label>
					<select name="id_familia" class="form-control chosen-select">
						<option value="-1">Seleccionar...</option>
						<?php if ($familias):foreach ($familias as $key => $value): ?>
						<option value="<?php echo $value->id_familia ?>"><?php echo $value->nombre ?></option>
						<?php endforeach; endif ?>
					</select>
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>