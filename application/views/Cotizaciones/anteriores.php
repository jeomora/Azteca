<style type="text/css" media="screen">
	tr:hover {background-color: #21b9bb !important;}
	tr:hover > td{color: white !important;}
</style>
<?php 
if(!$this->session->userdata("username")){
	redirect("Welcome/Login", "");
}
?>
<div class="col-md-12">
	<h1>Registrar cotizaciones igual a semanas pasadas</h1>
</div>
<div class="col-md-3"></div>
<div class="col-md-6" style="height: 45rem;overflow-y: scroll;">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover" id="table_cot_admin">
			<thead>
				<tr>
					<th style="width: 4rem">AGREGAR</th>
					<th style="width: 4rem">ID PROVEEDOR</th>
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
<div class="col-md-3"></div>
