 <?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css" media="screen">
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
	.filts{
	    margin-left: 10rem;
	    background-color: #24C6C8;
	    color: white;
	    border-radius: 5px;
	    padding: 1rem;
	    margin-top: 7px;
	}
	.btng{
	    display:  inline-flex;
	    margin-left:  5rem;
	}
	.slct{
		border: 2px solid #24C6C8;
	    border-top-left-radius: 5px;
	    border-bottom-left-radius: 5px;
	    padding: 1rem;
	    height: 5rem
	}
	.filtro{
		padding: 2rem;
	    border: 2px solid #24C6C8;
	    height: 5rem
	}
	.btsrch{
	    background-color: #1D84C6;
	    color: #fff;
	    height: 5rem;
	    width: 7rem;
	}
	div#table_cot_admin_processing {
	    position: absolute;
	    left: 38%;
	    top: 10%;
	}
	.btng1{
		display: inline-flex;
	    background-color: #23c6c8;
	    border-radius: 5px;
	    color: #FFF;
	    margin-left: 3rem;
    	padding: 0;
    	padding-top: 4px;
    	padding-right: 5px;
	}
	.lblget{
		font-family: inherit;
	    font-weight: normal;
	    font-size: 14px;
	    padding: 7px;
	}
	select#id_proves2{display: none}
	.fill_form{display: none}
	select#id_proves {color: #000;}

	.spinns{width:35rem;height:25rem;background-color:#FFF;text-align:center;line-height:25rem;border-radius: 5px;border: 3px solid #3b467b;}
</style>
<div class="wrapper wrapper-content animated fadeInRight" style="padding-left: 0;padding-right: 0">
	<div class="row">
		<div class="col-lg-12" style="padding: 0">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE COTIZACIONES</h5>
				</div>
				<div class="ibox-content" style="padding-top: 4rem;">
					<div class="btn-group">
						<div class="col-sm-2">
							<a href="<?php echo base_url('./assets/uploads/Formato_precios.xlsx'); ?>" target="_blank" data-toggle="tooltip" title="Decargar Formato Precios" class="btn btn-info"><i class="fa fa-cloud-download"></i><span class="nav-label" download></span> </a>
						</div>
					</div>
					<div class="btn-group">
						<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;margin-top: -2rem;">
							Subir Precios Sistema y Precio 4
						</div>
						<?php echo form_open_multipart("", array('id' => 'upload_precios')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_precios" name="file_precios" value=""/>
							</div>
						<?php echo form_close(); ?>
					</div>
					<div class="btn-group">
						<?php echo form_open("Cotizaciones/fill_excel", array("id" => 'reporte_cotizaciones', "target" => '_blank')); ?>
							<button class="btn btn-primary" name="excel" data-toggle="tooltip" title="Exportar a Excel" type="submit">
								<i class="fa fa-file-excel-o"></i>
							</button>
						<?php echo form_close(); ?>
					</div>
					<!--<div class="btn-group">
						<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;margin-top: -2rem;">
							Subir cotizaciones de varios proveedores
						</div>
						<?php //echo form_open_multipart("", array('id' => 'upload_allcotizaciones')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info file_cotizaciones" type="file" name="file_cotizaciones" value=""/>
							</div>
						<?php //echo form_close(); ?>
					</div> -->
					<?php echo form_open("Cotizaciones/fill_formato", array("id" => 'reporte_form', "target" => '_blank',"class" => 'btn-group')); ?>
					<div class="btn-group btng1">
						
						<label for="id_proveedor" class="lblget">Proveedor</label>
						<select name="id_proves2" id="id_proves2" class="form-control">
							<option value="nope">Seleccionar...</option>
							<?php if ($proveedores): ?>
								<?php foreach ($proveedores as $key => $value): ?>
									<?php if ($value->nombre != "MASTER"): ?>
										<option value="<?php echo $value->nombre.' '.$value->apellido ?>"></option>
									<?php endif ?>
								<?php endforeach ?>
							<?php endif ?>
						</select>
						<select name="id_proves" id="id_proves" class="form-control">
							<option value="nope">Seleccionar...</option>
							<?php if ($proveedores): ?>
								<?php foreach ($proveedores as $key => $value): ?>
									<?php if ($value->nombre != "MASTER"): ?>
										<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre.' '.$value->apellido ?></option>
									<?php endif ?>
								<?php endforeach ?>
							<?php endif ?>
						</select>
						<div class="btn-group">
							<button class="btn btn-primary fill_form" name="excel" data-toggle="tooltip" title="Exportar a Excel" type="submit">
								<i class="fa fa-file-excel-o"></i>
							</button>
						
					</div>
					</div>
					<?php echo form_close(); ?>
					<div class="table-responsive"> 
						<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
							<thead>
								<tr>
									<th>FAMILIA</th>
									<th>CÓDIGO</th>
									<th>DESCRIPCIÓN</th>
									<th>SISTEMA</th>
									<th>PRECIO 4</th>
									<th>FACTURA</th>
									<th>C/PROMOCIÓN</th>
									<th>PROVEEDOR</th>
									<th>OBSERVACIÓN</th>
									<th>PRECIO MAXIMO</th>
									<th>PRECIO PROMEDIO</th>
									<th>FACTURA</th>
									<th>C/PROMOCIÓN</th>
									<th>2DO PROVEEDOR</th>
									<th>OBSERVACIÓN</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($cotizaciones): foreach ($cotizaciones as $key => $value): ?>
										<tr>
											<td><?php echo strtoupper($value->familia) ?></td>
											<td><?php echo strtoupper($value->codigo) ?></td>
											<td><?php echo strtoupper($value->producto) ?></td>
											<td><?php echo '$ '.number_format($value->precio_sistema,2,'.',',') ?></td>
											<td><?php echo '$ '.number_format($value->precio_four,2,'.',',') ?></td>
											<td><?php echo '$ '.number_format($value->precio_firsto,2,'.',',') ?></td>
											<?php if($value->precio_first >= $value->precio_sistema): ?>
												<td><?php echo '<div class="preciomas">$ '.number_format($value->precio_first,2,'.',',').'</div>' ?></td>
											<?php else: ?>
												<td><?php echo '<div class="preciomenos">$ '.number_format($value->precio_first,2,'.',',').'</div>' ?></td>
											<?php endif ?>
											<td><?php echo strtoupper($value->proveedor_first) ?></td>
											<td><?php echo strtoupper($value->promocion_first) ?></td>
											<td><?php echo '$ '.number_format($value->precio_maximo,2,'.',',') ?></td>
											<td><?php echo '$ '.number_format($value->precio_promedio,2,'.',',') ?></td>
											<td><?php echo '$ '.number_format($value->precio_nexto,2,'.',',') ?></td>
											<?php if($value->precio_next >= $value->precio_sistema): ?>
												<td><?php echo ($value->precio_next >0) ? '<div class="preciomas">$ '.number_format($value->precio_next,2,'.',',').'</div>' : ''?></td>
											<?php else: ?>
												<td><?php echo ($value->precio_next >0) ? '<div class="preciomenos">$ '.number_format($value->precio_next,2,'.',',').'</div>' : ''?></td>
											<?php endif ?>
											<td><?php echo strtoupper($value->proveedor_next) ?></td>
											<td><?php echo strtoupper($value->promocion_next) ?></td>
											<td>
												<button id="update_cotizacion" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-cotizacion="<?php echo $value->id_cotizacion ?>">
													<i class="fa fa-pencil"></i>
												</button>
												<button id="delete_cotizacion" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-cotizacion="<?php echo $value->id_cotizacion ?>">
													<i class="fa fa-trash"></i>
												</button>
											</td>
										</tr>
								<?php endforeach; endif ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>