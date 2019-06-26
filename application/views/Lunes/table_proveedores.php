<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<style>
	div#page-wrapper{background: #008b8b;}
	.top-navigation .nav>li>a{color:#000;background:#fff;}
	.white-bg .navbar-fixed-top, .white-bg .navbar-static-top{background: #fff;}
	.top-navigation .navbar-brand{background:#fff;color:#000;}
	#progress{color:#FFF !important;background:#008b8b !important;}
	.logo_img>img{border: 4px solid #008b8b;}
	.top-navigation .navbar-nav .dropdown-menu{background:#004479;color:#ffffff;}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE PROVEEDORES</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_proveedor">
							<i class="fa fa-plus"></i> AGREGAR UN PROVEEDOR
						</button>

					</div>
					<table class="table table-striped table-bordered table-hover" id="table_usuarios">
						<thead>
							<tr>
								<th>NO</th>
								<th>NOMBRE</th>
								<th>ALIAS (Pestaña excel)</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php if ($proveedores): ?>
								<?php foreach ($proveedores as $key => $value): ?>
									<tr>
										<th><?php echo $value->id_proveedor ?></th>
										<td><?php echo $value->nombre ?></td>
										<td><?php echo $value->alias ?></td>
										<td>
											<button id="update_proveedor" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-proveedor="<?php echo $value->id_proveedor ?>">
												<i class="fa fa-pencil"></i>
											</button>
											<button id="delete_proveedor" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-proveedor="<?php echo $value->id_proveedor ?>">
												<i class="fa fa-trash"></i>
											</button>
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