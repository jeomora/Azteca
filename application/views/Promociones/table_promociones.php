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
							<button class="btn btn-primary" title="Registrar" id="new_promocion">
								<i class="fa fa-plus"></i>
							</button>
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
									<th>PRE. DESCUENTO</th>
									<th>DESCUENTO 1</th>
									<th>DESCUENTO 2</th>
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
											<td><?php echo ($value->precio_inicio > 0 ) ? '$ '.number_format($value->precio_inicio,2,'.',',') : '' ?></td>
											<td><?php echo ($value->precio_fin > 0) ? '$ '.number_format($value->precio_fin,2,'.',',') : '' ?></td>
											<?php if (! $this->ion_auth->is_admin()): ?>
												<td>
													<button id="update_promocion" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-promocion="<?php echo $value->id_promocion ?>">
														<i class="fa fa-pencil"></i>
													</button>
													<button id="delete_promocion" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-promocion="<?php echo $value->id_promocion ?>">
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