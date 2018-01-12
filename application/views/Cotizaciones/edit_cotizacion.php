<style type="text/css" media="screen">
	.datepicker{ z-index: 9999 !important; }
</style>

<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_cotizacion_edit')); ?>
		<input type="hidden" name="id_cotizacion" id="id_cotizacion" value="<?php echo $cotizacion->id_cotizacion ?>">
		<div class="row">
			<div class="col-sm-8">
				<label for="">Artículo</label>
				<div class="form-group">
					<input type="text" class="form-control" value="<?php echo $cotizacion->producto ?>" readonly="">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<label for="fecha_caducidad">Fecha caducidad</label>
					<div class="input-group">
						<input type="text" name="fecha_caducidad" id="fecha_caducidad" class="form-control datepicker" value="<?php echo date('d-m-Y', strtotime($cotizacion->fecha_caduca)) ?>" placeholder="00-00-0000">
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<label for="existencias">Existencias</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-slack"></i></span>
						<input type="text" name="existencias" id="existencias" class="form-control number" value="<?php echo $cotizacion->existencias ?>" placeholder="0">
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<label for="promocion">Promoción del artículo</label>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"> <input type="radio" class="promocion"> </span>
						<input class="form-control numeric" placeholder="0" id="num_one" name="num_one" type="text" value="<?php echo $cotizacion->num_one ?>" readonly="">
						<span class="input-group-addon"><b>En</b></span>
						<input class="form-control numeric" placeholder="0" id="num_two" name="num_two" type="text" value="<?php echo $cotizacion->num_two ?>" readonly="">
					</div>
				</div>
				<div class="form-group">
					<input class="form-control" placeholder="Nombre de la promoción" name="nombre" id="nombre" type="text" value="<?php echo $cotizacion->promocion ?>" >
				</div>
				<div class="input-group m-b">
					<span class="input-group-addon"> <input type="radio" class="descuento"> <b>Descuento</b> </span>
					<input type="text" id="porcentaje" name="porcentaje" class="form-control numeric" value="<?php echo $cotizacion->descuento ?>" placeholder="0" readonly="">
					<span class="input-group-addon sm">%</span>
				</div>
			</div>
			<div class="col-sm-4">
				<label for="precio">Precio factura</label>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
					<input type="text" name="precio"  id="precio" class="form-control number" value="<?php echo $cotizacion->precio ?>" placeholder="0.00">
					<span class="validar"></span>
				</div>
			</div>
		
			<div class="col-sm-4">
				<div class="form-group">
					<label for="precio_promocion">Precio factura C/Promoción</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
						<input type="text" name="precio_promocion"  id="precio_promocion" class="form-control number" value="<?php echo $cotizacion->precio_promocion ?>" placeholder="0.00" readonly="">
						<span class="validar"></span>
					</div>
				</div>
			</div>
			
			<div class="col-sm-8">
				<div class="form-group">
					<label for="observaciones">Observaciones</label>
					<textarea type="text" rows="5" placeholder="Escriba las observaciones" class="form-control" name="observaciones" id="observaciones"><?php echo $cotizacion->observaciones ?></textarea>
				</div>
			</div>
		</div>

		<input type="hidden" id="name_user" value="<?php echo strtoupper($usuario->username) ?>"/>

		<?php echo form_close(); ?>
	</div>
</div>