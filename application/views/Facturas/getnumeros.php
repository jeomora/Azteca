<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
	.modal-header{background:#1e92bf}
	.modal-footer{background:#1e92bf}
	.modal-body{height:70vh;overflow-y:scroll;}
	.modal-lg{width:80vw}
	.btn-delall{background:#ff3939;border:1px solid #ff3939;padding:10px 40px;font-size:16px;border-radius:2px;margin-bottom:15px;}
</style>
<div class="col-md-12">
	<div class="row col-md-12">
		<div class="col-md-12">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>PROVEEDOR</th>
						<th style="background-color:rgb(192,0,0,.8);">CEDIS</th>
						<th style="background-color:rgb(1,76,240,.8);">ABARROTES</th>
						<th style="background-color:rgb(255,0,0,.8);">VILLAS</th>
						<th style="background-color:rgb(226,108,11,.8);">TIENDA</th>
						<th style="background-color:rgb(197,197,197,.8);">ULTRA</th>
						<th style="background-color:rgb(146,208,91,.8);">TRINCHERAS</th>
						<th style="background-color:rgb(177,160,199,.8);">MERCADO</th>
						<th style="background-color:rgb(218,150,148,.8);">TENENCIA</th>
						<th style="background-color:rgb(76,172,198,.8);">TIJERAS</th>
					</tr>
				</thead>
				<tbody>
					<?php if($numeros):foreach ($numeros as $key => $value):?>
						<tr>
							<td><?php echo $value["nombre"] ?></td>
							<td style="background-color:rgb(192,0,0,.1);font-family:monospace;border:1px solid #ccc">
								<?php foreach ($value[87] as $key => $val):echo "=>  ".$val["folio"]."<br>";endforeach;?>
							</td>
							<td style="background-color:rgb(1,76,240,.1);font-family:monospace;border:1px solid #ccc">
								<?php foreach ($value[90] as $key => $val):echo "=>  ".$val["folio"]."<br>";endforeach;?>
							</td>
							<td style="background-color:rgb(255,0,0,.1);font-family:monospace;border:1px solid #ccc">
								<?php foreach ($value[57] as $key => $val):echo "=>  ".$val["folio"]."<br>";endforeach;?>
							</td>
							<td style="background-color:rgb(226,108,11,.1);font-family:monospace;border:1px solid #ccc">
								<?php foreach ($value[58] as $key => $val):echo "=>  ".$val["folio"]."<br>";endforeach;?>
							</td>
							<td style="background-color:rgb(197,197,197,.1);font-family:monospace;border:1px solid #ccc">
								<?php foreach ($value[59] as $key => $val):echo "=>  ".$val["folio"]."<br>";endforeach;?>
							</td>
							<td style="background-color:rgb(146,208,91,.1);font-family:monospace;border:1px solid #ccc">
								<?php foreach ($value[60] as $key => $val):echo "=>  ".$val["folio"]."<br>";endforeach;?>
							</td>
							<td style="background-color:rgb(177,160,199,.1);font-family:monospace;border:1px solid #ccc">
								<?php foreach ($value[61] as $key => $val):echo "=>  ".$val["folio"]."<br>";endforeach;?>
							</td>
							<td style="background-color:rgb(218,150,148,.1);font-family:monospace;border:1px solid #ccc">
								<?php foreach ($value[62] as $key => $val):echo "=>  ".$val["folio"]."<br>";endforeach;?>
							</td>
							<td style="background-color:rgb(76,172,198,.1);font-family:monospace;border:1px solid #ccc">
								<?php foreach ($value[63] as $key => $val):echo "=>  ".$val["folio"]."<br>";endforeach;?>
							</td>
						</tr>
					<?php endforeach;else: ?>
						<tr>
							<td colspan="10">Sin facturas registradas</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>