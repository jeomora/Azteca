<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_pedido_new')); ?>
		<div class="row">
			<div class="col-sm-8">
				<div class="form-group">
					<label for="id_proveedor">Proveedores</label>
					<select name="id_proveedor" id="id_proveedor" class="form-control">
						<option value="">Seleccionar...</option>
						<?php if ($proveedores): ?>
							<?php foreach ($proveedores as $key => $value): ?>
								<option value="<?php echo $value->id ?>"><?php echo strtoupper($value->first_name.'	'.$value->last_name) ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>
			</div>
		
		</div>

		<div class="row">
			<div class="col-sm-12">
				<table class="table">
					<thead>
						<tr>
							<th>NOMBRE</th>
							<th>PRECIO</th>
							<th>CANTIDAD</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
					<tfoot>
						<tr></tr>
						<tr></tr>
						<tr></tr>
					</tfoot>
				</table>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$("#form_pedido_new").validate({
		rules: {
			nombre: {required: true}
		}
	});

	jQuery.extend(jQuery.validator.messages, {
		required: "Este campo es requerido",
	});

	$(document).off("click", ".save").on("click", ".save", function(event) {
		event.preventDefault();
		if($("#form_pedido_new").valid()){
			sendDatos("Pedidos/accion/I", $("#form_pedido_new"), "Pedidos/pedidos_view");
		}
	});

</script>