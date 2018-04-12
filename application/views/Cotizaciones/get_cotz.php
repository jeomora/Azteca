<style type="text/css" media="screen">
	.datepicker{ z-index: 9999 !important; }
	.modal-lg {width: 800px !important;}
	table#table_cot_admin {width: 620px !important;}
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
							
						</tr>
					<?php endforeach;endif; ?>
				</tbody>
			</table>
		</div>

	</div>


	
</div>
