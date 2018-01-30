<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="infoMessage"><?php echo $message;?></div>

<div class="ibox-content">
	<div class="row">
		<?php echo form_open("Auth/create_user");?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="first_name">Nombre</label>
					<input type="text" name="first_name" value="" required="" class="form-control" placeholder="Nombre">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="last_name">Apellido</label>
					<input type="text" name="last_name" value="" required="" class="form-control" placeholder="Apellido">
				</div>
			</div>
		</div>
		<?php
			if($identity_column!=='email') {
				echo '<p>';
				echo lang('create_user_identity_label', 'identity');
				echo '<br />';
				echo form_error('identity');
				echo form_input($identity);
				echo '</p>';
			}
		?>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="company">Compañia</label>
					<input type="text" name="company" value="" class="form-control" placeholder="Compañia">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="email">Correo</label>
					<input type="text" name="email" value="" required="" class="form-control" placeholder="Correo">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="phone">Teléfono</label>
					<input type="text" name="phone" value="" class="form-control" placeholder="0000000000">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="grupo">Tipos</label>
					<select name="grupo" class="form-control" required="">
						<option value="">Seleccionar....</option>
						<?php if ($grupos): foreach ($grupos as $key => $value): ?>
							<option value="<?php echo $value['id'] ?>"> <?php echo strtoupper($value['name']) ?> </option>
						<?php endforeach; endif ?>
					</select>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="password">Contraseña</label>
					<input type="password" name="password" value="" required="" class="form-control" placeholder="**********">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="password_confirm">Confirmar contraseña</label>
					<input type="password" name="password_confirm" value="" required="" class="form-control" placeholder="**********">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-2 pull-right">
				<button class="btn btn-primary pull-right" type="submit">
					<span class="bold"><i class="fa fa-floppy-o"></i></span>
					&nbsp;Guardar
				</button>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>