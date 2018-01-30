<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
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
					<!-- Por el momento no se ocupa crear grupos
					<div class="btn-group">
						<a data-tooltip="tooltip" title="Agregar grupo" class="btn tool btn-primary" href="<?php echo site_url('Auth/create_group'); ?>">
							<i class="fa fa-users"></i>
						</a>
					</div> -->
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_usuarios">
							<thead>
								<tr>
									<th>NO</th>
									<th>NOMBRE</th>
									<th>TELÉFONO</th>
									<th>CORREO</th>
									<th>EMPRESA</th>
									<th>USUARIO</th>
									<th>TIPO</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($users): foreach ($users as $key => $user): ?>
									<tr>
										<th><?php echo htmlspecialchars($user->id,ENT_QUOTES,'UTF-8');?></th>
										<td><?php echo htmlspecialchars(strtoupper($user->first_name.' '.$user->last_name),ENT_QUOTES,'UTF-8');?></td>
										<td><?php echo htmlspecialchars($user->phone,ENT_QUOTES,'UTF-8');?></td>
										<td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
										<td><?php echo htmlspecialchars(strtoupper($user->company),ENT_QUOTES,'UTF-8');?></td>
										<td><?php echo htmlspecialchars($user->username,ENT_QUOTES,'UTF-8');?></td>
										<td><?php if($user->groups): foreach ($user->groups as $group):?>
											<!-- <?php echo anchor("Auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br /> -->
											<?php echo strtoupper($group->name) ?>
											<?php endforeach; endif; ?>
										</td>
										<td>
											<button id="update_usuario" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-user="<?php echo $user->id ?>">
												<i class="fa fa-pencil"></i>
											</button>
											<?php if ($user->active == 1): ?>
												<button id="desactivar_usuario" class="btn btn-success" data-toggle="tooltip" title="Desactivar" data-id-user="<?php echo $user->id ?>">
													<i class="fa fa-thumbs-o-down"></i>
												</button>
											<?php else: ?>
												<button id="activar_usuario" class="btn btn-danger" data-toggle="tooltip" title="Activar" data-id-user="<?php echo $user->id ?>">
													<i class="fa fa-thumbs-o-up"></i>
												</button>
											<?php endif ?>
											<button id="change_password" class="btn btn-warning" data-toggle="tooltip" title="Cambiar contraña" data-id-user="<?php echo $user->id ?>">
												<i class="fa fa-key"></i>
											</button>
										</td>
									</tr>
								<?php endforeach; endif ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>