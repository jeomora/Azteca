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
						<table class="table table-striped table-bordered table-hover" id="table_compara_precios">
							<thead>
								<tr>
									<th>NO</th>
									<th>ARTICULO</th>
									<th>PRECIO</th>
									<th>PROMOCIÓN</th>
									<th>VS</th>
									<th>ARTICULO</th>
									<th>PRECIO</th>
									<th>PROMOCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php $a=0; if ($comparaPrecios): ?>
									<?php foreach ($comparaPrecios as $key => $value): ?>
										<tr>
											<th><?php echo $a+1 ?></th>
											<td><?php echo '' ?></td>
											<td><?php echo '' ?></td>
											<td><?php echo '' ?></td>
											<th>|</th>
											<td><?php echo '' ?></td>
											<td><?php echo '' ?></td>
											<td><?php echo '' ?></td>
											
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