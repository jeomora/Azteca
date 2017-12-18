<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_familia_delete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_pedido" id="id_pedido" value="<?php echo $pedido->id_pedido ?>">
			<p style="font-size: 25px; text-align: center;">
				¿Desea eliminar el Pedido: <strong><?php echo '' ?></strong> ?.</p>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$(document).off("click", ".delete").on("click", ".delete", function(event) {
		event.preventDefault();
		sendDatos("Pedidos/accion/D", $("#form_familia_delete"), "Pedidos/pedidos_view");
	});

</script>