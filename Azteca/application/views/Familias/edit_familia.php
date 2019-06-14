<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_familia_edit')); ?>
		<input type="hidden" name="id_familia" id="id_familia" value="<?php echo $familia->id_familia ?>">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="<?php echo $familia->nombre ?>" class="form-control" placeholder="Nombre de la familia">
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>