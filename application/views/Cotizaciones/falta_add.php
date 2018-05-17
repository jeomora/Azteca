<style type="text/css" media="screen">
	.datepicker{ z-index: 9999 !important; }
  .modal-body {height: auto !important;}
  	.modal-lg {width: 700px !important;}
</style>


<div class="ibox-content" style="padding-left:50px">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_faltante_new')); ?>
		<div class="row">
			<div class="col-sm-8">
				<div class="form-group">
					<label for="id_producto">LISTA DE ARTICULOS</label>
					<select name="id_producto" id="id_producto" class="form-control chosen-select">
						<option value="">Seleccionar...</option>
						<?php if ($productos): ?>
							<?php foreach ($productos as $key => $value): ?>
								<option value="<?php echo $value->id_producto ?>"><?php echo strtoupper($value->nombre) ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>
			</div>
		</div>

    <div class="row">
      <div class="col-sm-4">
				<label for="semanas">NO. SEMANAS</label>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-slack"></i></span>
					<input type="text" name="semanas"  id="semanas" class="form-control number" value="" placeholder="0">
          <input type="text" name="id_proveedor"  id="id_proveedor" value="<?php echo $usuario->id_usuario ?>" hidden>
					<span class="validar"></span>
				</div>
			</div>
    </div>
    <br>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="fecha_termino">FECHA TERMINO</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
						<input type="text" name="fecha_termino" id="fecha_termino" class="form-control" placeholder="dd/mm/yyyy" readonly="">
						<span class="validar"></span>
					</div>
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>
