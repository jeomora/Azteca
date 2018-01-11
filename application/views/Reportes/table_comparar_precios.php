<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>COMPARATIVA DE PRECIOS</h5>
				</div>
				<div class="ibox-content">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_compara_precios">
							<thead>
								<tr>
									<th>FAMILIAS</th>
									<th>CÓDIGO</th>
									<th>ARTÍCULO</th>
									<th>PRECIO</th>
									<th>PROMOCIÓN</th>
									<th>VS</th>
									<th>CÓDIGO</th>
									<th>ARTÍCULO</th>
									<th>PRECIO</th>
									<th>PROMOCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php $a=0; if ($comparaPrecios): ?>
									<?php foreach ($comparaPrecios as $key => $value): ?>
										<tr>
											<td colspan="10"> <b><?php echo $value['familia'] ?> </b> </td>
											<?php if ($value['articulos']): foreach ($value['articulos'] as $key => $val): ?>
												<tr>
													<td></td>
													<td><?php echo $val['codigo'] ?></td>
													<td><?php echo $val['producto'] ?></td>
													<td><?php echo '$ '.number_format($val['precio_befor'],2,'.',',') ?></td>
													<td><?php echo $val['promocion_befor'] ?></td>
													<th><?php echo ($val['precio_now'] > $val['precio_befor']) ? '$ '.number_format(($val['precio_now'] - $val['precio_befor']),2,'.',',') : '$ '.number_format(($val['precio_befor'] - $val['precio_now']),2,'.',',') ?></th>
													<td><?php echo $val['codigo'] ?></td>
													<td><?php echo $val['producto'] ?></td>
													<td><?php echo '$ '.number_format($val['precio_now'],2,'.',',') ?></td>
													<td><?php echo $val['promocion_now'] ?></td>
												</tr>
											<?php endforeach; endif ?>
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