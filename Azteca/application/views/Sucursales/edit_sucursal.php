<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_sucursal_edit')); ?>
		<input type="hidden" name="id_sucursal" id="id_sucursal" value="<?php echo $sucursal->id_sucursal ?>">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="<?php echo $sucursal->nombre ?>" class="form-control" placeholder="Nombre de la sucursal">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="telefono">Teléfono</label>
					<input type="text" name="telefono" value="<?php echo $sucursal->telefono ?>" class="form-control" placeholder="000 0000 000">
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>