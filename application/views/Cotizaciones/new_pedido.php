<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_pedido_new')); ?>
		<div class="row col-sm-12">
			<p style="font-size: 25px; text-align: center;">
				Precio Sistema : $ <?php echo sprintf("%.2f",$proveedor->precio_sistema) ?>
			</p>
			<p style="font-size: 25px; text-align: center;">
				Precio 4 : $ <?php echo sprintf("%.2f",$proveedor->precio_four)?>
			</p>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>NO</th>
							<th>PROVEEDOR</th>
							<th>PRECIO</th>
							<th>OBSERVACIÓN</th>
							<th>CANTIDAD</th>
							<th>IMPORTE</th>
						</tr>
					</thead>
					<tbody id="body_response">
						<tr>
							<td><input type='checkbox' value="<?php echo $proveedor->id_prod ?>" class='id_producto'></td>
							<td><?php echo $proveedor->proveedor_first ?></td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
									<input type='text' value="<?php echo sprintf("%.2f",$proveedor->precio_first) ?>" class='form-control precio' readonly=''>
								</div>
							</td>
							<td><?php echo $proveedor->promocion_first ?></td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-slack'></i></span>
									<input type='text' value='' class='form-control cantidad numeric'  readonly=''> 
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
									<input type='text' value='' class='form-control importe numeric' readonly=''>
								</div>
							</td>
						</tr>
						<?php if ($proveedor->id_next !== NULL): ?>
							<tr>
							<td><input type='checkbox' value="<?php echo $proveedor->id_prod ?>" class='id_producto'></td>
							<td><?php echo $proveedor->proveedor_next ?></td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
										<input type='text' value="<?php echo sprintf("%.2f",$proveedor->precio_next) ?>" class='form-control precio' readonly=''>
								</div>
							</td>
							<td><?php echo $proveedor->promocion_next ?></td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-slack'></i></span>
									<input type='text' value='' class='form-control cantidad numeric' onkeypress='return event.charCode >= 48 && event.charCode <= 57' readonly=''> 
								</div>
							</td>
							<td>
								<div class='input-group m-b'>
									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
									<input type='text' value='' class='form-control importe numeric' readonly=''>
								</div>
							</td>
						</tr>
						<?php endif ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="4"></td>
							<th>Total</th>
							<td>
								<div class="input-group m-b">
									<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
									<input type="text" id="total" name="total" value="" class="form-control numeric" placeholder="0.00" readonly="">
								</div>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
