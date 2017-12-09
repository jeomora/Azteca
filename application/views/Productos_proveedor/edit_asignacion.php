<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_asignacion_edit')); ?>
		<input type="hidden" name="id_producto_proveedor" id="id_producto_proveedor" value="<?php echo $prod_prov->id_producto_proveedor ?>">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="precio">Precio</label>
					<input type="text" name="precio" value="<?php echo $prod_prov->precio ?>" class="form-control" placeholder="0.00">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" value="<?php echo $producto->nombre ?>" class="form-control" readonly="">
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
