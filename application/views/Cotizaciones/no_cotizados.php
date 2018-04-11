<style type="text/css" media="screen">
	.datepicker{ z-index: 9999 !important; }
	.modal-lg {width: 1000px !important;}
	table#table_cot_admin {width: 920px !important;}
	.modal-body{
		height: 40rem;
    	overflow-y: scroll;
	}
</style>

<div class="ibox-content" style="border-color: #3f47cc">
	<div class="row">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
				<thead>
					<tr>
						<th>FAMILIA</th>
						<th>CÓDIGO</th>
						<th>DESCRIPCIÓN</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($cotizados as $key => $value): ?>
						<tr>
							<td style="text-align: center;"><?php echo $value->familia ?></td>
							<td style="text-align: center;"><?php echo $value->codigo ?></td>
							<td style="text-align: center;"><?php echo $value->producto ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>

	</div>


	
</div>