<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_sucursal_edit')); ?>
		<input type="hidden" name="id_sucursal" id="id_sucursal" value="<?php echo $sucursal->id_sucursal ?>">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="<?php echo $sucursal->nombre ?>" class="form-control" placeholder="Nombre de la sucursal">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="telefono">Teléfono</label>
					<input type="text" name="telefono" value="<?php echo $sucursal->telefono ?>" class="form-control" placeholder="000 0000 000">
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$("#form_sucursal_edit").validate({
		rules: {
			nombre: {required: true},
			telefono: {required: true}
		}
	});

	jQuery.extend(jQuery.validator.messages, {
		required: "Este campo es requerido",
	});

	$(document).off("click", ".update").on("click", ".update", function(event) {
		event.preventDefault();
		if ($("#form_sucursal_edit").valid()) {
			sendDatos("Sucursales/accion/U/",$("#form_sucursal_edit"), "Sucursales/sucursales_view");
		}
	});
</script>