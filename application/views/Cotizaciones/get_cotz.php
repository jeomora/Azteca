<style type="text/css" media="screen">
	.datepicker{ z-index: 9999 !important; }
	.modal-lg {width: 800px !important;}
	table#table_cot_admin {width: 620px !important;}
	.modal-body {
	    height: 64vh;
	    overflow-y: scroll;
	}
	.blockUI.blockOverlay {z-index: 10000 !important}	
	.blockUI.blockMsg.blockElement{z-index: 10001 !important}
</style>

<div class="ibox-content" style="border-color: #3f47cc">
	<div class="row">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
				<thead>
					<tr>
						<th>CODIGO</th>
						<th>DESCRIPCIÓN</th>
						<th>PRECIO</th>
						<th>PRECIO C/PROMOCIÓN</th>
						<th>OBSERVACIONES</th>
					</tr>
				</thead>
				<tbody>
					<?php if($cotizaciones) :foreach ($cotizaciones as $key => $value): ?>
						<tr>
							<td><?php echo strtoupper($value->codigo) ?></td>
							<td><?php echo strtoupper($value->producto) ?></td>
							<td><?php echo ($value->precio >0) ? '$ '.number_format($value->precio,2,'.',',') : '' ?></td>
							<td><?php echo ($value->precio_promocion >0) ? '$ '.number_format($value->precio_promocion,2,'.',',') : ''?></td>
							<td><?php echo $value->observaciones ?></td>
						</tr>
					<?php endforeach;endif; ?>
				</tbody>
			</table>
		</div>

	</div>


	
</div>
