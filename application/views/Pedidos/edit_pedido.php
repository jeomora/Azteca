<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_pedido_edit')); ?>
		<input type="hidden" name="id_pedido" id="id_pedido" value="<?php echo $pedido->id_pedido ?>">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="" class="form-control" placeholder="">
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$("#form_pedido_edit").validate({
		rules: {
			nombre: {required: true}
		}
	});

	jQuery.extend(jQuery.validator.messages, {
		required: "Este campo es requerido",
	});

	$(document).off("click", ".update").on("click", ".update", function(event) {
		event.preventDefault();
		if ($("#form_pedido_edit").valid()) {
			sendDatos("Pedidos/accion/U/",$("#form_pedido_edit"), "Pedidos/pedidos_view");
		}
	});
</script>