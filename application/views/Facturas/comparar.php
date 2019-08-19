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
	.checkhim{display:none}
	.cerra{position:absolute;top:-22px;right:-1px;background:#279c9173;cursor:pointer;display:none;padding:0px 10px;border:1px solid #0b73ce;}
	.cantu{font-family:monospace;}
	.fixdiv2{padding:0;position:fixed;right:20px;top:60vh;height:25vh;}
	.btnsalvar{background:#79d079;font-size:20px;padding:10px;border:1px solid green;border-radius:3px;width:250px}
	.btnnel{background:#d07979;font-size:20px;padding:10px;border:1px solid red;border-radius:3px;width:250px}
	.devolucion{position:absolute;font-size:20px;background:#efff00;border-radius:10px;cursor:pointer;z-index:10000000;}
	.devolucion:hover{color:#efff00;background:#FFF}
	#difis{width:30px;height:20px;border:1px solid #000;color:#000;padding:3px;font-size:14px;display:none}
	#cuerpo{overflow-y:scroll;height:250vh;}

</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h1>Comparar productos factura con pedidos realizados</h1>
			<div class="col-md-5 col-lg-5" style="margin-top:20px">
				<div class="ibox">
					<div class="ibox-content ">
						<h2 class="m-b-md">Seleccione el proveedor</h2>
						<select id="proveedor" name="proveedor">
							<option value="0">Seleccione un proveedor</option>
							<?php foreach ($proveedores as $key => $value):?>
								<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
							<?php endforeach; ?>
						</select>
					</div> 
				</div>
			</div>
		</div>
		<div class="dragscroll col-lg-12 col-md-12" style="border:1px solid #000;padding:30px;overflow-x: scroll; cursor: grab; cursor : -o-grab; cursor : -moz-grab; cursor : -webkit-grab;">
			<div class="col-md-12 col-lg-12" style="margin-top:20px">
				<div class="ibox">
					<div class="col-md-12 col-lg-12 ibox-content ">
						<h2 class="m-b-md">Seleccionar filtros</h2>
						<!--<div class="col-md-3 col-lg-2">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="" id="allcheck">
								<label class="form-check-label" for="allcheck" style="color:#000;background:#FFF !important;">
								   MOSTRAR TODAS
								</label>
							</div>
						</div>-->
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
						<!--<div class="col-md-12 col-lg-12" style="border-bottom:1px solid #000;height:20px;margin-bottom: 10px"></div>
						<div class="col-md-3 col-lg-2">
							<div class="form-check">
								<input class="form-check-input tienda" type="checkbox" value="" id="tienda<?php echo $value->id_usuario ?>">
								<label class="form-check-label" for="tienda<?php echo $value->id_usuario ?>" style="color:red;background:#FFF !important;">
								   ROJOS
								</label>
							</div>
						</div>
						<div class="col-md-4 col-lg-2">
							<div class="form-check">
								<input class="form-check-input tienda" type="checkbox" value="" id="tienda<?php echo $value->id_usuario ?>">
								<label class="form-check-label" for="tienda<?php echo $value->id_usuario ?>" style="color:red;background:#FFF !important;">
								   PRODUCTOS SIN RELACIÓN
								</label>
							</div>
						</div>-->
					</div> 
				</div>
			</div>
			<div class="tablediv">
				<div class="tablepeds">
					<div class="divcontain">
						<div class="backblack bbtotal" style="width:100%"></div>
					</div>
					<div class="divcontain">
						<div class="backblack" style="width:5%"> - </div>
						<div class="backblack" style="width:15%">CÓDIGO</div>
						<div class="backblack" style="width:50%">DESCRIPCIÓN</div>
						<div class="backblack" style="width:10%">PEDIDO</div>
						<div class="backblack" style="width:20%">PROMOCIÓN</div>
					</div>
					<div class="tablepeds-body" style="padding:0;width:100%">
						
					</div>
				</div>
				<div class="tabletien">
					<?php foreach ($tiendas as $key => $value): ?>
						<div class="ttc tienda<?php echo $value->id_usuario ?>">
							<div class="ttchead" style="display:inline-flex;background-color:<?php echo $value->color ?>;">
								<h3 style="width: 50%"><?php echo $value->nombre ?></h3> &nbsp;&nbsp;&nbsp;
								<?php echo form_open_multipart("", array('id' => 'upload_facturas'.$value->id_usuario)); ?>
									<input class="btn btn-info" type="file" id="file_factura" name="file_factura" value="" style="width:170px;background-color:<?php echo $value->color ?>" data-id-tienda="<?php echo $value->id_usuario ?>"/>
								<?php echo form_close(); ?>
							</div>
							<div class="divcontain">
								<div class="backblack" style="width:19%">COD</div>
								<div class="backblack" style="width:9%">PED</div>
								<div class="backblack" style="width:9%">ENT</div>
								<div class="backblack" style="width:9%">DIF</div>
								<div class="backblack" style="width:9%">COS</div>
								<div class="backblack" style="width:9%">FAC</div>
								<div class="backblack" style="width:9%">DIF</div>
								<div class="backblack" style="width:9%">TOT</div>
								<div class="backblack" style="width:9%">TOT</div>
								<div class="backblack" style="width:9%">DIF</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
				
		</div>
	</div>
</div>

<div class="col-lg-12 col-md-12 checkhim">
	<div class="col-lg-12 col-md-12">
		<h1 class="h1folio">
			RESULTADOS DE LA FACTURA
		</h1>
		<div class="col-md-12 col-lg-12" style="padding:0">
			<div class="col-md-8 col-lg-8" style="padding-left:0">
				<div class="col-md-12 col-lg-12" style="padding:0;display:inline-flex;">
					<div class="col-md-2 col-lg-2 head2">CÓDIGO</div>
					<div class="col-md-3 col-lg-3 head3">DESCRIPCIÓN</div>
					<div class="col-md-2 col-lg-2 head2">COSTO</div>
					<div class="col-md-1 col-lg-1 head1">CANT</div>
					<div class="col-md-4 col-lg-4 head4">PEDIDOS SISTEMA</div>
				</div>
				<div class="col-md-12 col-lg-12" style="padding:0;" id="cuerpo">
					
				</div>
			</div>
			<div class="col-md-4 col-lg-4 fixdiv">
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
