<?php 
if(!$this->session->userdata("username")){
	redirect("Welcome/Login", "");
}
?>
<style type="text/css" media="screen">
	td {width: 8rem !important;}
	table#table_cot_admin {width: 79rem !important;
</style>
<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_cotizacion_delete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_cotizacion" id="id_cotizacion" value="<?php echo $cotizacion->id_cotizacion ?>">
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
								<?php foreach ($cots as $key => $value): ?>
									<tr>
										<td><?php echo $value->nomb ?></td>
										<td><?php echo $value->precio ?></td>
										<?php if($value->precio_promocion > 0): ?>
											<td><?php echo $value->precio_promocion ?></td>
										<?php else: ?>
											<td><?php echo $value->precio ?></td>
										<?php endif ?>
										<td><?php echo $value->observaciones ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>