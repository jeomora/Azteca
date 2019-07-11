<style>
	#exislun>thead>tr>th{background:#000 !important;color:#FFF !important;}
	#exislunnot>thead>tr>th{background:#000 !important;color:#FFF !important;}
</style>
<div class="ibox-content">
	<div class="row">
		<div class="row"> 
			<div class="col-md-12">
				<h1>
					Sin Existencias
				</h1>
			</div>
			<div class="col-sm-12">
				<table class="table table-striped table-bordered table-hover" id="exislunnot">
					<thead>
						<tr>
							<th>No</th>
							<th>CÓDIGO</th>
							<th>DESCRIPCIÓN</th>
						</tr>
					</thead>
					<tbody>
						<?php if($existenciasnot):foreach ($existenciasnot as $key => $value): ?>
							<tr>
								<td><?php echo ($key+1) ?></td>
								<td><?php echo $value->codigo ?></td>
								<td><?php echo $value->descripcion ?></td>
							</tr>
						<?php endforeach;endif; ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<h1>
					Existencias Registradas
				</h1>
			</div>
			<div class="col-sm-12">
				<table class="table table-striped table-bordered table-hover" id="exislun">
					<thead>
						<tr>
							<th>CÓDIGO</th>
							<th>DESCRIPCIÓN</th>
							<th>CAJAS</th>
							<th>PIEZAS</th>
							<th>PEDIDO</th>
						</tr>
					</thead>
					<tbody>
						<?php if($existencias):foreach ($existencias as $key => $value): ?>
							<tr>
								<td><?php echo $value->codigo ?></td>
								<td><?php echo $value->descripcion ?></td>
								<td><?php echo $value->cajas ?></td>
								<td><?php echo $value->piezas ?></td>
								<td><?php echo $value->pedido ?></td>
							</tr>
						<?php endforeach;endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>