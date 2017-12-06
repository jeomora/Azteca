<div id="wrapper">
	<div id="page-wrapper" class="gray-bg">
		<div class="row border-bottom white-bg">
		<nav class="navbar navbar-static-top" role="navigation">
			<div class="navbar-header">
				<button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
					<i class="fa fa-reorder"></i>
				</button>
				<a href="#" class="navbar-brand"><?php echo strtoupper($usuario->username) ?></a>
			</div>
			<div class="navbar-collapse collapse" id="navbar">
				<ul class="nav navbar-nav">
					<li class="active">
						<a aria-expanded="false" role="button" href="#">BIENVENIDO</a>
					</li>
					<?php if ($main_menu): ?>
						<?php foreach ($main_menu as $key => $value): ?>
							
						<?php endforeach ?>
					<?php endif ?>
					<li class="dropdown">
						<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> REGISTROS <span class="caret"></span></a>
						<ul role="menu" class="dropdown-menu">
							<li><a href="<?php echo site_url('Auth/create_user') ?>">Usuarios</a></li>
							<li><a href="<?php echo site_url('Productos_control/productos_view') ?>">Articulos</a></li>
							<li><a href="<?php echo site_url('Familias_control/familias_view') ?>">Familias</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">REPORTES <span class="caret"></span></a>
						<ul role="menu" class="dropdown-menu">
							<li><a href="#">Reporte 1</a></li>
							<li><a href="#">Reporte 2</a></li>
							<li><a href="#">Reporte 3</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-top-links navbar-right">
					<li>
						<a href="<?php echo site_url('Auth/logout') ?>">
							<i class="fa fa-sign-out"></i> Salir
						</a>
					</li>
				</ul>
			</div>
		</nav>
		</div>