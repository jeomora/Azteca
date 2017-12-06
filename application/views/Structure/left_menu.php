<body class="top-navigation">
	<div id="wrapper">
		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav" id="side-menu">
					<li class="nav-header">
						<div class="dropdown profile-element"> <span>
							<img alt="image" style="height: 170px; width: 150px;" class="img-circle" src="<?php echo base_url('/assets/img/avatar-5.jpg') ?>" />
							 </span>
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo strtoupper($usuario->username) ?></strong>
							 </span> <span class="text-muted text-xs block"><?php echo strtoupper($usuario->company) ?> <b class="caret"></b></span> </span> </a>
							<ul class="dropdown-menu animated fadeInRight m-t-xs">
								<li><a href="#"><?php echo $usuario->email ?></a></li>
								<li><a href="#"><?php echo $usuario->phone ?></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo site_url('Auth/logout') ?>">Salir</a></li>
							</ul>
						</div>
						<div class="logo-element">
							IN+
						</div>
					</li>

					<?php if ($main_menu): ?>
						<?php foreach ($main_menu as $key => $value): ?>
							<?php if ($value->nivel == 1): ?>
								<li class="active">
								<?php if ($value->ruta != ''): ?>
									<a href="<?php echo site_url($value->ruta); ?>">
										<i class="<?php echo $value->icono ?>"></i>
										<span class="nav-label"><?php echo strtoupper($value->nombre) ?></span>
									</a>
								<?php else: ?>
									<a href="#">
										<i class="<?php echo $value->icono ?>"></i>
										<span class="nav-label"><?php echo strtoupper($value->nombre) ?></span>
										<span class="fa arrow"></span>
									</a>
								<?php endif ?>
								</li>
							<?php endif ?>
						<?php endforeach ?>
					<?php endif ?>
				</ul>
			</div>
		</nav>
