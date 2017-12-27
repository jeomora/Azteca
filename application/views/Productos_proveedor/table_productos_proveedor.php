<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Listado de Cotizaciones</h5>
				</div>
				<div class="ibox-content">
					<?php if (! $this->ion_auth->is_admin()): ?>
						<div class="btn-group">
							<a data-toggle="modal" data-tooltip="tooltip" title="Registrar" class="btn btn-primary tool btn-modal" href="<?php echo site_url('Productos_proveedor/add_asignacion'); ?>" data-target="#myModal">
								<i class="fa fa-plus"></i>
							</a>
							<!--
							<a data-tooltip="tooltip" title="Registrar" class="btn btn-primary" href="<?php echo site_url('Productos_proveedor/add_asignacion'); ?>">
								<i class="fa fa-plus"></i>
							</a> -->
						</div>
					<?php endif ?>
						<table class="table table-striped table-bordered table-hover" id="table_prod_proveedor">
							<thead>
								<tr>
									<th>NO</th>
									<?php
										echo (! $this->ion_auth->is_admin()) ? '' : "<th>PROVEEDOR</th>";
									?>
									<th>CÓDIGO</th>
									<th>PRODUCTO</th>
									<th>PRECIO</th>
									<th>FAMILIA</th>
									<th>FECHA</th>
									<?php if (! $this->ion_auth->is_admin()): ?>
										<th>ACCIÓN</th>
									<?php endif ?>
								</tr>
							</thead>
							<tbody>
								<?php if ($productosProveedor): ?>
									<?php foreach ($productosProveedor as $key => $value): ?>
										<tr>
											<th><?php echo $value->id_producto_proveedor ?></th>
											<?php
												echo (! $this->ion_auth->is_admin()) ? '' : "<td>". strtoupper($value->first_name.' '.$value->last_name) ."</td>";
											?>
											<td><?php echo $value->codigo ?></td>
											<td><?php echo $value->producto ?></td>
											<td><?php echo '$ '.number_format($value->precio,2,'.',',') ?></td>
											<td><?php echo $value->familia ?></td>
											<td><?php echo $value->fecha ?></td>
											<?php if (! $this->ion_auth->is_admin()): ?>
												<td>
													<a data-toggle="modal" data-tooltip="tooltip" title="Editar"  class="btn tool btn-info btn-modal" href="<?php echo site_url('Productos_proveedor/get_update/'.$value->id_producto_proveedor);?>" data-target="#myModal" ><i class="fa fa-pencil"></i></a>
													<a data-toggle="modal" data-tooltip="tooltip" title="Eliminar"  class="btn tool btn-warning btn-modal" href="<?php echo site_url('Productos_proveedor/get_delete/'.$value->id_producto_proveedor);?>" data-target="#myModal" ><i class="fa fa-trash"></i></a>
												</td>
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

<script type="text/javascript">
	$(function($) {
		$("#table_prod_proveedor").dataTable({
			responsive: true,
			pageLength: 50,
			order: [[0, 'ASC']],
			dom: 'Bfrtip',
			lengthMenu: [
				[ 10, 30, 50, -1 ],
				[ '10 registros', '30 registros', '50 registros', 'Mostrar todos']
			],
			buttons: [
				{ extend: 'pageLength' },
				{
					extend: 'excel',
					exportOptions: {
						columns: [0,1,2,3,4,5,6]
					},
					title: 'Cotizaciones',
				},
			]
		});
	});
	
</script>