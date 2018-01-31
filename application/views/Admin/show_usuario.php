<div class="ibox-content">
	<div class="row">
		<table class="table" width="100%" border="5">
			<tr>
				<th>NOMBRE</th> 
				<td><?php echo $usuario->nombre ?></td>
				<th>APELLIDO</th>
				<td><?php echo $usuario->apellido ?></td>
			</tr>
			<tr>
				<th>TELÉFONO</th>
				<td><?php echo $usuario->telefono ?></td>
				<th>CORREO</th>
				<td><?php echo $usuario->email ?></td>
			</tr>
			<tr>
				<th>CONTRASEÑA</th>
				<td><?php echo $password ?></td>
				<th>TIPO</th>
				<td><?php echo $grupo->nombre ?></td>
			</tr>
		</table>
	</div>
</div>