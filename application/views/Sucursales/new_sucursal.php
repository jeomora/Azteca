<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_sucursal_new')); ?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="" class="form-control" placeholder="Nombre de la sucursal">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="telefono">Teléfono</label>
					<input type="text" name="telefono" value="" class="form-control" placeholder="000 0000 000">
				</div>
			</div>
		
		</div>

		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$("#form_sucursal_new").validate({
		rules: {
			nombre: {required: true},
			telefono: {required: true}
		}
	});

	jQuery.extend(jQuery.validator.messages, {
		required: "Este campo es requerido",
	});

	$(document).off("click", ".save").on("click", ".save", function(event) {
		event.preventDefault();
		if($("#form_sucursal_new").valid()){
			sendDatos("Familias/accion/I", $("#form_sucursal_new"), "Familias/sucursales_view");
		}
	});

</script>