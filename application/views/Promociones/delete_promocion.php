<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_promociondelete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_promocion" id="id_promocion" value="<?php echo $promocion->id_promocion ?>">
			<p style="font-size: 25px; text-align: center;">
				¿Desea eliminar la Promoción: <strong><?php echo $promocion->id_promocion ?></strong> ?.</p>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>