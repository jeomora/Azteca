<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}elseif ( $this->session->userdata("id_grupo") == 3) {
	redirect("Pedidos", "");
}
?>
<div class="wrapper wrapper-content animated fadeInRight" id="welcome_container">
	<div class="row col-lg-12">
		<div class="col-lg-4">
			<div class="ibox">
				<div class="ibox-content ">
					<h5 class="m-b-md">ARTICULOS</h5>
					<h2 class="text-navy">
						<i class="fa fa-play fa-rotate-45"></i> <?php echo empty($productos) ? 0 : number_format(sizeof($productos),2,'.',',') ?>
					</h2>
					<small>EN EL SISTEMA</small>
				</div> 
			</div>
		</div>
		<div class="col-lg-4">
			<div class="ibox">
				<div class="ibox-content">
					<h5 class="m-b-md">FAMILIAS</h5>
					<h2 class="text-navy">
						<i class="fa fa-play fa-rotate-45"></i> <?php echo empty($familias) ? 0 : number_format(sizeof($familias),2,'.',',') ?>
					</h2>
					<small>EN EL SISTEMA</small>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="ibox">
				<div class="ibox-content">
					<h5 class="m-b-md">PROVEEDORES</h5>
					<h2 class="text-navy">
						<i class="fa fa-play fa-rotate-45"></i> <?php echo empty($proveedores) ? 0 : number_format(sizeof($proveedores),2,'.',',') ?>
					</h2>
					<small>EN EL SISTEMA</small>
				</div>
			</div>
		</div>
	</div>
	<div class="row col-lg-12">
		<?php if ($ides <> 2): ?>
			<div class="col-md-4 cotizados">
				<div class="ibox-content" style="border-color:#000">
					<h2 class="text-navy" style="color:#3f47cc">
						<i class="fa fa-cubes"></i> <strong>PRODUCTOS</strong> <br> SIN COTIZACIÓN
					</h2>
					<button id="no_cotizados" class="btn btn-success" data-toggle="tooltip" title="VER" data-id-producto="'.$id_producto.'">
						<i class="fa fa-eye"></i>
					</button>
				</div>
			</div>
			<div class="col-md-4 cotizados">
				<div class="ibox-content" style="border-color:#000">
					<h2 class="text-navy" style="color:#3f47cc">
						<i class="fa fa-group"></i> <strong>PROVEEDORES</strong> <br> SIN COTIZAR
					</h2>
					<button id="no_cotiz" class="btn btn-success" data-toggle="tooltip" title="VER" data-id-producto="'.$id_producto.'">
						<i class="fa fa-eye"></i>
					</button>
				</div>
			</div>
			<div class="col-md-4 cotizados">
				<div class="ibox-content" style="border-color:#000">
					<h2 class="text-navy" style="color:#3f47cc">
						<i class="fa fa-group"></i> <strong>COTIZACIONES</strong> <br> DIFERENCIA 20%
					</h2>
					<a href="<?php echo base_url('Cotizaciones/fastedit') ?>" rel="external-new-window" id="fastedit"><button id="fastedit" class="btn btn-success" data-toggle="tooltip" title="VER">
						<i class="fa fa-eye"></i>
					</button></a>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="col-lg-12" style="height: 20px"></div>
	<div class="row col-lg-12">
		<div class="col-lg-4">
			<div class="ibox">
				<div class="ibox-content ">
					<h2 class="m-b-md">EXISTENCIAS TIENDAS</h2>
					<div style="width:100%">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th style="color:#FFF;background:#000;width:20%">TIENDA</th>
									<th style="color:#FFF;background:#000">GENERAL</th>
									<th style="color:#FFF;background:#000">VOLÚMENES</th>
									<th style="color:#FFF;background:#000">LUNES</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="border:1px solid #63FFFB99;background:#63FFFB99;font-weight:bold">CEDIS</td>
									<td id="gen0"></td>
									<td id="vol0"></td>
									<td id="lun0"></td>
								</tr>
								<tr>
									<td style="border:1px solid #CC009999;background:#CC009999;font-weight:bold">SUPER</td>
									<td id="gen1"></td>
									<td id="vol1"></td>
									<td id="lun1"></td>
								</tr>
								<tr>
									<td style="border:1px solid #00B0F099;background:#00B0F099;font-weight:bold">ABARROTES</td>
									<td id="gen2"></td>
									<td id="vol2"></td>
									<td id="lun2"></td>
								</tr>
								<tr>
									<td style="border:1px solid #FF000099;background:#FF000099;font-weight:bold">V. PEDREGAL</td>
									<td id="gen3"></td>
									<td id="vol3"></td>
									<td id="lun3"></td>
								</tr>
								<tr>
									<td style="border:1px solid #E26B0A99;background:#E26B0A99;font-weight:bold">TIENDA</td>
									<td id="gen4"></td>
									<td id="vol4"></td>
									<td id="lun4"></td>
								</tr>
								<tr>
									<td style="border:1px solid #C5C5C599;background:#C5C5C599;font-weight:bold">ULTRAMARINOS</td>
									<td id="gen5"></td>
									<td id="vol5"></td>
									<td id="lun5"></td>
								</tr>
								<tr>
									<td style="border:1px solid #92D05099;background:#92D05099;font-weight:bold">TRINCHERAS</td>
									<td id="gen6"></td>
									<td id="vol6"></td>
									<td id="lun6"></td>
								</tr>
								<tr>
									<td style="border:1px solid #B1A0C799;background:#B1A0C799;font-weight:bold">AZT. MERCADO</td>
									<td id="gen7"></td>
									<td id="vol7"></td>
									<td id="lun7"></td>
								</tr>
								<tr>
									<td style="border:1px solid #DA969499;background:#DA969499;font-weight:bold">TENENCIA</td>
									<td id="gen8"></td>
									<td id="vol8"></td>
									<td id="lun8"></td>
								</tr>
								<tr>
									<td style="border:1px solid #4BACC699;background:#4BACC699;font-weight:bold">TIJERAS</td>
									<td id="gen9"></td>
									<td id="vol9"></td>
									<td id="lun9"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div> 
			</div>
		</div>
		<div class="col-lg-5">
			<div class="ibox">
				<div class="ibox-content ">
					<h2 class="m-b-md">Último Final Volúmen</h2>
					<div style="width:100%">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th style="color:#FFF;background:#000;width:20%">COD</th>
									<th style="color:#FFF;background:#000">DESCRIPCIÓN</th>
									<th style="color:#FFF;background:#000">FECHA</th>
								</tr>
							</thead>
							<tbody>
								<?php if($resVol):foreach ($resVol as $key => $value):?>
									<tr>
										<td><?php $value->codigo ?></td>
										<td><?php $value->nombre ?></td>
										<td><?php $value->lastfecha ?></td>
									</tr>
								<?php endforeach;endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="ibox">
				<div class="ibox-content ">
					<h2 class="m-b-md">General SIN Finales</h2>
					<div style="width:100%">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th style="color:#FFF;background:#000;">COD</th>
									<th style="color:#FFF;background:#000;">DESCRIPCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php if($resGen):foreach ($resGen as $key => $value):?>
									<tr>
										<td><?php $value->codigo ?></td>
										<td><?php $value->nombre ?></td>
									</tr>
								<?php endforeach;endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-2.1.1.js') ?>"></script>

<script src="../scripts/admin.js" type="text/javascript"></script>