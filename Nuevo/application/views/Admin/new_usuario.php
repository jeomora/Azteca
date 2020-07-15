<style>
	.conjus{display: none}
</style>
<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_usuario_new')); ?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Nombre/Empresa *</label>
					<input type="text" name="nombre" value="" class="form-control" placeholder="Empresa">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="apellido">Apellido</label>
					<input type="text" name="apellido" value="" class="form-control" placeholder="Nombre Completo Proveedor">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="telefono">Teléfono</label>
					<input type="text" name="telefono" value="" class="form-control" placeholder="443 000 0000">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="correo">Correo *</label>
					<input type="text" name="correo" value="" class="form-control" placeholder="ejemplo@email.com">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="password">Contraseña *</label>
					<input type="text" name="password" value="" class="form-control" placeholder="*********">
				</div>
			</div>
 
			<div class="col-sm-3">
				<div class="form-group">
					<label for="estatus">Tipo Usuario *</label>
					<select name="estatus" class="form-control chosen-select" id="estatus">
						<option value="-1">Seleccionar...</option>
						<?php foreach ($grupo as $key => $value): ?>
							<option value="<?php echo $value->id_grupo ?>"><?php echo $value->nombre ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group conjus">
					<label for="conjunto">Nombre de la Sucursal</label>
					<select name="conjunto" class="form-control chosen-select" id="conjunto">
						<?php foreach ($sucursal as $key => $value): ?>
							<option value="<?php echo $value->id_sucursal ?>"><?php echo $value->nombre ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>