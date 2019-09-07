<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<style>
	div#page-wrapper{background: #00318b;}
	.top-navigation .nav>li>a{color:#000;background:#fff;}
	.white-bg .navbar-fixed-top, .white-bg .navbar-static-top{background: #fff;}
	.top-navigation .navbar-brand{background:#fff;color:#000;}
	#progress{color:#FFF !important;background:#00318b !important;}
	.logo_img>img{border: 4px solid #00318b;}
	.top-navigation .navbar-nav .dropdown-menu{background:#004479;color:#ffffff;}
}
</style>
<script language="Javascript">
//document.oncontextmenu = function(){return false}
</script>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE PRODUCTOS FORMATO REYMA</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_producto">
							<i class="fa fa-plus"></i> AGREGAR UN PRODUCTO 
						</button>

					</div>
					<!--<div class="btn-group">
						<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;margin-top: -2rem;">
							Subir formato de cotizaciones
						</div>
						<?php echo form_open_multipart("", array('id' => 'upload_cotizaciones')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_otizaciones" name="file_otizaciones"/>
							</div>
						<?php echo form_close(); ?>
					</div>-->
					<table class="table table-striped table-bordered table-hover" id="table_usuarios">
						<thead>
							<tr>
								<th>DESCRIPCIÓN</th>
								<th>CÓD PROVEEDOR</th>
								<th>CÓD PIEZA</th>
								<th>CÓD CAJA</th>
								<th>UM</th>
								<th>FAMILIA</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php if ($productos): ?>
								<?php foreach ($productos as $key => $value): ?>
									<tr>
										<td><?php echo $value->nombre ?></td>
										<th><?php echo $value->codprov ?></th>
										<th><?php echo $value->codpz ?></th>
										<th><?php echo $value->codcaja ?></th>
										<td><?php echo $value->unidad ?></td>
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
							<?php endif ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>