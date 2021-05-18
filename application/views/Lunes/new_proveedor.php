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
			<div class="col-sm-6">
				<div class="form-group">
					<label for="cargo">Responsable</label>
					<input type="text" name="cargo" value="" class="form-control" placeholder="Responsable del proveedor, EXT:00">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="elaborado">Elaborado por</label>
					<input type="text" name="elaborado" value="" class="form-control" placeholder="Formato elaborado por, EXT:00">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="detalles">Detalles</label>
					<input type="text" name="detalles" value="" class="form-control" placeholder="Tiene pedido pendiente">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="pagot">Tipo Pago</label>
					<input type="text" name="pagot" value="" class="form-control" placeholder="Da 7 días de credito">
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>