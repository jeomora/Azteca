<style type="text/css" media="screen">
	.datepicker{ z-index: 9999 !important; }
</style>


<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_cotizacion_new')); ?>
		<input type="text" name="idca2" id="idca2" value="<?php echo $catalogo->id_catalogo ?>" style="display:none">
		<div class="row">
			<div class="col-sm-12">
				<h3><?php echo "Código: ".$catalogo->id_catalogo ?><br></h3>
					<h3><?php echo "Descripción: ".$catalogo->descripcion ?><br></h3>
			</div>
			<div class="col-sm-12"></div>
			<div class="col-sm-8">
				<div class="form-group">
					<select name="id_producto" id="id_producto" class="form-control chosen-select">
						<option value="">Seleccionar...</option>
						<?php if ($productos): ?>
							<?php foreach ($productos as $key => $value): ?>
								<option value="<?php echo $value->codigo ?>"><?php echo $value->codigo." => ".strtoupper($value->descripcion) ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

