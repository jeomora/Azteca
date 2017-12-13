<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_producto_delete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_producto" id="id_producto" value="<?php echo $producto->id_producto ?>">
			<p style="font-size: 25px; text-align: center;">
				¿Desea eliminar: <strong><?php echo $producto->nombre ?></strong> ? </p>
		<!-- 	<p style="font-size: 25px; text-align: center;">
				con un precio de <strong><?php echo number_format($producto->precio,2,'.',',') ?></strong> ?.</p> -->
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$(document).off("click", ".delete").on("click", ".delete", function(event) {
		event.preventDefault();
		sendDatos("Productos/accion/D",$("#form_producto_edit"), "Productos/productos_view");
	});
</script>