<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	function show_password($password){
		$key='APGoyQGOKAR5iXQ1wiO6i4jNczeMV7Sg';//Clave de encriptación
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($password), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		return $decrypted;
	}
?>

<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_usuario_edit')); ?>
		<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $usuario->id_usuario ?>">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="<?php echo $usuario->nombre ?>" class="form-control" placeholder="Nombre">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="apellido">Apellido</label>
					<input type="text" name="apellido" value="<?php echo $usuario->apellido ?>" class="form-control" placeholder="Apellido">
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
					<label for="password">Contraseña</label>
					<input type="text" name="password" value="<?php echo show_password($usuario->password) ?>" class="form-control" placeholder="*********">
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_grupo">Grupos</label>
					<select name="id_grupo" class="form-control chosen-select">
						<option value="-1">Seleccionar...</option>
						<?php if ($grupos):foreach ($grupos as $key => $value): ?>
						<option value="<?php echo $value->id_grupo ?>" <?php echo $usuario->id_grupo == $value->id_grupo ? 'selected' : '' ?>><?php echo $value->nombre ?></option>
						<?php endforeach; endif ?>
					</select>
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>