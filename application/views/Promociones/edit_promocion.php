<style type="text/css" media="screen">
	.datepicker{ z-index: 9999 !important; }
</style>

<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_promocion_edit')); ?>
		<input type="hidden" name="id_promocion" id="id_promocion" value="<?php echo $promocion->id_promocion ?>">
		<div class="row">
			<div class="col-sm-8">
				<div class="form-group">
					<label for="id_producto">Articulos</label>
					<select name="id_producto" id="id_producto" class="form-control">
						<option value="">Seleccionar...</option>
						<?php if ($productos): ?>
							<?php foreach ($productos as $key => $value): ?>
								<option value="<?php echo $value->id_producto ?>" <?php echo $promocion->id_producto == $value->id_producto ? 'selected' : '' ?> ><?php echo strtoupper($value->nombre) ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input class="form-control " placeholder="Nombre de la promoción" name="nombre" id="nombre" value="<?php echo $promocion->nombre ?>" type="text">
				</div>
			</div>

		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="rango">Rango</label>
					<div class="input-daterange input-group">
						<span class="input-group-addon">De</span>
						<input class="form-control number" placeholder="0.00" name="precio_desde" id="precio_desde" value="<?php echo $promocion->precio_inicio ?>" type="text">
						<span class="input-group-addon">a</span>
						<input class="form-control number" placeholder="0.00" name="precio_hasta" id="precio_hasta" value="<?php echo $promocion->precio_fin ?>" type="text">
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label for="precio_producto">Precio</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
						<input type="text" name="precio_producto"  id="precio_producto" class="form-control number" value="<?php echo $promocion->precio_fijo ?>" placeholder="0.00">
						<span class="validar"></span>
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label for="porcentaje">Porcentaje</label>
					<div class="input-group m-b">
						<input type="text" name="porcentaje" id="porcentaje" class="form-control number" value="<?php echo $promocion->descuento ?>" placeholder="0.00">
						<span class="input-group-addon sm">%</span>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="precio_descuento">Precio total</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
						<input type="text" name="precio_descuento" id="precio_descuento" class="form-control number" value="<?php echo $promocion->precio_descuento ?>" readonly="">
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label for="fecha">Fecha</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" name="fecha" id="fecha" class="form-control datepicker" value="<?php echo date('d-m-Y', strtotime($promocion->fecha_registro)) ?>" placeholder="">
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label for="fecha_vence">Fecha vence</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" name="fecha_vence" id="fecha_vence" class="form-control datepicker" value="<?php echo date('d-m-Y', strtotime($promocion->fecha_caduca)) ?>" placeholder="00-00-0000">
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="existencias">Existencias</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-slack"></i></span>
						<input type="text" name="existencias" id="existencias" class="form-control number" value="<?php echo $promocion->existencias ?>" placeholder="0">
					</div>
				</div>
			</div>

			<div class="col-sm-8">
				<div class="form-group">
					<label for="observaciones">Observaciones</label>
					<textarea type="text" rows="5" placeholder="Escrina las observaciones" class="form-control" name="observaciones" id="observaciones"><?php echo $promocion->observaciones ?></textarea>
				</div>
			</div>
		</div>

		<input type="hidden" id="name_user" value="<?php echo strtoupper($usuario->username) ?>"/>
		
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
		datePicker();

	$(".number").inputmask("currency", {radixPoint: ".", prefix: ""});

	$("#form_promocion_edit").validate({
		rules: {
			id_producto: {required: true, min:0},
			precio_producto: {required: true},
		
		}
	});

	jQuery.extend(jQuery.validator.messages, {
		required: "Este campo es requerido",
		min: jQuery.validator.format("Este campo es requerido"),
	});

	$(document).off("click", ".update").on("click", ".update", function(event) {
		if($("#form_promocion_edit").valid()){
			sendDatos("Promociones/update", $("#form_promocion_edit"), "Promociones/promociones_view", "show");
		}
	});

	$("#porcentaje").keyup(function() {
		var descuento = $(this).val().replace(/[^0-9\.]+/g,"");
		var precio_producto = $("#precio_producto").val().replace(/[^0-9\.]+/g,"");
		if(precio_producto != ''){
			$("#precio_descuento").val((precio_producto - (precio_producto * descuento)));
		}
	});
</script>