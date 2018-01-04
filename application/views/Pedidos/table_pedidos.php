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
						<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_pedido">
							<i class="fa fa-plus"></i>
						</button>
					</div>
					<div class="table-responsive">
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
												<button id="update_pedido" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-pedido="<?php echo $value->id_pedido ?>">
													<i class="fa fa-pencil"></i>
												</button>
												<button id="show_pedido" class="btn btn-success" data-toggle="tooltip" title="Ver" data-id-pedido="<?php echo $value->id_pedido ?>">
													<i class="fa fa-eye"></i>
												</button>
												<button id="delete_pedido" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-pedido="<?php echo $value->id_pedido ?>">
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
</div>