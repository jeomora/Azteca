<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
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
	.spinns{width:35rem;height:25rem;background-color: rgba(255,255,255,0.5);
	    padding: 10rem;
	    color: #FF6805;
	    border: 2px solid #FF6805;
	    border-radius: 5px;
	    margin-left: 35%}
	.fa-spin{margin-left: 4rem}
	.wrapper.wrapper-content.animated.fadeInRight {
	    height: 91vh;
	    overflow-y: scroll;
	}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
					<?php echo form_open("Cotizaciones/fill_formato1", array("id" => 'reporte_form', "target" => '_blank',"class" => 'btn-group')); ?>
					<div class="btn-group btng1">
						<label for="id_proveedor" class="lblget">Proveedor</label>
						<select name="id_proves2" id="id_proves2" class="form-control">
							<option value="nope">Seleccionar...</option>
							<option value="VARIOS1">VARIOS 1°</option>
							<option value="VARIOS2">VARIOS 2°</option>
							<option value="VARIOS3">VARIOS 3°</option>
							<option value="VARIOS4">VARIOS 4°</option>
							<option value="VOLUMEN">VOLUMEN</option>
							<option value="MODERNA">MODERNA</option>
							<option value="COSTENA">COSTEÑA</option>
							<option value="CUETARA">CUETARA</option>
							<option value="MEXICANO">MEXICANO</option>
							<option value="AMARILLOS">AMARILLOS</option>
							<?php if($conjuntos):foreach ($conjuntos as $key => $value): ?>
								<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
							<?php endforeach;endif; ?>
						</select>
						<select name="id_proves4" id="id_proves4" class="form-control">
							<option value="nope">Seleccionar...</option>
							<option value="VARIOS1">VARIOS 1°</option>
							<option value="VARIOS2">VARIOS 2°</option>
							<option value="VARIOS3">VARIOS 3°</option>
							<option value="VARIOS4">VARIOS 4°</option>
							<option value="VOLUMEN">VOLUMEN</option>
							<option value="MODERNA">MODERNA</option>
							<option value="COSTENA">COSTEÑA</option>
							<option value="CUETARA">CUETARA</option>
							<option value="MEXICANO">MEXICANO</option>
							<option value="AMARILLOS">AMARILLOS</option>
							<?php if($conjuntos):foreach ($conjuntos as $key => $value): ?>
								<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
							<?php endforeach;endif; ?>
						</select>
						<div class="btn-group">
							<button class="btn btn-primary fill_form" name="excel" data-toggle="tooltip" title="Exportar a Excel" type="submit">
								<i class="fa fa-file-excel-o"></i> Descargar Excel Pedidos
							</button>
						</div>

					</div>
					<?php echo form_close(); ?>
					<br>
					<?php echo form_open("Cotizaciones/fill_existe", array("id" => 'reporte_form', "target" => '_blank',"class" => 'btn-group')); ?>
					<div style="padding: 0;margin: 1rem;margin-left: 3rem">
							<div class="btn-group">
								<button class="btn btn-danger fill_exist" name="excele" data-toggle="tooltip" title="Exportar a Excel" type="submit">
									<i class="fa fa-file-excel-o"></i> Descargar existencias todas
								</button>
							</div>
						</div>
					<?php echo form_close(); ?>
					<?php echo form_open("Cotizaciones/fill_existeNot", array("id" => 'reporte_form', "target" => '_blank',"class" => 'btn-group')); ?>
					<div style="padding: 0;margin: 1rem;margin-left: 3rem">
							<div class="btn-group">
								<button class="btn btn-danger fill_exist" name="excele" data-toggle="tooltip" title="Exportar a Excel" type="submit">
									<i class="fa fa-file-excel-o"></i> Descargar existencias NO COTIZADOS
								</button>
							</div>
						</div>
					<?php echo form_close(); ?>
					<!--<div class="col-md-4 prodiv">
						<div class="btn-group">
						<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;margin-top: -2rem;">
							Subir formato duero
						</div>
						<?php echo form_open_multipart("", array('id' => 'reporte_sat')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_p" name="file_p" value="" size="20" />
							</div>
						<?php echo form_close(); ?>
					</div>
					</div>-->


					<div class="col-md-12">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>LISTADO DE PEDIDOS</h5>
							</div>
							<div class="col-md-12 wonder">
								
							</div>
						</div>
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
								<!<?php //if ($pedidos): ?>
									<?php //foreach ($pedidos as $key => $value): ?>
										<tr>
											<th><?php //echo $value->id_pedido ?></th>
											<td><?php //echo $value->proveedor ?></td>
											<td><?php //echo '$ '.number_format($value->total,2,'.',',') ?></td>
											<td><?php //echo $value->fecha ?></td>
											<td><?php //echo strtoupper($value->sucursal) ?></td>
											<td>
												<button id="update_pedido" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-pedido="<?php //echo $value->id_pedido ?>">
													<i class="fa fa-pencil"></i>
												</button>
												<button id="show_pedido" class="btn btn-success" data-toggle="tooltip" title="Ver" data-id-pedido="<?php //echo $value->id_pedido ?>">
													<i class="fa fa-eye"></i>
												</button>
												<button id="delete_pedido" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-pedido="<?php //echo $value->id_pedido ?>">
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