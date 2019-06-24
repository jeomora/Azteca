<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_proveedor_delete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_proveedor" id="id_proveedor" value="<?php echo $proveedor->id_proveedor ?>">
			<p style="font-size: 25px; text-align: center;">
				¿Desea eliminar el Proveedor: <strong><?php echo $proveedor->nombre ?></strong> </p>
			<p style="font-size: 25px; text-align: center;">
				con un alias:  <strong><?php echo $proveedor->alias ?></strong> ?.</p>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>