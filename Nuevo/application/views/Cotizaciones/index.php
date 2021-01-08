<style>
	.dz-message.drop1{margin:0 !important}
</style>
<!--begin::Content-->
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
	<!--begin::Subheader-->
	<div class="subheader py-5 py-lg-10 gutter-b  subheader-transparent " id="kt_subheader" style="background-color: #663259; background-position: right bottom; background-size: auto 100%; background-repeat: no-repeat; background-image: url(assets/media/svg/illustrations/cotiz.svg)">
		<div class=" container  d-flex flex-column">
			<!--begin::Title-->
			<div class="d-flex align-items-sm-end flex-column flex-sm-row mb-5">
				<h2 class="text-white mr-5 mb-0">Cotización De La Semana</h2>
				<span class="text-white opacity-60 font-weight-bold"></span>
			</div>
			<!--end::Title-->
			<!--begin::Search Bar-->
			<div class="d-flex align-items-md-center mb-2 flex-column flex-md-row">
				<div class="bg-white rounded p-4 d-flex flex-grow-1 flex-sm-grow-0">
					<!--begin::Form-->
					<div class="form d-flex align-items-md-center flex-sm-row flex-column flex-grow-1 flex-sm-grow-0">
						<!--begin::Input-->
						<div class="d-flex align-items-center py-3 py-sm-0 px-sm-3">
							<span class="svg-icon svg-icon-lg">
								<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								        <rect x="0" y="0" width="24" height="24"/>
								        <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
								        <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"/>
								    </g>
								</svg>
								<!--end::Svg Icon-->
							</span>
							<input type="text" class="form-control border-0 font-weight-bold pl-2" placeholder="Buscar producto" id="productSearch"/>
						</div>
						<!--end::Input-->

						<!--begin::Input-->
						<span class="bullet bullet-ver h-25px d-none d-sm-flex mr-2"></span>
						<div class="d-flex align-items-center py-3 py-sm-0 px-sm-3">
							<button type="button" class="btn btn-light-success font-weight-bold mt-3 mt-sm-0 px-7"  data-toggle="modal" data-target="#kt_agregar_prod">
								<span class="svg-icon svg-icon-lg">
									<!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									        <rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"/>
									        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/>
									    </g>
									</svg>
									<!--end::Svg Icon-->
									Agregar producto
								</span>
							</button>
						</div>
						<!--end::Input-->

						<!--begin::Input-->
						<span class="bullet bullet-ver h-25px d-none d-sm-flex mr-2"></span>
						<div class="d-flex align-items-center py-3 py-sm-0 px-sm-3">
							<?php echo form_open("Productos/print_productos", array("id" => 'reporte_cotizaciones', "target" => '_blank')); ?>
							<button type="submit" class="btn btn-light-warning font-weight-bold mt-3 mt-sm-0 px-7">
								<span class="svg-icon svg-icon-lg">
									<!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									        <polygon points="0 0 24 0 24 24 0 24"/>
									        <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
									        <path d="M14.8875071,11.8306874 L12.9310336,11.8306874 L12.9310336,9.82301606 C12.9310336,9.54687369 12.707176,9.32301606 12.4310336,9.32301606 L11.4077349,9.32301606 C11.1315925,9.32301606 10.9077349,9.54687369 10.9077349,9.82301606 L10.9077349,11.8306874 L8.9512614,11.8306874 C8.67511903,11.8306874 8.4512614,12.054545 8.4512614,12.3306874 C8.4512614,12.448999 8.49321518,12.5634776 8.56966458,12.6537723 L11.5377874,16.1594334 C11.7162223,16.3701835 12.0317191,16.3963802 12.2424692,16.2179453 C12.2635563,16.2000915 12.2831273,16.1805206 12.3009811,16.1594334 L15.2691039,12.6537723 C15.4475388,12.4430222 15.4213421,12.1275254 15.210592,11.9490905 C15.1202973,11.8726411 15.0058187,11.8306874 14.8875071,11.8306874 Z" fill="#000000"/>
									    </g>
									</svg>
									<!--end::Svg Icon-->
									Descargar formato
								</span>
							</button>
							<?php echo form_close(); ?>
						</div>
						<!--end::Input-->

						<!--begin::Input-->
						<span class="bullet bullet-ver h-25px d-none d-sm-flex mr-2"></span>
						<div class="d-flex align-items-center py-3 py-sm-0 px-sm-3">
							<button type="button" class="btn btn-light-dark font-weight-bold" style="padding:0">
								<div id="my-dropzoneProd" class="dropzone btn btn-light-dark font-weight-bold" style="background:transparent;">
	                                <div class="dz-message drop1" data-dz-message>
	                                    <span class="svg-icon svg-icon-lg">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											        <polygon points="0 0 24 0 24 24 0 24"/>
											        <path d="M5.74714567,13.0425758 C4.09410362,11.9740356 3,10.1147886 3,8 C3,4.6862915 5.6862915,2 9,2 C11.7957591,2 14.1449096,3.91215918 14.8109738,6.5 L17.25,6.5 C19.3210678,6.5 21,8.17893219 21,10.25 C21,12.3210678 19.3210678,14 17.25,14 L8.25,14 C7.28817895,14 6.41093178,13.6378962 5.74714567,13.0425758 Z" fill="#000000" opacity="0.3"/>
											        <path d="M11.1288761,15.7336977 L11.1288761,17.6901712 L9.12120481,17.6901712 C8.84506244,17.6901712 8.62120481,17.9140288 8.62120481,18.1901712 L8.62120481,19.2134699 C8.62120481,19.4896123 8.84506244,19.7134699 9.12120481,19.7134699 L11.1288761,19.7134699 L11.1288761,21.6699434 C11.1288761,21.9460858 11.3527337,22.1699434 11.6288761,22.1699434 C11.7471877,22.1699434 11.8616664,22.1279896 11.951961,22.0515402 L15.4576222,19.0834174 C15.6683723,18.9049825 15.6945689,18.5894857 15.5161341,18.3787356 C15.4982803,18.3576485 15.4787093,18.3380775 15.4576222,18.3202237 L11.951961,15.3521009 C11.7412109,15.173666 11.4257142,15.1998627 11.2472793,15.4106128 C11.1708299,15.5009075 11.1288761,15.6153861 11.1288761,15.7336977 Z" fill="#000000" fill-rule="nonzero" transform="translate(11.959697, 18.661508) rotate(-90.000000) translate(-11.959697, -18.661508) "/>
											    </g>
											</svg>
											<!--end::Svg Icon-->
											Subir varios productos
										</span>
	                                </div>
	                            </div>
							</button>
						</div>
						<!--end::Input-->
					</div>
					<!--end::Form-->
				</div>
			</div>
			<!--end::Search Bar-->
		</div>
	</div>
	<!--end::Subheader-->

	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid">
		<!--begin::Container-->
		<div class=" container ">
			<!--begin::Dashboard-->

			<!--begin::Card-->
			<div class="card card-custom card-transparent">
				<div class="card-body p-0">
					<!--begin::Wizard-->
					<div class="wizard wizard-4" id="kt_wizard" data-wizard-state="step-first" data-wizard-clickable="true">
						<!--begin::Wizard Nav-->
						<div class="wizard-nav">
							<div class="wizard-steps">
								<div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
									<div class="wizard-wrapper">
										<div class="wizard-number">1</div>
										<div class="wizard-label">
											<div class="wizard-title">Productos</div>
											<div class="wizard-desc"></div>
										</div>
									</div>
								</div>
								<div class="wizard-step" data-wizard-type="step">
									<div class="wizard-wrapper">
										<div class="wizard-number">2</div>
										<div class="wizard-label">
											<div class="wizard-title">Faltantes</div>
											<div class="wizard-desc"></div>
										</div>
									</div>
								</div>
								<div class="wizard-step" data-wizard-type="step">
									<div class="wizard-wrapper">
										<div class="wizard-number">3</div>
										<div class="wizard-label">
											<div class="wizard-title">Pendientes</div>
											<div class="wizard-desc"></div>
										</div>
									</div>
								</div>
								<div class="wizard-step" data-wizard-type="step">
									<div class="wizard-wrapper">
										<div class="wizard-number">4</div>
										<div class="wizard-label">
											<div class="wizard-title">Precios Sistema</div>
											<div class="wizard-desc"></div>
										</div>
									</div>
								</div>
								<div class="wizard-step" data-wizard-type="step">
									<div class="wizard-wrapper">
										<div class="wizard-number">5</div>
										<div class="wizard-label">
											<div class="wizard-title">Cotización Proveedor</div>
											<div class="wizard-desc"></div>
										</div>
									</div>
								</div>
								<div class="wizard-step" data-wizard-type="step">
									<div class="wizard-wrapper">
										<div class="wizard-number">6</div>
										<div class="wizard-label">
											<div class="wizard-title">Autorizar Cotización</div>
											<div class="wizard-desc"></div>
										</div>
									</div>
								</div>
								<div class="wizard-step" data-wizard-type="step">
									<div class="wizard-wrapper">
										<div class="wizard-number">7</div>
										<div class="wizard-label">
											<div class="wizard-title">Diferencias</div>
											<div class="wizard-desc"></div>
										</div>
									</div>
								</div>
								<div class="wizard-step" data-wizard-type="step">
									<div class="wizard-wrapper">
										<div class="wizard-number">8</div>
										<div class="wizard-label">
											<div class="wizard-title">Cotizacion General</div>
											<div class="wizard-desc"></div>
										</div>
									</div>
								</div>
								<div class="wizard-step" data-wizard-type="step">
									<div class="wizard-wrapper">
										<div class="wizard-number">9</div>
										<div class="wizard-label">
											<div class="wizard-title">Existencias Sucursales</div>
											<div class="wizard-desc"></div>
										</div>
									</div>
								</div>
								<div class="wizard-step" data-wizard-type="step">
									<div class="wizard-wrapper">
										<div class="wizard-number">10</div>
										<div class="wizard-label">
											<div class="wizard-title">Formatos</div>
											<div class="wizard-desc"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--end::Wizard Nav-->
						<!--begin::Card-->
						<div class="card card-custom card-shadowless rounded-top-0">
							<!--begin::Body-->
							<div class="card-body p-0">
								<div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
									<div class="col-xl-12 col-xxl-10">
										<!--begin::Wizard Form-->
										<form class="form" id="kt_form">
											<div class="row justify-content-center">
												<div class="col-xl-9">
													<!--begin::Wizard Step 1-->
													<div class="my-5 step" data-wizard-type="step-content" data-wizard-state="current">
														<h5 class="text-dark font-weight-bold mb-10">User's Profile Details:</h5>
														<!--begin::Group-->
														<div class="form-group row">
															<label class="col-xl-3 col-lg-3 col-form-label text-left">Avatar</label>
															<div class="col-lg-9 col-xl-9">
																<div class="image-input image-input-outline" id="kt_user_add_avatar">
																	<div class="image-input-wrapper" style="background-image: url(assets/media/users/100_6.jpg)"></div>
																	<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
																		<i class="fa fa-pen icon-sm text-muted"></i>
																		<input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg" />
																		<input type="hidden" name="profile_avatar_remove" />
																	</label>
																	<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
																		<i class="ki ki-bold-close icon-xs text-muted"></i>
																	</span>
																</div>
															</div>
														</div>
														<!--end::Group-->
														<!--begin::Group-->
														<div class="form-group row">
															<label class="col-xl-3 col-lg-3 col-form-label">First Name</label>
															<div class="col-lg-9 col-xl-9">
																<input class="form-control form-control-solid form-control-lg" name="firstname" type="text" value="" />
															</div>
														</div>
														<!--end::Group-->
														<!--begin::Group-->
														<div class="form-group row">
															<label class="col-xl-3 col-lg-3 col-form-label">Last Name</label>
															<div class="col-lg-9 col-xl-9">
																<input class="form-control form-control-solid form-control-lg" name="lastname" type="text" value="" />
															</div>
														</div>
														<!--end::Group-->
														<!--begin::Group-->
														<div class="form-group row">
															<label class="col-xl-3 col-lg-3 col-form-label">Company Name</label>
															<div class="col-lg-9 col-xl-9">
																<input class="form-control form-control-solid form-control-lg" name="companyname" type="text" value="Loop Inc." />
																<span class="form-text text-muted">If you want your invoices addressed to a company. Leave blank to use your full name.</span>
															</div>
														</div>
														<!--end::Group-->
														<!--begin::Group-->
														<div class="form-group row">
															<label class="col-xl-3 col-lg-3 col-form-label">Contact Phone</label>
															<div class="col-lg-9 col-xl-9">
																<div class="input-group input-group-solid input-group-lg">
																	<div class="input-group-prepend">
																		<span class="input-group-text">
																			<i class="la la-phone"></i>
																		</span>
																	</div>
																	<input type="text" class="form-control form-control-solid form-control-lg" name="phone" value="5678967456" placeholder="Phone" />
																</div>
																<span class="form-text text-muted">Enter valid US phone number(e.g: 5678967456).</span>
															</div>
														</div>
														<!--end::Group-->
														<!--begin::Group-->
														<div class="form-group row">
															<label class="col-xl-3 col-lg-3 col-form-label">Email Address</label>
															<div class="col-lg-9 col-xl-9">
																<div class="input-group input-group-solid input-group-lg">
																	<div class="input-group-prepend">
																		<span class="input-group-text">
																			<i class="la la-at"></i>
																		</span>
																	</div>
																	<input type="text" class="form-control form-control-solid form-control-lg" name="email" value="" />
																</div>
															</div>
														</div>
														<!--end::Group-->
														<!--begin::Group-->
														<div class="form-group row">
															<label class="col-xl-3 col-lg-3 col-form-label">Company Site</label>
															<div class="col-lg-9 col-xl-9">
																<div class="input-group input-group-solid input-group-lg">
																	<input type="text" class="form-control form-control-solid form-control-lg" name="companywebsite" placeholder="Username" value="loop" />
																	<div class="input-group-append">
																		<span class="input-group-text">.com</span>
																	</div>
																</div>
															</div>
														</div>
														<!--end::Group-->
													</div>
													<!--end::Wizard Step 1-->
													<!--begin::Wizard Step 2-->
													<div class="my-5 step" data-wizard-type="step-content">
														<h5 class="text-dark font-weight-bold mb-10 mt-5">User's Account Details</h5>
														<!--begin::Group-->
														<div class="form-group row">
															<label class="col-form-label col-xl-3 col-lg-3">Language</label>
															<div class="col-xl-9 col-lg-9">
																<select class="form-control form-control-lg form-control-solid" name="language">
																	<option value="">Select Language...</option>
																	<option value="id">Bahasa Indonesia - Indonesian</option>
																	<option value="msa">Bahasa Melayu - Malay</option>
																	<option value="ca">Català - Catalan</option>
																	<option value="cs">Čeština - Czech</option>
																</select>
															</div>
														</div>
														<!--end::Group-->
														<!--begin::Group-->
														<div class="form-group row">
															<label class="col-form-label col-xl-3 col-lg-3">Time Zone</label>
															<div class="col-xl-9 col-lg-9">
																<select class="form-control form-control-lg form-control-solid" name="timezone">
																	<option data-offset="-39600" value="International Date Line West">(GMT-11:00) International Date Line West</option>
																	<option data-offset="-39600" value="Midway Island">(GMT-11:00) Midway Island</option>
																	<option data-offset="-39600" value="Samoa">(GMT-11:00) Samoa</option>
																</select>
															</div>
														</div>
														<!--end::Group-->
														<!--begin::Group-->
														<div class="form-group row">
															<label class="col-form-label col-xl-3 col-lg-3">Communication</label>
															<div class="col-xl-9 col-lg-9 col-form-label">
																<div class="checkbox-inline">
																	<label class="checkbox">
																	<input name="communication" type="checkbox" />
																	<span></span>Email</label>
																	<label class="checkbox">
																	<input name="communication" type="checkbox" />
																	<span></span>SMS</label>
																	<label class="checkbox">
																	<input name="communication" type="checkbox" />
																	<span></span>Phone</label>
																</div>
															</div>
														</div>
														<!--end::Group-->
														<div class="separator separator-dashed my-10"></div>
														<h5 class="text-dark font-weight-bold mb-10">User's Account Settings</h5>
														<!--begin::Group-->
														<div class="form-group row">
															<label class="col-form-label col-xl-3 col-lg-3">Login verification</label>
															<div class="col-xl-9 col-lg-9">
																<button type="button" class="btn btn-light-primary font-weight-bold btn-sm">Setup login verification</button>
																<div class="form-text text-muted mt-3">After you log in, you will be asked for additional information to confirm your identity and protect your account from being compromised.
																<a href="#">Learn more</a>.</div>
															</div>
														</div>
														<!--end::Group-->
														<!--begin::Group-->
														<div class="form-group row">
															<label class="col-form-label col-xl-3 col-lg-3">Password reset verification</label>
															<div class="col-xl-9 col-lg-9">
																<div class="checkbox-inline">
																	<label class="checkbox mb-2">
																	<input type="checkbox" />
																	<span></span>Require personal information to reset your password.</label>
																</div>
																<div class="form-text text-muted">For extra security, this requires you to confirm your email or phone number when you reset your password.
																<a href="#" class="font-weight-bold">Learn more</a>.</div>
															</div>
														</div>
														<!--end::Group-->
														<!--begin::Group-->
														<div class="form-group row mt-10">
															<label class="col-xl-3 col-lg-3"></label>
															<div class="col-xl-9 col-lg-9">
																<button type="button" class="btn btn-light-danger font-weight-bold btn-sm">Deactivate your account ?</button>
															</div>
														</div>
														<!--end::Group-->
													</div>
													<!--end::Wizard Step 2-->
													<!--begin::Wizard Step 3-->
													<div class="my-5 step" data-wizard-type="step-content">
														<h5 class="mb-10 font-weight-bold text-dark">Setup Your Address</h5>
														<!--begin::Group-->
														<div class="form-group">
															<label>Address Line 1</label>
															<input type="text" class="form-control form-control-solid form-control-lg" name="address1" placeholder="Address Line 1" value="Address Line 1" />
															<span class="form-text text-muted">Please enter your Address.</span>
														</div>
														<!--end::Group-->
														<!--begin::Group-->
														<div class="form-group">
															<label>Address Line 2</label>
															<input type="text" class="form-control form-control-solid form-control-lg" name="address2" placeholder="Address Line 2" value="Address Line 2" />
															<span class="form-text text-muted">Please enter your Address.</span>
														</div>
														<!--begin::Row-->
														<div class="row">
															<div class="col-xl-6">
																<!--begin::Group-->
																<div class="form-group">
																	<label>Postcode</label>
																	<input type="text" class="form-control form-control-solid form-control-lg" name="postcode" placeholder="Postcode" value="3000" />
																	<span class="form-text text-muted">Please enter your Postcode.</span>
																</div>
															</div>
															<!--end::Group-->
															<!--begin::Group-->
															<div class="col-xl-6">
																<div class="form-group">
																	<label>City</label>
																	<input type="text" class="form-control form-control-solid form-control-lg" name="city" placeholder="City" value="Melbourne" />
																	<span class="form-text text-muted">Please enter your City.</span>
																</div>
															</div>
															<!--end::Group-->
														</div>
														<!--end::Row-->
														<!--begin::Row-->
														<div class="row">
															<div class="col-xl-6">
																<!--begin::Group-->
																<div class="form-group">
																	<label>State</label>
																	<input type="text" class="form-control form-control-solid form-control-lg" name="state" placeholder="State" value="VIC" />
																	<span class="form-text text-muted">Please enter your State.</span>
																</div>
																<!--end::Group-->
															</div>
															<div class="col-xl-6">
																<!--begin::Group-->
																<div class="form-group">
																	<label>Country</label>
																	<select name="country" class="form-control form-control-solid form-control-lg">
																		<option value="">Select</option>
																	</select>
																</div>
																<!--end::Group-->
															</div>
														</div>
													</div>
													<!--end::Wizard Step 3-->
													<!--begin::Wizard Step 4-->
													<div class="my-5 step" data-wizard-type="step-content">
														<h5 class="mb-10 font-weight-bold text-dark">Review your Details and Submit</h5>
														<!--begin::Item-->
														<div class="border-bottom mb-5 pb-5">
															<div class="font-weight-bolder mb-3">Your Account Details:</div>
															<div class="line-height-xl">John Wick
															<br />Phone: +61412345678
															<br />Email: johnwick@reeves.com</div>
														</div>
														<!--end::Item-->
														<!--begin::Item-->
														<div class="border-bottom mb-5 pb-5">
															<div class="font-weight-bolder mb-3">Your Address Details:</div>
															<div class="line-height-xl">Address Line 1
															<br />Address Line 2
															<br />Melbourne 3000, VIC, Australia</div>
														</div>
														<!--end::Item-->
														<!--begin::Item-->
														<div>
															<div class="font-weight-bolder">Payment Details:</div>
															<div class="line-height-xl">Card Number: xxxx xxxx xxxx 1111
															<br />Card Name: John Wick
															<br />Card Expiry: 01/21</div>
														</div>
														<!--end::Item-->
													</div>
													<!--end::Wizard Step 4-->
													<!--begin::Wizard Actions-->
													<div class="d-flex justify-content-between border-top pt-10 mt-15">
														<div class="mr-2">
															<button type="button" id="prev-step" class="btn btn-light-primary font-weight-bolder px-9 py-4" data-wizard-type="action-prev">Previous</button>
														</div>
														<div>
															<button type="button" class="btn btn-success font-weight-bolder px-9 py-4" data-wizard-type="action-submit">Submit</button>
															<button type="button" id="next-step" class="btn btn-primary font-weight-bolder px-9 py-4" data-wizard-type="action-next">Next</button>
														</div>
													</div>
													<!--end::Wizard Actions-->
												</div>
											</div>
										</form>
										<!--end::Wizard Form-->
									</div>
								</div>
							</div>
							<!--end::Body-->
						</div>
						<!--end::Card-->
					</div>
					<!--end::Wizard-->
				</div>
			</div>
			<!--end::Card-->

		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Content-->

