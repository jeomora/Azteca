<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Listado de Pedidos</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<a data-toggle="modal" data-tooltip="tooltip" title="Registrar" class="btn btn-primary tool btn-modal" href="<?php echo site_url('Pedidos/add_pedido'); ?>" data-target="#myModal">
							<i class="fa fa-plus"></i>
						</a>
					</div>
						<table class="table table-striped table-bordered table-hover" id="table_pedidos">
							<thead>
								<tr>
									<th>NO</th>
									<th>PROVEEDOR</th>
									<th>TOTAL</th>
									<th>FECHA</th>
									<th>SUCURSAL</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($pedidos): ?>
									<?php foreach ($pedidos as $key => $value): ?>
										<tr>
											<th><?php echo $value->id_pedido ?></th>
											<td><?php echo $value->proveedor ?></td>
											<td><?php echo '$ '.number_format($value->total,2,'.',',') ?></td>
											<td><?php echo $value->fecha ?></td>
											<td><?php echo strtoupper($value->sucursal) ?></td>
											<td>
												<a data-toggle="modal" data-tooltip="tooltip" title="Editar"  class="btn tool btn-info btn-modal" href="<?php echo site_url('Pedidos/get_update/'.$value->id_pedido);?>" data-target="#myModal" ><i class="fa fa-pencil"></i></a>
												<a data-toggle="modal" data-tooltip="tooltip" title="Ver"  class="btn tool btn-success btn-modal" href="<?php echo site_url('Pedidos/get_detalle/'.$value->id_pedido);?>" data-target="#myModal" ><i class="fa fa-eye"></i></a>
												<a data-toggle="modal" data-tooltip="tooltip" title="Eliminar"  class="btn tool btn-warning btn-modal" href="<?php echo site_url('Pedidos/get_delete/'.$value->id_pedido);?>" data-target="#myModal" ><i class="fa fa-trash"></i></a>
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
		fillDataTable("table_pedidos", 'DESC', 10);
	});
</script>