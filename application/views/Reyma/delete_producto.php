<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_producto_delete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_producto" id="id_producto" value="<?php echo $producto->id_producto ?>">
			<p style="font-size: 25px; text-align: center;">
				¿Desea eliminar el Producto: <strong><?php echo $producto->nombre ?></strong> </p>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>