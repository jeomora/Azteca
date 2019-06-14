<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<style>
	input[type="text"] {font-size: 12px !important;}
	button#update_prods {border-radius: 50px;font-size: 20px;padding: 10px 15px;background: linear-gradient(134deg,#74da74 50%,#6ec76e 50%);color: #353434;}
	button#update_prods:active{background: linear-gradient(134deg,#6ec76e 50%,#74da74 50%)}
	button#edit_prods {border-radius: 50px;font-size: 20px;padding: 10px 15px;background: linear-gradient(134deg,#c2c76e 50%,#cfda74 50%);color: #353434;}
	button#edit_prods:active {background: linear-gradient(134deg,#cfda74 50%,#c2c76e 50%);}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>CÓDIGOS DUERO</h5>
				</div>
				<div class="ibox-content">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table_codis">
							<thead>
								<tr>
									<th style="width:75px !important">CÓDIGO FACTURA</th>
									<th>DESCRIPCIÓN</th>
									<th>CLAVE</th>
									<th>CÓDIGO ASOCIADO</th>
									<th>DESCRIPCIÓN EN SISTEMA</th>	
									<th>EDITAR</th>	
								</tr>
							</thead>
							<tbody>
								<?php if ($productos): ?>
									<?php foreach ($productos as $key => $value): ?>
										<tr>
											<td style="width:95px !important">
												<input style="width:95px !important" type="text" class="codigo_factura" name="codigo_factura" value="<?php echo $value->codigo_factura ?>" disabled>
											</td>
											<td style="width:300px !important">
												<input style="width:300px !important" type="text" class="descripcion" name="descripcion" value="<?php echo strtoupper($value->descripcion) ?>" disabled>
											</td>
											<td style="width:120px !important">
												<input style="width:120px !important" type="text" class="clave" name="clave" value="<?php echo $value->clave ?>" disabled>
											</td>
											<td style="width:120px !important">
												<input style="width:120px !important" type="text" class="codigo" name="codigo" value="<?php echo $value->codigo ?>" disabled>
											</td>
											<td><?php echo $value->nombre ?></td>
											<td>
												<button id="update_prods" data-toggle="tooltip" title="Editar" data-id-prods="<?php echo $value->id_prodcaja ?>">
													<i class="fa fa-pencil"></i>
												</button>
												<button id="edit_prods" data-toggle="tooltip" title="Editar" data-id-prods="<?php echo $value->id_prodcaja ?>" style="display: none">
													<i class="fa fa-check"></i>
												</button>
											</td>
										</tr>
									<?php endforeach ?>
								<?php endif ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>