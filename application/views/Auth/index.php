<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Listado de Usuarios</h5>
				</div>
				<div class="ibox-content">
					<div class="row col-lg-12">
						<div class="btn-group">
							<a data-toggle="modal" data-tooltip="tooltip" title="Registrar" class="btn tool btn-primary btn-modal" href="<?php echo site_url('Auth/create_user'); ?>" data-target="#myModal" >
								<i class="fa fa-plus"></i>
							</a>
						</div>
					<!-- Por el momento no se ocupa crear grupos
					<div class="btn-group">
						<a data-tooltip="tooltip" title="Agregar grupo" class="btn tool btn-primary" href="<?php echo site_url('Auth/create_group'); ?>">
							<i class="fa fa-users"></i>
						</a>
					</div> -->
					</div>
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
										<a data-toggle="modal" data-tooltip="tooltip" title="Editar"  class="btn tool btn-info btn-modal" href="<?php echo site_url('Auth/edit_user/'.$user->id) ?>" data-target="#myModal" ><i class="fa fa-pencil"></i></a>
										<!-- <?php echo anchor("Auth/edit_user/".$user->id, 'Edit') ;?> -->
										<!-- <?php echo ($user->active) ? anchor("Auth/deactivate/".$user->id, lang('index_active_link')) : anchor("Auth/activate/". $user->id, lang('index_inactive_link'));?> -->
										<?php if ($user->active == 1): ?>
											<a data-toggle="modal" data-tooltip="tooltip" title="Desactivar"  class="btn tool btn-success btn-modal" href="<?php echo site_url('Auth/deactivate/'.$user->id) ?>" data-target="#myModal" ><i class="fa fa-thumbs-o-down"></i></a>
										<?php else: ?>
											<a data-tooltip="tooltip" title="Activar" class="btn tool btn-danger" href="<?php echo site_url('Auth/activate/'.$user->id) ?>"><i class="fa fa-thumbs-o-up"></i></a>
										<?php endif ?>
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

<script type="text/javascript">
	$(function ($) {
		fillDataTable("table_usuarios", 'DESC', 10);
	});
</script>
