<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<style>
	.seld,.ibox{box-shadow: 0 10px 20px rgba(0,0,0,0.79), 0 6px 6px rgba(0,0,0,0.93);border-radius:5px;}
	.ibox-content{border-radius:5px;padding-bottom:20px}
	select{padding:10px;border-radius:5px;margin-bottom:15px;}
	.top-navigation .nav>li.active{background:#ffffff;border:none;}
	.top-navigation .nav > li.active > a {color: #000;background: #FFF;}
	th{text-align:center !important}
	.table-hover>tbody>tr:hover{background-color:#00bcd438;}
	.tablediv{display:inline-flex;}
	.tablepeds{width: 1200px;display:grid;}
	.backblack{background-color:#000;color:#FFF;height:40px;border:1px solid #FFF;padding:0;line-height:40px;text-align:center;font-size:16px;}
	.ttc{width: 650px;padding:0}
	.tabletien{display: inline-flex;}
	.ttchead{width:100%;height:40px;border:1px solid #FFF;font-size:16px;line-height:40px;text-align:center;}
	.divcontain,.divcont{padding:0;display:inline-flex;width:100%}
	.divcontaindiv{height:50px;text-align:center;font-size:14px;border:1px solid #c5c2c2;;padding:5px;}
	.divcont:hover{background:#aadcaa;}
	.checkhim {width: auto;position: absolute ;z-index: 10000000000000;background-color: #FFFFFFFA;top: 10px ;bottom: 10px ;left: 10px ;right: 10px ;
		box-shadow: inset 0px 0px 0px #000, 0px 5px 0px 0px #000, 0px 10px 50px #000;border-radius: 50px 0px 50px 50px;border: 1px solid #000;}
	.head2,.head3,.head1,.head4{font-size:16px;color:#FFF;background:#000;padding:10px;text-align:center;height:50px;border:1px solid #FFF}
	.body2,.body3,.body1,.body4{font-size:14px;color:#000;text-align:center;padding:30px 10px 15px 10px;border-right:1px solid #000;border-bottom:2px solid #000;font-family: monospace;}
	.fixdiv{padding:0;border:1px solid #0b73ce;position:fixed;right:20px;top:72px;overflow-y:scroll;height:50vh;}
	.pedsist{background:#279c9173;font-size:14px;padding:15px 10px;border:1px solid #0b73ce}
	.witheblock {border:1px dotted#000;font-size:14px;text-align:center;padding:20px;}
	.checkhim{display:none;height: 100vh;}
	.cerra{position:absolute;top:-22px;right:-1px;background:#279c9173;cursor:pointer;display:none;padding:0px 10px;border:1px solid #0b73ce;}
	.cantu{font-family:monospace;}
	.fixdiv2{padding:0;position:fixed;right:20px;top:60vh;height:25vh;}
	.btnsalvar{background:#79d079;font-size:20px;padding:10px;border:1px solid green;border-radius:3px;width:250px}
	.btnnel{background:#d07979;font-size:20px;padding:10px;border:1px solid red;border-radius:3px;width:250px}
	.devolucion{position:absolute;font-size:20px;background:#efff00;border-radius:10px;cursor:pointer;z-index:10000000;}
	.devolucion:hover{color:#efff00;background:#FFF}
	#difis{width:30px;height:20px;border:1px solid #000;color:#000;padding:3px;font-size:14px;display:none}
	#cuerpo{overflow-y:scroll;height:250vh;}
	.btndownload{font-size:20px;padding:10px 35px;background:cadetblue;border:1px solid cadetblue;border-radius:5px;box-shadow:0 10px 20px rgba(0,0,0,0.79),0 6px 6px rgba(0,0,0,0.93);}
	.btndownload:hover{background:#FFF;color:cadetblue}
	.headfact{text-align:center;font-size:30px;font-family:fantasy;padding:2px;background:red;border:2px solid}
	.subheadfact{font-size:20px;text-align:center;border:2px solid;height:50px;line-height:50px;border-top:0}
	.subheadfact2{padding:0}
	.fecharep,.fechafact{font-size:15px;height:25px;border-bottom:2px solid;border-right:2px solid;}
	.totfact,.totpend,.totpaga{text-align:right;font-size:20px;font-weight:bold}
	.notafolio,.sumtotal,.sumnota{font-size:20px;text-align:center;font-weight:bold;line-height:30px}
	.factlistItem{font-size:14px;text-align:center;border:1px solid #000;height:40px;line-height:40px;border-top:0;font-family:monospace;}
	.sumtotal,.sumnota{font-size:15px}
	.factlisty:hover{background:#c7c7c7}
	#searchy{width:100%;font-size:20px;border:0;border-bottom:2px solid blue;}
	.elvis,.elvis2,.factdetails{display:none}
	.btnExcel{color:#000;font-size:20px;width:80%;padding:10px;box-shadow:0 10px 20px rgba(0,0,0,0.79),0 6px 6px rgba(0,0,0,0.93);background:#ec8b5c;border:1px solid #ec8b5c;}
	.btnExcel:hover{border:1px solid #ec8b5c;background:transparent;color:#ec8b5c}
	.gifted{position:absolute;font-size:20px;background:#c388e8;border-radius:50px;cursor:pointer;z-index:10000000;bottom:8px;padding:4px 10px;}
	.style1::-webkit-scrollbar{width:6px;background-color:#F5F5F5;} 
	.style1::-webkit-scrollbar-thumb{background-color:#000000;}
	.style1::-webkit-scrollbar-track{-webkit-box-shadow:inset 0 0 6px rgba(0,0,0,0.3);background-color:#F5F5F5;}
	.tienda,.facty{height:15px;width:15px;}
	.tienda[type="checkbox"]:before,.facty[type="checkbox"]:before {width:15px;height:15px;}
	.tienda[type="checkbox"]:after,.facty[type="checkbox"]:after {top:-15px;width: 15px;height: 15px;}
	td{border: 1px solid;}
	.inputtab{width:100%;height:100%;border:0;margin:-1px;border-bottom:1px solid;}
	.downl{width:100%;font-size:16px;padding:10px;background:#7db7ff;border:2px solid #7db7ff;border-radius:5px;}
	.downl:hover{background:transparent;color: #7db7ff}
	.elimbtn{border:2px solid #ec3939 !important;color:#ec3939;background:#FFF !important}
	.elimbtn:hover{background:#ec3939 !important;color:#FFF;}
	.sinFact{background:#7db7ff;border:2px solid #7db7ff;border-radius:5px;color:#FFF;font-size:16px;padding:20px;width:100%;}
	.sinFact:hover{background:#FFF;color:#7db7ff}
	.gifted2{font-size:20px;background:#c388e8;border-radius:0px;cursor:pointer;border:1px solid;line-height:normal;height:80%;margin-top:1%;z-index:10000000;}
	.gifted2:hover{background:#FFF;color:#c388e8;}
	.devolucion2{font-size:20px;background:#efff00;cursor:pointer;z-index:10000000;margin-top:1%;height:80%;border:1px solid;display:inline-flex;}
	.devolucion2:hover{background:#FFF}
	#difis2{width:50px;height:105%;border:1px solid #000;color:#000;padding:3px;font-size:14px;margin-top:-2%;display:none}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<div class="col-md-10 col-lg-10" style="margin-top:20px">
				<div class="ibox">
					<div class="col-md-12 col-lg-12 ibox-content" style="border:1px solid #7db7ff;">
						<h1 style="border-bottom:1px solid #7db7ff;margin-bottom:15px;color:#7db7ff">Seleccione un proveedor para descargar todas las facturas de la semana</h1>
						<?php if($provis):foreach ($provis as $key => $value): ?>
							<?php if($value->id_usuario == 102): ?>
								<div class="col-md-3 col-lg-2">
									<a target="_blank" href="<?php echo base_url('Facturas/imprimeDimuflo/'.$value->id_usuario) ?>" style="color:#FFF"><button class="downl" data-id-prove="<?php echo $value->id_usuario ?>"><i class="fa fa-download" aria-hidden="true"></i>
										&nbsp;&nbsp;&nbsp;<?php echo $value->nombre ?></button></a>
								</div>
							<?php else: ?>
								<div class="col-md-3 col-lg-2">
									<a target="_blank" href="<?php echo base_url('Facturas/imprimeR/'.$value->id_usuario) ?>" style="color:#FFF"><button class="downl" data-id-prove="<?php echo $value->id_usuario ?>"><i class="fa fa-download" aria-hidden="true"></i>
										&nbsp;&nbsp;&nbsp;<?php echo $value->nombre ?></button></a>
								</div>
							<?php endif; ?>
						<?php endforeach;endif; ?>
					</div> 
				</div>
			</div>
			<div class="col-md-2 col-lg-2" style="border:1px solid #7db7ff;margin-top:20px;padding:24px;border-radius:5px">
				<button type="button" class="sinFact">
					Número De Facturas Registradas Por Proveedor
				</button>
			</div>
		</div>
		<div class="col-lg-12 col-md-12">
			<h1>Seleccione una sucursal</h1>
			<div class="col-md-12 col-lg-12" style="margin-top:20px">
				<div class="ibox">
					<div class="col-md-12 col-lg-12 ibox-content">
						<?php foreach ($tiendas as $key => $value): ?>
							<div class="col-md-3 col-lg-2">
								<div class="form-check">
									<input class="form-check-input tienda" type="checkbox" value="" id="tienda<?php echo $value->id_usuario ?>">
									<label class="form-check-label" for="tienda<?php echo $value->id_usuario ?>" style="color:#000;background:#FFF !important;">
									   <?php echo $value->nombre ?>
									</label>
								</div>
							</div>
						<?php endforeach; ?>
					</div> 
				</div>
			</div>
		</div>

		<table>
			<tbody class="yy">
				
			</tbody>
		</table>

		
		<div class="col-md-12 col-lg-12 elvis" style="padding:0">
			<div class="col-lg-6 col-md-6">
				<div class="col-md-12 col-lg-12" style="margin-top:20px;">
					<div class="ibox" style="box-shadow:0 10px 20px rgba(0,0,0,0.79),0 6px 6px rgba(0,0,0,0.93)">
						<div class="col-md-12 col-lg-12 ibox-content">
							<h1>Comparar productos factura con pedidos realizados<a href="<?php echo base_url('assets/uploads/FacturaExcel.xlsx') ?>" download><i class='fa fa-download'></i></a></h2></h1>
							<div class="col-lg-6 col-md-6">
								<select id="proveedor" name="proveedor">
									<option value="0">Seleccione un proveedor</option>
									<?php foreach ($proveedores as $key => $value):?>
										<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-lg-6 col-md-6">
								<?php echo form_open_multipart("", array('id' => 'upload_facturas')); ?>
									<input class="btn btn-info" type="file" id="file_factura" name="file_factura" value="" style="background-color:#2347c8;border-color:#2347c8;" />
								<?php echo form_close(); ?>
							</div>
							<div class="col-lg-12 col-md-12">
								Para comparar una factura con los pedidos registrados, seleccione un proveedor y posteriormente clic al boton "Seleccionar archivo".
							</div>
						</div> 
					</div>
				</div>
			</div>
			<!--<div class="col-md-6 col-lg-6" style="padding:50px">
				<button type="button" class="btndownload">
					<i class="fa fa-download" aria-hidden="true"></i> DESCARGAR REPORTE (Sin detalles)
				</button>
			</div>-->
		</div>
		<!-- RESULTADOS DE LA FACTURA  -->
		<div class="col-lg-12 col-md-12 elvis2" style="margin-top:20px;border:1px solid #000;padding:30px;display:block;">
			<div class="col-md-12 col-lg-12" style="margin-top:20px">
				<div class="ibox">
					<div class="col-md-12 col-lg-12 ibox-content">
						<div class="col-md-12 col-lg-12 facture">
							
						</div>
					</div> 
					<div class="col-md-12 col-lg-12 ibox-content">
						<div class="col-md-12 col-lg-12 factdetails">
							<div class="col-md-12 col-lg-12" style="padding:20px">
								<div class="col-md-4 col-lg-4 btnExcelComp BE1" style="text-align:center;">
									
								</div>
								<div class="col-md-4 col-lg-4 btnExcelFact BE2" style="text-align:center;">
									
								</div>
								<div class="col-md-4 col-lg-4 btnExcelComp BE3" style="text-align:center;">
									
								</div>
							</div>
							<div class="col-lg-12 col-md-12" style="padding:0">
								<div class="col-md-12 col-lg-12 headfact">
									
								</div>

								<div class="col-md-5 col-lg-5 subheadfact">
									
								</div>
								<div class="col-lg-7 col-md-7 subheadfact2">
									<div class="col-lg-6 col-md-6" style="font-size:15px;height:25px;border-bottom:2px solid;border-right:1px solid gray;line-height:25px">
										Fecha de Reporte
									</div>
									<div class="col-lg-6 col-md-6 fecharep">
										
									</div>
									<div class="col-lg-6 col-md-6" style="font-size:15px;height:25px;border-bottom:2px solid;border-right:1px solid gray;line-height:25px">
										Fecha en Factura
									</div>
									<div class="col-lg-6 col-md-6 fechafact">
										
									</div>
								</div>

								<div class="col-md-4 col-lg-4" style="height:50px;border:2px solid;border-top:0;line-height:50px;text-align:center;font-size:18px;">
									DESCRIPCIÓN
								</div>
								<div class="col-md-1 col-lg-1" style="height:50px;border:2px solid;border-top:0;line-height:50px;text-align:center;font-size:14px;border-left:0">
									PROMO
								</div>
								<div class="col-md-1 col-lg-1" style="padding:0">
									<div class="col-lg-12 col-md-12" style="height:25px;border:2px solid;border-top:0;line-height:25px;text-align:center;font-size:14px;border-left:0;border-bottom:1px solid gray">
										PRECIO EN
									</div>
									<div class="col-lg-12 col-md-12" style="height:25px;border:2px solid;border-top:0;line-height:25px;text-align:center;font-size:14px;border-left:0">
										PEDIDO
									</div>
								</div>
								<div class="col-md-1 col-lg-1" style="padding:0">
									<div class="col-lg-12 col-md-12" style="height:25px;border:2px solid;border-top:0;line-height:25px;text-align:center;font-size:14px;border-left:0;border-bottom:1px solid gray">
										CANT
									</div>
									<div class="col-lg-12 col-md-12" style="height:25px;border:2px solid;border-top:0;line-height:25px;text-align:center;font-size:14px;border-left:0">
										PEDIDO
									</div>
								</div>
								<div class="col-md-1 col-lg-1" style="padding:0">
									<div class="col-lg-12 col-md-12" style="height:25px;border:2px solid;border-top:0;line-height:25px;text-align:center;font-size:14px;border-left:0;border-bottom:1px solid gray">
										CANT
									</div>
									<div class="col-lg-12 col-md-12" style="height:25px;border:2px solid;border-top:0;line-height:25px;text-align:center;font-size:14px;border-left:0">
										FACTURA
									</div>
								</div>
								<div class="col-md-1 col-lg-1" style="padding:0">
									<div class="col-lg-12 col-md-12" style="height:25px;border:2px solid;border-top:0;line-height:25px;text-align:center;font-size:14px;border-left:0;border-bottom:1px solid gray">
										PRECIO
									</div>
									<div class="col-lg-12 col-md-12" style="height:25px;border:2px solid;border-top:0;line-height:25px;text-align:center;font-size:14px;border-left:0">
										FACTURA
									</div>
								</div>
								<div class="col-md-1 col-lg-1" style="height:50px;border:2px solid;border-top:0;line-height:50px;text-align:center;font-size:18px;border-left:0">
									DIF.
								</div>
								<div class="col-md-1 col-lg-1" style="padding:0">
									<div class="col-lg-12 col-md-12" style="height:25px;border:2px solid;border-top:0;line-height:25px;text-align:center;font-size:18px;border-left:0;border-bottom:1px solid gray">
										NOTA
									</div>
									<div class="col-lg-12 col-md-12" style="height:25px;border:2px solid;border-top:0;line-height:25px;text-align:center;font-size:18px;border-left:0">
										CREDITO
									</div>
								</div>
								<div class="col-md-1 col-lg-1" style="padding:0">
									<div class="col-lg-12 col-md-12" style="height:25px;border:2px solid;border-top:0;line-height:25px;text-align:center;font-size:18px;border-left:0;border-bottom:1px solid gray">
										TOTAL
									</div>
									<div class="col-lg-12 col-md-12" style="height:25px;border:2px solid;border-top:0;line-height:25px;text-align:center;font-size:18px;border-left:0">
										A PAGAR
									</div>
								</div>
							</div>

							<div class="col-md-12 col-lg-12 factlist" style="padding:0">
								
							</div>

							<div class="col-md-12 col-lg-12" style="padding:0">
								<div class="col-md-8 col-lg-8" style="height:30px;border-bottom:2px solid;border-right:2px solid"></div>
								<div class="col-md-2 col-lg-2 notafolio" style="height:30px;border:2px solid;border-left:0;"></div>
								<div class="col-md-1 col-lg-1 sumnota" style="height:30px;border:2px solid;border-left:0;"></div>
								<div class="col-md-1 col-lg-1 sumtotal" style="height:30px;border:2px solid;border-left:0;"></div>
							</div>
							<div class="col-lg-12 col-md-12" style="padding:0">
								<div class="col-md-4 col-lg-4" style="border:2px solid;border-top:0;height:30px;line-height:30px;"></div>
								<div class="col-md-1 col-lg-1" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;"></div>
								<div class="col-md-1 col-lg-1" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;"></div>
								<div class="col-md-1 col-lg-1" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;"></div>
								<div class="col-md-1 col-lg-1" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;"></div>
								<div class="col-md-2 col-lg-2" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;text-align:center;">TOTAL DE FACTURA</div>
								<div class="col-md-2 col-lg-2 totfact" style="background:rgb(35,149,144);height:30px;border:2px solid;border-left:0;border-top:0"></div>
							</div>
							<div class="col-lg-12 col-md-12" style="padding:0">
								<div class="col-md-4 col-lg-4" style="border:2px solid;border-top:0;height:30px;line-height:30px;"></div>
								<div class="col-md-1 col-lg-1" style="border:2px solid #000;border-top:0;border-left:0;height:30px;line-height:30px;text-align:center;color:red;font-weight:bolder;background:#e08989">DEVUELTO</div>
								<div class="col-md-1 col-lg-1" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;text-align:center;">DIFERENC</div>
								<div class="col-md-1 col-lg-1" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;"></div>
								<div class="col-md-1 col-lg-1" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;"></div>
								<div class="col-md-2 col-lg-2" style="border:2px solid;border-top:0;border-left:0;height:30px;text-align:center;font-size:10px">PENDIENTE X<br>DEVOLUCIÓN Y</div>
								<div class="col-md-2 col-lg-2 totpend" style="background:rgb(35,149,144);height:30px;border:2px solid;border-left:0;border-top:0"></div>
							</div>
							<div class="col-lg-12 col-md-12" style="padding:0">
								<div class="col-md-4 col-lg-4" style="border:2px solid;border-top:0;height:30px;line-height:30px;"></div>
								<div class="col-md-1 col-lg-1 devuel" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;"></div>
								<div class="col-md-1 col-lg-1 difer" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;"></div>
								<div class="col-md-1 col-lg-1" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;"></div>
								<div class="col-md-1 col-lg-1" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;"></div>
								<div class="col-md-2 col-lg-2" style="border:2px solid;border-top:0;border-left:0;height:30px;line-height:30px;text-align:center;font-weight:bold;font-size:20px;">TOTAL A PAGAR</div>
								<div class="col-md-2 col-lg-2 totpaga" style="background:rgb(35,149,144);height:30px;border:2px solid;border-left:0;border-top:0"></div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
		<!-- RESULTADOS DE LA FACTURA  -->
	</div>
</div>

<div class="col-lg-12 col-md-12 checkhim">
	<div class="col-lg-12 col-md-12">
		<h1 class="h1folio">
			RESULTADOS DE LA FACTURA
		</h1>
		<div class="col-md-12 col-lg-12" style="padding:0">
			<div class="col-md-8 col-lg-8 style1" style="padding-left:0">
				<div class="col-md-12 col-lg-12" style="padding:0;display:inline-flex;">
					<div class="col-md-2 col-lg-2 head2">CÓDIGO</div>
					<div class="col-md-3 col-lg-3 head3">DESCRIPCIÓN</div>
					<div class="col-md-2 col-lg-2 head2">COSTO</div>
					<div class="col-md-1 col-lg-1 head1">CANT</div>
					<div class="col-md-4 col-lg-4 head4">PEDIDOS SISTEMA</div>
				</div>
				<div class="col-md-12 col-lg-12 style1" style="padding:0;height:83vh" id="cuerpo">
					
				</div>
			</div>
			<div class="col-md-4 col-lg-4 fixdiv style1">
				<div class="col-lg-12 col-md-12">
					<input type="text" name="searchy" id="searchy" placeholder="Buscar" />
				</div>
				<div class="head4 col-md-12 col-lg-12" style="background:#0b73ce;">
					PEDIDOS EN SISTEMA
				</div>
				<div class="col-md-12 col-lg-12" style="padding:0;" id="cuerpo2" ondrop="drop(event)">
					<div class="col-lg-12 col-md-12 witheblock">
						
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-4 fixdiv2" style="padding:2%;">
				<div class="col-md-12 col-lg-12" style="padding-bottom:20px;">
					<button type="button" class="btnsalvar">
						<i class="fa fa-floppy-o" aria-hidden="true"></i> GUARDAR
					</button>
				</div>
				<div class="col-md-12 col-lg-12">
					<button type="button" class="btnnel">
						<i class="fa fa-ban" aria-hidden="true"></i> CANCELAR
					</button>
				</div>
			</div>
			
		</div>
	</div>
</div>
