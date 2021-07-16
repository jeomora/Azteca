<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_cond_new')); ?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="nombre">Seleccione el producto</label>
					<select class="form-group" name="codigo" id="codigo" style="font-size:14px;padding:5px">
						<?php foreach ($productos as $key => $value):if($value->id_condicion <> $id_c):?>
							<option value="<?php echo $value->codigo ?>"><?php echo $value->descripcion." - ".$value->codigo ?></option>
						<?php endif;endforeach?>
					</select>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>