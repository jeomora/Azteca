<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_familia_new')); ?>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="" class="form-control" placeholder="Nombre de la familia">
				</div>
			</div>
		
		</div>

		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$("#form_familia_new").validate({
		rules: {
			nombre: {required: true}
		}
	});

	jQuery.extend(jQuery.validator.messages, {
		required: "Este campo es requerido",
	});

	$(document).off("click", ".save").on("click", ".save", function(event) {
		event.preventDefault();
		if($("#form_familia_new").valid()){
			sendDatos("Familias/accion/I", $("#form_familia_new"), "Familias/familias_view");
		}
	});

</script>