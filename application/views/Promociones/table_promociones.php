<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Listado de Promociones</h5>
				</div>
				<div class="ibox-content">
					<?php if (! $this->ion_auth->is_admin()): ?>
						<div class="btn-group">
							<a data-toggle="modal" data-tooltip="tooltip" title="Registrar" id="show_modal" class="btn btn-primary tool btn-modal" href="<?php echo site_url('Promociones/add_promocion'); ?>" data-target="#myModal">
								<i class="fa fa-plus"></i>
							</a>
						</div>
					<?php endif ?>
						<table class="table table-striped table-bordered table-hover" id="table_promociones">
							<thead>
								<tr>
									<th>NO</th>
									<th>NOMBRE</th>
									<th>PRODUCTO</th>
									<?php
										echo (! $this->ion_auth->is_admin()) ? '' : "<th>PROVEEDOR</th>";
									?>
									<th>F. REGISTRO</th>
									<th>F. VENCE</th>
									<th>EXISTENCIAS</th>
									<th>PRECIO</th>
									<th>DESCUENTO</th>
									<th>P. DESCUENTO</th>
									<th>RANGO PRECIOS</th>
									<?php if (! $this->ion_auth->is_admin()): ?>
										<th>ACCIÓN</th>
									<?php endif ?>
								</tr>
							</thead>
							<tbody>
								<?php if ($promociones): ?>
									<?php foreach ($promociones as $key => $value): ?>
										<tr>
											<th><?php echo $value->id_promocion ?></th>
											<td><?php echo $value->promocion ?></td>
											<td><?php echo strtoupper($value->producto) ?></td>
											<?php
												echo (! $this->ion_auth->is_admin()) ? '' : "<td>". strtoupper($value->first_name.' '.$value->last_name) ."</td>";
											?>
											<td><?php echo date('d-m-Y', strtotime($value->fecha_registro)) ?></td>
											<td><?php echo ($value->fecha_caduca != '') ? date('d-m-Y', strtotime($value->fecha_caduca)) : '' ?></td>
											<td><?php echo ($value->existencias > 0) ? number_format($value->existencias,2,'.',',') : '' ?></td>
											<td><?php echo '$ '.number_format($value->precio_fijo,2,'.',',') ?></td>
											<td><?php echo ($value->descuento > 0) ? number_format($value->descuento,2,'.',',').' %' : '' ?></td>
											<td><?php echo ($value->precio_descuento > 0) ? '$ '.number_format($value->precio_descuento,2,'.',',') : '' ?></td>
											<td>
												<?php echo ($value->precio_inicio > 0 && $value->precio_fin > 0) 
														? 'DE: $ '.number_format($value->precio_inicio,2,'.',',').' A: $ '.number_format($value->precio_fin,2,'.',',')
														: ''
												?>
											</td>
											<?php if (! $this->ion_auth->is_admin()): ?>
												<td>
													<a data-toggle="modal" data-tooltip="tooltip" title="Editar"  class="btn tool btn-info btn-modal" href="<?php echo site_url('Promociones/update_promocion/'.$value->id_promocion);?>" data-target="#myModal" ><i class="fa fa-pencil"></i></a>
													<a data-toggle="modal" data-tooltip="tooltip" title="Eliminar"  class="btn tool btn-warning btn-modal" href="<?php echo site_url('Promociones/delete_promocion/'.$value->id_promocion);?>" data-target="#myModal" ><i class="fa fa-trash"></i></a>
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
	$(function ($) {
		$("#table_promociones").dataTable({
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
						columns: [0,1,2,3,4,5,6,7,8,9,10]
					},
					title: 'Promociones',
				},
			]
		});
	});
</script>