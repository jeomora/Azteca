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
	.top-navigation .wrapper.wrapper-content{padding:40px 0px !important;}
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
					<table class="table table-striped table-bordered table-hover" id="table_usuarios">
						<thead>
							<tr>
								<th>Subir Exictencias</th>
								<?php foreach ($tiendas as $key => $value):?>
									<th colspan="3" style="background-color:<?php echo $value->color."99" ?>;border:1px solid <?php echo $value->color."99" ?>;">
										<div class="btn-group">
											<?php echo form_open_multipart("", array('id' => 'upload_cotizaciones')); ?>
												<div>
													<input style="width:118px" type="file" id="file_otizaciones" name="file_otizaciones" data-id-proveedor="<?php echo $value->id_sucursal ?>"/>
												</div>
											<?php echo form_close(); ?>
										</div>
									</th>
								<?php endforeach; ?>
							</tr>
							<tr>
								<th><?php echo $fecha ?></th>
								<?php foreach ($tiendas as $key => $value):?>
									<th colspan="3" style="text-align:center;border:1px solid <?php echo $value->color."99" ?>;background-color:<?php echo $value->color."99" ?>"><?php echo $value->nombre ?></th>
								<?php endforeach; ?>
							</tr>
							<tr>
								<th></th>
								<?php foreach ($tiendas as $key => $value):?>
									<th style="border:1px solid <?php echo $value->color."99" ?>;background-color:<?php echo $value->color."99" ?>">
										Cjs
									</th>
									<th style="border:1px solid <?php echo $value->color."99" ?>;background-color:<?php echo $value->color."99" ?>">
										Pzs
									</th>
									<th style="border:1px solid <?php echo $value->color."99" ?>;background-color:<?php echo $value->color."99" ?>">
										Pdo
									</th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 1</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">3</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">34</td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td>producto 2</td>
								<?php foreach ($tiendas as $key => $value):?>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">32</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">1</td>
									<td style="border:1px solid <?php echo $value->color."99" ?>;">2</td>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>