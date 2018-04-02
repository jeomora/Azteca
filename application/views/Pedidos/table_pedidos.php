<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
	.td2Form{background-color: #000 !important; color:#FFF !important;}
	th {text-align: center}
	tr:hover {background-color: #cfffc3 !important;}
	select#id_proves2{display: none}
	.fill_form{display: none}
	select#id_proves {color: #000;}
	.preciomas{
		background-color: #ea9696;
	    color: red;
	    font-weight: bold;
	    text-align: center;
	}
	.preciomenos{
		background-color: #96eaa8;
	    color: green;
	    font-weight: bold;
	    text-align: center;
	}
	.btng1{
		display: inline-flex;
	    background-color: #23c6c8;
	    border-radius: 5px;
	    color: #FFF;
	    margin-left: 3rem;
    	padding: 5px;
    	padding-top: 4px;
    	padding-right: 5px;
    	margin-bottom: 2rem;
	}
	.lblget {
	    font-family: inherit;
	    font-weight: normal;
	    font-size: 14px;
	    padding: 7px; 
	}
	select#id_proves4 {color: black;}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
					<?php echo form_open("Cotizaciones/fill_formato1", array("id" => 'reporte_form', "target" => '_blank',"class" => 'btn-group')); ?>
					<div class="btn-group btng1">
						<label for="id_proveedor" class="lblget">Proveedor</label>
						<select name="id_proves2" id="id_proves2" class="form-control">
							<option value="nope">Seleccionar...</option>
							<option value="27">TACAMBA</option>
							<option value="4">SAHUAYO</option>
							<option value="2">DECASA</option>
							<option value="5,6,24,17,21,56">VARIOS 1ER</option>
							<option value="20,18,8,7,9,49,53,54,51">VARIOS 2DO</option>
							<option value="45,25,34,68,32,10,69,39,40,64,70,15,47,44,42,65,71">VARIOS 3RO</option>
							<option value="13,46,66,19,22,35,26,23,12,28,67,11,29">VARIOS 4TO</option>
							<option value="3">DUERO</option>
						</select>
						<select name="id_proves4" id="id_proves4" class="form-control">
							<option value="nope">Seleccionar...</option>
							<option value="27">TACAMBA</option>
							<option value="4">SAHUAYO</option>
							<option value="2">DECASA</option>
							<option value="5,6,24,17,21,56">VARIOS 1ER</option>
							<option value="20,18,8,7,9,49,53,54,51">VARIOS 2DO</option>
							<option value="45,25,34,68,32,10,69,39,40,64,70,15,47,44,42,65,71">VARIOS 3RO</option>
							<option value="13,46,66,19,22,35,26,23,12,28,67,11,29">VARIOS 4TO</option>
							<option value="3">DUERO</option>
						</select>
						<div class="btn-group">
							<button class="btn btn-primary fill_form" name="excel" data-toggle="tooltip" title="Exportar a Excel" type="submit">
								<i class="fa fa-file-excel-o"></i>
							</button>
						</div>
					</div>
					<?php echo form_close(); ?>
					<div class="col-md-12 wonder" style="padding: 0">

					</div>
			<!--<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE PEDIDOS</h5>
				</div>

				<div class="ibox-content">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_pedidos" style="text-align:  center;"">
							<thead>
								<tr>
									<th>PRODUCTO</th>
									<th style="background-color: #01B0F0" colspan="3">ABARROTES</th>
									<th style="background-color: #E26C0B" colspan="3">TIENDA</th>
									<th style="background-color: #C5C5C5" colspan="3">ULTRAMARINOS</th>
									<th style="background-color: #92D051" colspan="3">TRINCHERAS</th>
									<th style="background-color: #B1A0C7" colspan="3">AZT MERCADO</th>
									<th style="background-color: #DA9694" colspan="3">TENENCIA</th>
									<th style="background-color: #4CACC6" colspan="3">TIJERAS</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="td2Form">DESCRIPCION</td>
									<td class="td2Form" colspan="3">EXISTENCIAS</td>
									<td class="td2Form" colspan="3">EXISTENCIAS</td>
									<td class="td2Form" colspan="3">EXISTENCIAS</td>
									<td class="td2Form" colspan="3">EXISTENCIAS</td>
									<td class="td2Form" colspan="3">EXISTENCIAS</td>
									<td class="td2Form" colspan="3">EXISTENCIAS</td>
									<td class="td2Form" colspan="3">EXISTENCIAS</td>
								</tr>
								<tr>
									<td class="td2Form"></td>
									<td class="td2Form">CAJAS</td>
									<td class="td2Form">PIEZAS</td>
									<td class="td2Form">PEDIDO</td>
									<td class="td2Form">CAJAS</td>
									<td class="td2Form">PIEZAS</td>
									<td class="td2Form">PEDIDO</td>
									<td class="td2Form">CAJAS</td>
									<td class="td2Form">PIEZAS</td>
									<td class="td2Form">PEDIDO</td>
									<td class="td2Form">CAJAS</td>
									<td class="td2Form">PIEZAS</td>
									<td class="td2Form">PEDIDO</td>
									<td class="td2Form">CAJAS</td>
									<td class="td2Form">PIEZAS</td>
									<td class="td2Form">PEDIDO</td>
									<td class="td2Form">CAJAS</td>
									<td class="td2Form">PIEZAS</td>
									<td class="td2Form">PEDIDO</td>
									<td class="td2Form">CAJAS</td>
									<td class="td2Form">PIEZAS</td>
									<td class="td2Form">PEDIDO</td>
								</tr>
								<!-- <?php //if ($pedidos): ?>
									<?php //foreach ($pedidos as $key => $value): ?>
										<tr>
											<th><?php //echo $value->id_pedido ?></th>
											<td><?php //echo $value->proveedor ?></td>
											<td><?php //echo '$ '.number_format($value->total,2,'.',',') ?></td>
											<td><?php //echo $value->fecha ?></td>
											<td><?php //echo strtoupper($value->sucursal) ?></td>
											<td>
												<button id="update_pedido" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-pedido="<?php echo $value->id_pedido ?>">
													<i class="fa fa-pencil"></i>
												</button>
												<button id="show_pedido" class="btn btn-success" data-toggle="tooltip" title="Ver" data-id-pedido="<?php echo $value->id_pedido ?>">
													<i class="fa fa-eye"></i>
												</button>
												<button id="delete_pedido" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-pedido="<?php echo $value->id_pedido ?>">
													<i class="fa fa-trash"></i>
												</button>

											</td>
										</tr>
									<?php //endforeach ?>
								<?php //endif ?>-->
							<!--</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>-->
	</div>
</div>