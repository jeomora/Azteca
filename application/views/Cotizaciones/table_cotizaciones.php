<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
if(!$this->session->userdata("username")){
	redirect("Compras/Login", "");
}
?>
<style type="text/css" media="screen">
	.ibox-content {
    padding-top: 3rem;
 }
.zopim {
    display: none;
}
 tr:hover {background-color: #21b9bb !important;}

</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE COTIZACIONES</h5>
				</div>
				<div class="ibox-content">
					<!--<div class="btn-group">
						<button class="btn btn-primary" data-toggle="tooltip" title="Registrar" id="new_cotizacion">
							<i class="fa fa-plus"></i> Agregar Cotización
						</button>
					</div>
					<div class="btn-group">
							<?php echo form_open("Cotizaciones/fill_excel_pro", array("id" => 'reporte_cotizaciones', "target" => '_blank')); ?>
							<input type="text" name="id_pro" id="id_pro" value="<?php echo $usuario['id_usuario'] ?>" hidden>
								<button class="btn btn-info" name="excel" data-toggle="tooltip" title="Exportar a Excel" type="submit">
									<i class="fa fa-cloud-download"></i> Descargar formato cotizaciones
								</button>
							<?php echo form_close(); ?>
						</div>
					
					
					
					<div class="btn-group">
						<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;margin-top: -2rem;">
							Subir formato de cotizaciones
						</div>
						<?php echo form_open_multipart("", array('id' => 'upload_cotizaciones')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_otizaciones" name="file_otizaciones" value="" size="20" />
							</div>
						<?php echo form_close(); ?>
					</div>-->
					

					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_cot_proveedores">
							<thead>
								<tr>
									<th>DESCRIPCIÓN</th>
									<th>FECHA REGISTRO</th>
									<th>PRECIO FACTURA</th>
									<th>PRECIO FACTURA C/PROMOCIÓN</th>
									<th>DESCUENTO ADICIONAL</th>
									<th colspan="2">PROMOCIÓN</th>
									<th>OBSERVACIONES</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($cotizaciones): foreach ($cotizaciones as $key => $value): ?>
										<tr>
											<td><?php echo strtoupper($value->producto) ?></td>
											<td><?php echo date('d-m-Y', strtotime($value->fecha_registro)) ?></td>
											<td><?php echo ($value->precio >0) ? '$ '.number_format($value->precio,2,'.',',') : '' ?></td>
											<td><?php echo ($value->precio_promocion >0) ? '$ '.number_format($value->precio_promocion,2,'.',',') : ''?></td>
											<td><?php echo ($value->descuento > 0) ? number_format($value->descuento,0,'.',',').' %' : ''  ?></td>
											<td><?php echo ($value->num_one > 0 && $value->num_two > 0) ? $value->num_one.'&nbsp; EN &nbsp;'.$value->num_two : '' ?></td>
											<td><?php echo $value->promocion ?></td>
											<td><?php echo $value->observaciones ?></td>
										</tr>
								<?php endforeach; endif ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
