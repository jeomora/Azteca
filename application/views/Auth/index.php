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
							<a data-tooltip="tooltip" title="Agregar Usuarios" class="btn tool btn-primary" href="<?php echo site_url('Auth/create_user'); ?>">
								<i class="fa fa-user"></i>
							</a>
						</div>
					<div class="btn-group">
						<a data-tooltip="tooltip" title="Agregar grupo" class="btn tool btn-primary" href="<?php echo site_url('Auth/create_group'); ?>">
							<i class="fa fa-users"></i>
						</a>
					</div>
					</div>
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>NO</th>
								<th>Nombre</th>
								<th>Apellido</th>
								<th>Correo</th>
								<th>Grupo</th>
								<th>Estatus</th>
								<th>Acción</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($users): foreach ($users as $key => $user): ?>
								<tr>
									<td><?php echo htmlspecialchars($user->id,ENT_QUOTES,'UTF-8');?></td>
									<td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
									<td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
									<td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
									<td><?php if($user->groups): foreach ($user->groups as $group):?>
										<?php echo anchor("Auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br />
										<?php endforeach; endif; ?>
									</td>
									<td><?php echo ($user->active) ? anchor("Auth/deactivate/".$user->id, lang('index_active_link')) : anchor("Auth/activate/". $user->id, lang('index_inactive_link'));?></td>
									<td><?php echo anchor("Auth/edit_user/".$user->id, 'Edit') ;?></td>
								</tr>
							<?php endforeach; endif ?>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>
</div>

<!-- <div id="infoMessage"><?php echo $message;?></div>

<table cellpadding=0 cellspacing=10>
	<tr>
		<th><?php echo lang('index_fname_th');?></th>
		<th><?php echo lang('index_lname_th');?></th>
		<th><?php echo lang('index_email_th');?></th>
		<th><?php echo lang('index_groups_th');?></th>
		<th><?php echo lang('index_status_th');?></th>
		<th><?php echo lang('index_action_th');?></th>
	</tr>
	<?php foreach ($users as $user):?>
		<tr>
            <td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
            <td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
            <td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
			<td>
				<?php foreach ($user->groups as $group):?>
					<?php echo anchor("Auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br />
                <?php endforeach?>
			</td>
			<td><?php echo ($user->active) ? anchor("Auth/deactivate/".$user->id, lang('index_active_link')) : anchor("Auth/activate/". $user->id, lang('index_inactive_link'));?></td>
			<td><?php echo anchor("Auth/edit_user/".$user->id, 'Edit') ;?></td>
		</tr>
	<?php endforeach;?>
</table>

<p><?php echo anchor('Auth/create_user', lang('index_create_user_link'))?> | <?php echo anchor('Auth/create_group', lang('index_create_group_link'))?></p> -->