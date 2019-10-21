<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
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
	.top-navigation #page-wrapper {overflow-y:scroll;}
</style>
<div class="col-md-12 wrapper wrapper-content animated fadeInRight">
	<div class="col-md-12 row">
		<div class="col-md-12">
			<div class="col-lg-4">
				<div class="ibox">
					<div class="ibox-content ">
						<h3 class="m-b-md">ARTICULOS FORMATO LUNES</h3>
						<h2 class="text-navy">
							<i class="fa fa-play fa-rotate-45"></i> <?php if ($cuantas):echo $cuantas->cuantas;else:echo 0;endif;?> de 
							<?php echo $noprod->noprod; ?>
						</h2>
						<small>Presione <a id="luninfo" style="color:red;font-size:17px">aquí</a> para ver los productos sin existencia.</small>
					</div> 
				</div>
			</div>
			<div class="col-lg-4">
				<div class="ibox">
					<div class="ibox-content ">
						<h3 class="m-b-md">ARTICULOS FORMATO VOLUMEN</h3>
						<h2 class="text-navy">
							<i class="fa fa-play fa-rotate-45"></i> <?php if ($volcuantas):echo $volcuantas->cuantas;else:echo 0;endif;?> de 
							<?php echo $novol->total; ?>
						</h2>
						<small>Presione <a style="color:red;font-size:17px" id="volinfo">aquí</a> para ver los productos sin existencia.</small>
					</div> 
				</div>
			</div>
			<div class="col-lg-4">
				<div class="ibox">
					<div class="ibox-content ">
						<h3 class="m-b-md">ARTICULOS COMPRAS</h3>
						<h2 class="text-navy">
							<i class="fa fa-play fa-rotate-45"></i> <?php if ($allcuantas):echo $allcuantas->cuantas;else:echo 0;endif;?> de 
							<?php echo $noall->total; ?>
						</h2>
						<small>Presione <a id="allinfo" style="color:red;font-size:17px">aquí</a> para ver los productos sin existencia.</small>
					</div> 
				</div>
			</div>
		</div>
		<div class="col-md-12" style="box-shadow:inset 0px 0px 0px #000,0px 5px 0px 0px #000,0px 10px 5px #000;border:1px solid #000;padding:20px">
			<?php echo form_open_multipart("", array('id' => 'upload_pedidos')); ?>
			<div class="col-md-12">
				<h2 style="text-align:center;padding-bottom: 20px">SUBIR EXISTENCIAS Y PEDIDOS DE PRODUCTOS</h2>
			</div>
			<div class="col-md-12">
				<div class="col-md-2"></div>
				<div class="btn-group btng1 col-md-3">
					<label for="id_proveedor" class="lblget">FORMATO</label>
					<select name="id_proves2" id="id_proves2" class="form-control">
						<option value="nope">Seleccionar...</option>
						<option value="LUNES">FORMATO LUNES</option>
						<option value="VOLUMEN">VOLÚMENES</option>
						<option value="GENERAL">GENERAL</option>
					</select>
					<select name="id_proves4" id="id_proves4" class="form-control">
						<option value="nope">Seleccionar...</option>
						<option value="LUNES">FORMATO LUNES</option>
						<option value="VOLUMEN">VOLÚMENES</option>
						<option value="GENERAL">GENERAL</option>
					</select>
				</div>
				<div class="col-md-3">
					<div class="btn-group" style="margin-top: 5px;">
						<div class="col-sm-4">
							<input class="btn btn-info" type="file" id="file_cotizaciones" name="file_cotizaciones" value=""/>
						</div>
					</div>
				</div>
				<div class="col-md-3"></div>
			</div>
			<div class="col-md-12">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<p>Para subir archivo de existencias, seleccione en la parte izquierda el tipo de formato al que corresponde. Sí selecciona "Formato de los lunes" y en el archivo que seleccione contiene productos del formato "Volúmenes" no se producira ningún cambio en las existencias.</p>
					<p>¡Importante!, al guardarse existencias de un producto, no podrá editar las existencias y/o pedido de tal producto incluso subiendo nuevamente los formatos. En tal caso le pedimos comunicarse con el área de compras en CEDIS GRUPO AZTECA para solicitar el cambio de las existencias.</p>
				</div>
				<div class="col-md-2"></div>
			</div>
			<div class="col-md-12 wonder" style="padding: 0">

			</div>
			
			<?php echo form_close(); ?>



		</div>

		<div class="col-md-12" style="height:90px"></div>


		<div class="col-md-12" style="box-shadow:inset 0px 0px 0px #1b006d,0px 5px 0px 0px #1b006d,0px 10px 5px #1b006d;border:1px solid #1b006d;padding:20px">
			<?php echo form_open_multipart("", array('id' => 'upload_verduras')); ?>
			<div class="col-md-12">
				<h2 style="text-align:center;padding-bottom: 20px">FORMATO VERDURAS (MERMA) <a href="Verduras/print_productos" target="_blank"><i class='fa fa-download'></i> <span style="font-size:10px">(Descargar Formato)</span></a></h2>
			</div>
			<div class="col-md-12">
				<div class="col-md-4"></div>
				<div class="col-md-4" style="margin-top:5px;margin-bottom:5px">
					<div class="btn-group">
						<div class="col-sm-4">
							<input class="btn btn-info" style="background-color:#1b006d;border:1px solid #1b006d" type="file" id="file_verduras" name="file_verduras" value=""/>
						</div>
					</div>
				</div>
				<div class="col-md-4"></div>
			</div>
			<div class="col-md-12">

			</div>
			<div class="col-md-4 wonder" style="padding: 0">
				<table class="table table-striped table-bordered table-hover" id="table_verduras">
					<thead>
						<th style="background-color:#000;color:#FFF">ID</th>
						<th style="background-color:#000;color:#FFF">DESCRIPCIÓN</th>
						<th style="background-color:#000;color:#FFF">TOTAL</th>
					</thead>
					<tbody>
						<?php if($verduras):foreach ($verduras as $key => $value): ?>
							<tr>
								<td><?php echo $value->id_verdura; ?></td>
								<td><?php echo $value->descripcion ?></td>
								<td><?php echo $value->total ?></td>
							</tr>
						<?php endforeach;endif; ?>
						
					</tbody>
				</table>
			</div>
			
			<?php echo form_close(); ?>



		</div>
	</div>
</div>