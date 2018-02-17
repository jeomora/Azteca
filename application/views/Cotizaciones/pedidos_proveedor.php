<div class="ibox-content">
	<div class="row">
		
	</div>
	<div class="row">
		<?php echo form_open("", array("id"=>'form_pedido_new')); ?>


		<div class="row">
			<div class="col-sm-12">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>NO</th>
							<th>NOMBRE</th>
							<th>PRECIO</th>
							<th>CANTIDAD</th>
							<th>IMPORTE</th>
						</tr>
					</thead>
					<tbody id="body_response">
						
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<table>
					<tfoot>
						<tr>
							<td colspan="3"></td>
							<th>IVA</th>
							<td>
								<div class="input-group m-b">
									<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
									<input type="text" id="iva" value="" class="form-control numeric" placeholder="0.00" readonly="">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="3"></td>
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