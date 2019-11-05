<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
	th{border: 2px solid #000 !important;text-align:center;}
	/*.table-responsive{width: 80vw;overflow-x:scroll;}*/
	button.btnPrec{background:#2935ca;border:#2935ca;padding:10px 30px;border-radius:3px;margin-bottom:10px;color:#FFF;}
	button.btnPrec{background:#4a1e43;border:#4a1e43;padding:10px 30px;border-radius:3px;margin-bottom:10px;color:#FFF;}
</style>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<style>
	div#page-wrapper{background: #4a1e43;}
	.top-navigation .nav>li>a{color:#000;background:#fff;}
	.white-bg .navbar-fixed-top, .white-bg .navbar-static-top{background: #fff;}
	.top-navigation .navbar-brand{background:#fff;color:#000;}
	#progress{color:#FFF !important;background:#4a1e43 !important;}
	.logo_img>img{border: 4px solid #4a1e43;}
	.top-navigation .navbar-nav .dropdown-menu{background:#004479;color:#ffffff;}
	td{font-size:16px;font-family:monospace;}
	.btndown{background:navy;font-size:18px;color:#FFF;padding:7px 25px;border:0;border-radius:3px;margin-top:-5px;}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Control Frutas</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<a href="Frutas/print_productos2" target="_blank"><button type="button" class="btnPrec"><i class='fa fa-download'></i> <span style="font-size:16px">(Descargar Formato Precios)</span></button></a>
					</div>
					<div class="btn-group">
						<div class="col-sm-12" style="text-align:center;font-size:16px;color:#000;margin-top:-2rem;">
							Subir formato precios
						</div>
						<?php echo form_open_multipart("", array('id' => 'upload_precios')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_precios" name="file_precios" style="background-color:#4a1e43;border-color:#4a1e43;color:#FFF" />
							</div>
						<?php echo form_close(); ?>
					</div>
					<div class="btn-group">
						<a href="Frutas/print_formato" target="_blank"><button type="button" class="btnForma btndown"><i class='fa fa-download'></i> <span style="font-size:16px">Descargar Formato Frutas</span></button></a>
					</div>
					<div class="">
						<div class="btn-group">
							<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_producto" style="font-size:16px">
								<i class="fa fa-plus"></i> Agregar Frutas
							</button>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_usuarios">
							<thead>
								<tr>
									<th colspan="5" style="background-color:#FFF">CONTROL DE MERMA</th>
									<th colspan="10" style="background-color:rgb(183,222,232);">FRUTAS</th>
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
									<th style="background-color:#FFF;" colspan="2"></th>
									<th id="tnds87">0 DE 55</th>
									<th id="tnds89">0 DE 55</th>
									<th id="tnds90">0 DE 55</th>
									<th id="tnds57">0 DE 55</th>
									<th id="tnds58">0 DE 55</th>
									<th id="tnds59">0 DE 55</th>
									<th id="tnds60">0 DE 55</th>
									<th id="tnds61">0 DE 55</th>
									<th id="tnds62">0 DE 55</th>
									<th id="tnds63">0 DE 55</th>
									<th style="background-color:#FFF;" colspan="3"></th>
								</tr>
								<tr>
									<th>CÓDIGO</th>
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
										<td style="width:50px !important;"><?php echo $value->codigo ?></td>
										<td><?php echo $value->descripcion ?></td>
										<td style="background-color:#948a544a">
											<input type="text" id="cedis<?php echo $value->id_fruta ?>" value="<?php echo $value->cedis ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#92d0504a;">
											<input type="text" id="super<?php echo $value->id_fruta ?>" value="<?php echo $value->super ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#bfbfbf4a;">
											<input type="text" id="villas<?php echo $value->id_fruta ?>" value="<?php echo $value->villas ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#ffff004a">
											<input type="text" id="soli<?php echo $value->id_fruta ?>" value="<?php echo $value->soli ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#00b0504a">
											<input type="text" id="tienda<?php echo $value->id_fruta ?>" value="<?php echo $value->tienda ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#00b0f04a;">
											<input type="text" id="ultra<?php echo $value->id_fruta ?>" value="<?php echo $value->ultra ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#e26b0a4a;">
											<input type="text" id="trinch<?php echo $value->id_fruta ?>" value="<?php echo $value->trinch ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#ff999b4a;">
											<input type="text" id="merca<?php echo $value->id_fruta ?>" value="<?php echo $value->merca ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#b1a0c74a;">
											<input type="text" id="tenen<?php echo $value->id_fruta ?>" value="<?php echo $value->tenen ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td style="background-color:#FF37374a;">
											<input type="text" id="tenen<?php echo $value->id_fruta ?>" value="<?php echo $value->tije ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td>
											<input type="text" name="dude" class="dude<?php echo $value->id_fruta ?>" id="dude" value="<?php echo $dude ?>" style="width:100px !important;border:0;background-color:transparent;" readOnly>
										</td>
										<td>$
											<input type="text" name="precio" class="precio<?php echo $value->id_fruta ?>" id="precio" value="<?php echo $value->precio ?>" style="width:100px !important" data-id-verd="<?php echo $value->id_fruta ?>">
										</td>
										<td>$
											<input type="text" name="total" class="total<?php echo $value->id_fruta ?>" id="total" value="<?php echo number_format($value->precio*$dude,2,'.',',') ?>" style="width:100px !important;border:0;background-color:transparent;" readOnly>
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
									<td style="width:50px !important;"><?php echo $value->id_fruta ?></td>
									<td style="background-color:red"><?php echo $value->descripcion ?></td>
									<td style="background-color:#948a544a">
										<input type="text" id="cedis2<?php echo $value->id_fruta ?>" value="<?php echo $value->cedis ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#92d0504a;">
										<input type="text" id="super2<?php echo $value->id_fruta ?>" value="<?php echo $value->super ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#bfbfbf4a;">
										<input type="text" id="villas2<?php echo $value->id_fruta ?>" value="<?php echo $value->villas ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#ffff004a">
										<input type="text" id="soli2<?php echo $value->id_fruta ?>" value="<?php echo $value->soli ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#00b0504a">
										<input type="text" id="tienda2<?php echo $value->id_fruta ?>" value="<?php echo $value->tienda ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#00b0f04a;">
										<input type="text" id="ultra2<?php echo $value->id_fruta ?>" value="<?php echo $value->ultra ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#e26b0a4a;">
										<input type="text" id="trinch2<?php echo $value->id_fruta ?>" value="<?php echo $value->trinch ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#ff999b4a;">
										<input type="text" id="merca2<?php echo $value->id_fruta ?>" value="<?php echo $value->merca ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#b1a0c74a;">
										<input type="text" id="tenen2<?php echo $value->id_fruta ?>" value="<?php echo $value->tenen ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td style="background-color:#FF37374a;">
										<input type="text" id="tenen2<?php echo $value->id_fruta ?>" value="<?php echo $value->tije ?>" style="width:80px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td>
										<input type="text" name="dud2" class="dude2<?php echo $value->id_fruta ?>" id="dud2" value="<?php echo $dude ?>" style="width:100px !important;border:0;background-color:transparent;" readOnly>
									</td>
									<td>$
										<input type="text" name="preci2" class="precio2<?php echo $value->id_fruta ?>" id="preci2" value="<?php echo $value->precio ?>" style="width:100px !important" data-id-verd="<?php echo $value->id_fruta ?>">
									</td>
									<td>$
										<input type="text" name="tota2" class="total2<?php echo $value->id_fruta ?>" id="tota2" value="<?php echo number_format($value->precio*$dude,2,'.',',') ?>" style="width:100px !important;border:0;background-color:transparent;" readOnly>
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