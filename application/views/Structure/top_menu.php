<div id="wrapper">
	<div id="page-wrapper" class="blue-bg">
		<div class="row border-bottom white-bg">
			<nav class="navbar navbar-static-top" role="navigation">
				<!--
				<div class="navbar-header">
					<img alt="image" width="50px;" style="margin:auto; padding: 5px;" class="img-responsive" src="<?php echo base_url('/assets/img/avatar-3.jpg'); ?>" />
				</div>
				-->
				<div class="navbar-header">
					<button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
						<i class="fa fa-reorder"></i>
					</button>
					<a href="#" class="navbar-brand"><?php echo strtoupper($usuario->username) ?></a>
				</div>
				<div class="navbar-collapse collapse" id="navbar">
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="#" style="color: #F7AC59; font-weight: bolder">BIENVENIDO</a>
						</li>
					
					<?php if ($this->ion_auth->is_admin()): ?><!--Solo los Administradores pueden ver -->
						<?php if ($main_menu):?>
							<?php foreach ($main_menu as $key => $value): ?>
								<?php if ($value->nivel == 1): ?>
									<li class="dropdown">
										<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo strtoupper($value->nombre) ?> <span class="caret"></span></a>
										<?php if (isset($value->submenu) && count($value->submenu) > 0 ): ?>
											<ul role="menu" class="dropdown-menu">
											<?php foreach ($value->submenu as $key => $val): ?>
												<li><a href="<?php echo site_url($val->ruta2) ?>"><?php echo $val->nombre2 ?></a></li>
											<?php endforeach ?>
											</ul>
										<?php endif ?>
									</li>
								<?php endif ?>
							<?php endforeach ?>
						<?php endif ?>

					</ul>
					<?php else: ?> <!--Solo Usuario proveedor -->
						<li class="dropdown">
							<a href="<?php echo site_url('Cotizaciones') ?>" >COTIZACIONES </a>
						</li>
						<li class="dropdown">
							<a href="#">ARTICULOS COTIZADOS: <?php echo empty($cotizaciones) ? 0 : sizeof($cotizaciones) ?> </a>
						</li>
					</ul>

					<?php endif ?>
					<ul class="nav navbar-top-links navbar-right">
						<li>
							<a href="<?php echo site_url('Auth/logout'); ?>" class="close-session">
								<i class="fa fa-sign-out"></i> Salir
							</a>
						</li>
					</ul>
				</div>
			</nav>
		</div>

		<div id="notifications">
			<!-- Para mostra las notificaciones -->
		</div>

		<div id="myProgress" style="display: none;">
			<!-- Para mostrar un progressbar  -->
			<div id="barra">10%</div>
		</div>

		<div id="main_container">
			<!-- Contenedor principal para cargar las vistas -->
		</div>
