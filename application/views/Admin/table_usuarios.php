<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	function show_password($password){
		$key='APGoyQGOKAR5iXQ1wiO6i4jNczeMV7Sg';//Clave de encriptación
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($password), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		return $decrypted;
	}
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE USUARIOS</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_usuario">
							<i class="fa fa-plus"></i>
						</button>
					</div>
						<table class="table table-striped table-bordered table-hover" id="table_usuarios">
							<thead>
								<tr>
									<th>NO</th>
									<th>NOMBRE</th>
									<th>TELÉFONO</th>
									<th>CORREO</th>
									<th>CONTRASEÑA</th>
									<th>TIPO</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($usuarios): ?>
									<?php foreach ($usuarios as $key => $value): ?>
										<tr>
											<th><?php echo $value->id_usuario ?></th>
											<td><?php echo $value->nombre.' '.$value->apellido ?></td>
											<td><?php echo $value->telefono ?></td>
											<td><?php echo $value->email ?></td>
											<td><?php echo show_password($value->password) ?></td>
											<td><?php echo $value->grupo ?></td>
											<td>
												<button id="update_usuario" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-usuario="<?php echo $value->id_usuario ?>">
													<i class="fa fa-pencil"></i>
												</button>
												<button id="delete_usuario" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-usuario="<?php echo $value->id_usuario ?>">
													<i class="fa fa-trash"></i>
												</button>
											</td>
										</tr>
									<?php endforeach ?>
								<?php endif ?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
</div>