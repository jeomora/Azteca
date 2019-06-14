<div class="ibox-content">
	<div class="row">
		<table class="table">
			<thead>
				<tr>
					<th>NO</th>
					<th>PRODUCTOS</th>
					<th>CANTIDAD</th>
					<th>PRECIO</th>
					<th>IMPORTE</th>
				</tr>
			</thead>
			<tbody>
				<?php $a=0; $subtotal =0; if ($detallePedido): foreach ($detallePedido as $key => $value): ?>
					<tr>
						<td><?php echo $a+1 ?></td>
						<td><?php echo strtoupper($value->producto) ?></td>
						<td><?php echo number_format($value->cantidad,2,'.',',') ?></td>
						<td><?php echo '$ '.number_format($value->precio,2,'.',',') ?></td>
						<td><?php echo '$ '.number_format($value->importe,2,'.',',') ?></td>
					</tr>
					<?php $a++; $subtotal += $value->importe ?>
				<?php endforeach; endif ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3"></td>
					<th>Subtotal</th>
					<td><?php echo '$ '.number_format($subtotal,2,'.',',') ?></td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<th>IVA</th>
					<td><?php echo '$ '.number_format(($subtotal * 0.16),2,'.',',') ?></td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<th>Total</th>
					<td><?php echo '$ '.number_format(($subtotal * 1.16),2,'.',',') ?></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>