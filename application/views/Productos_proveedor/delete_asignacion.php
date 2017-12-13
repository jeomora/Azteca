<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_asignacion_delete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_producto_proveedor" id="id_producto_proveedor" value="<?php echo $prod_prov->id_producto_proveedor ?>">
			<p style="font-size: 25px; text-align: center;">
				¿Desea eliminar la cotización: <strong><?php echo $producto->nombre ?></strong> </p>
			<p style="font-size: 25px; text-align: center;">
				con un precio de: <strong>$ <?php echo number_format($prod_prov->precio,2,'.',',') ?>. </strong>? </p>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$(document).off("click", ".delete").on("click", ".delete", function(event) {
		event.preventDefault();
		sendDatos("Productos_proveedor/accion/D", $("#form_asignacion_delete"),"Productos_proveedor/productos_proveedor_view");
	});
</script>