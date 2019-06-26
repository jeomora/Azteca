<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_proveedor_new')); ?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="" class="form-control" placeholder="Empresa">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="apellido">Alias (pestaña excel)</label>
					<input type="text" name="apellido" value="" class="form-control" placeholder="Alias">
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>