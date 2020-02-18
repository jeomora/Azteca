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
}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE PRODUCTOS</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_producto">
							<i class="fa fa-plus"></i> AGREGAR UN PRODUCTO
						</button>

					</div>
					<div class="btn-group" style="border:1px solid #21b9bb;margin-left:20rem;padding:10px 30px;">
						<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;">
							Asociar códigos proveedor al sistema.
						</div>
						<?php echo form_open_multipart("", array('id' => 'upload_cotizaciones')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_otizaciones" name="file_otizaciones"/>
							</div>
						<?php echo form_close(); ?>
						<div class="col-sm-12">
							<span>Orden de las columnas: A) Código Sistema | B) Descripción Sistema | C) Código Proveedor</span>
						</div>
					</div>
					<table class="table table-striped table-bordered table-hover" id="table_usuarios">
						<thead>
							<tr>
								<th>CÓDIGO</th>
								<th>DESCRIPCIÓN</th>
								<th style="background:aliceblue">PROVEEDOR</th>
								<th style="background:aliceblue">CÓDIGO PROVEEDOR</th>
								<th>UM</th>
								<th>PRECIO</th>
								<th>SISTEMA</th>
								<th>PROMOCIÓN</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php if ($productos): ?>
								<?php foreach ($productos as $key => $value): ?>
									<tr>
										<th><?php echo $value->codigo ?></th>
										<td><?php echo $value->descripcion ?></td>
										<td style="background:aliceblue"><?php echo $value->nombre ?></td>
										<td style="background:aliceblue"><?php echo $value->id_catalogo ?></td>
										<td><?php echo number_format($value->unidad,0,".",",") ?></td>
										<td><?php echo "$ ".number_format($value->precio,2,".",",") ?></td>
										<td><?php echo "$ ".number_format($value->sistema,2,".",",") ?></td>
										<td><?php echo $value->observaciones ?></td>
										<td>
											<button id="update_producto" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-producto="<?php echo $value->codigo ?>">
												<i class="fa fa-pencil"></i>
											</button>
											<button id="delete_producto" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-producto="<?php echo $value->codigo ?>">
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