<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_cotizacion_delete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_cotizacion" id="id_cotizacion" value="<?php echo $cotizacion->id_cotizacion ?>">
			<p style="font-size: 25px; text-align: center;">
				¿Desea eliminar la Cotización <strong><?php echo $cotizacion->nombre ?></strong> </p>
			<p style="font-size: 25px; text-align: center;">
				del producto: <strong><?php echo $producto->nombre ?></strong> ?.</p>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>