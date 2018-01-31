<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_usuario_delete')); ?>
		<div class="row col-sm-12">
			<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $usuario->id_usuario ?>">
			<p style="font-size: 25px; text-align: center;">
				¿Desea eliminar el Usuario: <strong><?php echo $usuario->nombre.' '.$usuario->apellido ?></strong> </p>
			<p style="font-size: 25px; text-align: center;">
				con un correo de:  <strong><?php echo $usuario->email ?></strong> ?.</p>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>