<style>
	.dz-message.drop1{margin:0 !important}
</style>
<!--begin::Content-->
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
	<!--begin::Subheader-->
	<div class="subheader py-5 py-lg-10 gutter-b  subheader-transparent " id="kt_subheader" style="background-color: #663259; background-position: right bottom; background-size: auto 100%; background-repeat: no-repeat; background-image: url(assets/media/svg/illustrations/productos.svg)">
		<div class=" container  d-flex flex-column">
			<!--begin::Title-->
			<div class="d-flex align-items-sm-end flex-column flex-sm-row mb-5">
				<h2 class="text-white mr-5 mb-0">Productos Registrados</h2>
				<span class="text-white opacity-60 font-weight-bold"><?php echo $cuantos->total ?> productos </span>
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

			<!--begin::Row-->
			<div class="row">		    
	    		<div class="col-xl-12">
	        		<!--begin::Advance Table Widget 10-->
					<div class="card card-custom gutter-b card-stretch">
					    <div class="card-body py-4">
					    	<div class="alert alert-light alert-elevate" role="alert" style="display:inline-flex;">
						        <div class="alert-icon" style="padding-right:20px"><i class="flaticon-warning kt-font-danger" style="color:red;font-size:25px;"></i></div>
						        <div class="alert-text">
						           Si desea agregar más de un producto, puede descargar el formato (botón amarillo) y completarlo. Posteriormente clic al botón "Subir varios productos" y seleccionar el archivo. <br>Ejemplo de archivo subir productos:
						           <img src="assets/img/uploadprod.png" style="max-width:100%;padding-top:10px;">
						        </div>
						    </div>
					    </div>

					    <!--begin::Body-->
					    <div class="card-body py-0" style="padding-bottom:30px !important">
					    	<div class="kt-portlet__body kt-portlet__body--fit">
					            <!--begin: Datatable -->
					            <div class="kt-datatable datatable datatable-bordered datatable-head-custom" id="prodTable"></div>
					            <!--end: Datatable -->
					        </div>
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


<!--begin::Modal Eliminar-->
<div class="modal fade" id="kt_del_prod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        	<?php echo form_open("", array("id"=>'form_producto_delete')); ?>
            <input type="hidden" name="id_producto" id="id_producto" value="">
            <div class="modal-header">
                <h5 class="modal-title">¿Desea eliminar el producto?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
            	<p>Sí esta completamente seguro de eliminar el producto <br><span style="font-weight:bold;" id="spanprodr"></span>, por favor haga clic en el botón eliminar. <br><span style="font-weight:bold;color:red;">Esta acción no se puede deshacer</span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger delete_usuario">Eliminar</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!--end::Modal-->

<!--begin::Modal Imagen-->
<div class="modal fade" id="kt_imagen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background:rgba(255,255,255,0.5)">
        	<div class="modal-body" style="text-align:center;">
        		<img src="" id="imgprod">
        	</div>
        </div>
    </div>
</div>
<!--end::Modal-->

<!--begin::Modal Editar-->
<div class="modal fade" id="kt_edit_prod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <?php echo form_open("", array("id"=>'form_producto_edit')); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_productos" id="id_productos" value="">
                <input type="hidden" name="codigo2" id="codigo2" value="">
                <div class="row">
                	<div class="col-sm-3">
                        <div class="form-group">
                            <label for="codigo">Código Caja</label>
                            <input id="codigo" type="text" name="codigo" class="form-control" placeholder="Código Caja">
                            <div class="invalid-feedback" id="codigoFeed">Esta campo es requerido.</div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input id="nombre" type="text" name="nombre" value="" class="form-control" placeholder="Descripción del producto">
                            <div class="invalid-feedback" id="nombreFeed">Ingrese la descripción(nombre) del producto.</div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="unidad">UM</label>
                            <input id="unidad" type="text" name="unidad" value="" class="form-control" placeholder="Unidad Medida">
                            <div class="invalid-feedback" id="unidadFeed">Ingrese la unidad de medida.</div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="colorp">Conversión</label>
                            <select name="colorp" class="form-control chosen-select" id="colorp">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="pieza">Código pieza</label>
                            <input id="pieza" type="text" name="pieza" value="" class="form-control" placeholder="Código">
                        </div>
                    </div>	
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="id_familia">Familia</label>
                            <select name="id_familia" class="form-control chosen-select" id="id_familia">
                                <option value="">Seleccionar...</option>
                                <?php if ($familias):foreach ($familias as $key => $value): ?>
                                    <option value="<?php echo $value->id_familia ?>"><?php echo $value->nombre ?></option>
                                <?php endforeach; endif ?>
                            </select>
                            <div class="invalid-feedback" id="familiaFeed">Seleccione una familia.</div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="estatus">Tipo Producto</label>
                            <select name="estatus" class="form-control chosen-select" id="estatus">
                                <option value="1">Normal</option>
                                <option value="2">Volúmen</option>
                                <option value="3">Amarillo</option>
                                <option value="4">Moderna</option>
                                <option value="5">Costeña</option>
                                <option value="6">Cuetara</option>
                                <option value="7">Mexicano</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="casa">Casa Comercial</label>
                            <input id="casa" type="text" name="casa" value="" class="form-control" placeholder="Ejem: MARCAS NESTLE SA DE CV,GRUPO CLARASOL">
                        </div>
                    </div>	
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary update_producto">Editar Producto</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!--end::Modal-->


<!--begin::Modal Editar-->
<div class="modal fade" id="kt_agregar_prod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <?php echo form_open("", array("id"=>'form_agregar_producto')); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                	<div class="col-sm-3">
                        <div class="form-group">
                            <label for="codigoA">Código Caja</label>
                            <input id="codigoA" type="text" name="codigoA" class="form-control" placeholder="Código Caja">
                            <div class="invalid-feedback" id="codigoFeedA">Esta campo es requerido.</div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="nombreA">Nombre</label>
                            <input id="nombreA" type="text" name="nombreA" value="" class="form-control" placeholder="Descripción del producto">
                            <div class="invalid-feedback" id="nombreFeedA">Ingrese la descripción(nombre) del producto.</div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="unidadA">UM</label>
                            <input id="unidadA" type="text" name="unidadA" value="" class="form-control" placeholder="Unidad Medida">
                            <div class="invalid-feedback" id="unidadFeedA">Ingrese la unidad de medida.</div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="colorpA">Conversión</label>
                            <select name="colorpA" class="form-control chosen-select" id="colorpA">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="piezaA">Código pieza</label>
                            <input id="piezaA" type="text" name="piezaA" value="" class="form-control" placeholder="Código">
                        </div>
                    </div>	
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="id_familiaA">Familia</label>
                            <select name="id_familiaA" class="form-control chosen-select" id="id_familiaA">
                                <option value="">Seleccionar...</option>
                                <?php if ($familias):foreach ($familias as $key => $value): ?>
                                    <option value="<?php echo $value->id_familia ?>"><?php echo $value->nombre ?></option>
                                <?php endforeach; endif ?>
                            </select>
                            <div class="invalid-feedback" id="familiaFeedA">Seleccione una familia.</div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="estatusA">Tipo Producto</label>
                            <select name="estatusA" class="form-control chosen-select" id="estatusA">
                                <option value="1">Normal</option>
                                <option value="2">Volúmen</option>
                                <option value="3">Amarillo</option>
                                <option value="4">Moderna</option>
                                <option value="5">Costeña</option>
                                <option value="6">cuetara</option>
                                <option value="7">Mexicano</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="casaA">Casa Comercial</label>
                            <input id="casaA" type="text" name="casaA" value="" class="form-control" placeholder="Ejem: MARCAS NESTLE SA DE CV,GRUPO CLARASOL">
                        </div>
                    </div>	
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary agregar_producto">Agregar Producto</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!--end::Modal-->