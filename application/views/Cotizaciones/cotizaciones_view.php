<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css" media="screen">
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
	.filts{
	    margin-left: 10rem;
	    background-color: #24C6C8;
	    color: white;
	    border-radius: 5px;
	    padding: 1rem;
	    margin-top: 7px;
	}
	.btng{
	    display:  inline-flex;
	    margin-left:  5rem;
	}
	.slct{
		border: 2px solid #24C6C8;
	    border-top-left-radius: 5px;
	    border-bottom-left-radius: 5px;
	    padding: 1rem;
	    height: 5rem
	}
	.filtro{
		padding: 2rem;
	    border: 2px solid #24C6C8;
	    height: 5rem
	}
	.btsrch{
	    background-color: #1D84C6;
	    color: #fff;
	    height: 5rem;
	    width: 7rem;
	}
	div#table_cot_admin_processing {
	    position: absolute;
	    left: 38%;
	    top: 10%;
	}
	td{width: 13rem !important;}
	table{width: 129rem !important}

	.spinns{width:35rem;height:25rem;background-color:#FFF;text-align:center;line-height:25rem;border-radius: 5px;border: 3px solid #3b467b;}
	@media screen and (min-width: 1500px){
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
	}	
</style>
<div class="wrapper wrapper-content animated fadeInRight" style="padding-left: 0;padding-right: 0">
	<div class="row">
		<div class="col-lg-12" style="padding: 0">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE COTIZACIONES</h5>
				</div>
				<div class="ibox-content">
					<div class="btn-group">
						<div class="col-sm-2">
							<a href="<?php echo base_url('./assets/uploads/Formato_precios.xlsx'); ?>" target="_blank" data-toggle="tooltip" title="Decargar Formato Precios" class="btn btn-info"><i class="fa fa-cloud-download"></i><span class="nav-label" download></span> </a>
						</div>
					</div>
					<div class="btn-group">
						<?php echo form_open_multipart("", array('id' => 'upload_precios')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_precios" name="file_precios" value=""/>
							</div>
						<?php echo form_close(); ?>
					</div>
					<div class="btn-group">
						<?php echo form_open("Cotizaciones/fill_excel", array("id" => 'reporte_cotizaciones', "target" => '_blank')); ?>
							<button class="btn btn-primary" name="excel" data-toggle="tooltip" title="Exportar a Excel" type="submit">
								<i class="fa fa-file-excel-o"></i>
							</button>
						<?php echo form_close(); ?>
					</div>
					<div class="table-responsive"> 
						<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
							<thead>
								<tr>
									<th>FAMILIA</th>
									<th>CÓDIGO</th>
									<th>DESCRIPCIÓN</th>
									<th>SISTEMA</th>
									<th>PRECIO 4</th>
									<th>FACTURA</th>
									<th>C/PROMOCIÓN</th>
									<th>PROVEEDOR</th>
									<th>OBSERVACIÓN</th>
									<th>PRECIO MAXIMO</th>
									<th>PRECIO PROMEDIO</th>
									<th>FACTURA</th>
									<th>C/PROMOCIÓN</th>
									<th>2DO PROVEEDOR</th>
									<th>OBSERVACIÓN</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>