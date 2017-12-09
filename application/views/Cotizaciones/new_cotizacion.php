<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_cotizacion_new')); ?>
		
		<p style="font-size: 15px; margin-left: 10px;"> PROVEEDOR:
			<?php echo strtoupper($usuario->first_name.'	'.$usuario->last_name) ?>
		</p>
		<input type="hidden" name="id_proveedor" value="<?php echo $usuario->id ?>">
			
		<input type="hidden" id="name_user" value="<?php echo strtoupper($usuario->username) ?>"/>
	
		<?php echo form_close(); ?>
	</div>
</div>