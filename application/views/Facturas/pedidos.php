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
	.ibox-content{border-radius:5px;padding-bottom:70px}
	select{padding:10px;border-radius:5px;margin-bottom:15px;}
	.top-navigation .nav>li.active{background:#ffffff;border:none;}
	.top-navigation .nav > li.active > a {color: #000;background: #FFF;}
	.elimPed{border:2px solid #ef4c4c;font-size:16px;padding:10px 50px;border-radius:2px;color:#ef4c4c;background:#FFF}
	.elimPed:hover{background:#ef4c4c;color:#FFF;}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h1>Subir pedidos finales.</h1>
			<div class="col-md-12 col-lg-12" style="margin-top:20px">
				<div class="ibox col-md-12 col-lg-12">
					<div class="col-md-4 col-lg-4" style="border-right:1px solid #ccc;padding-bottom:20px;">
						<div class="col-md-12 col-lg-12"><h2 class="m-b-md">Buscar Pedido</h2></div>
						<div class="col-lg-12 col-md-12" style="padding-bottom:10px;">
							<h3>Buscar por producto</h3>
							<input type="text" name="producto" id="producto" style="padding:8px 10px;border-radius:5px;border:1px solid rgb(169,169,169);width:360px;">
							<br><small>A partir del 4to carácter se empezarán a mostrar los resultados</small>
						</div>
						<div class="col-lg-12 col-md-12">
							<h3>Buscar por proveedor</h3>
							<select id="provs" name="provs">
								<option value="0">Seleccione un proveedor</option>
								<?php foreach ($proveedores as $key => $value):?>
									<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="col-md-8 col-lg-8" style="padding-bottom:20px;">
						<?php echo form_open_multipart("", array('id' => 'upload_codigos')); ?>
							<div class="col-md-12 col-lg-12"><h2 class="m-b-md">Subir Excel 
								<a href="<?php echo base_url('assets/uploads/finales.xlsx') ?>" download><i class='fa fa-download'></i></a></h2>
							</div>
							<div class="col-md-5 col-lg-5" style="border-right:1px solid #ccc;">								
								<h5 class="m-b-md">1.- Seleccione un el proveedor a quien corresponde el formato.</h5>
								<select id="proveedor" name="proveedor">
									<?php foreach ($proveedores as $key => $value):?>
										<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-5 col-lg-5">
								<h5 class="m-b-md">2.- Clic al boton y seleccione el archivo Excel.</h5>
								<div class="col-sm-4">
									<input class="btn btn-info" type="file" id="file_codigos" name="file_codigos" value=""/>
								</div><br>
							</div>
							<div class="col-md-12">
								<div class="col-md-2"></div>
								<div class="col-md-4" style="border-top:1px solid #c5c5c5;padding-top:20px;">
									<button class="elimPed" type="button">
										Eliminar Pedidos Del Proveedor
									</button>
								</div>
								<div class="col-md-4"></div>
							</div>
						<?php echo form_close(); ?>
					</div> 
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 seld" style="border:1px solid #000;padding:30px">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" style="font-size:16px">
					<thead id="thead">
						<tr>
							<th>CÓDIGO</th>
							<th>DESCRIPCIÓN</th>
							<th>COSTO</th>
							<th>PROVEEDOR</th>
							<th style="background-color:rgb(192,0,0);">CEDIS</th>
							<th style="background-color:rgb(1,76,240);">ABARROTES</th>
							<th style="background-color:rgb(255,0,0);">VILLAS</th>
							<th style="background-color:rgb(226,108,11);">TIENDA</th>
							<th style="background-color:rgb(197,197,197);">ULTRA</th>
							<th style="background-color:rgb(146,208,91);">TRINCHERAS</th>
							<th style="background-color:rgb(177,160,199);">MERCADO</th>
							<th style="background-color:rgb(218,150,148);">TENENCIA</th>
							<th style="background-color:rgb(76,172,198);">TIJERAS</th>
							<th>PROMOCIÓN</th>
						</tr>
					</thead>
					<tbody id="tbodys">
						<tr>
							<td colspan="14">Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td>
						</tr>
					</tbody>
				</table>	
			</div>
		</div>
	</div>
</div>