<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Información del Usuario</h5>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
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
							<tr>
								<td><?php echo htmlspecialchars(strtoupper($usuario->first_name.' '.$usuario->last_name),ENT_QUOTES,'UTF-8') ?></td>
								<td><?php echo htmlspecialchars($usuario->phone,ENT_QUOTES,'UTF-8')?> </td>
								<td><?php echo htmlspecialchars($usuario->email,ENT_QUOTES,'UTF-8') ?> </td>
								<td><?php echo htmlspecialchars(strtoupper($usuario->company),ENT_QUOTES,'UTF-8') ?></td>
								<td><?php echo htmlspecialchars($usuario->username,ENT_QUOTES,'UTF-8')?> </td>
								<td><?php echo 'PROVEEDOR' ?> </td>
								<td>
									<a data-toggle="modal" data-tooltip="tooltip" title="Cambiar contraseña"  class="btn tool btn-info btn-modal" href="<?php echo site_url('Auth/change_password/'.$usuario->id) ?>" data-target="#myModal" ><i class="fa fa-key"></i></a>
								</td>
							</tr>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>
</div>
