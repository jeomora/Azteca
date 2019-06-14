<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
			redirect("Compras/Login", "");
		}
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE SUCURSALES</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_sucursal">
							<i class="fa fa-plus"></i>
						</button>
					</div>

						<table class="table table-striped table-bordered table-hover" id="table_sucursales">
							<thead>
								<tr>
									<th>NO</th>
									<th>NOMBRE</th>
									<th>TÉLEFONO</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($sucursales): ?>
									<?php foreach ($sucursales as $key => $value): ?>
										<tr>
											<th><?php echo $value->id_sucursal ?></th>
											<td><?php echo strtoupper($value->nombre) ?></td>
											<td><?php echo $value->telefono ?></td>
											<td>
												<button id="update_sucursal" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-sucursal="<?php echo $value->id_sucursal ?>">
													<i class="fa fa-pencil"></i>
												</button>
												<button id="delete_sucursal" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-sucursal="<?php echo $value->id_sucursal ?>">
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