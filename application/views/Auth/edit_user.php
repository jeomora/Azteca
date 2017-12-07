<div class="ibox-content">
	<div class="row">

	<div id="infoMessage"><?php echo $message;?></div>

	<?php echo form_open(uri_string());?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="first_name">Nombre</label>
					<input type="text" name="first_name" value="<?php echo $user->first_name ?>" required="" class="form-control" placeholder="Nombre">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="last_name">Apellido</label>
					<input type="text" name="last_name" value="<?php echo $user->last_name ?>" required="" class="form-control" placeholder="Apellido">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="company">Compañia</label>
					<input type="text" name="company" value="<?php echo $user->company ?>" required="" class="form-control" placeholder="Compañia">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="email">Correo</label>
					<input type="text" name="email" value="<?php echo $user->email ?>" required="" class="form-control" placeholder="Correo">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="phone">Teléfono</label>
					<input type="text" name="phone" value="<?php echo $user->phone ?>" required="" class="form-control" placeholder="0000000000">
				</div>
			</div>
		</div>
	
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="password">Contraseña (Si quieres cambiarla) </label>
					<input type="password" name="password" value="" required="" class="form-control" placeholder="**********">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="password_confirm">Confirmar contraseña (Si quieres cambiarla) </label>
					<input type="password" name="password_confirm" value="" required="" class="form-control" placeholder="**********">
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<?php if ($this->ion_auth->is_admin()): ?>
				<p style="font-size: 25px; margin-left: 20px;"> Tipo de Usuario</p>
				<?php foreach ($groups as $group):?>
					<div class="col-sm-2">
						<label class="checkbox" style="margin-left: 30px;">
							<?php
								$gID=$group['id'];
								$checked = null;
								$item = null;
								foreach($currentGroups as $grp) {
									if ($gID == $grp->id) {
										$checked= ' checked="checked"';
										break;
									}
								}
							?>
							<input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
							<?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
						</label>
					</div>

				<?php endforeach?>
			<?php endif ?>
		</div>

		<?php echo form_hidden('id', $user->id);?>
		<?php echo form_hidden($csrf); ?>

		<div class="row">
			<div class="col-sm-2 pull-right">
				<button class="btn btn-primary pull-right" type="submit">
					<span class="bold"><i class="fa fa-floppy-o"></i></span>
					&nbsp;Guardar cambios
				</button>
			</div>
		</div>

		<?php echo form_close();?>
	</div>
</div>

</div> <!-- Cierra el cuerpo de la modal-->
<div class="modal-footer">
	<br>  <br>
</div>
