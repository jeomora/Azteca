<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_promocion_delete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_promocion" id="id_promocion" value="<?php echo $promocion->id_promocion ?>">
			<p style="font-size: 25px; text-align: center;">
				¿Desea eliminar la Promoción: <strong><?php echo "$ ".number_format($promocion->precio_fijo,2,'.',',') ?></strong> </p>
			<p style="font-size: 25px; text-align: center;">
				del producto: <strong><?php echo $producto->nombre ?></strong> ?.</p>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$(document).off("click", ".delete").on("click", ".delete", function(event) {
		event.preventDefault();
		sendDatos("Promociones/accion/D", $("#form_promocion_delete"), "Promociones/promociones_view");
	});
</script>