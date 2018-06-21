<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
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
	.btng1{
		display: inline-flex;
	    background-color: #23c6c8;
	    border-radius: 5px;
	    color: #FFF;
	    margin-left: 3rem;
    	padding: 0;
    	padding-top: 4px;
    	padding-right: 5px;
	}
	.lblget{
		font-family: inherit;
	    font-weight: normal;
	    font-size: 14px;
	    padding: 7px;
	}
	tr:hover {background-color: #cfffc3 !important;}
	select#id_proves2{display: none}
	.fill_form{display: none}
	select#id_proves {color: #000;}

	.spinns{width:35rem;height:25rem;background-color:#FFF;text-align:center;line-height:25rem;border-radius: 5px;border: 3px solid #3b467b;}
	.dataTables_wrapper {padding-bottom: 30px;overflow-x: scroll;}
</style>
<div class="wrapper wrapper-content animated fadeInRight"> 
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>CONSULTAR COTIZACIONES</h5>
				</div>
				<div class="ibox-content">
					<div class="panel-body">
						<?php echo form_open("Reportes/fill_reporte", array("id" => 'consultar_cotizaciones', "target" => '_blank')); ?>
						<div class="col-sm-3">
							<div class="form-group">
								<label for="fecha_registro">Fecha consultar</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" name="fecha_registro" id="fecha_registro" class="form-control datepicker" value="" placeholder="00-00-0000">
								</div>
							</div>
						</div>

						<!--<div class="col-sm-6">
							<div class="form-group">
								<label for="id_proveedor">Proveedores</label>
								<select name="id_proveedor" id="id_proveedor" class="form-control chosen-select">
									<option value="">Seleccionar...</option>
									<?php /*if ($proveedores): foreach ($proveedores as $key => $value): ?>
										<option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre.' '.$value->apellido ?></option>
									<?php endforeach; endif */?>
								</select>
							</div>
						</div> -->

						<!-- <div class="col-sm-12">
							<div class="pull-right">
								<button class="btn btn-danger" data-toggle="tooltip" name="pdf" title="PDF" type="submit">
									<i class="fa fa-file-pdf-o"></i>
								</button>
								<button class="btn btn-primary" data-toggle="tooltip" name="excel" title="Excel" type="submit">
									<i class="fa fa-file-excel-o"></i>
								</button>
							</div>
						</div> -->
						<div class="col-sm-12 whodid"></div>
						<?php echo form_close(); ?>
						<div class="col-sm-2">
							<a title="Filtrar" id="filter_show" data-toggle="tooltip" class="btn btn-info" href="#" >
								<i class="fa fa-filter"></i>
							</a>
						</div>
						<div class="row">
							<div class="col-lg-4 searchboxs">
								<label>Buscar:<input class="form-control input-sm" type="text" id="myInput" onkeyup="myFunction2()" placeholder="Producto..."></label>
							</div>
						</div>
						<div id="respuesta_show" class="row">
								<div class="row col-sm-12 table-responsive tblm">

								</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>