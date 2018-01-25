<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Listado de Productos</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_producto">
							<i class="fa fa-plus"></i>
						</button>
					</div>
						<table class="table table-striped table-bordered table-hover" id="table_productos">
							<thead>
								<tr>
									<th>NO</th>
									<th>CÓDIGO</th>
									<th>NOMBRE</th>
									<th>FAMILIA</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<!-- <?php if ($productos): ?>
									<?php foreach ($productos as $key => $value): ?>
										<tr>
											<th><?php echo $value->id_producto ?></th>
											<td><?php echo $value->codigo ?></td>
											<td><?php echo $value->producto ?></td>
											<td><?php echo $value->familia ?></td>
											<td>
												<button id="update_producto" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-producto="<?php echo $value->id_producto ?>">
													<i class="fa fa-pencil"></i>
												</button>
												<button id="delete_producto" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-producto="<?php echo $value->id_producto ?>">
													<i class="fa fa-trash"></i>
												</button>
											</td>
										</tr>
									<?php endforeach ?>
								<?php endif ?> -->
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
</div>