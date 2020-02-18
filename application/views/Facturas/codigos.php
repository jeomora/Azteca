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
</style>
<div class="col-md-12">
	<div class="col-md-12" style="padding-top: 20px">
		<h1>Códigos (catálogos) de Proveedores</h1>
	</div>
	<div class="col-md-12">
		<div class="col-md-3" style="padding-top:20px;">
			<select class="custom-select2" id="id_sucursal">
				<option value="0" style="color:#bf1e61;background:#FFF;font-size:14px">Seleccione una sucursal...</option>
				<?php if($sucursales):foreach ($sucursales as $key => $value):?>
					<option value="<?php echo $value->id_sucursal ?>" style="color:#bf1e61;background:#FFF;font-size:14px"><?php echo $value->nombre ?></option>
				<?php endforeach;endif; ?>
			</select>
		</div>
	</div>
</div>
