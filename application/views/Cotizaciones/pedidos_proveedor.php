<style type="text/css" media="screen">
.modal-lg {
    width: 100%;
}
.modal-dialog{
	margin: 0px auto !important;
}
table#table_provs {
    width: 112rem !important;
}
th.sorting {
    width: 110px !important;
}
th.sorting_asc {
    width: 20px !important;
}
</style>
<div class="ibox-content">
	<div class="row">
		
	</div>
	<div class="row">
		<?php echo form_open("", array("id"=>'form_pedido_new')); ?>


		<div class="row">
			<div class="col-sm-12">
				<table class="table table-bordered" id="table_provs">
					<thead>
						<tr>
							<th style="width: 20px !important;">NO</th>
							<th style="width: 250px !important">NOMBRE</th>
							<th style="width: 250px !important">PRECIO</th>
							<th style="width: 250px !important">OBSERVACIÓN</th>
							<th style="width: 250px !important">CANTIDAD</th>
							<th style="width: 250px !important">IMPORTE</th>
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