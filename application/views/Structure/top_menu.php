<style type="text/css" media="screen">
	.logo_img > img {
		max-width: 125%;
	    position: absolute;
	    margin-top: 0rem;
	    background-color: #FFF;
	    border-radius: 9rem;
	    height: 9rem;
	    width: 9rem;
	    border: 4px solid #827f7f;
	}
	.navbar-header {display: inline-flex;}
	.cambcontra{padding-top: 15px;
    background-color: #FF6805;
    border: 0;
    font-size: 13px;
    font-family: "open sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-weight: 600;}
    @media only screen and (max-width: 600px){
    	.logo_img > img {
		    border-radius: 6rem;
		    height: 6rem;
		    width: 6rem;
		}
    }
</style>
<div id="wrapper">
	<div id="page-wrapper" class="blue-bg" style="overflow-y:scroll">
		<div class="row border-bottom white-bg">
			<nav class="navbar navbar-static-top" role="navigation">
				<div class="navbar-header">
					<div class="logo_img">
						<?php if ($usuario['id_grupo'] == 2): ?>
							<a href="#" class="logo_img"><img  src="<?php echo base_url('/assets/img/abarrotes.png') ?>" /></a>
						<?php else: ?>
							<a href="/Main/" class="logo_img"><img  src="<?php echo base_url('/assets/img/abarrotes.png') ?>" /></a>
						<?php endif ?>
					</div>
					<button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
						<i class="fa fa-reorder"></i>
					</button>
					
					<?php if ($usuario['id_grupo'] == 2): ?>
						<a href="<?php echo base_url('/Main') ?>" class="navbar-brand" style="padding-left: 12rem !important;"><?php echo strtoupper($usuario['username']) ?></a>
					<?php else: ?>
						<a href="<?php echo base_url('/Main') ?>" class="navbar-brand" style="padding-left: 12rem !important;"><?php echo strtoupper($usuario['username']) ?></a>
					<?php endif ?>
				</div>
				<div class="navbar-collapse collapse" id="navbar">
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="#" id="progress" style="color: #F7AC59; font-weight: bolder">BIENVENIDA(O)</a>
						</li>
					<?php if ($usuario['id_grupo'] == 1 || $usuario['id_grupo'] == 4): ?><!--El grupo 1 es Administrador -->
						<?php if ($main_menu):?>
							<?php foreach ($main_menu as $key => $value): ?>
								<?php if ($value->nivel == 1): ?>
									<li class="dropdown">
										<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo strtoupper($value->nombre) ?> <span class="caret"></span></a>
										<?php if (isset($value->submenu) && count($value->submenu) > 0 ): ?>
											<ul role="menu" class="dropdown-menu">
											<?php foreach ($value->submenu as $key => $val): ?>
												<li class="gotocot"><a href="<?php echo site_url($val->ruta2) ?>"><?php echo $val->nombre2 ?></a></li>
											<?php endforeach ?>
											</ul>
										<?php endif ?>
									</li>
								<?php endif ?>
							<?php endforeach ?>
						<?php endif ?>

					</ul>
					<?php elseif($usuario['id_grupo'] == 2): ?> <!--El grupo 2 son Proveedores -->
						<li class="dropdown">
							<a href="<?php echo site_url('Cotizaciones') ?>" >COTIZACIONES </a>
						</li>
						<li class="dropdown">
							<a href="#">ARTICULOS COTIZADOS: <?php echo empty($cotizaciones) ? 0 : sizeof($cotizaciones) ?> </a>
						</li>
						<button id="cambcontra" class="btn btn-success cambcontra" data-toggle="tooltip" title="Cambiar Contraseña" data-id-usuario="<?php echo $usuario['id_usuario'] ?>">
							<i class="fa fa-key"></i>  CAMBIAR CONTRASEÑA
						</button>
					</ul>

					<?php elseif($usuario['id_grupo'] == 3): ?> <!--El grupo 3 son Sucursales -->
						<li class="dropdown">
							<a href="<?php echo site_url('Pedidos') ?>" >PEDIDOS </a>
						</li>
						<li class="dropdown">
							<?php if ($usuario['id_grupo'] == 3): ?>

							<?php else: ?>
								<a href="#">ARTICULOS COTIZADOS: <?php echo empty($cotizaciones) ? 0 : sizeof($cotizaciones) ?> </a>
							<?php endif ?>
						</li>
						
					</ul>
					<?php endif ?>
					<ul class="nav navbar-top-links navbar-right">
						<?php if ($usuario['id_grupo'] == 1 || $usuario['id_grupo'] == 4): ?>
						<li>
							<h5 style="color:#FFF">Hora limite lunes</h5>
							<input class="form-control" id="kt_timepicker_2" placeholder="Seleccionar hora" type="text" value="<?php echo $horario->hora_limite ?>" style="width:200px;font-family:monospace;font-size:18px;font-weight:bold" />
						</li>
						<?php endif ?>
						<li>
							<a href="<?php echo site_url('Compras/logout'); ?>" class="close-session">
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
