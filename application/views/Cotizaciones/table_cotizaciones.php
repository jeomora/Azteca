<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE COTIZACIONES</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_cotizacion">
							<i class="fa fa-plus"></i>
						</button>
					</div>
					<div class="btn-group">
						<div class="col-sm-2">
							<a href="<?php echo base_url('./assets/uploads/Formato_cotizaciones.xlsx'); ?>" download="<?php echo ''.date('Y-m-d').' '.$usuario['username'].'.xlsx' ; ?>" target="_blank" data-toggle="tooltip" title="Decargar Formato Cotizaciones" class="btn btn-info"><i class="fa fa-cloud-download"></i><span class="nav-label" download></span> </a>
						</div>
					</div>
					<div class="btn-group">
						<?php echo form_open_multipart("", array('id' => 'upload_cotizaciones')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_cotizaciones" name="file_cotizaciones" value=""/>
							</div>
						<?php echo form_close(); ?>
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_cot_proveedores">
							<thead>
								<tr>
									<th>DESCRIPCIÓN</th>
									<th>FECHA REGISTRO</th>
									<th>PRECIO FACTURA</th>
									<th>PRECIO FACTURA C/PROMOCIÓN</th>
									<th>DESCUENTO ADICIONAL</th>
									<th colspan="2">PROMOCIÓN</th>
									<th>OBSERVACIONES</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($cotizaciones): foreach ($cotizaciones as $key => $value): ?>
										<tr>
											<td><?php echo strtoupper($value->producto) ?></td>
											<td><?php echo date('d-m-Y', strtotime($value->fecha_registro)) ?></td>
											<td><?php echo ($value->precio >0) ? '$ '.number_format($value->precio,2,'.',',') : '' ?></td>
											<td><?php echo ($value->precio_promocion >0) ? '$ '.number_format($value->precio_promocion,2,'.',',') : ''?></td>
											<td><?php echo ($value->descuento > 0) ? number_format($value->descuento,0,'.',',').' %' : ''  ?></td>
											<td><?php echo ($value->num_one > 0 && $value->num_two > 0) ? $value->num_one.'&nbsp; EN &nbsp;'.$value->num_two : '' ?></td>
											<td><?php echo $value->promocion ?></td>
											<td><?php echo $value->observaciones ?></td>
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