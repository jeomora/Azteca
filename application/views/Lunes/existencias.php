<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<style>
	.buscale {width: 50vw;padding: 5px 10px;border: 2px solid #23c6c8;font-size: 16px;border-radius: 5px;}
	div#page-wrapper{background: #008b8b;}
	.top-navigation .nav>li>a{color:#000;background:#fff;}
	.white-bg .navbar-fixed-top, .white-bg .navbar-static-top{background: #fff;}
	.top-navigation .navbar-brand{background:#fff;color:#000;}
	#progress{color:#FFF !important;background:#008b8b !important;}
	.logo_img>img{border: 4px solid #008b8b;}
	.top-navigation .navbar-nav .dropdown-menu{background:#004479;color:#ffffff;}
	.top-navigation .wrapper.wrapper-content{padding:40px 0px !important;}
	body{font-family:sans-serif;}
}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12" style="padding:0">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE EXISTENCIAS</h5>
				</div>
				<div class="ibox-content">
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
					<br>
					<div class="btn-group" style="margin-top:20px;margin-bottom:20px;">
						<input type="text" name="buscale" id="buscale" class="buscale" placeholder="Ingrese la descripción o código del producto"><br>
						<p>A partir de 4 caracteres se mostrarán los resultados en la tabla </p>
					</div>
					<table class="table table-striped table-bordered table-hover" style="font-size:10px">
						<thead>
							<tr>
								<th colspan="3" width="200px !important">Subir Existencias</th>
								<?php foreach ($tiendas as $key => $value):?>
									<?php if ($value->id_sucursal == 2): ?>
										<th colspan="3" style="background-color:<?php echo $value->color."99" ?>;border:1px solid <?php echo $value->color."99" ?>;">
											
										</th>
									<?php else: ?>
										<th colspan="3" style="background-color:<?php echo $value->color."99" ?>;border:1px solid <?php echo $value->color."99" ?>;">
											<div class="btn-group">
												<?php echo form_open_multipart("", array('id' => 'upload_cotizaciones'.$value->id_sucursal)); ?>
													<div>
														<input style="width:118px" type="file" id="file_otizaciones" name="file_otizaciones" data-id-tienda="<?php echo $value->id_sucursal ?>"/>
													</div>
												<?php echo form_close(); ?>
											</div>
										</th>
									<?php endif; ?>		
								<?php endforeach; ?>
							</tr>
							<tr>
								<th colspan="3" width="200px !important"><?php echo $fecha ?></th>
								<?php foreach ($tiendas as $key => $value):?>
									<th colspan="3" style="text-align:center;border:1px solid <?php echo $value->color."99" ?>;background-color:<?php echo $value->color."99" ?>"><?php echo $value->nombre ?></th>
								<?php endforeach; ?>
							</tr>
							<tr>
								<th colspan="3" width="200px !important">Existencias Subidas</th>
								<?php foreach ($tiendas as $key => $value):?>
									<th colspan="3" style="text-align:center;border:2px solid <?php echo $value->color."99" ?>;" id="ths<?php echo $value->id_sucursal ?>">
										<?php echo $cuantas[$key]->cuantas." de ".$noprod->noprod ?>
									</th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody id="tbody_exist">
							<tr>
								<td colspan='36'>
									Introduzca mas de 4 caracteres en el recuadro de busqueda
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>