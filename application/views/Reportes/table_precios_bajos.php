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
						<!-- <?php echo $table ?> -->
						<table class="table table-striped table-bordered table-hover" id="table_precios_bajos">
							<thead>
								<tr>
									<th>ARTICULO</th>
									<th>PROVEEDOR</th>
									<th>PRECIO</th>
									<th>DESCUENTO</th>
									<th>PRECIO DESCUENTO</th>
									<th>POMOCIÓN</th>
									<th>OBSERVACIONES</th>
									<th>DESCUENTO 1</th>
									<th>DESCUENTO 2</th>
									<th>|</th>
									<th>PROVEEDOR</th>
									<th>PRECIO</th>
									<th>DESCUENTO</th>
									<th>PRECIO DESCUENTO</th>
									<th>POMOCIÓN</th>
									<th>OBSERVACIONES</th>
									<th>DESCUENTO 1</th>
									<th>DESCUENTO 2</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($preciosBajos): ?>
									<?php foreach ($preciosBajos as $key => $value): ?>
										<tr>
											<td><?php echo $value->producto ?></td>
											<td><?php echo $value->proveedor_minimo ?></td>
											<td><?php echo '$ '.number_format($value->precio_minimo,2,'.',',') ?></td>
											<td><?php echo number_format($value->descuento_minimo,2,'.',',').' %' ?></td>
											<td><?php echo '$ '.number_format($value->precio_descuento_minimo,2,'.',',') ?></td>
											<td><?php echo strtoupper($value->promocion_minimo) ?></td>
											<td><?php echo strtoupper($value->observaciones_minimo) ?></td>
											<td><?php echo '$ '.number_format($value->precio_inicio_minimo,2,'.',',') ?></td>
											<td><?php echo '$ '.number_format($value->precio_fin_minimo,2,'.',',') ?></td>
											<th>|</th>
											<td><?php echo $value->proveedor_siguiente ?></td>
											<td><?php echo '$ '.number_format($value->precio_siguiente,2,'.',',') ?></td>
											<td><?php echo number_format($value->descuento_siguiente,2,'.',',').' %' ?></td>
											<td><?php echo '$ '.number_format($value->precio_descuento_siguiente,2,'.',',') ?></td>
											<td><?php echo strtoupper($value->promocion_siguiente) ?></td>
											<td><?php echo strtoupper($value->observaciones_siguiente) ?></td>
											<td><?php echo '$ '.number_format($value->precio_inicio_siguiente,2,'.',',') ?></td>
											<td><?php echo '$ '.number_format($value->precio_fin_siguiente,2,'.',',') ?></td>
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

<script type="text/javascript">
	$(function($) {
		$("#table_precios_bajos").dataTable({
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
						columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,18]
					},
					title: 'Precios_bajos',
				},
			]
		});
	});
</script>