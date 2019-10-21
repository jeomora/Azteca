<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
	th{border: 2px solid #000 !important;text-align:center;}
	/*.table-responsive{width: 80vw;overflow-x:scroll;}*/
	button.btnPrec{background:#2935ca;border:#2935ca;padding:10px 30px;border-radius:3px;margin-bottom:10px;color:#FFF;}
</style>
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
	td{font-size:16px;font-family:monospace;}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Control Verduras</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<a href="Verduras/print_productos2" target="_blank"><button type="button" class="btnPrec"><i class='fa fa-download'></i> <span style="font-size:16px">(Descargar Formato Precios)</span></button></a>
					</div>
					<div class="btn-group">
						<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;margin-top: -2rem;">
							Subir formato precios
						</div>
						<?php echo form_open_multipart("", array('id' => 'upload_precios')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_precios" name="file_precios"/>
							</div>
						<?php echo form_close(); ?>
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_usuarios">
							<thead>
								<tr>
									<th colspan="5" style="background-color:#FFF">CONTROL DE MERMA</th>
									<th colspan="10" style="background-color:#fcd6b4">VERDURAS</th>
								</tr>
								<tr>
									<th colspan="6" style="background-color:#99ffcc">GRUPO ABARROTES AZTECA</th>
									<th colspan="9" style="background-color:#FFF">AL</th>
								</tr>
								<tr>
									<th colspan="6" style="background-color:#FFF"></th>
									<th colspan="9" style="background-color:#FFF"><?php echo $fecha ?></th>
								</tr>
								<tr>
									<th>ID</th>
									<th>DESCRIPCIÓN</th>
									<th style="background-color:#948a54;">CEDIS</th>
									<th style="background-color:#92d050;">SUPER</th>
									<th style="background-color:#bfbfbf;">VILLAS</th>
									<th style="background-color:#FF0;">SOLIDARIDAD</th>
									<th style="background-color:#00b050;">TIENDA</th>
									<th style="background-color:#00b0f0;">ULTRAMARINOS</th>
									<th style="background-color:#e26b0a;">TRINCHERAS</th>
									<th style="background-color:#ff999b;">MERCADO</th>
									<th style="background-color:#b1a0c7;">TENENCIA</th>
									<th style="background-color:#FF3737;">TIJERAS</th>
									<th style="background-color:#FFF;">TOTAL KGS</th>
									<th style="background-color:#FFF;">PRECIO</th>
									<th style="background-color:#FFF;">TOTAL GRAL</th>
								</tr>
							</thead>
							<tbody>
								<?php $dd=0;;if($verdurasEx):foreach ($verdurasEx as $key => $value): ?>
									<tr>
										<?php $dude = ($value->cedis+$value->super+$value->villas+$value->soli+$value->tienda+$value->ultra+$value->trinch+$value->merca+$value->tenen+$value->tije) ?>
										<td style="width:50px !important;"><?php echo $value->id_verdura ?></td>
										<td><?php echo $value->descripcion ?></td>
										<td style="background-color:#948a544a">
											<input type="text" id="cedis<?php echo $value->id_verdura ?>" value="<?php echo $value->cedis ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#92d0504a;">
											<input type="text" id="super<?php echo $value->id_verdura ?>" value="<?php echo $value->super ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#bfbfbf4a;">
											<input type="text" id="villas<?php echo $value->id_verdura ?>" value="<?php echo $value->villas ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#ffff004a">
											<input type="text" id="soli<?php echo $value->id_verdura ?>" value="<?php echo $value->soli ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#00b0504a">
											<input type="text" id="tienda<?php echo $value->id_verdura ?>" value="<?php echo $value->tienda ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#00b0f04a;">
											<input type="text" id="ultra<?php echo $value->id_verdura ?>" value="<?php echo $value->ultra ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#e26b0a4a;">
											<input type="text" id="trinch<?php echo $value->id_verdura ?>" value="<?php echo $value->trinch ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#ff999b4a;">
											<input type="text" id="merca<?php echo $value->id_verdura ?>" value="<?php echo $value->merca ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#b1a0c74a;">
											<input type="text" id="tenen<?php echo $value->id_verdura ?>" value="<?php echo $value->tenen ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#FF37374a;">
											<input type="text" id="tenen<?php echo $value->id_verdura ?>" value="<?php echo $value->tije ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td>
											<input type="text" name="dude" class="dude<?php echo $value->id_verdura ?>" id="dude" value="<?php echo $dude ?>" style="width:100px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td>$
											<input type="text" name="precio" class="precio<?php echo $value->id_verdura ?>" id="precio" value="<?php echo $value->precio ?>" style="width:100px !important" data-id-verd="<?php echo $value->id_verdura ?>">
										</td>
										<td>$
											<input type="text" name="total" class="total<?php echo $value->id_verdura ?>" id="total" value="<?php echo ($value->precio*$dude) ?>" style="width:100px !important;border:0;background-color:transparent;" readOnly>
											<?php $dd += ($value->precio*$dude) ?>
										</td>
									</tr>
								<?php endforeach;endif; ?>
								<tr>
									<td colspan="14" style="text-align:right;">
										TOTAL
									</td>
									<td>$
										<input type="text" name="stotal" id="stotal" value="<?php echo number_format($dd,3,'.',',') ?>" style="width:100px !important;border:0;background-color:transparent;" readOnly>
									</td>
								</tr>
								<tr>
									<td colspan="15"></td>
								</tr>
								<tr>
									<td colspan="15"></td>
								</tr>
								<tr><?php $dd=0;;if($verduras):foreach ($verduras as $key => $value): ?>
									<?php $dude = ($value->cedis+$value->super+$value->villas+$value->soli+$value->tienda+$value->ultra+$value->trinch+$value->merca+$value->tenen+$value->tije) ?>
									<td style="width:50px !important;"><?php echo $value->id_verdura ?></td>
									<td style="background-color:red"><?php echo $value->descripcion ?></td>
									<td style="background-color:#948a544a">
										<input type="text" id="cedis2<?php echo $value->id_verdura ?>" value="<?php echo $value->cedis ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#92d0504a;">
										<input type="text" id="super2<?php echo $value->id_verdura ?>" value="<?php echo $value->super ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#bfbfbf4a;">
										<input type="text" id="villas2<?php echo $value->id_verdura ?>" value="<?php echo $value->villas ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#ffff004a">
										<input type="text" id="soli2<?php echo $value->id_verdura ?>" value="<?php echo $value->soli ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#00b0504a">
										<input type="text" id="tienda2<?php echo $value->id_verdura ?>" value="<?php echo $value->tienda ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#00b0f04a;">
										<input type="text" id="ultra2<?php echo $value->id_verdura ?>" value="<?php echo $value->ultra ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#e26b0a4a;">
										<input type="text" id="trinch2<?php echo $value->id_verdura ?>" value="<?php echo $value->trinch ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#ff999b4a;">
										<input type="text" id="merca2<?php echo $value->id_verdura ?>" value="<?php echo $value->merca ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#b1a0c74a;">
										<input type="text" id="tenen2<?php echo $value->id_verdura ?>" value="<?php echo $value->tenen ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#FF37374a;">
										<input type="text" id="tenen2<?php echo $value->id_verdura ?>" value="<?php echo $value->tije ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td>
										<input type="text" name="dud2" class="dude2<?php echo $value->id_verdura ?>" id="dud2" value="<?php echo $dude ?>" style="width:100px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td>$
										<input type="text" name="preci2" class="precio2<?php echo $value->id_verdura ?>" id="preci2" value="<?php echo $value->precio ?>" style="width:100px !important" data-id-verd="<?php echo $value->id_verdura ?>">
									</td>
									<td>$
										<input type="text" name="tota2" class="total2<?php echo $value->id_verdura ?>" id="tota2" value="<?php echo ($value->precio*$dude) ?>" style="width:100px !important;border:0;background-color:transparent;" readOnly>
										<?php $dd += ($value->precio*$dude) ?>
									</td><?php endforeach;endif; ?>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>