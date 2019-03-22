<style type="text/css" media="screen">
	.datepicker{ z-index: 9999 !important; }
	.modal-lg {width: 700px !important;}
	table#table_cot_admin {width: 620px !important;}
</style>

<div class="ibox-content" style="border-color: #3f47cc">
	<div class="row">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
				<thead>
					<tr>
						<th>ID PROVEEDOR</th>
						<th>PROVEEDOR</th>
						<th>ÚLTIMA COTIZACIÓN</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($cotizados as $key => $value): ?>
						<tr>
							<td style="text-align: center;"><?php echo $value->id_usuario ?></td>
							<td style="text-align: center;"><?php echo $value->nombre ?></td>
							<td style="text-align: center;">
								<?php echo $dias[date('w',strtotime($value->fecha))]." ".date('d',strtotime($value->fecha))." DE ".$meses[date('n',strtotime($value->fecha))-1]." ".date('H:i:s', strtotime($value->fecha)) ?><br>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>

	</div>


	
</div>
