<style>
	.modal-dialog.modal-xl{min-width:95vw !important;max-height:90vh !important;}
</style>
<!--begin::Content-->
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
	<!--begin::Subheader-->
	<div class="subheader py-5 py-lg-10 gutter-b  subheader-transparent " id="kt_subheader" style="background-color:#663259;background-position:right bottom;background-size:auto 100%;background-repeat:no-repeat;background-image:url(assets/media/svg/illustrations/sucursalexcel.svg);height:14vh">
		<div class=" container  d-flex flex-column">
			<!--begin::Title-->
			<div class="d-flex align-items-sm-end flex-column flex-sm-row mb-5">
				<h2 class="text-white mr-5 mb-0">Existencias y Pedidos</h2>
				<span class="text-white opacity-60 font-weight-bold" id="spanweek">Del al </span>
			</div>
			<!--end::Title-->
		</div>
	</div>
	<!--end::Subheader-->

	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid">
		<!--begin::Container-->
		<div class=" container ">
			<!--begin::Dashboard-->

			<!--begin::Row-->
			<div class="row">
			    <div class="col-xl-4">
			        <!--begin::Tiles Widget 8-->
					<div class="card card-custom gutter-b card-stretch">
					    <!--begin::Header-->
					    <div class="card-header border-0 pt-5">
					        <div class="card-title">
					            <div class="card-label">
					                <div class="font-weight-bolder">Descargar Formatos</div>
					                <div class="font-size-sm text-muted mt-2"><?php echo $fecha ?></div>
					            </div>
					        </div>
					        <div class="card-toolbar">
					            <div class="dropdown dropdown-inline">
					            </div>
					        </div>
					    </div>
    					<!--end::Header-->

					    <!--begin::Body--> 
					    <div class="card-body d-flex flex-column p-0">
					        <!--begin::Items-->
					        <div class="flex-grow-1 card-spacer">
					        	
					            <!--begin::Item-->
					            <div class="d-flex align-items-center justify-content-between mb-10">
					                <div class="d-flex align-items-center mr-2">
					                    <div class="symbol symbol-40 symbol-light-primary mr-3 flex-shrink-0">
					                        <div class="symbol-label">
					                            <span class="svg-icon svg-icon-lg svg-icon-primary">
					                            	<!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
					                            	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													        <rect x="0" y="0" width="24" height="24"/>
													        <rect fill="#000000" opacity="0.3" x="4" y="4" width="4" height="4" rx="1"/>
													        <path d="M5,10 L7,10 C7.55228475,10 8,10.4477153 8,11 L8,13 C8,13.5522847 7.55228475,14 7,14 L5,14 C4.44771525,14 4,13.5522847 4,13 L4,11 C4,10.4477153 4.44771525,10 5,10 Z M11,4 L13,4 C13.5522847,4 14,4.44771525 14,5 L14,7 C14,7.55228475 13.5522847,8 13,8 L11,8 C10.4477153,8 10,7.55228475 10,7 L10,5 C10,4.44771525 10.4477153,4 11,4 Z M11,10 L13,10 C13.5522847,10 14,10.4477153 14,11 L14,13 C14,13.5522847 13.5522847,14 13,14 L11,14 C10.4477153,14 10,13.5522847 10,13 L10,11 C10,10.4477153 10.4477153,10 11,10 Z M17,4 L19,4 C19.5522847,4 20,4.44771525 20,5 L20,7 C20,7.55228475 19.5522847,8 19,8 L17,8 C16.4477153,8 16,7.55228475 16,7 L16,5 C16,4.44771525 16.4477153,4 17,4 Z M17,10 L19,10 C19.5522847,10 20,10.4477153 20,11 L20,13 C20,13.5522847 19.5522847,14 19,14 L17,14 C16.4477153,14 16,13.5522847 16,13 L16,11 C16,10.4477153 16.4477153,10 17,10 Z M5,16 L7,16 C7.55228475,16 8,16.4477153 8,17 L8,19 C8,19.5522847 7.55228475,20 7,20 L5,20 C4.44771525,20 4,19.5522847 4,19 L4,17 C4,16.4477153 4.44771525,16 5,16 Z M11,16 L13,16 C13.5522847,16 14,16.4477153 14,17 L14,19 C14,19.5522847 13.5522847,20 13,20 L11,20 C10.4477153,20 10,19.5522847 10,19 L10,17 C10,16.4477153 10.4477153,16 11,16 Z M17,16 L19,16 C19.5522847,16 20,16.4477153 20,17 L20,19 C20,19.5522847 19.5522847,20 19,20 L17,20 C16.4477153,20 16,19.5522847 16,19 L16,17 C16,16.4477153 16.4477153,16 17,16 Z" fill="#000000"/>
													    </g>
													</svg>
													<!--end::Svg Icon-->
												</span>
											</div>
					                    </div>
					                    <div>
					                        <a href="Sucursales/general" target="_blank" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">General</a><br>
					                        <a href="Sucursales/generalSin" target="_blank" class="font-size-sm text-muted font-weight-bold mt-1">Sin imágenes</a>
					                    </div>
					                </div>
					            </div>
					            <!--end::Item-->

					            <!--begin::Item-->
					            <div class="d-flex align-items-center justify-content-between mb-10">
					                <div class="d-flex align-items-center mr-2">
					                    <div class="symbol symbol-40 symbol-light-success mr-3 flex-shrink-0">
					                        <div class="symbol-label">
					                            <span class="svg-icon svg-icon-lg svg-icon-success">
					                            	<!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
					                            	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													        <rect x="0" y="0" width="24" height="24"/>
													        <rect fill="#000000" opacity="0.3" x="4" y="4" width="4" height="4" rx="1"/>
													        <path d="M5,10 L7,10 C7.55228475,10 8,10.4477153 8,11 L8,13 C8,13.5522847 7.55228475,14 7,14 L5,14 C4.44771525,14 4,13.5522847 4,13 L4,11 C4,10.4477153 4.44771525,10 5,10 Z M11,4 L13,4 C13.5522847,4 14,4.44771525 14,5 L14,7 C14,7.55228475 13.5522847,8 13,8 L11,8 C10.4477153,8 10,7.55228475 10,7 L10,5 C10,4.44771525 10.4477153,4 11,4 Z M11,10 L13,10 C13.5522847,10 14,10.4477153 14,11 L14,13 C14,13.5522847 13.5522847,14 13,14 L11,14 C10.4477153,14 10,13.5522847 10,13 L10,11 C10,10.4477153 10.4477153,10 11,10 Z M17,4 L19,4 C19.5522847,4 20,4.44771525 20,5 L20,7 C20,7.55228475 19.5522847,8 19,8 L17,8 C16.4477153,8 16,7.55228475 16,7 L16,5 C16,4.44771525 16.4477153,4 17,4 Z M17,10 L19,10 C19.5522847,10 20,10.4477153 20,11 L20,13 C20,13.5522847 19.5522847,14 19,14 L17,14 C16.4477153,14 16,13.5522847 16,13 L16,11 C16,10.4477153 16.4477153,10 17,10 Z M5,16 L7,16 C7.55228475,16 8,16.4477153 8,17 L8,19 C8,19.5522847 7.55228475,20 7,20 L5,20 C4.44771525,20 4,19.5522847 4,19 L4,17 C4,16.4477153 4.44771525,16 5,16 Z M11,16 L13,16 C13.5522847,16 14,16.4477153 14,17 L14,19 C14,19.5522847 13.5522847,20 13,20 L11,20 C10.4477153,20 10,19.5522847 10,19 L10,17 C10,16.4477153 10.4477153,16 11,16 Z M17,16 L19,16 C19.5522847,16 20,16.4477153 20,17 L20,19 C20,19.5522847 19.5522847,20 19,20 L17,20 C16.4477153,20 16,19.5522847 16,19 L16,17 C16,16.4477153 16.4477153,16 17,16 Z" fill="#000000"/>
													    </g>
													</svg>
													<!--end::Svg Icon-->
												</span>
											</div>
					                    </div>
					                    <div>
					                        <a href="Sucursales/volumen" target="_blank" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">Volúmen</a><br>
					                        <a href="Sucursales/volumenSin" target="_blank" class="font-size-sm text-muted font-weight-bold mt-1">Sin imágenes</a>
					                    </div>
					                </div>
					            </div>
					            <!--end::Item-->

					            <!--begin::Item-->
					            <div class="d-flex align-items-center justify-content-between mb-10">
					                <div class="d-flex align-items-center mr-2">
					                    <div class="symbol symbol-40 symbol-light-danger mr-3 flex-shrink-0">
					                        <div class="symbol-label">
					                            <span class="svg-icon svg-icon-lg svg-icon-danger">
					                            	<!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
					                            	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													        <rect x="0" y="0" width="24" height="24"/>
													        <rect fill="#000000" opacity="0.3" x="4" y="4" width="4" height="4" rx="1"/>
													        <path d="M5,10 L7,10 C7.55228475,10 8,10.4477153 8,11 L8,13 C8,13.5522847 7.55228475,14 7,14 L5,14 C4.44771525,14 4,13.5522847 4,13 L4,11 C4,10.4477153 4.44771525,10 5,10 Z M11,4 L13,4 C13.5522847,4 14,4.44771525 14,5 L14,7 C14,7.55228475 13.5522847,8 13,8 L11,8 C10.4477153,8 10,7.55228475 10,7 L10,5 C10,4.44771525 10.4477153,4 11,4 Z M11,10 L13,10 C13.5522847,10 14,10.4477153 14,11 L14,13 C14,13.5522847 13.5522847,14 13,14 L11,14 C10.4477153,14 10,13.5522847 10,13 L10,11 C10,10.4477153 10.4477153,10 11,10 Z M17,4 L19,4 C19.5522847,4 20,4.44771525 20,5 L20,7 C20,7.55228475 19.5522847,8 19,8 L17,8 C16.4477153,8 16,7.55228475 16,7 L16,5 C16,4.44771525 16.4477153,4 17,4 Z M17,10 L19,10 C19.5522847,10 20,10.4477153 20,11 L20,13 C20,13.5522847 19.5522847,14 19,14 L17,14 C16.4477153,14 16,13.5522847 16,13 L16,11 C16,10.4477153 16.4477153,10 17,10 Z M5,16 L7,16 C7.55228475,16 8,16.4477153 8,17 L8,19 C8,19.5522847 7.55228475,20 7,20 L5,20 C4.44771525,20 4,19.5522847 4,19 L4,17 C4,16.4477153 4.44771525,16 5,16 Z M11,16 L13,16 C13.5522847,16 14,16.4477153 14,17 L14,19 C14,19.5522847 13.5522847,20 13,20 L11,20 C10.4477153,20 10,19.5522847 10,19 L10,17 C10,16.4477153 10.4477153,16 11,16 Z M17,16 L19,16 C19.5522847,16 20,16.4477153 20,17 L20,19 C20,19.5522847 19.5522847,20 19,20 L17,20 C16.4477153,20 16,19.5522847 16,19 L16,17 C16,16.4477153 16.4477153,16 17,16 Z" fill="#000000"/>
													    </g>
													</svg>
													<!--end::Svg Icon-->
												</span>
											</div>
					                    </div>
					                    <div>
					                        <a href="Sucursales/lunesSin" target="_blank" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">Lunes</a><br>
					                    </div>
					                </div>
					            </div>
					            <!--end::Item-->
					            
					            	<h3>Los formatos podrán descargarse el día Sábado a partir de la 1:30pm</h3>
					        
		                    </div>
		        			<!--end::Items-->
					    </div>
					    <!--end::Body-->
					</div>
					<!--end::Tiles Widget 8-->
    			</div>
    		<div class="col-xl-8">
        		<!--begin::Advance Table Widget 10-->
				<div class="card card-custom gutter-b card-stretch">
				    <!--begin::Header-->
				    <div class="card-header border-0 py-5">
				        <h3 class="card-title align-items-start flex-column">
				            <span class="card-label font-weight-bolder text-dark">Mis Existencias</span>
				            <span class="text-muted mt-3 font-weight-bold font-size-sm">Existencias</span>
				        </h3>
				    </div>
				    <!--end::Header-->
				    <div class="card-body py-0">
				    	<div class="alert alert-light alert-elevate" role="alert" style="display:inline-flex;">
					        <div class="alert-icon" style="padding-right:20px"><i class="flaticon-warning kt-font-danger" style="color:red;font-size:25px;"></i></div>
					        <div class="alert-text">
					           Si desea editar las existencias o pedidos ya registrados, le pedimos comunicarse con el área de compras para realizar el cambio. Si cuenta con productos sin existencia, puede subir el archivo y solo tomará en cuenta los productos sin existencias.
					        </div>
					    </div>
				    </div>
				    <!--begin::Body-->
				    <div class="card-body py-0">
				    	<div class="form-group row">

				    		<h3>Se podrán subir pedidos el día lunes solo antes de la <?php echo $horario->hora_limite ?></h3>
				    		
				    		<?php  $timestamp = time();if(date('D', $timestamp) === 'Mon' && date('H:i:s',strtotime('-5 hours')) < $horario->hora_limite ):  ?>
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="dropzone dropzone-default dropzone-success" id="kt_dropzone_sucursal">
									<div class="dropzone-msg dz-message needsclick">
									    <h3 class="dropzone-msg-title">Arrastre o clic para seleccionar archivo.</h3>
									    <span class="dropzone-msg-desc">Seleccione o arrastre el formato de cotizaciones en el recuadro para agregar al sistema.</span>
									</div>
								</div>
							</div>
						<?php endif; ?>


						</div>
				    </div>
				    <!--end::Body-->

				    <!--begin::Body-->
				    <div class="card-body py-0">
				        <!--begin::Table-->
				        <div class="table-responsive">
				            <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_4">
				                <thead>
				                    <tr class="text-left">
				                        <th style="min-width: 110px">Formato</th>
				                        <th class="pr-0 text-right" style="min-width: 200px">Ver<br>Existencias</th>
				                    </tr>
				                </thead>
				                <tbody>
				                    <tr>
				                        <td>
				                            <span class="text-dark-75 font-weight-bolder d-block font-size-lg">
				                                General
				                            </span>
				                        </td>
				                        <td class="pr-0 text-right">
				                            <span class="label label-lg label-light-primary label-inline text-center" style="cursor:pointer;height:60px;width:60%;" data-toggle="modal" data-target="#kt_modal_sgen">
				                                <?php if ($allcuantas):echo $allcuantas->cuantas;else:echo 0;endif;?> de <?php echo $noall->total; ?> <br>
				                                Ver detalles
				                            </span>
				                        </td>
									</tr>
									<tr>
				                        <td>
				                            <span class="text-dark-75 font-weight-bolder d-block font-size-lg">
				                                Volúmen
				                            </span>
				                        </td>
				                        <td class="pr-0 text-right">
				                            <span class="label label-lg label-light-success label-inline text-center" style="cursor:pointer;height:60px;width:60%;" data-toggle="modal" data-target="#kt_modal_svol">
				                                <?php if ($volcuantas):echo $volcuantas->cuantas;else:echo 0;endif;?> de  <?php echo $novol->total; ?><br>
				                                Ver detalles
				                            </span>
				                        </td>
									</tr>
									<tr>
				                        <td>
				                            <span class="text-dark-75 font-weight-bolder d-block font-size-lg">
				                                Lunes
				                            </span>
				                        </td>
				                        <td class="pr-0 text-right">
				                            <span class="label label-lg label-light-danger label-inline text-center" style="cursor:pointer;height:60px;width:60%;" data-toggle="modal" data-target="#kt_modal_slun">
				                                <?php if($cuantas):echo $cuantas->cuantas;else:echo 0;endif;?> de <?php echo $noprod->noprod; ?><br>
				                                Ver detalles
				                            </span>
				                        </td>
									</tr>
								</tbody>
							</table>
				        </div>
				        <!--end::Table-->
				    </div>
				    <!--end::Body-->
				</div>
				<!--end::Advance Table Widget 10-->
		    </div>
		</div>
		<!--end::Row-->

		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Content-->


<!--begin::Modal Lunes-->
<div class="modal fade" id="kt_modal_slun" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#FFE2E5">
                <h5 class="modal-title" id="exampleModalLabel">Existencias/Pedidos <span style="font-size:1.5rem;font-weight:bold;font-style:oblique;color:#F64E60">Lunes</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" style="display:inline-flex;">
            	<div class="kt-portlet kt-portlet--mobile col-xl-4">

			        <div class="kt-portlet__body">
			        	<h2 style="color:red">Sin Existencias</h2>
			            <!--begin: Search Form -->
			            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
			                <div class="row align-items-center">
			                    <div class="col-xl-12 order-2 order-xl-1">
			                        <div class="row align-items-center">
			                            <div class="kt-margin-b-20-tablet-and-mobile">
			                                <div class="kt-input-icon kt-input-icon--left">
			                                    <input type="text" class="form-control" placeholder="Buscar..." id="generalSearch2">
			                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
			                                        <span><i class="la la-search"></i></span>
			                                    </span>
			                                </div>
			                            </div>

			                        </div>
			                    </div>
			                </div>
			            </div>

			            <!--end: Search Form -->
			        </div>
			        <div class="kt-portlet__body kt-portlet__body--fit">
			            <!--begin: Datatable -->
			            <div class="kt-datatable datatable datatable-bordered datatable-head-custom" id="lunessin"></div>
			            <!--end: Datatable -->
			        </div>
			    </div>
			    <div class="kt-portlet kt-portlet--mobile col-xl-1" style="border-right:2px solid #4e0000"></div>
            	<!-- Con Existencias -->
        		<div class="kt-portlet kt-portlet--mobile col-xl-6" style="padding-left:30px">

			        <div class="kt-portlet__body">
			        	<h2 style="color:green">Con Existencias</h2>
			            <!--begin: Search Form -->
			            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
			                <div class="row align-items-center">
			                    <div class="col-xl-12 order-2 order-xl-1">
			                        <div class="row align-items-center">
			                            <div class="kt-margin-b-20-tablet-and-mobile">
			                                <div class="kt-input-icon kt-input-icon--left">
			                                    <input type="text" class="form-control" placeholder="Buscar..." id="generalSearch3">
			                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
			                                        <span><i class="la la-search"></i></span>
			                                    </span>
			                                </div>
			                            </div>

			                        </div>
			                    </div>
			                </div>
			            </div>

			            <!--end: Search Form -->
			        </div>
			        <div class="kt-portlet__body kt-portlet__body--fit">
			            <!--begin: Datatable -->
			            <div class="kt-datatable datatable datatable-bordered datatable-head-custom" id="lunescon"></div>
			            <!--end: Datatable -->
			        </div>
			    </div>
            </div>
            <div class="modal-footer" style="background:#FFE2E5">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background:#F64E60;border-color:#F64E60;color:#FFF">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->

<!--begin::Modal General-->
<div class="modal fade" id="kt_modal_sgen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#EEE5FF">
                <h5 class="modal-title" id="exampleModalLabel">Existencias/Pedidos <span style="font-size:1.5rem;font-weight:bold;font-style:oblique;color:#8950FC">General</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" style="display:inline-flex;">
            	<div class="kt-portlet kt-portlet--mobile col-xl-4">

			        <div class="kt-portlet__body">
			        	<h2 style="color:red">Sin Existencias</h2>
			            <!--begin: Search Form -->
			            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
			                <div class="row align-items-center">
			                    <div class="col-xl-12 order-2 order-xl-1">
			                        <div class="row align-items-center">
			                            <div class="kt-margin-b-20-tablet-and-mobile">
			                                <div class="kt-input-icon kt-input-icon--left">
			                                    <input type="text" class="form-control" placeholder="Buscar..." id="generalSearch4">
			                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
			                                        <span><i class="la la-search"></i></span>
			                                    </span>
			                                </div>
			                            </div>

			                        </div>
			                    </div>
			                </div>
			            </div>

			            <!--end: Search Form -->
			        </div>
			        <div class="kt-portlet__body kt-portlet__body--fit">
			            <!--begin: Datatable -->
			            <div class="kt-datatable datatable datatable-bordered datatable-head-custom" id="generalsin"></div>
			            <!--end: Datatable -->
			        </div>
			    </div>
			    <div class="kt-portlet kt-portlet--mobile col-xl-1" style="border-right:2px solid #4e0000"></div>
            	<!-- Con Existencias -->
        		<div class="kt-portlet kt-portlet--mobile col-xl-6" style="padding-left:30px">

			        <div class="kt-portlet__body">
			        	<h2 style="color:green">Con Existencias</h2>
			            <!--begin: Search Form -->
			            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
			                <div class="row align-items-center">
			                    <div class="col-xl-12 order-2 order-xl-1">
			                        <div class="row align-items-center">
			                            <div class="kt-margin-b-20-tablet-and-mobile">
			                                <div class="kt-input-icon kt-input-icon--left">
			                                    <input type="text" class="form-control" placeholder="Buscar..." id="generalSearch5">
			                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
			                                        <span><i class="la la-search"></i></span>
			                                    </span>
			                                </div>
			                            </div>

			                        </div>
			                    </div>
			                </div>
			            </div>

			            <!--end: Search Form -->
			        </div>
			        <div class="kt-portlet__body kt-portlet__body--fit">
			            <!--begin: Datatable -->
			            <div class="kt-datatable datatable datatable-bordered datatable-head-custom" id="generalcon"></div>
			            <!--end: Datatable -->
			        </div>
			    </div>
            </div>
            <div class="modal-footer" style="background:#EEE5FF">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background:#8950FC;border-color:#8950FC;color:#FFF">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->


<!--begin::Modal Volúmen-->
<div class="modal fade" id="kt_modal_svol" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#C9F7F5">
                <h5 class="modal-title" id="exampleModalLabel">Existencias/Pedidos <span style="font-size:1.5rem;font-weight:bold;font-style:oblique;color:#1BC5BD">Volúmen</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" style="display:inline-flex;">
            	<div class="kt-portlet kt-portlet--mobile col-xl-4">

			        <div class="kt-portlet__body">
			        	<h2 style="color:red">Sin Existencias</h2>
			            <!--begin: Search Form -->
			            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
			                <div class="row align-items-center">
			                    <div class="col-xl-12 order-2 order-xl-1">
			                        <div class="row align-items-center">
			                            <div class="kt-margin-b-20-tablet-and-mobile">
			                                <div class="kt-input-icon kt-input-icon--left">
			                                    <input type="text" class="form-control" placeholder="Buscar..." id="generalSearch6">
			                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
			                                        <span><i class="la la-search"></i></span>
			                                    </span>
			                                </div>
			                            </div>

			                        </div>
			                    </div>
			                </div>
			            </div>

			            <!--end: Search Form -->
			        </div>
			        <div class="kt-portlet__body kt-portlet__body--fit">
			            <!--begin: Datatable -->
			            <div class="kt-datatable datatable datatable-bordered datatable-head-custom" id="volumensin"></div>
			            <!--end: Datatable -->
			        </div>
			    </div>
			    <div class="kt-portlet kt-portlet--mobile col-xl-1" style="border-right:2px solid #4e0000"></div>
            	<!-- Con Existencias -->
        		<div class="kt-portlet kt-portlet--mobile col-xl-6" style="padding-left:30px">

			        <div class="kt-portlet__body">
			        	<h2 style="color:green">Con Existencias</h2>
			            <!--begin: Search Form -->
			            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
			                <div class="row align-items-center">
			                    <div class="col-xl-12 order-2 order-xl-1">
			                        <div class="row align-items-center">
			                            <div class="kt-margin-b-20-tablet-and-mobile">
			                                <div class="kt-input-icon kt-input-icon--left">
			                                    <input type="text" class="form-control" placeholder="Buscar..." id="generalSearch7">
			                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
			                                        <span><i class="la la-search"></i></span>
			                                    </span>
			                                </div>
			                            </div>

			                        </div>
			                    </div>
			                </div>
			            </div>

			            <!--end: Search Form -->
			        </div>
			        <div class="kt-portlet__body kt-portlet__body--fit">
			            <!--begin: Datatable -->
			            <div class="kt-datatable datatable datatable-bordered datatable-head-custom" id="volumencon"></div>
			            <!--end: Datatable -->
			        </div>
			    </div>
            </div>
            <div class="modal-footer" style="background-color:#C9F7F5">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background:#1BC5BD;border-color:#1BC5BD;color:#FFF">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
