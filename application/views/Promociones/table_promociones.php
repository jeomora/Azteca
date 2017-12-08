<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Listado de Promociones</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<a data-toggle="modal" data-tooltip="tooltip" title="Registrar" class="btn btn-primary tool btn-modal" href="<?php echo site_url('Promociones/add_promocion'); ?>" data-target="#myModal">
							<i class="fa fa-plus"></i>
						</a>
					</div>
						<table class="table table-striped table-bordered table-hover" id="">
							<thead>
								<tr>
									<th>NO</th>
									<th>PRODUCTO</th>
									<th>PROVEEDOR</th>
									<th>F. REGISTRO</th>
									<th>F. FIN</th>
									<th>EXISTENCIAS</th>
									<th>DESCUENTO</th>
									<th>RANGO PRECIOS</th>
									<th>PRECIO</th>
									<th>P. DESCUENTO</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($promociones): ?>
									<?php foreach ($promociones as $key => $value): ?>
										<tr>
											<th><?php echo $value->id_promocion ?></th>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td><?php echo number_format($value->precio_fijo,2,'.',',') ?></td>
											<td></td>
											<td>
												<a data-toggle="modal" data-tooltip="tooltip" title="Editar"  class="btn tool btn-info btn-modal" href="<?php echo site_url();?>" data-target="#myModal" ><i class="fa fa-pencil"></i></a>
												<a data-toggle="modal" data-tooltip="tooltip" title="Eliminar"  class="btn tool btn-warning btn-modal" href="<?php echo site_url();?>" data-target="#myModal" ><i class="fa fa-trash"></i></a>
											</td>
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
