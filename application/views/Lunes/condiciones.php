<style>
	.footer{display: none;}
	.thPa{text-align:center;font-size:20px;color:#FFF;background:#000 !important;}
	.tdPa{font-size:16px;text-align:center;}
	.btnAddCond{background:cornflowerblue;color:#FFF;padding:5px;width:80%;border:1px solid #FFF;}
	.btnAddCond:hover{background:#FFF;color:cornflowerblue;border:1px solid cornflowerblue;}
	.btnDelCond{background:#ea4747;color:#FFF;border:1px solid #ea4747;padding:5px;width:50%;}
	.btnDelCond:hover{background:#FFF;color:#ea4747;}
</style>
<div class="ibox-content">
	<div class="row">
		<div class="row"> 
			<div class="col-md-12">
				<h1>
					CONDICIONES FORMATOS LUNES
				</h1>
			</div>
			<div class="col-sm-12">
				<?php $condi = 1;if($condiciones):foreach ($condiciones as $key => $value): ?>

					<?php if($condi <> $value->id_condicion && $condi > 1): ?>				
						</tbody>
							</table>
					<?php endif; ?>				

					<?php if($condi <> $value->id_condicion):$condi = $value->id_condicion; ?>
				
						<table class="table table-striped table-bordered table-hover" id="exislunnot">
							<thead>
								<tr>
									<th class="thPa"><?php echo "PROVEEDOR : ".$value->nombre ?></th>
									<th class="thPa" style="background:#54ab6b !important;"><?php echo $value->descri." : ".number_format($value->no_cajas,2,".",",") ?></th>
									<th class="thPa">
										<button class="btnAddCond" data-id-user="<?php echo $value->proveedor ?>" data-id-cotiz="<?php echo $value->id_condicion ?>">Agregar Producto</button>
									</th>
								</tr>
								<tr>
									<th class="thPa">CÓDIGO</th>
									<th class="thPa">DESCRIPCIÓN</th>
									<th class="thPa">NO APLICA</th>
								</tr>
							</thead>
							<tbody>

					<?php endif; ?>

						<tr>
							<td class="tdPa"><?php echo $value->codigo ?></td>
							<td class="tdPa"><?php echo $value->descripcion ?></td>
							<td class="tdPa">
								<button class="btnDelCond" data-id-user="<?php echo $value->codigo ?>">Eliminar Condición</button>
							</td>
						</tr>
				
				<?php endforeach;endif; ?>
			</div>
		</div>
	</div>
</div>