<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_proveedor_edit')); ?>
		<input type="hidden" name="id_proveedor" id="id_proveedor" value="<?php echo $proveedor->id_proveedor ?>">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="<?php echo $proveedor->nombre ?>" class="form-control" placeholder="Empresa">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="apellido">Alias (pestaña excel)</label>
					<input type="text" name="apellido" value="<?php echo $proveedor->alias ?>" class="form-control" placeholder="Alias">
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>