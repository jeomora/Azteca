<div class="ibox-content">
	<div class="row">
		<table class="table" width="100%" border="5">
			<tr>
				<th>EMPRESA</th> 
				<td><?php echo $usuario->nombre ?></td>
				<th>NOMBRE COMPLETO</th>
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
				<td><?php echo $usuario->estatus == 1 ? "MASTER ADMIN": "USUARIO FINAL" ?></td>
			</tr>
		</table>
	</div>
</div>