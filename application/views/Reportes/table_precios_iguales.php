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
									<th>PRODUCTO</th>
									<th>PRECIO</th>
									<th>P. DESCUENTO</th>
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
											<th><?php echo $value->id_promocion ?></th>
											<td><?php echo strtoupper($value->first_name.' '.$value->last_name) ?> </td>
											<td><?php echo strtoupper($value->producto) ?></td>
											<td><?php echo '$ '.number_format($value->precio_fijo,2,'.',',') ?></td>
											<td><?php echo ($value->precio_descuento > 0) ? '$ '.number_format($value->precio_descuento,2,'.',',') : '' ?></td>
											<td><?php echo $value->promocion ?></td>
											<td><?php echo $value->observaciones ?></td>
											<td><?php echo ($value->precio_inicio > 0) ? '$ '.number_format($value->precio_inicio,2,'.',',') : '' ?></td>
											<td><?php echo ($value->precio_fin > 0) ? '$ '.number_format($value->precio_fin,2,'.',',') : '' ?></td>
											<td><?php echo ($value->descuento > 0) ? number_format($value->descuento,2,'.',',').' %' : '' ?></td>
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