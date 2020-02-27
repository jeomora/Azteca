<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
	.info-label{border:2px solid #008062;margin-bottom:10px;padding:10px;font-size:12px;color:#008062;}
	.promdes{display:none}
	input{font-family:monospace;}
</style>
<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_producto_edit')); ?>
		<input type="hidden" name="codigos" id="codigos" value="<?php echo $producto->codigo ?>">
		<div class="row">
			<div class="col-sm-3" style="display: none">
				<div class="form-group">
					<label for="codigo2">Código</label>
					<input type="text" name="codigo2" value="<?php echo $producto->codigo ?>" class="form-control" placeholder="Código del producto">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="codigo">Código</label>
					<input type="text" name="codigo" value="<?php echo $producto->codigo ?>" class="form-control" placeholder="Código del producto">
				</div>
			</div>
			<div class="col-sm-8">
				<div class="form-group">
					<label for="descripcion">Descripción</label>
					<input type="text" name="descripcion" value="<?php echo $producto->descripcion ?>" class="form-control" placeholder="descripción">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="unidad Medida">Unidad Medida</label>
					<input type="text" name="unidad" value="<?php echo $producto->unidad ?>" class="form-control numeric" placeholder="Número de prods">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="sistema">Precio Sistema</label>
					<input type="text" name="sistema" value="<?php echo $producto->sistema ?>" class="form-control numeric" placeholder="Precio del Sistema">
				</div>
			</div>
			<div class="col-sm-5">
				<div class="form-group">
					<label for="observaciones">Observaciones</label>
					<input type="text" name="observaciones" value="<?php echo $producto->observaciones ?>" class="form-control" placeholder="Promoción">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_proveedor">Proveedor Asignado</label>
					<select name="id_proveedor" class="form-control chosen-select" id="id_proveedor">
						<option value="-1">Seleccionar...</option>
						<?php if ($proveedores):foreach ($proveedores as $key => $value): ?>
							<option value="<?php echo $value->id_proveedor ?>" <?php echo $producto->id_proveedor == $value->id_proveedor ? 'selected' : '' ?>><?php echo $value->nombre ?></option>
						<?php endforeach; endif ?>
					</select>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="sistema">Precio Proveedor</label>
					<input type="text" name="precio" value="<?php echo $producto->precio ?>" class="form-control numeric" placeholder="Precio del Proveedor">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="id_catalogo" style="background-color:#007eff !important;">Código Proveedor</label>
					<input type="text" name="id_catalogo" value="<?php if($cata):echo $cata->id_catalogo;endif ?>" class="form-control" placeholder="Código Proveedor">
				</div>
			</div>
			<div class="col-sm-3" style="display:none">
				<div class="form-group">
					<label for="id_catalogo" style="background-color:#007eff !important;">Código Proveedor</label>
					<input type="text" name="id_catalogo2" value="<?php if($cata):echo $cata->id_catalogo;endif ?>" class="form-control" placeholder="Código Proveedor">
				</div>
			</div>
			<div class="col-sm-12"></div>
			<div class="col-sm-2"></div>
			<div class="col-sm-8" style="padding-bottom:10px;border-top:1px dashed #c5c5c5;"></div>
			<div class="col-sm-2"></div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="promo">Promoción</label>
					<select name="promo" class="form-control chosen-select" id="promo">
						<option value="0" <?php if($promo):if($promo->promo === "0" || $promo->promo === 0):echo 'selected';endif;endif; ?>>SIN PROMOCIÓN</option>
						<option value="1" <?php if($promo):if($promo->promo === "1" || $promo->promo === 1):echo 'selected';endif;endif; ?>># EN  #</option>
						<option value="2" <?php if($promo):if($promo->promo === "2" || $promo->promo === 2):echo 'selected';endif;endif; ?>>% DE DESCUENTO</option>
						<option value="3" <?php if($promo):if($promo->promo === "3" || $promo->promo === 3):echo 'selected';endif;endif; ?>>PRODUCTOS S/CARGO</option>
					</select>
				</div>
			</div>
			<div class="col-sm-12"></div>
			
			<div class="col-sm-12 promodescuentos1 promdes" <?php if($promo):if ($promo->promo === 1 || $promo->promo === "1"):echo 'style="display:block"';endif;endif; ?>>
				<div class="col-sm-12 info-label">
					<div class="col-sm-1" style="font-size:24px">
						<i class="fa fa-info-circle" aria-hidden="true"></i>
					</div>
					<div class="col-sm-11">
						Productos sin cargo, siendo del mismo producto que se menciona arriba. <br>EJEMPLO: "En la compra de 2 clarasol, 1 sin cargo"
					</div>
				</div>
				<div class="col-sm-12" style="padding: 0">
					<div class="col-sm-5">
						<div class="form-group">
							<label for="mins" style="background-color:#008062 !important;">MINIMO DE PRODUCTOS</label>
							<input type="text" name="mins" value="<?php if($promo):echo $promo->mins;endif ?>" class="form-control" placeholder="MINIMO COMPRA" id="mins">
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="ieps" style="background-color:#008062 !important;">% IEPS</label>
							<input type="text" name="ieps" value="<?php if($promo):echo $promo->ieps;endif ?>" class="form-control" placeholder="% IEPS" id="ieps">
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label for="cuantos2" style="background-color:#008062 !important;"># SIN CARGO</label>
						<input type="text" name="cuantos2" value="<?php if($promo):echo $promo->cuantos2;endif ?>" class="form-control" placeholder="# Sin cargo" id="cuantos2">
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label for="cuantos2" style="background-color:#008062 !important;">EN  #</label>
						<input type="text" name="cuantos1" value="<?php if($promo):echo $promo->cuantos1;endif ?>" class="form-control" placeholder="En  #" id="cuantos1">
					</div>
				</div>
			</div>
			
			<div class="col-sm-12 promodescuentos2 promdes" <?php if($promo):if ($promo->promo === 2 || $promo->promo === "2"):echo 'style="display:block"';endif;endif; ?>>
				<div class="col-sm-12 info-label">
					<div class="col-sm-1" style="font-size:24px">
						<i class="fa fa-info-circle" aria-hidden="true"></i>
					</div>
					<div class="col-sm-11">
						Ingrese el porcentaje de descuento que se agregará al producto. <br>EJEMPLO: "Clarasol con un 10% de descuento, ingrese el numero 10 en la casilla"
					</div>
				</div>
				<div class="col-sm-12" style="padding: 0">
					<div class="col-sm-5">
						<div class="form-group">
							<label for="mins2" style="background-color:#008062 !important;">MINIMO DE PRODUCTOS</label>
							<input type="text" name="mins2" value="<?php if($promo):echo $promo->mins;endif ?>" class="form-control" placeholder="MINIMO COMPRA" id="mins2">
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="ieps" style="background-color:#008062 !important;">% IEPS</label>
							<input type="text" name="ieps2" value="<?php if($promo):echo $promo->ieps;endif ?>" class="form-control" placeholder="% IEPS" id="ieps2">
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label for="descuento" style="background-color:#008062 !important;">% DE DESCUENTO </label>
						<input type="text" name="descuento" value="<?php if($promo):echo $promo->descuento;endif ?>" class="form-control" placeholder="% DESCUENTO" id="descuento">
					</div>
				</div>
			</div>
			
			
			<div class="col-sm-12 promodescuentos3 promdes" <?php if($promo):if ($promo->promo === 3 || $promo->promo === "3"):echo 'style="display:block"';endif;endif; ?>>
				<div class="col-sm-12 info-label">
					<div class="col-sm-1" style="font-size:24px">
						<i class="fa fa-info-circle" aria-hidden="true"></i>
					</div>
					<div class="col-sm-11">
						Productos sin cargo, de otro producto o presentación. <br>EJEMPLO: "En la compra de 2 clarasol, 1 aceite sin cargo"
					</div>
				</div>
				<div class="col-sm-12" style="padding: 0">
					<div class="col-sm-5">
						<div class="form-group">
							<label for="mins3" style="background-color:#008062 !important;">MINIMO DE PRODUCTOS</label>
							<input type="text" name="mins3" value="<?php if($promo):echo $promo->mins;endif ?>" class="form-control" placeholder="MINIMO COMPRA" id="mins3">
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="ieps" style="background-color:#008062 !important;">% IEPS</label>
							<input type="text" name="ieps3" value="<?php if($promo):echo $promo->ieps;endif ?>" class="form-control" placeholder="% IEPS" id="ieps3">
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label for="cuanto1" style="background-color:#008062 !important;">EN LA COMPRA DE</label>
						<input type="text" name="cuanto1" value="<?php if($promo):echo $promo->cuantos1;endif ?>" class="form-control" placeholder="En la compra de" id="cuanto1">
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label for="cuanto2" style="background-color:#008062 !important;">SIN CARGO</label>
						<input type="text" name="cuanto2" value="<?php if($promo):echo $promo->cuantos2;endif ?>" class="form-control" placeholder="Sin cargo" id="cuanto2">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="prod" style="background-color:#008062 !important;">PRODUCTO SIN CARGO</label>
						<select name="prod" class="form-control chosen-select" id="prod" style="font-size: 10px !important">
							<?php if ($productos):foreach ($productos as $key => $value): ?>
								<option value="<?php echo $value->codigo ?>" <?php if($promo): echo $promo->codigo == $value->codigo ? 'selected' : '' ;endif?>><?php echo $value->descripcion." - ".$value->codigo ?></option>
							<?php endforeach; endif ?>
						</select>
					</div>
				</div>
			</div>
			
		</div>

		<?php echo form_close(); ?>
	</div>
</div>