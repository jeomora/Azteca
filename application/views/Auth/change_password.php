<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="infoMessage"><?php echo $message;?></div>

<div class="ibox-content">
	<div class="row">
		<?php echo form_open("Auth/change_password");?>
		<div class="row">
			<div class="col-lg-12">

			<div class="form-group">
				<label for="old">Contraseña actual</label>
				<input type="password" name="old" value="" required="" class="form-control" placeholder="********">
			</div>
			</div>
		</div>
		<div class="row ">
			<div class="col-lg-6">
				<div class="form-group">
				<label for="new">Contraseña nueva (Requiere 8 caracteres de longitud)</label>
					<input type="password" name="new" value="" required="" class="form-control" placeholder="********">
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
				<label for="new_confirm">Confirmar contraseña</label>
					<input type="password" name="new_confirm" value="" required="" class="form-control" placeholder="********">
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
		<?php echo form_close();?>
	</div>
</div>