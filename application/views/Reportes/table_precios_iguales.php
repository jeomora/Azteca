<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>REPORTE DE PRECIOS IGUALES</h5>
				</div>
				<div class="ibox-content">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_precios_iguales">
							<thead>
								<tr>
									<th>NO</th>
									<th>PROVEEDOR</th>
									<th>ARTÍCULO</th>
									<th>PRECIO BAJO</th>
									<th>PRECIO FACTURA</th>
									<th>PROMOCIÓN</th>
									<th>OBSERVACIONES</th>
									<th>DESCUENTO 1</th>
									<th>DESCUENTO 2</th>
									<th>DESCUENTO</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($promociones_igual): ?>
									<?php foreach ($promociones_igual as $key => $value): ?>
										<tr>
											<th><?php echo $value->id_cotizacion ?></th>
											<td><?php echo $value->proveedor ?> </td>
											<td><?php echo strtoupper($value->producto) ?></td>
											<td><?php echo '$ '.number_format($value->precio,2,'.',',') ?></td>
											<td><?php echo ($value->precio_factura > 0) ? '$ '.number_format($value->precio_factura,2,'.',',') : '' ?></td>
											<td><?php echo $value->promocion ?></td>
											<td><?php echo $value->observaciones ?></td>
											<td><?php echo ($value->num_one > 0) ? number_format($value->num_one,2,'.',',') : '' ?></td>
											<td><?php echo ($value->num_two > 0) ? number_format($value->num_two,2,'.',',') : '' ?></td>
											<td><?php echo ($value->descuento > 0) ? $value->descuento.' %' : '' ?></td>
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