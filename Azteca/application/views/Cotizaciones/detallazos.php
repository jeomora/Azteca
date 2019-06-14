<style type="text/css" media="screen">
	td {width: 8rem !important;}
	.spanmo{font-weight: 600}
	h2 {
	    font-size: 24px;
	    border: 1px solid rgb(255, 104, 5);
	    padding: 1rem;
	    background-color: rgb(255, 104, 5);
	    color: white;
	}
</style>
<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_cotizacion_delete')); ?>
		<div class="row col-sm-12">
			<?php if($lastweek):?><div class="row col-sm-12">
				<h2>Precio semana pasada <span class="spanmo">$ <?php echo number_format($lastweek[0]->precio_promocion,2,'.',',') ?></span> por <span class="spanmo"><?php echo $lastweek[0]->nomb ?></span></h2>
			</div><?php endif; ?>
			<div class="row col-sm-12">
			<h2>Cotizaciones Eliminadas y/o faltantes</h2>
			<div class="table-responsive"> 
				<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
					<thead>
						<tr>
							<th>PROVEEDOR</th>
							<th>FACTURA</th>
							<th>FACTURA C/PROMOCIÓN</th>
							<th>OBSERVACIONES</th>
						</tr>
					</thead>
					<tbody>
						<?php if($cotss):foreach ($cotss as $key => $value): ?>
							<tr>
								<td><?php echo $value->nomb ?></td>
								<td><?php echo $value->precio ?></td>
								<td><?php echo $value->precio_promocion ?></td>
								<td><?php echo $value->observaciones ?></td>
							</tr>
						<?php endforeach;endif; ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row col-sm-12">
			<h2>Faltantes</h2>
			<div class="table-responsive"> 
				<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
					<thead>
						<tr>
							<th>PROVEEDOR</th>
							<th>FECHA TERMINO</th>
						</tr>
					</thead>
					<tbody>
						<?php if($faltas):foreach ($faltas as $key => $value): ?>
							<tr>
								<td><?php echo $value->nomb ?></td>
								<td><?php echo $value->fecha_termino ?></td>
							</tr>
						<?php endforeach;endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		</div>
	</div>
</div>

