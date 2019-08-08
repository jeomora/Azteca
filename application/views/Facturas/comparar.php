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
							<?php foreach ($proveedores as $key => $value):?>
								<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
							<?php endforeach; ?>
						</select>
					</div> 
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12" style="border:1px solid #000;padding:30px">
			<h2>Pedidos registrados.</h2><br>
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" style="font-size:16px;">
					<thead id="thead">
						<tr>
							<th style="background-color:#000;color:#FFF" colspan="3"></th>
							<th style="background-color:rgb(192,0,0);" colspan="3">CEDIS</th>
							<th style="background-color:rgb(1,76,240);" colspan="3">ABARROTES</th>
							<th style="background-color:rgb(255,0,0);" colspan="3">VILLAS</th>
							<th style="background-color:rgb(226,108,11);" colspan="3">TIENDA</th>
							<th style="background-color:rgb(197,197,197);" colspan="3">ULTRA</th>
							<th style="background-color:rgb(146,208,91);" colspan="3">TRINCHERAS</th>
							<th style="background-color:rgb(177,160,199);" colspan="3">MERCADO</th>
							<th style="background-color:rgb(218,150,148);" colspan="3">TENENCIA</th>
							<th style="background-color:rgb(76,172,198);" colspan="3">TIJERAS</th>
							<th style="background-color:#000;color:#FFF">PROMOCIÓN</th>
						</tr>
						<tr>
							<th style="background-color:#000;color:#FFF">CÓDIGO</th>
							<th style="background-color:#000;color:#FFF">DESCRIPCIÓN</th>
							<th style="background-color:#000;color:#FFF">COD PROVEEDOR</th>
							<th style="background-color:#000;color:#FFF">CANTIDAD</th>
							<th style="background-color:#000;color:#FFF">COSTOS</th>
							<th style="background-color:#000;color:#FFF">TOTAL</th>
							<th style="background-color:#000;color:#FFF">CANTIDAD</th>
							<th style="background-color:#000;color:#FFF">COSTOS</th>
							<th style="background-color:#000;color:#FFF">TOTAL</th>
							<th style="background-color:#000;color:#FFF">CANTIDAD</th>
							<th style="background-color:#000;color:#FFF">COSTOS</th>
							<th style="background-color:#000;color:#FFF">TOTAL</th>
							<th style="background-color:#000;color:#FFF">CANTIDAD</th>
							<th style="background-color:#000;color:#FFF">COSTOS</th>
							<th style="background-color:#000;color:#FFF">TOTAL</th>
							<th style="background-color:#000;color:#FFF">CANTIDAD</th>
							<th style="background-color:#000;color:#FFF">COSTOS</th>
							<th style="background-color:#000;color:#FFF">TOTAL</th>
							<th style="background-color:#000;color:#FFF">CANTIDAD</th>
							<th style="background-color:#000;color:#FFF">COSTOS</th>
							<th style="background-color:#000;color:#FFF">TOTAL</th>
							<th style="background-color:#000;color:#FFF">CANTIDAD</th>
							<th style="background-color:#000;color:#FFF">COSTOS</th>
							<th style="background-color:#000;color:#FFF">TOTAL</th>
							<th style="background-color:#000;color:#FFF">CANTIDAD</th>
							<th style="background-color:#000;color:#FFF">COSTOS</th>
							<th style="background-color:#000;color:#FFF">TOTAL</th>
							<th style="background-color:#000;color:#FFF">CANTIDAD</th>
							<th style="background-color:#000;color:#FFF">COSTOS</th>
							<th style="background-color:#000;color:#FFF">TOTAL</th>
							<th style="background-color:#000;color:#FFF"></th>
						</tr>
					</thead>
					<tbody id="tbodys">
						<tr>
							<td rowspan="3">750103700</td>
							<td rowspan="3">FIBRAS AJAX 24/5 PZAS.</td>
							<td rowspan="3">750103700</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td rowspan="3"></td>
						</tr>
						<tr>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
						</tr>
						<tr>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
						</tr>
						<tr>
							<td colspan="31">Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td>
						</tr>
					</tbody>
				</table>	
			</div>
		</div>
	</div>
</div>