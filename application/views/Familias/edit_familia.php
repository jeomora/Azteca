<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_familia_edit')); ?>
		<input type="hidden" name="id_familia" id="id_familia" value="<?php echo $familia->id_familia ?>">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="<?php echo $familia->nombre ?>" class="form-control" placeholder="Nombre de la familia">
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$("#form_familia_edit").validate({
		rules: {
			nombre: {required: true}
		}
	});

	jQuery.extend(jQuery.validator.messages, {
		required: "Este campo es requerido",
	});

	$(document).off("click", ".update").on("click", ".update", function(event) {
		event.preventDefault();
		if ($("#form_familia_edit").valid()) {
			sendDatos("Familias/accion/U/",$("#form_familia_edit"), "Familias/familias_view");
		}
	});
</script>