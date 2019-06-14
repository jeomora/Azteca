<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_pedido_delete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_pedido" id="id_pedido" value="<?php echo $pedido->id_pedido ?>">
			<p style="font-size: 25px; text-align: center;">
				¿Desea eliminar el pedido del Proveedor: <strong><?php echo strtoupper($proveedor->first_name.' '.$proveedor->last_name) ?></strong> </p>
			<p style="font-size: 25px; text-align: center;">
				por la cantidad de: <strong>$ <?php echo number_format($pedido->total, 2, '.',',') ?></strong> ?.
			</p>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>