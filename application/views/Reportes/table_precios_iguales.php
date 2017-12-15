<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>REPORTE DE PRECIOS IGUALES</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<a data-toggle="modal" data-tooltip="tooltip" title="Registrar" class="btn btn-primary tool btn-modal" href="<?php echo site_url('Familias/add_familia'); ?>" data-target="#myModal">
							<i class="fa fa-plus"></i>
						</a>
					</div>
						<table class="table table-striped table-bordered table-hover" id="table_precios_bajos">
							<thead>
								<tr>
									<th>NO</th>
									<th>PRODUCTO</th>
									<th>PROVEEDOR</th>
									<th>F. REGISTRO</th>
									<th>F. VENCE</th>
									<th>EXISTENCIAS</th>
									<th>PRECIO</th>
									<th>DESCUENTO</th>
									<th>P. DESCUENTO</th>
									<th>RANGO PRECIOS</th>
									<th>OBSERVACIONES</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($promociones_igual): ?>
									<?php foreach ($promociones_igual as $key => $value): ?>
										<tr>
											<th><?php echo $value->id_promocion ?></th>
											<td><?php echo strtoupper($value->producto) ?></td>
											<td><?php echo strtoupper($value->first_name.' '.$value->last_name) ?> </td>
											<td><?php echo date('d-m-Y', strtotime($value->fecha_registro)) ?></td>
											<td><?php echo ($value->fecha_caduca != '') ? date('d-m-Y', strtotime($value->fecha_caduca)) : '' ?></td>
											<td><?php echo ($value->existencias > 0) ? number_format($value->existencias,2,'.',',') : '' ?></td>
											<td><?php echo '$ '.number_format($value->precio_fijo,2,'.',',') ?></td>
											<td><?php echo ($value->descuento > 0) ? number_format($value->descuento,2,'.',',').' %' : '' ?></td>
											<td><?php echo ($value->precio_descuento > 0) ? '$ '.number_format($value->precio_descuento,2,'.',',') : '' ?></td>
											<td>
												<?php echo ($value->precio_inicio > 0 && $value->precio_fin > 0) 
														? 'DE: $ '.number_format($value->precio_inicio,2,'.',',').' A: $ '.number_format($value->precio_fin,2,'.',',')
														: ''
												?>
											</td>
											<td><?php echo $value->observaciones ?></td>
										</tr>
									<?php endforeach ?>
								<?php endif ?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function($) {
		
	});
</script>