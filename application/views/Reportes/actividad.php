<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username")){
	redirect("Welcome/Login", "");
}
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Registros recientes</h5>
				</div>
				<div class="ibox-content">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_precios_iguales">
							<thead>
								<tr>
									<th>NO</th>
									<th>FECHA REGISTRO</th>
									<th>USUARIO</th>
									<th>ACCIÓN</th>
									<th>ANTES</th>
									<th>DESPUÉS</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($cambios): ?>
									<?php foreach ($cambios as $key => $value): ?>
										<tr>
											<th><?php echo $value->id_cambio ?></th>
											<td><?php echo $value->fecha_cambio ?> </td>
											<td><?php echo strtoupper($value->usuario) ?></td>
											<td><?php echo strtoupper($value->accion) ?></td>
											<td><?php echo $value->antes ?></td>
											<?php if (strtoupper($value->accion) === 'SUBE ARCHIVO' || strtoupper($value->accion) === 'SUBE PEDIDOS'): ?>
												<td>
													<a href="<?php echo base_url($value->despues); ?>" target="_blank" data-toggle="tooltip" title="Decargar" class="btn btn-info" download><i class="fa fa-cloud-download"></i><span class="nav-label" download></span> </a>
												</td>
											<?php else: ?>
												<td><?php echo $value->despues ?></td>
											<?php endif ?>
											
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
</div>