<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_usuario_edit')); ?>
		<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $usuario->id_usuario ?>">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Empresa</label>
					<input type="text" name="nombre" value="<?php echo $usuario->nombre ?>" class="form-control" placeholder="Empresa">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="apellido">Nombre Completo Proveedor</label>
					<input type="text" name="apellido" value="<?php echo $usuario->apellido ?>" class="form-control" placeholder="Nombre Completo Proveedor">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="telefono">Teléfono</label>
					<input type="text" name="telefono" value="<?php echo $usuario->telefono ?>" class="form-control" placeholder="443 000 0000">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="correo">Correo</label>
					<input type="text" name="correo" value="<?php echo $usuario->email ?>" class="form-control" placeholder="ejemplo@email.com">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="password">Contraseña</label> <!-- $password trae la contraseña desencritada -->
					<input type="text" name="password"  class="form-control" placeholder="*********">
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_grupo">Grupos</label>
					<select name="id_grupo" class="form-control chosen-select">
						<option value="-1">Seleccionar...</option>
						<?php if ($grupos):foreach ($grupos as $key => $value): ?>
							<?php if ($grupo == 4 && $value->nombre <> 'ADMINISTRADOR'): ?>
								<option value="<?php echo $value->id_grupo ?>" <?php echo $usuario->id_grupo == $value->id_grupo ? 'selected' : '' ?>><?php echo $value->nombre ?></option>
							<?php endif; ?>
							<?php if ($grupo == 1): ?>
								<option value="<?php echo $value->id_grupo ?>" <?php echo $usuario->id_grupo == $value->id_grupo ? 'selected' : '' ?>><?php echo $value->nombre ?></option>
							<?php endif; ?>
						<?php endforeach; endif ?>
					</select>
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>