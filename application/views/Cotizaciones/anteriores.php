<style type="text/css" media="screen">
	tr:hover {background-color: #21b9bb !important;}
	tr:hover > td{color: white !important;}
	table#table_cot_admin {width: 96% !important;}
	
</style>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<div class="col-lg-12" style="height: 10rem">
	<h1>Registrar cotizaciones igual a semanas pasadas</h1>
</div>
<div class="ibox-content" style="padding: 4%">
	<div class=""></div>
	<div class="" >
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
				<thead>
					<tr>
						<th style="width: 4%">AGREGAR</th>
						<th style="width: 4%">ID PROVEEDOR</th>
						<th>PROVEEDOR</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($cotizados as $key => $value): ?>
						<tr><td><button id="no_cotizo" class="btn btn-success" data-toggle="tooltip" title="VER" data-id-producto="<?php echo $value->ides ?>">
								<i class="fa fa-plus"></i>
							</button></td>
							<td style="text-align: center;"><?php echo $value->ides ?></td>
							<td style="text-align: center;"><?php echo $value->proveedor ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class=""></div>
</div>