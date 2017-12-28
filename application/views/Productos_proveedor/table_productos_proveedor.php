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
							<button class="btn btn-primary tool" title="Registrar" id="new_asignacion">
								<i class="fa fa-plus"></i>
							</button>
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
													<button id="update_asignacion" class="btn btn-info tool" data-toggle="tooltip" title="Editar" data-id-prod_proveedor="<?php echo $value->id_producto_proveedor ?>">
														<i class="fa fa-pencil"></i>
													</button>
													<button id="delete_asignacion" class="btn btn-warning tool" data-toggle="tooltip" title="Eliminar" data-id-prod_proveedor="<?php echo $value->id_producto_proveedor ?>">
														<i class="fa fa-trash"></i>
													</button>
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