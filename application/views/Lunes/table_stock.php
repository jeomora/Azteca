<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>LISTADO DE ARTÍCULOS</h5>
				</div>
				<div class="ibox-content" style="padding-top:30px;">
					
					<div class="btn-group">
						<a class="btn btn-info" target="_blank" href="../assets/uploads/updatestock.xlsx">
							<i class="fa fa-cloud-download"></i> Descargar Plantilla
						</a>
					</div>
					<div class="btn-group">
						<div class="col-sm-12" style="text-align:  center;font-size: 16px;color: #21b9bb;margin-top: -2rem;">
							Subir Stock
						</div>
						<?php echo form_open_multipart("", array('id' => 'upload_productos')); ?>
							<div class="col-sm-4">
								<input class="btn btn-info" type="file" id="file_productos" name="file_productos" value="" size="20" />
							</div>
						<?php echo form_close(); ?>
					</div>
					
					<table class="table table-striped table-bordered table-hover" id="table_productos">
						<thead>
							<tr>
								<th>CÓDIGO</th>
								<th>NOMBRE</th>
								<?php foreach ($tiendas as $key => $value):?>
									<?php if ($value->id_sucursal <> 2 && $value->id_sucursal <> 90 && $value->id_sucursal <> 58): ?>
										<th style="text-align:center;background:<?php echo $value->color."99" ?>;border:2px solid <?php echo $value->color ?>;">
											<?php echo $value->nombre ?>
										</th>
									<?php endif; ?>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody class="tbody_stocks">

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>