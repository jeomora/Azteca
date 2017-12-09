<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_asignacion_new')); ?>
		
		<p style="font-size: 15px; margin-left: 10px;"> PROVEEDOR:
			<?php echo strtoupper($usuario->first_name.'	'.$usuario->last_name) ?>
		</p>
		<input type="hidden" name="id_proveedor" value="<?php echo $usuario->id ?>">
		
		<hr>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>NOMBRE</th>
					<th>PRECIO</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($productos): ?>
					<?php foreach ($productos as $key => $value): ?>
						<tr>
							<td><input type="checkbox" class="id_producto"  value="<?php echo $value->id_producto ?>"></td>
							<td><?php echo $value->nombre ?></td>
							<td>
								<div class="input-group m-b">
									<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
									<input type="text" class="form-control precio" value="" readonly="" placeholder="0.00">
								</div>
							</td>
						</tr>
					<?php endforeach ?>
				<?php endif ?>
			</tbody>
		</table>
		
		<input type="hidden" id="name_user" value="<?php echo strtoupper($usuario->username) ?>"/>
	
		<?php echo form_close(); ?>
	</div>
</div>