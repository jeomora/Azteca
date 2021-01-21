<?php

?>
<!DOCTYPE html>

<html lang="es">

	<!-- begin::Head -->
	<head>

		<!--begin::Base Path (base relative path for assets of this page) -->
		<base href="<?php echo base_url('') ?>">

		<!--end::Base Path -->
		<meta charset="utf-8" />
		<title>Abarrotes Azteca | Dashboard</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<script src="../scripts/webfont.js" type="text/javascript"></script>
		<script src="./assets/vendors/webfont.js" type="text/javascript"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		</script>

		<!--end::Fonts -->

		<!--begin::Page Vendors Styles(used by this page) -->
		<link href="./assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Page Vendors Styles -->

		<!--begin:: Global Mandatory Vendors -->
		<link href="./assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css" />

		<!--end:: Global Mandatory Vendors -->

		<!--begin:: Global Optional Vendors -->
		<!--<link href="./assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />-->
		<!--<link href="./assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />-->
		<link href="./assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
		<!--<link href="./assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css" />-->
		<link href="./assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css" />
		<!--<link href="./assets/vendors/general/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet" type="text/css" />-->
		<!--<link href="./assets/vendors/general/nouislider/distribute/nouislider.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/owl.carousel/dist/assets/owl.carousel.css" rel="stylesheet" type="text/css" />-->
		<link href="./assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css" />
		<!--<link href="./assets/vendors/general/animate.css/animate.css" rel="stylesheet" type="text/css" />-->
		<link href="./assets/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />

		<link href="./assets/vendors/custom/vendors/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />

		<!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="./assets/css/demo4/style.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="./assets/img/progra.ico" />
		<script type="text/javascript">
			var base_url = "<?php echo base_url('') ?>";//No carga el archivo index
			var site_url = "<?php echo site_url('') ?>";//Si carga el index 
			var user_name = "<?php echo "Usuario" ?>";////strtoupper($usuario['nombre'])
		</script>

		<!-- Favicon-->
		<link rel="shortcut icon" href="<?php echo base_url('/assets/img/progra.ico') ?>" >

		<?php if (isset($links) && $links): ?>
			<?php foreach ($links as $link): ?>
				<link rel="stylesheet" href="<?php echo base_url($link.'.css') ?>">
			<?php endforeach ?>
		<?php endif ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122943105-2"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-122943105-2');
		</script>

	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body style="background-image: url(./assets/media/demos/demo4/header.jpg); background-position: center top; background-size: 100% 350px;" class="kt-page--loading-enabled kt-page--loading kt-page--fixed kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header--minimize-menu kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-page--loading">

		<!-- begin::Page loader -->

		<!-- end::Page Loader -->

		<!-- begin:: Page -->

		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="http:/abarrotesazteca.com/Main">
					<img alt="Logo" src="./assets/img/abarrotes.png" style="max-width: 70px" />
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more-1"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->
		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

					<!-- begin:: Header -->
					<div id="kt_header" class="kt-header  kt-header--fixed " data-ktheader-minimize="on">
						<div class="kt-container">

							<!-- begin:: Brand -->
							<div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
								<a class="kt-header__brand-logo" href="http:/abarrotesazteca.com/Main">
									<img alt="Logo" src="./assets/img/abarrotes.png" class="kt-header__brand-logo-default" style="max-width:80px" />
									<img alt="Logo" src="./assets/img/logo_abarrotes.png" class="kt-header__brand-logo-sticky" style="max-width:80px" />
								</a>
							</div>

							<!-- end:: Brand -->
							<?php if($this->session->userdata("id_grupo") <> 2 && $this->session->userdata("id_grupo") <> 3):?>
							<!-- begin: Header Menu -->
							<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
							<div class="kt-header-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_header_menu_wrapper">
								<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile ">
									<ul class="kt-menu__nav ">
										<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" ><a href="Abarrotes/admin" class="kt-menu__link"><span class="kt-menu__link-text">Abarrotes</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
										</li>

									</ul>
								</div>
							</div>

							<!-- end: Header Menu -->
							<?php endif?>
							<!-- begin:: Header Topbar -->
							<div class="kt-header__topbar kt-grid__item">
								<?php if($this->session->userdata("id_grupo") <> 2  && $this->session->userdata("id_grupo") <> 3):?>
								<!--begin: Search 
								<div class="kt-header__topbar-item kt-header__topbar-item--search dropdown" id="kt_quick_search_toggle">
									<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
										<span class="kt-header__topbar-icon">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect id="bound" x="0" y="0" width="24" height="24" />
													<path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" id="Path-2" fill="#000000" fill-rule="nonzero" opacity="0.3" />
													<path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" id="Path" fill="#000000" fill-rule="nonzero" />
												</g>
											</svg>

											
										</span>
									</div>
									<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-lg">
										<div class="kt-quick-search kt-quick-search--inline" id="kt_quick_search_inline">
											<form method="get" class="kt-quick-search__form">
												<div class="input-group">
													<div class="input-group-prepend"><span class="input-group-text"><i class="flaticon2-search-1"></i></span></div>
													<input type="text" class="form-control kt-quick-search__input" placeholder="Buscar...">
													<div class="input-group-append"><span class="input-group-text"><i class="la la-close kt-quick-search__close"></i></span></div>
												</div>
											</form>
											<div class="kt-quick-search__wrapper kt-scroll" data-scroll="true" data-height="300" data-mobile-height="200">
											</div>
										</div>
									</div>
								</div>

								end: Search -->

								<!--begin: Notifications -->
								<div class="kt-header__topbar-item dropdown">
									<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
										<span class="kt-header__topbar-icon kt-pulse kt-pulse--light">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect id="bound" x="0" y="0" width="24" height="24" />
													<path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" id="Combined-Shape" fill="#000000" opacity="0.3" />
													<path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" id="Combined-Shape" fill="#000000" />
												</g>
											</svg>

											<!--<i class="flaticon2-bell-alarm-symbol"></i>-->
											<span class="kt-pulse__ring"></span>
										</span>

										<!--<span class="kt-badge kt-badge--light"></span>-->
									</div>
									<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">
										<form>

											<!--begin: Head -->
											<div class="kt-head kt-head--skin-dark kt-head--fit-x kt-head--fit-b" style="background-image: url(./assets/media/misc/bg-1.jpg)">
												<h3 class="kt-head__title">
													- Sin cotizar(Proveedores/productos) <br>
													- Autorizar Cotizaciones <br>
													- Existencias Sucursales <br>
													&nbsp;
												</h3>
												<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-success kt-notification-item-padding-x" role="tablist">
													<li class="nav-item">
														<a class="nav-link active show" data-toggle="tab" href="#topbar_notifications_notifications" role="tab" aria-selected="true">Proveedores</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-toggle="tab" href="#topbar_notifications_logs" role="tab" aria-selected="false">Productos</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-toggle="tab" href="#topbar_notifications_events" role="tab" aria-selected="false">Autorizar</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-toggle="tab" href="#topbar_notifications_logs" role="tab" aria-selected="false">Exitencias</a>
													</li>
												</ul>
											</div>

											<!--end: Head -->
											<div class="tab-content">
												<div class="tab-pane active show" id="topbar_notifications_notifications" role="tabpanel">
													<div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll" data-scroll="true" data-height="300" data-mobile-height="200">
														<?php if($cambiost):foreach ($cambiost as $key => $value):?>
															<a href="<?php echo base_url($value->despues); ?>" target="_blank" class="kt-notification__item">
																<div class="kt-notification__item-icon">
																	<i class="flaticon2-download kt-font-danger"></i>
																</div>
																<div class="kt-notification__item-details">
																	<div class="kt-notification__item-title">
																		<?php echo $value->nombre." (Clic descarga el archivo)"; ?>
																	</div>
																	<div class="kt-notification__item-time">
																		<?php echo $value->antes; ?>
																	</div>
																</div>
															</a>
														<?php endforeach;endif; ?>
													</div>
												</div>
												<div class="tab-pane" id="topbar_notifications_events" role="tabpanel">
													<div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll" data-scroll="true" data-height="300" data-mobile-height="200">
														<?php if($cambiosp):foreach ($cambiosp as $key => $value):?>
															<a href="<?php echo base_url($value->despues); ?>" target="_blank" class="kt-notification__item">
																<div class="kt-notification__item-icon">
																	<i class="flaticon2-download kt-font-warning"></i>
																</div>
																<div class="kt-notification__item-details">
																	<div class="kt-notification__item-title">
																		<?php echo $value->nombre." (Clic descarga el archivo)"; ?>
																	</div>
																	<div class="kt-notification__item-time">
																		<?php echo $value->antes; ?>
																	</div>
																</div>
															</a>
														<?php endforeach;endif; ?>
													</div>
												</div>
												<div class="tab-pane" id="topbar_notifications_logs" role="tabpanel">
													<div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll" data-scroll="true" data-height="300" data-mobile-height="200">
														<?php if($cambiosc):foreach ($cambiosc as $key => $value):?>
															<a href="<?php echo base_url($value->despues); ?>" target="_blank" class="kt-notification__item">
																<div class="kt-notification__item-icon">
																	<i class="flaticon2-download kt-font-success"></i>
																</div>
																<div class="kt-notification__item-details">
																	<div class="kt-notification__item-title">
																		<?php if($value->accion === "Sube archivo"):echo $value->nombre." (Clic descarga el archivo)";else: echo $value->nombre;endif; ?>
																	</div>
																	<div class="kt-notification__item-time">
																		<?php echo $value->antes; ?>
																		<?php if($value->accion === "Sube archivo"):echo $value->antes." (Clic descarga el archivo)";else: echo $value->despues;endif; ?>
																	</div>
																</div>
															</a>
														<?php endforeach;endif; ?>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>

								<!--end: Notifications -->
								<?php endif ?>

								<!--begin: User bar -->
								<div class="kt-header__topbar-item kt-header__topbar-item--user">
									<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
										<span class="kt-header__topbar-welcome">HOLA,</span>
										<span class="kt-header__topbar-username"><?php echo strtoupper($usuario['nombre']) ?></span>
										<span class="kt-header__topbar-icon"><b><?php echo strtoupper($usuario['nombre'][0]) ?></b></span>
									</div>
									<?php if($this->session->userdata("id_grupo") <> 2 && $this->session->userdata("id_grupo") <> 3):?>
									<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">

										<!--begin: Head -->
										<div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(./assets/media/misc/bg-1.jpg)">
											<div class="kt-user-card__avatar">
												<img class="kt-hidden" alt="Pic" src="./assets/media/users/300_25.jpg" />

												<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
												<span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success"><?php echo strtoupper($usuario['nombre'][0]) ?></span>
											</div>
											<div class="kt-user-card__name">
												<?php echo strtoupper($usuario['nombre']) ?>
											</div>
										</div>

										<!--end: Head -->

										<!--begin: Navigation -->
										<div class="kt-notification">
											<a class="kt-notification__item"  data-toggle='modal' data-target='#kt_modal_90'>
												<div class="kt-notification__item-icon">
													<i class="flaticon2-lock kt-font-success"></i>
												</div>
												<div class="kt-notification__item-details">
													<div class="kt-notification__item-title kt-font-bold">
														Seguridad
													</div>
													<div class="kt-notification__item-time">
														Cambiar Contraseña
													</div>
												</div>
											</a>
											<a class="kt-notification__item">
												<div class="kt-notification__item-icon">
													<i class="flaticon2-mail kt-font-warning"></i>
												</div>
												<div class="kt-notification__item-details">
													<div class="kt-notification__item-title kt-font-bold">
														Mensajes
													</div>
													<div class="kt-notification__item-time">
														Prox.
													</div>
												</div>
											</a>
											<a class="kt-notification__item">
												<div class="kt-notification__item-icon">
													<i class="flaticon2-time kt-font-danger"></i>
												</div>
												<div class="kt-notification__item-details">
													<div class="kt-notification__item-title kt-font-bold">
														Mis Actividades
													</div>
													<div class="kt-notification__item-time">
														Prox
													</div>
												</div>
											</a>
											<a class="kt-notification__item">
												<div class="kt-notification__item-icon">
													<i class="flaticon2-rocket-1 kt-font-brand"></i>
												</div>
												<div class="kt-notification__item-details">
													<div class="kt-notification__item-title kt-font-bold">
														Mis Tareas
													</div>
													<div class="kt-notification__item-time">
														Prox
													</div>
												</div>
											</a>
											<a class="kt-notification__item">
												<div class="kt-notification__item-icon">
													<i class="flaticon2-analytics-2 kt-font-warning"></i>
												</div>
												<div class="kt-notification__item-details">
													<div class="kt-notification__item-title kt-font-bold">
														Facturas
													</div>
													<div class="kt-notification__item-time">
														Prox
													</div>
												</div>
											</a>
											<div class="kt-notification__custom kt-space-between">
												<a href="<?php echo base_url('Inicio/Logout')?>" class="btn btn-clean btn-sm btn-bold">Cerrar Sesión</a>
											</div>
										</div>

										<!--end: Navigation -->
									</div>
									<?php else: ?>
									<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">


										<!--begin: Navigation -->
										<div class="kt-notification">
											<div class="kt-notification__custom kt-space-between">
												<a href="Inicio/Logout" class="btn btn-clean btn-sm btn-bold">Cerrar Sesión</a>
											</div>
										</div>

										<!--end: Navigation -->
									</div>
									<?php endif ?>
								</div>

								<!--end: User bar -->
							</div>

							<!-- end:: Header Topbar -->
						</div>
					</div>

					<!-- end:: Header -->
					<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
						<div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
							<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

								
