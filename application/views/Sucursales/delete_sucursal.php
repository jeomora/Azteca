<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_sucursal_delete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_sucursal" id="id_sucursal" value="<?php echo $sucursal->id_sucursal ?>">
			<p style="font-size: 25px; text-align: center;">
				¿Desea eliminar la Sucursal: <strong><?php echo $sucursal->nombre ?></strong> ?.</p>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$(document).off("click", ".delete").on("click", ".delete", function(event) {
		event.preventDefault();
		sendDatos("Sucursales/accion/D", $("#form_sucursal_delete"), "Sucursales/sucursales_view");
	});

</script>