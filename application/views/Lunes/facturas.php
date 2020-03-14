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
	h1{font-size:40px;font-family:inherit;}
	option{background:#FFF;color:#2fbf1e;font-size:16px;height:40px;padding:10px !important;}
    select{background:#2fbf1e;color:#FFF;font-size:20px;padding:10px 50px;font-family:inherit;border:1px solid #2fbf1e;}
    .btn-down,.btn-ver{background:#1e92bf;color:#FFF;border:#1e92bf;padding:10px 40px;font-size:16px;border-radius:2px;}
    .btn-down2,.btn-ver2{background:#bf1e61;color:#FFF;border:#bf1e61;padding:10px 40px;font-size:16px;border-radius:2px;}
    #file_otizaciones2{background:#bf1e61;border-color:#bf1e61;border-radius:2px}
    #file_otizaciones{background:#1e92bf;border-color:#1e92bf;border-radius:2px}
    #lista,.disponibles{box-shadow:10px 10px 5px #aaaaaa;border:1px solid #aaaaaa;border-radius:2px;margin-bottom:20px;padding:0px;}
    #detallles{box-shadow:10px 10px 5px #aaaaaa;border:1px solid #aaaaaa;border-radius:2px;margin-bottom:20px}
    .lista-head,.lista-head-1,.head-1-precios,.head-1-factura{padding:0;}
    .lista-head{font-size:35px;text-align:center;font-family:fantasy;border-bottom:1px solid #aaa;}
    .head-1-des{font-size:18px;height:50px;line-height:50px;text-align:center;border:1px solid #aaa}
    .head-1-piden,.head-1-llegan{height:50px;padding:0;line-height:50px;font-size:10px;text-align:center;border:1px solid #aaa}
    .head-1-precios1,.head-1-precios2{height:50px;font-size:16px;padding:0;text-align:center;border:1px solid #aaa}
    .head-1-factura-title,.head-1-factura1,.head-1-factura2,.head-1-factura3,.head-1-factura4{height:25px;text-align:center;font-size:16px;border:1px solid #aaa;padding:0;background:#ffb10060;}
    .head-1-diferencia,.head-1-promo{line-height:50px}
    .head-1-pedido,.head-1-diferencia,.head-1-promo {font-size:18px;padding:0;text-align:center;border:1px solid #aaa;height:50px;}
    .head-1-factura-title{background:#ffb100;}
    .custom-select2{background:#bf1e61;border-color:#bf1e61;font-size:16px;border-radius:2px}
    .disponibles{border-radius:3px;padding:20px}
    .btn-dispon{font-size:16px;text-align:center;padding:10px;font-weight:bold;cursor:pointer;}
    .disponhover{background:#FFF;border-color:indianred;color:indianred;height:68px;overflow:hidden;}
    .btn-dispon:hover{background:#c5c5c5 !important;}
    .lista-body-desc,.lista-body-piden,.lista-body-llegan,.lista-body-promo,.lista-body-factura,.lista-body-pedido,.lista-body-sub,.lista-body-iva,.lista-body-ieps,.lista-body-total,.lista-body-totalgen,.lista-body-diferencia{height:50px;border:1px solid;padding-top:5px;font-family:monospace;}
    .lista-body-desc{font-size:12px;}
    .lista-body-piden,.lista-body-llegan{text-align:center;padding-top:15px;font-family:monospace;font-size:14px}
    .lista-body{padding:0}
    .lista-body-factura,.lista-body-pedido,.lista-body-sub,.lista-body-iva,.lista-body-ieps,.lista-body-total,.lista-body-totalgen,.lista-body-diferencia{text-align:right;}
    @media (max-width: 1400px){
    	.head-1-promo,.head-1-des,.head-1-piden,.head-1-llegan,.head-1-precios1,.head-1-precios2,.head-1-factura-title,.head-1-factura1,.head-1-factura2,.head-1-factura3,.head-1-factura4,.head-1-diferencia,.head-1-pedido,.head-1-diferencia{font-size:12px}
    	.head-1-piden,.head-1-llegan{font-size:7px}
    	.lista-body-factura{font-size:14px;}
    	.lista-body-piden,.lista-body-llegan{font-size:12px;padding:0px;padding-top:15px}
    }
    .factSem,.pedFinales{display:none}
    .btnSemFact{background:#2fbf1e;color:#FFF;border:2px solid #2fbf1e;font-size:20px;padding:5px 40px;font-family:inherit;}
    .btnSemFact:hover{background:#FFF;color:#2fbf1e;}
    .btnSemFactAct{background:#FFF !important;}
    .lista-body .col-md-6, .lista-body .col-md-1, .lista-body .col-md-2 {height:50px !important;overflow:hidden;}
    .renglon:hover{background:#e5ffe7;}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12" style="background:#FFF">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h1>FACTURAS FORMATOS LUNES</h1>
				</div>

				<div class="col-sm-12" style="padding-top:50px;border:0px;padding-bottom:20px">
					<div class="col-md-6">
						<div class="btn-group">
							<select class="custom-select" id="id_proveedor">
								<option value="0">Seleccione un proveedor...</option>
								<?php if($proveedores):foreach ($proveedores as $key => $value):?>
									<option value="<?php echo $value->id_proveedor ?>"><?php echo $value->nombre ?></option>
								<?php endforeach;endif; ?>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<button class="btnSemFact" type="button">Facturas Por Proveedor</button>
					</div>
					<div class="col-md-3">
						
					</div>
					<div class="col-sm-12 pedFinales" style="margin-top:20px;border:1px solid #1e92bf;">
						<div class="col-sm-12">
							<h2 style="margin-bottom:0px">Pedidos Finales</h2>
							<span>Agregue los pedidos finales antes de subir facturas, esto para que se realice la comparación de precios y número de productos.</span>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="col-sm-3" style="padding:20px;text-align:center;">
								<a href="<?php echo base_url('assets/uploads/Final Lunes.xlsx') ?>" download><button class="btn-down">
									Descargar plantilla
								</button></a>
							</div>
							<div class="col-sm-6" style="padding:20px;text-align:center;">
								<?php echo form_open_multipart("", array('id' => 'upload_cotizaciones')); ?>
									<input class="btn btn-info" type="file" id="file_otizaciones" name="file_otizaciones" style="display:initial;margin-bottom:0px" />
								<?php echo form_close(); ?>
								<div class="col-sm-12">
									<span style="color:#1e92bf">Orden de las columnas: A) Código Sistema | B) Descripción Sistema | C) Código Proveedor</span>
								</div>
							</div>
							<div class="col-sm-3" style="padding:20px;text-align:center;">
								<button class="btn-ver" id="ver_pedido">
									Ver pedidos finales
								</button>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 factSem" style="margin-top:20px;border:1px solid #bf1e61;padding-bottom: 20px">
						<div class="col-sm-12">
							<h2 style="margin-bottom:0px">Facturas de la semana</h2>
							<span>A continuación se muestran las facturas que se han registrado en el sistema y puede descargar y/o revisar</span>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="col-md-3" style="padding-top:20px;">
								<select class="custom-select2" id="id_sucursal">
									<option value="0" style="color:#bf1e61;background:#FFF;font-size:14px">Seleccione una sucursal...</option>
									<?php if($sucursales):foreach ($sucursales as $key => $value):?>
										<option value="<?php echo $value->id_sucursal ?>" style="color:#bf1e61;background:#FFF;font-size:14px"><?php echo $value->nombre ?></option>
									<?php endforeach;endif; ?>
								</select>
							</div>
							<div class="col-sm-3" style="padding:20px;text-align:center;">
								<a href="<?php echo base_url('assets/uploads/Factura Lunes.xlsx') ?>" download><button class="btn-down2">
									Descargar plantilla
								</button></a>
							</div>
							<div class="col-sm-6" style="padding:20px;text-align:center;">
								<?php echo form_open_multipart("", array('id' => 'upload_cotizaciones2')); ?>
									<input class="btn btn-info" type="file" id="file_otizaciones2" name="file_otizaciones2" style="display:initial;margin-bottom:0px" />
								<?php echo form_close(); ?>
								<div class="col-sm-12">
									<span style="color:#bf1e61">Orden de las columnas: A) Código Proveedor | B) Cantidad | C) Costo</span>
								</div>
							</div>
							<div class="col-md-12 disponibles">

							</div>
							<div class="col-sm-12" id="lista">
								<div class="col-md-12 lista-head">
									ABARROTES - GRUPO AZTECA, S.A DE C.V
								</div>
								<div class="col-md-12 lista-head-1">
									<div class="col-md-2 head-1-des">
										DESCRIPCIÓN
									</div>
									<div class="col-md-1" style="padding:0">
										<div class="col-md-6 head-1-piden">
											# PEDIDO
										</div>
										<div class="col-md-6 head-1-llegan">
											# FACTURA
										</div>
									</div>
									<div class="col-md-1 head-1-promo">
										PROMOCIÓN
									</div>
									<div class="col-md-2 head-1-precios">
										<div class="col-md-6 head-1-precios1">
											PRECIO<br>FACTURA
										</div>
										<div class="col-md-6 head-1-precios2">
											PRECIO<br>PEDIDO
										</div>
									</div>
									<div class="col-md-4 head-1-factura">
										<div class="col-md-12 head-1-factura-title">
											FACTURA
										</div>
										<div class="col-md-3 head-1-factura1">
											SUBTOTAL
										</div>
										<div class="col-md-3 head-1-factura2">
											+ IVA
										</div>
										<div class="col-md-3 head-1-factura3">
											IEPS
										</div>
										<div class="col-md-3 head-1-factura4">
											TOTAL
										</div>
									</div>
									<div class="col-md-1 head-1-pedido">
										TOTAL<br>PEDIDO
									</div>
									<div class="col-md-1 head-1-diferencia">
										DIFERENCIA
									</div>
								</div>
								<div class="col-md-12 lista-body">
									<?php if($factura):foreach ($factura as $key => $value): ?>
										<div class="col-md-2 lista-body-desc">
											<?php echo $value->codigo."<br>".$value->pdesc ?>
										</div>
										<div class="col-md-1" style="padding:0">
											<div class="col-md-6 lista-body-piden">
												<?php echo number_format($value->cedis,0,".",",") ?>
											</div>
											<div class="col-md-6 lista-body-llegan">
												<?php echo number_format($value->cantidad,0,".",",") ?>
											</div>
										</div>
										<div class="col-md-1 lista-body-promo">
											SIN PROMOCIÓN
										</div>
										<div class="col-md-1 lista-body-factura">
											<?php echo "$ ".number_format($value->precio,2,".",",") ?>
										</div>
										<div class="col-md-1 lista-body-pedido">
											
										</div>
										<div class="col-md-1 lista-body-sub">
											<?php $subtotal = ($value->precio * $value->cantidad);echo "$ ".number_format($subtotal,2,".",",") ?>
										</div>
										<div class="col-md-1 lista-body-iva">
											<?php echo "$ ".number_format(($subtotal * 0.16),2,".",",") ?>
										</div>
										<div class="col-md-1 lista-body-ieps">
											$ 0.00
										</div>
										<div class="col-md-1 lista-body-total">
											<?php echo "$ ".number_format(($subtotal * 1.16),2,".",",") ?>
										</div>
										<div class="col-md-1 lista-body-totalgen">
											
										</div>
										<div class="col-md-1 lista-body-diferencia">
											
										</div>
									<?php endforeach;endif; ?>
								</div>
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>