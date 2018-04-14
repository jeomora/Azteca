<style type="text/css" media="screen">
	.datepicker{ z-index: 9999 !important; }
	.modal-lg {width: 80vw !important;}
	table#table_cot_admin {width: 98% !important;}
	.modal-body {
	    height: 64vh;
	    overflow-y: scroll;
	}
	.blockUI.blockOverlay {z-index: 10000 !important}	
	.blockUI.blockMsg.blockElement{z-index: 10001 !important}
	tr:hover > td{color: black !important;}
</style>

<div class="ibox-content" style="padding: 4%">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_cotizacion_edit')); ?>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
				<thead>
					<tr>
						<th>DESCRIPCIÓN</th>
						<th>PRECIO</th>
						<th>PRECIO C/PROMOCIÓN</th>
						<th colspan="2" style="text-align: center;"># EN #</th>
						<th>DESCUENTO</th>
						<th>OBSERVACIONES</th>
					</tr>
				</thead>
				<tbody>
					<?php if($cotizaciones) :foreach ($cotizaciones as $key => $value): ?>
						<tr>
							<td><?php echo strtoupper($value->producto) ?></td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
									<input type='text' value='<?php echo $value->precio ?>' class='form-control precio numeric'>
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
									<?php if($value->precio_promocion > 0): ?> 
										<input type='text' value='<?php echo $value->precio_promocion ?>' class='form-control precio_promocion numeric' name="precio[]" readonly=''>
									<?php else: ?>
										<input type='text' value='<?php echo $value->precio ?>' name="precio_promocion[]" class='form-control precio_promocion numeric'>
									<?php endif ?>
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-slack'></i></span>
									<input type='text' value='<?php echo $value->num_one ?>' name="num_one[]" class='form-control num_one numeric'>
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-slack'></i></span>
									<input type='text' value='<?php echo $value->num_two ?>' name="num_two[]" class='form-control num_two numeric'>
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<input type='text' value='<?php echo $value->descuento ?>' name="descuento[]" class='form-control descuento numeric'>
									<span class='input-group-addon' style="font-weight: bold">%</i></span>
								</div>
							</td>
							<td><input type='text' value='<?php echo $value->observaciones ?>' name="observaciones[]" class='form-control observaciones'></td>
						</tr>
					<?php endforeach;endif; ?>
				</tbody>
			</table>
		</div>
		<?php echo form_close(); ?>
	</div>


	
</div>
