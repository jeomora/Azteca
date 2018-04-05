<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_usuario_new')); ?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="" class="form-control" placeholder="Nombre">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="apellido">Apellido</label>
					<input type="text" name="apellido" value="" class="form-control" placeholder="Apellido">
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
					<label for="correo">Correo</label>
					<input type="text" name="correo" value="" class="form-control" placeholder="ejemplo@email.com">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="password">Contraseña</label>
					<input type="text" name="password" value="" class="form-control" placeholder="*********">
				</div>
			</div>
 
			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_grupo">Grupos</label>
					<select name="id_grupo" class="form-control chosen-select">
						<option value="-1">Seleccionar...</option>
						<?php if ($grupos):foreach ($grupos as $key => $value): ?>
							<?php if ($grupo == 4 && $value->nombre <> 'ADMINISTRADOR'): ?>
								<option value="<?php echo $value->id_grupo ?>"><?php echo $value->nombre ?></option>
							<?php endif; ?>
							<?php if ($grupo == 1): ?>
								<option value="<?php echo $value->id_grupo ?>"><?php echo $value->nombre ?></option>
							<?php endif; ?>
						<?php endforeach; endif ?>
					</select>
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>