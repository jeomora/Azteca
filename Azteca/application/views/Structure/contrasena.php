<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_usuario_edit')); ?>
		<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $usuario->id_usuario ?>">
		<div class="row">
			<div class="col-sm-6">
				<h2>Ingrese su nueva contraseña</h2>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="password">Contraseña</label> <!-- $password trae la contraseña desencritada -->
					<input type="text" name="password" class="form-control" placeholder="*********">
				</div>
			</div>

			
			
			
		</div>

		<?php echo form_close(); ?>
	</div>
</div>