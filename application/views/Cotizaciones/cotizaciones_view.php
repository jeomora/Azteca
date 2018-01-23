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
						<div class="col-sm-2">
							<a href="<?php echo base_url('./assets/uploads/Formato_precios.csv'); ?>" target="_blank" data-toggle="tooltip" title="Decargar Formato de Cotizaciones" class="btn btn-info"><i class="fa fa-cloud-download"></i><span class="nav-label" download></span> </a>
						</div>
					</div>
					<div class="btn-group">
						<?php echo form_open_multipart("", array('id' => 'upload_precios')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_precios" name="file_precios" value=""/>
							</div>
							<input type="hidden" id="name_user" value="<?php echo strtoupper($usuario->username) ?>"/>
						<?php echo form_close(); ?>
					</div>
					<div class="btn-group">
						<?php echo form_open("Cotizaciones/fill_excel", array("id" => 'reporte_cotizaciones', "target" => '_blank')); ?>
							<button class="btn btn-primary" name="excel" data-toggle="tooltip" title="Exportar a Excel" type="submit">
								<i class="fa fa-file-excel-o"></i>
							</button>
						<?php echo form_close(); ?>
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
							<thead>
								<tr>
									<th>FAMILIAS</th>
									<th>CÓDIGO</th>
									<th>DESCRIPCIÓN</th>
									<th>SISTEMA</th>
									<th>PRECIO 4</th>
									<th>PRECIO MENOR</th>
									<th>PROVEEDOR</th>
									<th>PRECIO MAXIMO</th>
									<th>PRECIO PROMEDIO</th>
									<th>PRECIO 2</th>
									<th>2DO PROVEEDOR</th>
									<th>PROMOCIÓN</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($cotizacionesProveedor): ?>
								<?php foreach ($cotizacionesProveedor as $key => $value): ?>
									<tr>
										<td></td>
										<td><?php echo $value->codigo ?></td>
										<td><?php echo $value->producto ?></td>
										<td><?php echo ($value->precio_sistema >0) ? '$ '.number_format($value->precio_sistema,2,'.',',') : '' ?></td>
										<td><?php echo ($value->precio_four >0) ? '$ '.number_format($value->precio_four,2,'.',',') : '' ?></td>
										<td><?php echo '$ '.number_format($value->precio_first,2,'.',',') ?></td>
										<td><?php echo $value->proveedor_first ?></td>
										<td><?php echo '$ '.number_format($value->precio_maximo,2,'.',',') ?></td>
										<td><?php echo '$ '.number_format($value->precio_promedio,2,'.',',') ?></td>
										<td><?php echo ($value->precio_next >0) ?'$ '.number_format($value->precio_next,2,'.',',') : '' ?></td>
										<td><?php echo $value->proveedor_next ?></td>
										<td><?php echo $value->promocion_first ?></td>
										<td>
											<button id="update_cotizacion" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-cotizacion="<?php echo $value->id_cotizacion ?>">
												<i class="fa fa-pencil"></i>
											</button>
											<button id="delete_cotizacion" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-cotizacion="<?php echo $value->id_cotizacion ?>">
												<i class="fa fa-trash"></i>
											</button>
										</td>
									</tr>
								<?php endforeach ?>
							<?php endif ?>
							</tbody>
						</table>
						<div class="row col-sm-12">
							<?php echo isset($pages) ? $pages : '' ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>