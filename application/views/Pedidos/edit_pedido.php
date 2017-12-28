<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_pedido_edit')); ?>
		<input type="hidden" name="id_pedido" id="id_pedido" value="<?php echo $pedido->id_pedido ?>">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="" class="form-control" placeholder="">
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
