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
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h1>Códigos Alternos (facturas) Proveedores</h1>
		</div>
		
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item active">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Códigos asignados</a>
		  	</li>
		  	<li class="nav-item">
		  		<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Agregar</a>
		  	</li>
		  	<li class="nav-item">
		  		<a class="nav-link" id="cancel-tab" data-toggle="tab" href="#cancel" role="tab" aria-controls="cancel" aria-selected="false">Sin asignar</a>
		  	</li>
		</ul>


		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active in" id="home" role="tabpanel" aria-labelledby="home-tab">
				<div class="col-md-12 col-lg-12" style="height:30px"></div>
				<div class="col-lg-6 col-md-6" style="padding: 0">
					<div class="col-lg-12 col-md-12 seld" style="border:1px solid #000;padding:30px;margin-bottom: 30px">
						<div class="col-lg-6 col-md-6">
							<h3>Buscar por producto</h3>
							<input type="text" name="producto" id="producto" style="padding:8px 10px;border-radius:5px;border:1px solid rgb(169,169,169);width:360px;">
							<br><small>A partir del 4to carácter se empezarán a mostrar los resultados</small>
						</div>
						<div class="col-lg-6 col-md-6">
							<h3>Buscar por proveedor</h3>
							<select id="provs" name="provs">
								<option value="0">Seleccione un proveedor</option>
								<?php foreach ($proveedores as $key => $value):?>
									<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
								<?php endforeach; ?>
							</select>
						</div>				
					</div>
				</div>				
				<div class="col-md-7 col-lg-7"></div>
				<div class="col-lg-12 col-md-12 seld" style="border:1px solid #000;padding:30px">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" style="font-size:16px">
							<thead id="thead">
								<tr>
									<th>CÓDIGO</th>
									<th>DESCRIPCIÓN</th>
									<th>PROVEEDOR</th>
									<th>COD PROVEEDOR</th>
									<th>DESC PROVEEDOR</th>
									<th> - </th>
								</tr>
							</thead>
							<tbody id="tbodys">
								<tr>
									<td colspan="6">Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td>
								</tr>
							</tbody>
						</table>	
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
				<div class="col-md-5 col-lg-5" style="position:absolute;top:20rem">
					<div class="ibox">
						<div class="ibox-content ">
							<?php echo form_open_multipart("", array('id' => 'upload_codigos')); ?>
								<h2 class="m-b-md">AGREGAR CÓDIGOS A PROVEEDORES</h2>
								<h5 class="m-b-mcd">1.- Descargue el archivo <a href="<?php echo base_url('assets/uploads/codigos.xlsx') ?>" download>aquí</a></h5>
								<h5 class="m-b-md">2.- Seleccione un el proveedor para asignar códigos</h5>
								<select id="proveedor" name="proveedor">
									<?php foreach ($proveedores as $key => $value):?>
										<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
									<?php endforeach; ?>
								</select>
								<h5 class="m-b-md">3.- Clic al boton y seleccione el archivo con la relación de códigos</h5>
								<div class="col-sm-4">
									<input class="btn btn-info" type="file" id="file_codigos" name="file_codigos" value=""/>
								</div>
							<?php echo form_close(); ?>
						</div> 
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="cancel" role="tabpanel" aria-labelledby="cancel-tab">...</div>
		</div>
	</div>
</div>