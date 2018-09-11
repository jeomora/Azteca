<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_usuario_new')); ?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Empresa</label>
					<input type="text" name="nombre" value="" class="form-control" placeholder="Empresa">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="apellido">Nombre Completo Proveedor</label>
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
					<label for="correo">Correo</label>
					<input type="text" name="correo" value="" class="form-control" placeholder="ejemplo@email.com">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="password">Contraseña</label>
					<input type="text" name="password" value="" class="form-control" placeholder="*********">
				</div>
			</div>
 
			<div class="col-sm-3">
				<div class="form-group">
					<label for="id_grupo">Grupos</label>
					<select name="id_grupo" class="form-control chosen-select" id="id_grupo">
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
			<div class="col-sm-3 conj" style="display: none">
				<div class="form-group">
					<label for="conjunto">Conjunto</label>
					<select name="conjunto" class="form-control chosen-select">
						<option value="INDIVIDUAL" selected>INDIVIDUAL</option>
						<option value="VARIOS1">VARIOS 1°</option>
						<option value="VARIOS2">VARIOS 2°</option>
						<option value="VARIOS3">VARIOS 3°</option>
						<option value="VARIOS4">VARIOS 4°</option>
					</select>
				</div>
			</div>
			<div class="col-sm-3 conj" style="display: none">
				<div class="form-group">
					<label for="cargo">Responsable</label>
					<select name="cargo" class="form-control chosen-select">
						<option value="SIN" selected>---SELECCIONE---</option>
						<?php if ($cargos):foreach ($cargos as $key => $value): ?>
							<option value="<?php echo $value->nombre ?>"><?php echo $value->nombre ?></option>
						<?php endforeach; endif ?>
					</select>
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>