<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE COTIZACIONES</h5>
				</div>
				<div class="ibox-content">
					<?php if (! $this->ion_auth->is_admin()): ?>
						<div class="btn-group">
							<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_cotizacion">
								<i class="fa fa-plus"></i>
							</button>
						</div>
					<?php endif ?>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_cotizaciones">
							<thead>
								<tr>
									<th>NO</th>
									<th>PROMOCIÓN</th>
									<th>PRODUCTO</th>
									<?php
										echo (! $this->ion_auth->is_admin()) ? '' : "<th>PROVEEDOR</th>";
									?>
									<th>F. REGISTRO</th>
									<th>F. CADUCIDAD</th>
									<th>EXISTENCIAS</th>
									<th>PRECIO FACTURA C/PROMOCIÓN</th>
									<th>PRECIO FACTURA S/PROMOCIÓN</th>
									<th>PROMOCIÓN CON DESCUENTO 1</th>
									<th>EN</th>
									<th>PROMOCIÓN CON DESCUENTO 2</th>
									<th>DESCUENTO ADICIONAL</th>
									<?php if (! $this->ion_auth->is_admin()): ?>
										<th>ACCIÓN</th>
									<?php endif ?>
								</tr>
							</thead>
							<tbody>
								<?php if ($cotizaciones): ?>
									<?php foreach ($cotizaciones as $key => $value): ?>
										<tr>
											<th><?php echo $value->id_cotizacion ?></th>
											<td><?php echo $value->promocion ?></td>
											<td><?php echo strtoupper($value->producto) ?></td>
											<?php
												echo (! $this->ion_auth->is_admin()) ? '' : "<td>". strtoupper($value->first_name.' '.$value->last_name) ."</td>";
											?>
											<td><?php echo date('d-m-Y', strtotime($value->fecha_registro)) ?></td>
											<td><?php echo ($value->fecha_caduca != '') ? date('d-m-Y', strtotime($value->fecha_caduca)) : '' ?></td>
											<td><?php echo ($value->existencias > 0) ? number_format($value->existencias,2,'.',',') : '' ?></td>
											<td><?php echo '$ '.number_format($value->precio_factura,2,'.',',') ?></td>
											<td><?php echo ($value->precio > 0) ? '$ '.number_format($value->precio,2,'.',',') : '' ?></td>
											<td><?php echo ($value->num_one > 0 ) ? $value->num_one : '' ?></td>
											<td></td>
											<td><?php echo ($value->num_two > 0) ? $value->num_two : '' ?></td>
											<td><?php echo ($value->descuento > 0) ? $value->descuento.' %' : ''  ?></td>
											<?php if (! $this->ion_auth->is_admin()): ?>
												<td>
													<button id="update_cotizacion" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-promocion="<?php echo $value->id_cotizacion ?>">
														<i class="fa fa-pencil"></i>
													</button>
													<button id="delete_cotizacion" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-promocion="<?php echo $value->id_cotizacion ?>">
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
</div>