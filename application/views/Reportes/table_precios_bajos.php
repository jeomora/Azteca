<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username")){
	redirect("Welcome/Login", "");
}
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
									<th>1ER PROVEEDOR</th>
									<th>1ER PRECIO</th>
									<th>DESCUENTO</th>
									<th>PRECIO FACTURA</th>
									<th>|</th>
									<th>2DO PROVEEDOR</th>
									<th>2DO PRECIO</th>
									<th>DESCUENTO</th>
									<th>PRECIO FACTURA</th>
									<th>OBSERVACIONES</th>
									<th>PROMOCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php $a=0; if ($preciosBajos): ?>
									<?php foreach ($preciosBajos as $key => $value): ?>
										<tr>
											<th><?php echo $a+1 ?></th>
											<td><?php echo $value->producto ?></td>
											<td><?php echo $value->proveedor_first ?></td>
											<td><?php echo '$ '.number_format($value->precio_first,2,'.',',') ?></td>
											<td><?php echo ($value->descuento_first >0) ? number_format($value->descuento_first,2,'.',',').' %' : '' ?></td>
											<td><?php echo ($value->precio_promocion_first >0) ? '$ '.number_format($value->precio_promocion_first,2,'.',',') : '' ?></td>
											<th>|</th>
											<td><?php echo $value->proveedor_next ?></td>
											<td><?php echo ($value->precio_next >0) ? '$ '.number_format($value->precio_next,2,'.',',') : '' ?></td>
											<td><?php echo ($value->descuento_next >0) ? number_format($value->descuento_next,2,'.',',').' %' : '' ?></td>
											<td><?php echo ($value->precio_promocion_next >0) ? '$ '.number_format($value->precio_promocion_next,2,'.',',') : '' ?></td>
											<td><?php echo $value->observaciones_first ?></td>
											<td><?php echo $value->promocion_first ?></td>
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