<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_producto_new')); ?>
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label for="codigo">Código</label>
					<input type="text" name="codigo" value="" class="form-control" placeholder="7500000000">
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Descripción</label>
					<input type="text" name="nombre" value="" class="form-control" placeholder="Nombre">
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<label for="precio">Precio</label>
					<input type="text" name="precio" value="" class="form-control" placeholder="Precio">
				</div>
			</div>

		</div>

		<?php echo form_close(); ?>
	</div>
</div>