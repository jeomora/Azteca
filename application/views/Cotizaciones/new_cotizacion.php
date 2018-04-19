<style type="text/css" media="screen">
	.datepicker{ z-index: 9999 !important; }
</style>


<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_cotizacion_new')); ?>
		<div class="row">
			<div class="col-sm-8">
				<div class="form-group">
					<label for="id_producto">Articulos</label>
					<select name="id_producto" id="id_producto" class="form-control chosen-select">
						<option value="">Seleccionar...</option>
						<?php if ($productos): ?>
							<?php foreach ($productos as $key => $value): ?>
								<option value="<?php echo $value->id_producto ?>"><?php echo strtoupper($value->nombre) ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>
			</div>
			<div class="col-sm-4">
				<label for="precio">Precio factura</label>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
					<input type="text" name="precio"  id="precio" class="form-control number" value="" placeholder="0.00">
					<span class="validar"></span>
				</div>
			</div>
			
		</div>

		<div class="row">
			<div class="col-sm-4">
				<label for="promocion">Promoción del artículo</label>
				<div class="form-group">
					<div class="input-group">
						<input class="form-control number" placeholder="0" id="num_one" type="text" name="num_one" >
						<span class="input-group-addon"><b>En</b></span>
						<input class="form-control number" placeholder="0" id="num_two" type="text" name="num_two">
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<label for="promocion">Descuento</label>
				<div class="input-group m-b">
					<input type="text" name="porcentaje" id="porcentaje" class="form-control number" value="" placeholder="0">
					<span class="input-group-addon sm">%</span>
				</div>
			</div>
			
		
			<div class="col-sm-4">
				<div class="form-group">
					<label for="precio_promocion">Precio factura C/Promoción</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
						<input type="text" name="precio_promocion" id="precio_promocion" class="form-control number" placeholder="0.00" readonly="">
						<span class="validar"></span>
					</div>
				</div>
			</div>
			
			<div class="col-sm-8">
				<div class="form-group">
					<label for="observaciones">Observaciones</label>
					<textarea type="text" rows="5" placeholder="Escriba las observaciones" class="form-control" name="observaciones" id="observaciones"></textarea>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

