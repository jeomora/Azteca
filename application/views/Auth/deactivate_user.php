<div class="ibox-content">
	<div class="row">
	<!-- <p><?php echo sprintf(lang('deactivate_subheading'), $user->username);?></p> -->
	<?php echo form_open("Auth/deactivate/".$user->id);?>
		<div class="row">
			<div class="col-sm-12">
				<p style="font-size: 25px; text-align: center;">
					¿Seguro que quieres desactivar a: <strong><?php echo $user->first_name.' '.$user->last_name ?></strong> ?
				<p style="font-size: 25px; text-align: center;">
					Usuario: <strong><?php echo $user->username ?></strong> </p>
				<p style="font-size: 25px; text-align: center;">
					Correo: <strong><?php echo $user->email ?></strong> </p>
				
				<p style="font-size: 25px; text-align: center;">
				<!--   	<?php echo lang('deactivate_confirm_y_label', 'confirm');?> -->
					<label for="confirm" class="label label-success">ACEPTAR: </label>
					<input type="radio" name="confirm" value="yes" checked="checked" />
					<!-- <?php echo lang('deactivate_confirm_n_label', 'confirm');?> -->
					<label for="confirm" class="label label-danger">CANCELAR: </label>
					<input type="radio" name="confirm" value="no" />
				</p>

			<?php echo form_hidden($csrf); ?>
			<?php echo form_hidden(array('id'=>$user->id)); ?>

			<div class="row">
				<div class="col-sm-2 pull-right">
					<button class="btn btn-primary pull-right" type="submit">
						<span class="bold"><i class="fa fa-floppy-o"></i></span>
						&nbsp;Aceptar
					</button>
				</div>
			</div>

			</div>
		</div>
		
		<?php echo form_close();?>
	</div>
</div>

</div> <!-- Cierra el cuerpo de la modal-->
<div class="modal-footer">
	<br>	<br>
</div>