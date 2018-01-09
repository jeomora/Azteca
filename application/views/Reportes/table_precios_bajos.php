<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>REPORTE DE PRECIOS BAJOS</h5>
				</div>
				<div class="ibox-content">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_precios_bajos">
							<thead>
								<tr>
									<th>NO</th>
									<th>ARTICULO</th>
									<th>PROVEEDOR 1</th>
									<th>PRECIO</th>
									<th>DESCUENTO</th>
									<th>PRECIO DESCUENTO</th>
									<th>|</th>
									<th>PROVEEDOR 2</th>
									<th>PRECIO</th>
									<th>DESCUENTO</th>
									<th>PRECIO DESCUENTO</th>
								</tr>
							</thead>
							<tbody>
								<?php $a=0; if ($preciosBajos): ?>
									<?php foreach ($preciosBajos as $key => $value): ?>
										<tr>
											<th><?php echo $a+1 ?></th>
											<td><?php echo $value->producto ?></td>
											<td><?php echo $value->proveedor_minimo ?></td>
											<td><?php echo '$ '.number_format($value->precio_minimo,2,'.',',') ?></td>
											<td><?php echo ($value->descuento_minimo > 0) ? $value->descuento_minimo.' %' : '' ?></td>
											<td><?php echo isset($value->precio_factura_minimo) ? '$ '.number_format($value->precio_factura_minimo,2,'.',',') : '' ?></td>
											<th>|</th>
											<td><?php echo $value->proveedor_siguiente ?></td>
											<td><?php echo isset($value->precio_siguiente) ? '$ '.number_format($value->precio_siguiente,2,'.',',') : '' ?></td>
											<td><?php echo ($value->descuento_siguiente > 0) ? number_format($value->descuento_siguiente,2,'.',',').' %' :'' ?></td>
											<td><?php echo isset($value->precio_factura_siguiente) ? '$ '.number_format($value->precio_factura_siguiente,2,'.',',') : '' ?></td>
										</tr>
									<?php $a++; endforeach ?>
								<?php endif ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>