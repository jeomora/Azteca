<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
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
							<option value="27">TACAMBA</option>
							<option value="4">SAHUAYO</option>
							<option value="2">DECASA</option>
							<option value="5,6,24,17,21,56">VARIOS 1ER</option>
							<option value="20,18,8,7,9,49,53,54,51">VARIOS 2DO</option>
							<option value="45,25,34,68,32,10,69,39,40,64,70,15,47,44,42,65,71">VARIOS 3RO</option>
							<option value="13,46,66,19,22,35,26,23,12,28,67,11,29">VARIOS 4TO</option>
							<option value="3">DUERO</option>
						</select>
						<select name="id_proves4" id="id_proves4" class="form-control">
							<option value="nope">Seleccionar...</option>
							<option value="27">TACAMBA</option>
							<option value="4">SAHUAYO</option>
							<option value="2">DECASA</option>
							<option value="5,6,24,17,21,56">VARIOS 1ER</option>
							<option value="20,18,8,7,9,49,53,54,51">VARIOS 2DO</option>
							<option value="45,25,34,68,32,10,69,39,40,64,70,15,47,44,42,65,71">VARIOS 3RO</option>
							<option value="13,46,66,19,22,35,26,23,12,28,67,11,29">VARIOS 4TO</option>
							<option value="3">DUERO</option>
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



		</div>
	</div>
</div>