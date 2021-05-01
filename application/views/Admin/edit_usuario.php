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
					<label for="detalles">Detalles</label>
					<input type="text" name="Detalles" value="<?php echo $usuario->detalles ?>" class="form-control" placeholder="Detalles">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="extension">Extension</label>
					<input type="text" name="extension" value="<?php echo $usuario->extension ?>" class="form-control" placeholder="Extension">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="pagot">Tipo Pago</label>
					<input type="text" name="Tipo Pago" value="<?php echo $usuario->pagot ?>" class="form-control" placeholder="Credito 30 días">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="password">Contraseña</label> <!-- $password trae la contraseña desencritada -->
					<input type="text" name="password" class="form-control" placeholder="*********">
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<label for="id_grupo">Grupos</label>
					<select name="id_grupo" class="form-control chosen-select" id="id_grupo">
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
			
			<div class="col-sm-3 conj" <?php if($usuario->id_grupo <> 2): ?> style="display: none" <?php endif; ?>>
				<div class="form-group">
					<label for="conjunto">Conjunto</label>
					<select name="conjunto" class="form-control chosen-select">
						<option value="SIN" <?php echo $usuario->conjunto == 'SIN' ? 'selected' : '' ?>>SIN FORMATO PEDIDOS</option>
						<option value="INDIVIDUAL" <?php echo $usuario->conjunto == 'INDIVIDUAL' ? 'selected' : '' ?>>INDIVIDUAL</option>
						<option value="VARIOS1" <?php echo $usuario->conjunto == 'VARIOS1' ? 'selected' : '' ?>>VARIOS 1°</option>
						<option value="VARIOS2" <?php echo $usuario->conjunto == 'VARIOS2' ? 'selected' : '' ?>>VARIOS 2°</option>
						<option value="VARIOS3" <?php echo $usuario->conjunto == 'VARIOS3' ? 'selected' : '' ?>>VARIOS 3°</option>
						<option value="VARIOS4" <?php echo $usuario->conjunto == 'VARIOS4' ? 'selected' : '' ?>>VARIOS 4°</option>
					</select>
				</div>
			</div>

			<div class="col-sm-3 conj" <?php if($usuario->id_grupo <> 2): ?> style="display: none" <?php endif; ?>>
				<div class="form-group">
					<label for="cargo">Responsable</label>
					<select name="cargo" class="form-control chosen-select">
						<option value="SIN" <?php echo $usuario->cargo == 'SIN' ? 'selected' : '' ?>>---SELECCIONE---</option>
						<?php if ($cargos):foreach ($cargos as $key => $value): ?>
							<option value="<?php echo $value->nombre ?>" <?php echo $usuario->cargo == $value->nombre ? 'selected' : '' ?>><?php echo $value->nombre ?></option>
						<?php endforeach; endif ?>
					</select>
				</div>
			</div>

		</div>

		<?php echo form_close(); ?>
	</div>
</div>