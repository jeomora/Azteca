<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Listado de Sucursales</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<a data-toggle="modal" data-tooltip="tooltip" title="Registrar" class="btn btn-primary tool btn-modal" href="<?php echo site_url('Sucursales/add_sucursal'); ?>" data-target="#myModal">
							<i class="fa fa-plus"></i>
						</a>
					</div>
					
<!-- 					<button class="btn btn-success" id="new_sucursal">
						<i class="fa fa-plus"></i> Nueva
					</button> -->

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
												<a data-toggle="modal" data-tooltip="tooltip" title="Editar"  class="btn tool btn-info btn-modal" href="<?php echo site_url('Sucursales/get_update/'.$value->id_sucursal);?>" data-target="#myModal" ><i class="fa fa-pencil"></i></a>
												<a data-toggle="modal" data-tooltip="tooltip" title="Eliminar"  class="btn tool btn-warning btn-modal" href="<?php echo site_url('Sucursales/get_delete/'.$value->id_sucursal);?>" data-target="#myModal" ><i class="fa fa-trash"></i></a>
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
<script type="text/javascript">
	$(function($) {
		fillDataTable("table_sucursales", 'DESC', 10);
	});
</script>