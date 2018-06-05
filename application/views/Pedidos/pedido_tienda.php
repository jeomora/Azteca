<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Welcome/Login", "");
}
?>
<style>
	.td2Form{background-color: #000 !important; color:#FFF !important;}
	th {text-align: center}
	tr:hover {background-color: #cfffc3 !important;}
	select#id_proves2{display: none}
	.fill_form{display: none}
	select#id_proves {color: #000;}
	select#id_proves4 {color: #000;}
	.btng1{
		display: inline-flex;
	    background-color: #23c6c8;
	    border-radius: 5px;
	    color: #FFF;
	    margin-left: 3rem;
    	padding: 5px;
    	padding-top: 4px;
    	padding-right: 5px;
    	margin-bottom: 2rem;
	}
	.lblget {
	    font-family: inherit;
	    font-weight: normal;
	    font-size: 14px;
	    padding: 7px;
	}
	.preciomas{
		background-color: #ea9696;
	    color: red;
	    font-weight: bold;
	    text-align: center;
	}
	.preciomenos{
		background-color: #96eaa8;
	    color: green;
	    font-weight: bold;
	    text-align: center;
	}
	.numeric {width: 70px !important;}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
					<?php echo form_open("Cotizaciones/fill_formato", array("id" => 'reporte_form', "target" => '_blank',"class" => 'btn-group')); ?>
					<div class="btn-group btng1">
						<label for="id_proveedor" class="lblget">Proveedor</label>
						<select name="id_proves2" id="id_proves2" class="form-control">
							<option value="nope">Seleccionar...</option>
							<option value="VARIOS1">VARIOS 1°</option>
							<option value="VARIOS2">VARIOS 2°</option>
							<option value="VARIOS3">VARIOS 3°</option>
							<option value="VARIOS4">VARIOS 4°</option>
							<option value="VOLUMEN">VOLUMEN</option>
							<option value="AMARILLOS">AMARILLOS</option>
							<?php if($conjuntos):foreach ($conjuntos as $key => $value): ?>
								<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
							<?php endforeach;endif; ?>
						</select>
						<select name="id_proves4" id="id_proves4" class="form-control">
							<option value="nope">Seleccionar...</option>
							<option value="VARIOS1">VARIOS 1°</option>
							<option value="VARIOS2">VARIOS 2°</option>
							<option value="VARIOS3">VARIOS 3°</option>
							<option value="VARIOS4">VARIOS 4°</option>
							<option value="VOLUMEN">VOLUMEN</option>
							<option value="AMARILLOS">AMARILLOS</option>
							<?php if($conjuntos):foreach ($conjuntos as $key => $value): ?>
								<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre ?></option>
							<?php endforeach;endif; ?>
						</select>
						<div class="btn-group">
							<button class="btn btn-primary fill_form" name="excel" data-toggle="tooltip" title="Exportar a Excel" type="submit">
								<i class="fa fa-file-excel-o"></i>
							</button>
						</div>
					</div>
					<?php echo form_close(); ?>
			<div class="col-md-12 wonder" style="padding: 0">

			</div>
			<div class="btn-group" style="margin-left: 5rem;margin-top: -1rem;">
				<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;margin-top: -2rem;">
					Subir formato de pedidos
				</div>
				<?php echo form_open_multipart("", array('id' => 'upload_pedidos')); ?>
					<div class="col-sm-4">
						<input class="btn btn-info" type="file" id="file_cotizaciones" name="file_cotizaciones" value=""/>
					</div>
				<?php echo form_close(); ?>
			</div>



		</div>
	</div>
</div>