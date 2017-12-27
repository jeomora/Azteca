<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_familia_delete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_familia" id="id_familia" value="<?php echo $familia->id_familia ?>">
			<p style="font-size: 25px; text-align: center;">
				¿Desea eliminar la familia: <strong><?php echo $familia->nombre ?></strong> ?.</p>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>