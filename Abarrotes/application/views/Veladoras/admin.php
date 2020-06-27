<!-- begin:: Content -->
<style>
    ul.kt-menu__nav,.kt-header__topbar-item.kt-header__topbar-item--user,.kt-header__topbar-item.dropdown,.kt-subheader__toolbar{display:none !important;}
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <!--<div class="alert alert-light alert-elevate" role="alert">
        <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
        <div class="alert-text">
            Mensaje De Alerta
        </div>
    </div>-->
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Catalogo de productos
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <a onclick="goBack()" class="btn btn-clean btn-icon-sm">
                        <i class="la la-long-arrow-left"></i>
                        Regresar
                    </a>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            

            <!--begin: Search Form -->
            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
                <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                        <div class="row align-items-center">
                            <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                <div class="kt-input-icon kt-input-icon--left">
                                    <input type="text" class="form-control" placeholder="Buscar..." id="generalSearch">
                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                        <span><i class="la la-search"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 kt-align-right">
                        <a href="#" class="btn btn-default kt-hidden">
                            <i class="la la-cart-plus"></i> New Order
                        </a>
                        <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg d-xl-none"></div>
                    </div>
                </div>
            </div>

            <!--end: Search Form -->
        </div>
        <div class="kt-portlet__body kt-portlet__body--fit">

            <!--begin: Datatable -->
            <div class="kt-datatable" id="local_data"></div>

            <!--end: Datatable -->
        </div>
    </div>


    <!--begin::Modal-->
    <div class="modal fade" id="kt_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <?php echo form_open("", array("id"=>'form_usuario_new')); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Imagen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_produs" id="id_produs" value="">
                    <div class="row">
                        <div class="col-sm-3" style="border-right:5px solid #ccc">
                            <h4 style="text-align:center;">Anterior imagen</h4>
                            <img src="./assets/img/productos/sinimagen.jpg" class="imgprod" style="max-width:100%">
                        </div>
                        <div class="col-sm-9">
                            <div class="form-group">
                                <div id="my-dropzone" class="dropzone">
                                    <div class="dz-message" data-dz-message>
                                        <h3 class="kt-dropzone__msg-title" style="font-size:1.4rem">Arrastre o clic para seleccionar archivo.</h3>
                                        <span class="kt-dropzone__msg-desc">Seleccione la imagen que se le asignará al producto.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary update_usuario">Editar Producto</button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <!--end::Modal-->

</div>