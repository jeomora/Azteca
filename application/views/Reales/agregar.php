<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<style>
	.downl{width:100%;font-size:16px;padding:10px;background:#7db7ff;border:2px solid #7db7ff;border-radius:5px;color:#FFF}
	.downl:hover{background:transparent;color: #7db7ff}

</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-md-6 col-lg-6 ibox-content" style="border:2px solid #7db7ff;padding-bottom: 5px">
			<div class="col-md-6 col-lg-6">
				<a target="_blank" href="<?php echo base_url('Reales/print_productos') ?>" style="color:#FFF"><button class="downl">Descargar Plantilla</button></a>
			</div>
			<div class="col-lg-6 col-md-6">
				<?php echo form_open_multipart("", array('id' => 'upload_cotizaciones')); ?>
					<input class="btn btn-info" type="file" id="file_otizaciones" name="file_otizaciones" value="" style="background-color:#7db7ff;border-color:#7db7ff;" />
				<?php echo form_close(); ?>
			</div>
			<div class="col-md-12 col-lg-12" style="padding-top:10px">
				<h5><i class="fa fa-info-circle" aria-hidden="true"></i> &nbsp; PUEDE DESCARGAR LA PLANTILLA Y POSTERIOREMENTE SELECCIONARLA PARA ACTUALIZAR LOS PRECIOS REALES QUE APARECERAN EN LOS FORMATOS DE COTIZACIÓN DE LA SEMANA</h5>
			</div>
		</div> 
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h1>PRECIOS REALES DE LA SEMANA</h1>
				</div>
				<div class="ibox-content" style="margin-top:20px">
					<table class="table table-striped table-bordered table-hover" id="table_familias">
						<thead>
							<tr>
								<th>NO</th>
								<th>PRODUCTO</th>
								<th>PRECIO REAL</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($reales): ?>
								<?php foreach ($reales as $key => $value): ?>
									<tr>
										<th><?php echo $value->id_real ?></th>
										<td><?php echo $value->nombre ?></td>
										<td><?php echo "$ ".number_format($value->precio,2,".",","); ?></td>
									</tr>
								<?php endforeach ?>
							<?php else: ?>
								<tr>
									<td colspan="3" style="text-align:center;font-size:20px">NO SE HAN REGISTRADO "PRECIOS REALES" EN ESTA SEMANA</td>
								</tr>
							<?php endif ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>