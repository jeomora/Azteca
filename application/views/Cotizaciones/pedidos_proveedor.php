<style type="text/css" media="screen">
.modal-lg {width: 100%;}
.modal-dialog{margin: 0px auto !important;}
table#table_provs {width: 80rem !important;}
table#table_provss {width: 35rem !important;}
th.sorting {width: 110px !important;}
th.sorting_asc {width: 20px !important;}
.modal-header {background-color: #49B7E0;color: #FFF;}
.modal-footer {background-color: #E0FFF0;}
.modal-body {background-color: #E0FFF0 !important;}
i.fa.fa-close {color: #fff !important;font-size: 4rem;}
.close{opacity: 1}
::-webkit-scrollbar {width: 10px;}
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
    -webkit-border-radius: 10px;
    border-radius: 10px;
}
::-webkit-scrollbar-thumb {
    -webkit-border-radius: 10px;
    border-radius: 10px;
    background: rgba(0, 82, 27, 0.8); 
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
}
th.sorting_disabled {width: 12rem !important;}
::-webkit-scrollbar-thumb:window-inactive {background: rgba(255,0,0,0.4); }
</style>
<div class="ibox-content">

	<div class="row">
		<?php echo form_open("", array("id"=>'form_pedido_new')); ?>


		<div class="row">
			<div class="col-sm-8" style="height: 40rem !important;overflow: scroll;" id="style-15">
				<table class="table table-bordered" id="table_provs">
					<thead>
						<tr>
							<th style="width: 150px !important">NOMBRE</th>
							<th style="width: 150px !important">PRECIO</th>
							<th style="width: 150px !important">PROMOCIÓN</th>
							<th style="width: 150px !important">CANTIDAD</th>
							<th style="width: 20px !important;">AGREGAR</th>
						</tr>
					</thead>
					<tbody id="body_response">
						
					</tbody>
				</table>
			</div>
			<div class="col-sm-4" style="height: 40rem !important;overflow: scroll;" id="style-15">
				<table class="table table-bordered" id="table_provss">
					<thead>
						<tr>
							<th style="width: 150px !important">NOMBRE</th>
							<th style="width: 150px !important">PRECIO</th>
							<th style="width: 150px !important">CANTIDAD</th>
							<th style="width: 150px !important">IMPORTE</th>
						</tr>
					</thead>
					<tbody id="body_respons">
						
					</tbody>
				</table>
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
									<input type="number" id="totals" name="total" value="0" class="form-control" placeholder="0.00" readonly="">
								</div>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>	
		</div>
			</div>
		</div>
		
		<?php echo form_close(); ?>
	</div>
</div>