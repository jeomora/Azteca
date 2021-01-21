<!-- begin:: Content -->
<style>
    .kt-wizard-v4__nav-label-title{}
    .kt-wizard-v4__nav-number{color:#FFF !important;}
    .kt-wizard-v4__nav-item[data-ktwizard-state="current"] .kt-wizard-v4__nav-body .kt-wizard-v4__nav-label .kt-wizard-v4__nav-label-title{color:#FFF !important}
    .kt-wizard-v4__nav-item[data-ktwizard-state="current"] .kt-wizard-v4__nav-body .kt-wizard-v4__nav-number{background:#FFF !important}
    .solnav[data-ktwizard-state="current"]{background:#01B0F0 !important}
    .solnav[data-ktwizard-state="current"] .kt-wizard-v4__nav-body .kt-wizard-v4__nav-number{color:#01B0F0 !important}
    .tienav[data-ktwizard-state="current"]{background:#E26C0B !important}
    .tienav[data-ktwizard-state="current"] .kt-wizard-v4__nav-body .kt-wizard-v4__nav-number{color:#E26C0B !important}
    .ultnav[data-ktwizard-state="current"]{background:#C5C5C5 !important}
    .ultnav[data-ktwizard-state="current"] .kt-wizard-v4__nav-body .kt-wizard-v4__nav-number{color:#C5C5C5 !important}
    .trinav[data-ktwizard-state="current"]{background:#92D051 !important}
    .trinav[data-ktwizard-state="current"] .kt-wizard-v4__nav-body .kt-wizard-v4__nav-number{color:#92D051 !important}
    .mernav[data-ktwizard-state="current"]{background:#B1A0C7 !important}
    .mernav[data-ktwizard-state="current"] .kt-wizard-v4__nav-body .kt-wizard-v4__nav-number{color:#B1A0C7 !important}
    .tennav[data-ktwizard-state="current"]{background:#01B0F0 !important}
    .tennav[data-ktwizard-state="current"] .kt-wizard-v4__nav-body .kt-wizard-v4__nav-number{color:#01B0F0 !important}
    .tijnav[data-ktwizard-state="current"]{background:#4CACC6 !important}
    .tijnav[data-ktwizard-state="current"] .kt-wizard-v4__nav-body .kt-wizard-v4__nav-number{color:#4CACC6 !important}
    .supnav[data-ktwizard-state="current"]{background:#FF0066 !important}
    .supnav[data-ktwizard-state="current"] .kt-wizard-v4__nav-body .kt-wizard-v4__nav-number{color:#FF0066 !important}
    .vilnav[data-ktwizard-state="current"]{background:#FF0000 !important}
    .vilnav[data-ktwizard-state="current"] .kt-wizard-v4__nav-body .kt-wizard-v4__nav-number{color:#FF0000 !important}
    .table-bordered{font-size:16px}
    .provefalta{display:none}
    input#envio{width:120px;font-size:16px;}
    @media (max-width: 1000px){
        .table-bordered{font-size:14px}
        input#envio{width:80px;font-size:14px;}
        .kt-grid__item.kt-grid__item--fluid.kt-wizard-v4__wrapper{padding:5px}
    }
    @media (min-width: 1500px){
        .kt-container{width: 1680px;}
    }
    .kt-wizard-v4 .kt-wizard-v4__wrapper .kt-form{width:90%}
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <!--<div class="alert alert-light alert-elevate" role="alert">
        <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
        <div class="alert-text">
            Mensaje De Alerta
        </div>
    </div>-->
    <div class="kt-portlet kt-portlet--mobile">
       
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="kt-wizard-v4" id="kt_wizard_v4" data-ktwizard-state="step-first">

                <!--begin: Form Wizard Nav -->
                <div class="kt-wizard-v4__nav">
                    <div class="kt-wizard-v4__nav-items">
                        <a class="kt-wizard-v4__nav-item solnav" href="Cotizaciones/#" data-ktwizard-type="step" data-ktwizard-state="current" data-id-color="01B0F0">
                            <div class="kt-wizard-v4__nav-body">
                                <div class="kt-wizard-v4__nav-number" style="background:#01B0F0">
                                    1
                                </div>
                                <div class="kt-wizard-v4__nav-label">
                                    <div class="kt-wizard-v4__nav-label-title">
                                        Productos
                                    </div>
                                    <div class="kt-wizard-v4__nav-label-desc">
                                        
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="kt-wizard-v4__nav-item tienav" href="Cotizaciones/#" data-ktwizard-type="step" data-id-color="E26C0B">
                            <div class="kt-wizard-v4__nav-body">
                                <div class="kt-wizard-v4__nav-number" style="background:#E26C0B">
                                    2
                                </div>
                                <div class="kt-wizard-v4__nav-label">
                                    <div class="kt-wizard-v4__nav-label-title">
                                        Faltantes
                                    </div>
                                    <div class="kt-wizard-v4__nav-label-desc">
                                        
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="kt-wizard-v4__nav-item ultnav" href="Cotizaciones/#" data-ktwizard-type="step" data-id-color="C5C5C5">
                            <div class="kt-wizard-v4__nav-body">
                                <div class="kt-wizard-v4__nav-number" style="background:#C5C5C5">
                                    3
                                </div>
                                <div class="kt-wizard-v4__nav-label">
                                    <div class="kt-wizard-v4__nav-label-title">
                                        Pendientes
                                    </div>
                                    <div class="kt-wizard-v4__nav-label-desc">
                                        
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="kt-wizard-v4__nav-item trinav" href="Cotizaciones/#" data-ktwizard-type="step" data-id-color="92D051">
                            <div class="kt-wizard-v4__nav-body">
                                <div class="kt-wizard-v4__nav-number" style="background:#92D051">
                                    4
                                </div>
                                <div class="kt-wizard-v4__nav-label">
                                    <div class="kt-wizard-v4__nav-label-title">
                                        Precios Sistema
                                    </div>
                                    <div class="kt-wizard-v4__nav-label-desc">
                                        
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="kt-wizard-v4__nav-item mernav" href="Cotizaciones/#" data-ktwizard-type="step" data-id-color="B1A0C7">
                            <div class="kt-wizard-v4__nav-body">
                                <div class="kt-wizard-v4__nav-number" style="background:#B1A0C7">
                                    5
                                </div>
                                <div class="kt-wizard-v4__nav-label">
                                    <div class="kt-wizard-v4__nav-label-title">
                                        Agrega Cotizaciones
                                    </div>
                                    <div class="kt-wizard-v4__nav-label-desc">
                                        
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="kt-wizard-v4__nav-item tennav" href="Cotizaciones/#" data-ktwizard-type="step" data-id-color="DA9694">
                            <div class="kt-wizard-v4__nav-body">
                                <div class="kt-wizard-v4__nav-number" style="background:#DA9694">
                                    6
                                </div>
                                <div class="kt-wizard-v4__nav-label">
                                    <div class="kt-wizard-v4__nav-label-title">
                                        Repetir Cotizaciones
                                    </div>
                                    <div class="kt-wizard-v4__nav-label-desc">
                                        
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="kt-wizard-v4__nav-item tijnav" href="Cotizaciones/#" data-ktwizard-type="step" data-id-color="4CACC6">
                            <div class="kt-wizard-v4__nav-body">
                                <div class="kt-wizard-v4__nav-number" style="background:#4CACC6">
                                    7
                                </div>
                                <div class="kt-wizard-v4__nav-label">
                                    <div class="kt-wizard-v4__nav-label-title">
                                        Diferencias 20%
                                    </div>
                                    <div class="kt-wizard-v4__nav-label-desc">
                                        
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="kt-wizard-v4__nav-item supnav" href="Cotizaciones/#" data-ktwizard-type="step" data-id-color="FF0066">
                            <div class="kt-wizard-v4__nav-body">
                                <div class="kt-wizard-v4__nav-number" style="background:#FF0066">
                                    8
                                </div>
                                <div class="kt-wizard-v4__nav-label">
                                    <div class="kt-wizard-v4__nav-label-title">
                                        Cotización General
                                    </div>
                                    <div class="kt-wizard-v4__nav-label-desc">
                                        
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="kt-wizard-v4__nav-item vilnav" href="Cotizaciones/#" data-ktwizard-type="step" data-id-color="FF0000">
                            <div class="kt-wizard-v4__nav-body">
                                <div class="kt-wizard-v4__nav-number" style="background:#FF0000">
                                    9
                                </div>
                                <div class="kt-wizard-v4__nav-label">
                                    <div class="kt-wizard-v4__nav-label-title">
                                        Existencias
                                    </div>
                                    <div class="kt-wizard-v4__nav-label-desc">
                                        
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="kt-wizard-v4__nav-item vilnav" href="Cotizaciones/#" data-ktwizard-type="step" data-id-color="FF0000">
                            <div class="kt-wizard-v4__nav-body">
                                <div class="kt-wizard-v4__nav-number" style="background:#FF0000">
                                    10
                                </div>
                                <div class="kt-wizard-v4__nav-label">
                                    <div class="kt-wizard-v4__nav-label-title">
                                        Formatos
                                    </div>
                                    <div class="kt-wizard-v4__nav-label-desc">
                                        
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!--end: Form Wizard Nav -->
                <div class="kt-portlet">
                    <div class="kt-portlet__body kt-portlet__body--fit">
                        <div class="kt-grid">
                            <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v4__wrapper" style="border:5px solid #01B0F0">

                                <!--begin: Form Wizard Form-->
                                <div class="kt-form" id="kt_form">

                                    <!--begin: PRODUCTOS TABLA-->
                                    <div class="kt-wizard-v4__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
                                        <div class="kt-portlet__head kt-portlet__head--lg">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon">
                                                    <i class="kt-font-brand flaticon2-line-chart"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">
                                                    Lista Productos
                                                </h3>
                                            </div>
                                            <div class="kt-portlet__head-toolbar">
                                                <div class="kt-portlet__head-wrapper">
                                                    <div class="dropdown dropdown-inline">
                                                        <button type="button" class="btn btn-brand btn-icon-sm" id="new_usuario" data-toggle="modal" data-target="#kt_agregar_prod">
                                                            <i class="flaticon2-plus"></i> Agregar Producto
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="padding:20px">
                                            <label class="col-form-label col-lg-2 col-sm-12">
                                                <?php echo form_open("Productos/print_productos", array("id" => 'reporte_cotizaciones', "target" => '_blank')); ?>
                                                    <button type="submit" class="btn btn-outline-brand"><i class="flaticon-download"></i> Descargar Plantilla</button>
                                                <?php echo form_close(); ?>
                                            </label>
                                            <div class="col-lg-10 col-md-10 col-sm-12">
                                                <form method="post" action="new_is_add.php" id="reporte_cotizaciones" enctype="multipart/form-data" target="_blank">
                                                    <div class="form-group">
                                                        <div id="my-dropzoneProd" class="dropzone dz-clickable">
                                                            <div class="dz-message" data-dz-message="">
                                                                <h3 class="kt-dropzone__msg-title" style="font-size:1.4rem">Arrastre o clic para seleccionar archivo.</h3>
                                                        <span class="kt-dropzone__msg-desc">Para subir varios productos en un archivo excel, descargue el archivo y posteriormente arrastre hasta aquí el archivo</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        
                                        <div class="kt-portlet__body">
                                            <div class="kt-form kt-form--label-right" style="padding:0">
                                                <div class="row align-items-center">
                                                    <div class="col-xl-8 order-2 order-xl-1">
                                                        <div class="row align-items-center">
                                                            <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                                                <div class="kt-input-icon kt-input-icon--left">
                                                                    <input type="text" class="form-control" placeholder="Buscar..." id="productSearch">
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
                                        </div>
                                        <div class="kt-form__section kt-form__section--first">
                                            <div class="kt-wizard-v4__form">
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
                                        </div>
                                    </div>

                                    <!--end: PRODUCTOS TABLA-->

                                    <!--begin: TABLA FALTANTES-->
                                    <div class="kt-wizard-v4__content" data-ktwizard-type="step-content">
                                        <?php echo form_open_multipart("", array('id' => 'upload_faltantes')); ?>
                                        <div class="kt-portlet__head kt-portlet__head--lg">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon">
                                                    <i class="kt-font-brand flaticon2-line-chart"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">
                                                    Seleccione Proveedor
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-4 col-sm-4">
                                                <select name="id_profalta" id="id_profalta" class="form-control">
                                                    <option value="nope">Seleccionar...</option>
                                                        <?php foreach ($proveedores as $key => $value): ?>
                                                            <option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre.' '.$value->apellido ?></option>
                                                        <?php endforeach ?>
                                                </select>
                                            </label>
                                        </div>
                                        <div class="form-group row provefalta" style="padding:20px">
                                            <label class="col-form-label col-lg-2 col-sm-12">
                                                <a href="assets/faltantes.xlsx"><button type="button" class="btn btn-outline-brand"><i class="flaticon-download"></i> Descargar Plantilla</button></a>
                                            </label>
                                            <label class="col-form-label col-lg-3 col-sm-3">
                                                <button class="btn btn-brand btn-icon-sm" type="button" id="nuevo_fal"  data-toggle="modal" data-target="#kt_agregar_faltante">
                                                    <i class="fa fa-plus"></i> Agregar Faltante
                                                </button>
                                            </label>
                                            <label class="col-form-label col-lg-4 col-sm-4">
                                                <input class="btn btn-success" type="file" id="file_faltantes" name="file_faltantes" value="" size="20" />
                                                <?php echo form_close(); ?>
                                            </label>
                                            <label class="col-form-label col-lg-2 col-sm-2">
                                                <button class="btn btn-danger btn-icon-sm" data-toggle="tooltip" type="button" id="del_fal">
                                                    <i class="fa fa-trash"></i> Eliminar todos
                                                </button>
                                            </label>
                                            <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                                <div class="kt-input-icon kt-input-icon--left">
                                                    <input type="text" class="form-control" placeholder="Buscar..." id="myInput" onkeyup="myFunction()">
                                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                                        <span><i class="la la-search"></i></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-12 kt-margin-b-20-tablet-and-mobile">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered table-hover" id="table_prov_cots">
                                                        <thead>
                                                            <tr>
                                                                <th>CÓDIGO</th>
                                                                <th>DESCRIPCIÓN</th>
                                                                <th>SEMANAS FALTANTES</th>
                                                                <th>FECHA TERMINO</th>
                                                                <!--<th>ACCIONES</th>-->
                                                            </tr>
                                                        </thead>
                                                        <tbody class="cot-prov">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!--end: TABLE FALTANTES-->

                                    <!--begin: TABLA FALTANTES-->
                                    <div class="kt-wizard-v4__content" data-ktwizard-type="step-content">
                                        <div class="kt-portlet__head kt-portlet__head--lg">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon">
                                                    <i class="kt-font-brand flaticon2-line-chart"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">
                                                    Pedidos Pendientes
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="padding:20px">
                                            <label class="col-form-label col-lg-2 col-sm-12">
                                                <a href="assets/Pedidos Pendientes.xlsx"><button type="button" class="btn btn-outline-brand"><i class="flaticon-download"></i> Descargar Plantilla</button></a>
                                            </label>
                                            <?php echo form_open_multipart("", array('id' => 'upload_pendientes')); ?>
                                            <label class="col-form-label col-lg-4 col-sm-4">
                                                <input class="btn btn-success" type="file" id="file_pendientes" name="file_pendientes" value="" size="20" />
                                            </label>
                                            <?php echo form_close(); ?>
                                            <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                                <div class="kt-input-icon kt-input-icon--left">
                                                    <input type="text" class="form-control" placeholder="Buscar..." id="buscapend" onkeyup="buscapendiente()">
                                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                                        <span><i class="la la-search"></i></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 kt-margin-b-20-tablet-and-mobile">
                                            <table class="table table-striped table-bordered table-hover" id="table_pendientes">
                                                <thead>
                                                    <tr class="trpendientes">
                                                        <th style="background-color: #000;color:#FFF;">DESCRIPCIÓN</th>
                                                        <th style="background-color: #000;color:#FFF;">CÓDIGO</th>
                                                        <th style="width: 80px;background-color: #C00000">CEDIS/SUPER</th>
                                                        <th style="width: 80px;background-color: #01B0F0">ABARROTES</th>
                                                        <th style="width: 80px;background-color: #FF0000">PEDREGAL</th>
                                                        <th style="width: 80px;background-color: #E26C0B">TIENDA</th>
                                                        <th style="width: 80px;background-color: #C5C5C5">ULTRA</th>
                                                        <th style="width: 80px;background-color: #92D051">TRINCHERAS</th>
                                                        <th style="width: 80px;background-color: #B1A0C7">AZT MERCADO</th>
                                                        <th style="width: 80px;background-color: #DA9694">TENENCIA</th>
                                                        <th style="width: 80px;background-color: #4CACC6">TIJERAS</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tablePendv">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!--end: TABLA FALTANTES-->

                                    <!--begin: PRECIOS SISTEMA-->
                                    <div class="kt-wizard-v4__content" data-ktwizard-type="step-content">
                                        <div class="kt-portlet__head kt-portlet__head--lg">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon">
                                                    <i class="kt-font-brand flaticon2-line-chart"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">
                                                    Agregar Precios Del Sistema
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="padding:20px">
                                            <label class="col-form-label col-lg-2 col-sm-12">
                                                <a href="Cotizaciones/archivo_precios" target="_blank"><button type="button" class="btn btn-outline-brand"><i class="flaticon-download"></i> Descargar Plantilla</button></a>
                                            </label>
                                            <?php echo form_open_multipart("", array('id' => 'upload_precios')); ?>
                                            <label class="col-form-label col-lg-4 col-sm-4">
                                                <input class="btn btn-success" type="file" id="file_precios" name="file_precios" value="" size="20" />
                                            </label>
                                            <?php echo form_close(); ?>
                                            <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                                <div class="kt-input-icon kt-input-icon--left">
                                                    <input type="text" class="form-control" placeholder="Buscar..." id="buscasis" onkeyup="buscasistema()">
                                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                                        <span><i class="la la-search"></i></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-12 kt-margin-b-20-tablet-and-mobile">
                                                <table class="table table-striped table-bordered table-hover" id="table_sistema">
                                                    <thead>
                                                        <tr class="trsistema">
                                                            <th style="background-color: #000;color:#FFF;">DESCRIPCIÓN</th>
                                                            <th style="background-color: #000;color:#FFF;">CÓDIGO</th>
                                                            <th style="background-color: #000;color:#FFF;">SISTEMA</th>
                                                            <th style="background-color: #000;color:#FFF;">PRECIO 4</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tableSistema">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!--end: PRECIO SISTEMA-->

                                    <!--begin: AGREGA COTIZACIÓN-->
                                    <div class="kt-wizard-v4__content" data-ktwizard-type="step-content">
                                        <?php echo form_open("Cotizaciones/fill_excel_pro", array("id" => 'reporte_cotizaciones', "target" => '_blank')); ?>
                                        <div class="kt-portlet__head kt-portlet__head--lg">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon">
                                                    <i class="kt-font-brand flaticon2-line-chart"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">
                                                    Seleccione Un Proveedor
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-4 col-sm-4">
                                                <select name="id_proCotiz" id="id_proCotiz" class="form-control">
                                                    <option value="nope">Seleccionar...</option>
                                                        <?php foreach ($proveedores as $key => $value): ?>
                                                            <option value="<?php echo $value->id_usuario ?>"><?php echo $value->nombre.' '.$value->apellido ?></option>
                                                        <?php endforeach ?>
                                                </select>
                                            </label>
                                        </div>
                                        <div class="form-group row provefalta" style="padding:20px">
                                            <label class="col-form-label col-lg-2 col-sm-12">
                                                <button type="submit" class="btn btn-outline-brand"><i class="flaticon-download"></i> Descargar Plantilla</button>
                                            </label>
                                            <label class="col-form-label col-lg-3 col-sm-3">
                                                <button class="btn btn-brand btn-icon-sm" type="button" id="nuevo_fal"  data-toggle="modal" data-target="#kt_agregar_faltante">
                                                    <i class="fa fa-plus"></i> Agregar Cotización
                                                </button>
                                            </label>
                                            <?php echo form_close(); ?>
                                            
                                            <?php echo form_open_multipart("", array('id' => 'upload_cotizaciones')); ?>
                                            <label class="col-form-label col-lg-4 col-sm-4">
                                                <input class="btn btn-success" type="file" id="file_faltantes" name="file_faltantes" value="" size="20" />
                                            </label>
                                            <?php echo form_close(); ?>
                                            <?php echo form_open_multipart("", array("id" => "end_cotizaciones")); ?>
                                            <label class="col-form-label col-lg-2 col-sm-2">
                                                <button class="btn btn-danger btn-icon-sm" data-toggle="tooltip" id="end_cotizacion" type="button" data-toggle="modal" data-target="#kt_eliminar_cotización">
                                                    <i class="fa fa-trash"></i> Eliminar Cotización Semana
                                                </button>
                                            </label>
                                            <?php echo form_close(); ?>
                                            <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                                <div class="kt-input-icon kt-input-icon--left">
                                                    <input type="text" class="form-control" placeholder="Buscar..." id="myInput" onkeyup="buscaCotProvs()">
                                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                                        <span><i class="la la-search"></i></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-12 kt-margin-b-20-tablet-and-mobile">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered table-hover" id="table_cot_proveedores">
                                                        <thead>
                                                            <tr>
                                                                <th>DESCRIPCIÓN</th>
                                                                <th>CODIGO</th>
                                                                <th>FECHA REGISTRO</th>
                                                                <th>PRECIO FACTURA</th>
                                                                <th>PRECIO FACTURA C/PROMOCIÓN</th>
                                                                <th>DESCUENTO ADICIONAL</th>
                                                                <th colspan="2">PROMOCIÓN</th>
                                                                <th>OBSERVACIONES</th>
                                                                <th>ACCIÓN</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="cotz-prov">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--end: AGREGA COTIZACIÓN-->

                                    <!--begin: REPETIR COTIZACIÓN-->
                                    <div class="kt-wizard-v4__content" data-ktwizard-type="step-content">
                                        <div class="kt-portlet__head kt-portlet__head--lg">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon">
                                                    <i class="kt-font-brand flaticon2-line-chart"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">
                                                    Repetir Anteriores
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="padding:20px">
                                            <div class="col-md-12 kt-margin-b-20-tablet-and-mobile">
                                                <div class="row">
                                                <?php $color=[0=>"primary",1=>"warning",2=>"danger",3=>"success"];$flag=-1;foreach($cotizados as $key => $value):$flag++; ?>
                                                    <div class="col-xl-3 col-md-4">

                                                        <div class="kt-portlet kt-portlet--height-fluid">
                                                            <div class="kt-portlet__head kt-portlet__head--noborder">
                                                                <div class="kt-portlet__head-label">
                                                                </div>
                                                            </div>
                                                            <div class="kt-portlet__body">

                                                                <!--begin::Widget -->
                                                                <?php $r = array('success','brand','danger','success','warning','primary','info');$n = rand(0,6); ?>
                                                                <div class="kt-widget kt-widget--user-profile-2">
                                                                    <div class="kt-widget__head">
                                                                        <div class="kt-widget__media">
                                                                            <div class="kt-widget__pic kt-widget__pic--<?php echo $r[$n]; ?> kt-font-info kt-font-boldestn" style="font-size:2.5rem !important">
                                                                                <?php echo substr($value->nombre,0,1) ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="kt-widget__info">
                                                                            <a class="kt-widget__username">
                                                                                <?php echo strtoupper($value->nombre) ?>
                                                                            </a>
                                                                            <span class="kt-widget__desc">
                                                                                <?php echo $value->email ?>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="kt-widget__body">
                                                                        <div class="kt-widget__item">
                                                                            <div class="kt-widget__contact">
                                                                                <span class="kt-widget__label">No. productos:</span>
                                                                                <a class="kt-widget__data"><?php echo $value->total ?></a>
                                                                            </div>
                                                                            <div class="kt-widget__contact">
                                                                                <span class="kt-widget__label">Fecha:</span>
                                                                                <a class="kt-widget__data">
                                                                                    <?php echo $dias[date('w',strtotime($value->fecha_registro))]." ".date('d',strtotime($value->fecha_registro))." DE ".$meses[date('n',strtotime($value->fecha_registro))-1]." ".date('H:i:s', strtotime($value->fecha_registro)) ?>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="kt-widget__footer">
                                                                        <button type="button" id="verRepite" class="btn btn-label-danger btn-lg btn-upper" data-toggle="modal" data-target="#kt_modal_lastCotiz" data-id-user="<?php echo $value->id_proveedor ?>">Repetir Cotización</button>
                                                                    </div>
                                                                </div>
                                                                <!--end::Widget -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php ?>
                                                <?php endforeach; ?>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--end: REPETIR COTIZACIÓN-->

                                    <!--begin: DIFERENCIAS 20%-->
                                    <div class="kt-wizard-v4__content" data-ktwizard-type="step-content">
                                        <div class="kt-portlet__head kt-portlet__head--lg">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon">
                                                    <i class="kt-font-brand flaticon2-line-chart"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">
                                                    Diferencias +/- Del 20%
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="padding:20px">
                                            <div class="col-md-12 kt-margin-b-20-tablet-and-mobile">
                                                <table class="table table-striped table-bordered table-hover" id="table_diferencias">
                                                    <thead>
                                                        <tr class="trDiferencias">
                                                            <th>CODIGO</th>
                                                            <th>DESCRIPCIÓN</th>
                                                            <th>PRECIO SISTEMA</th>
                                                            <th>DIFERENCIA</th>
                                                            <th>FECHA REGISTRO</th>
                                                            <th>PROVEEDOR</th>
                                                            <th>PRECIO FACTURA</th>
                                                            <th>PRECIO FACTURA C/PROMOCIÓN</th>
                                                            <th>DESCUENTO ADICIONAL</th>
                                                            <th colspan="2">PROMOCIÓN</th>
                                                            <th>OBSERVACIONES</th>
                                                            <th>ACCIÓN</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tableDiferencias">
                                                        <?php if($diferencias):foreach ($diferencias as $key => $value): ?>
                                                            <tr>
                                                                <td><?php echo $value->codigo ?></td>
                                                                <td><?php echo $value->descrip ?></td>
                                                                <td><?php echo '$ '.number_format($value->precio_sistema,2,'.',',') ?></td>
                                                                <td style="background-color: #ffc1c1"><?php echo '$ '.number_format($value->diferencia,2,'.',',') ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime($value->fecha_registro)) ?> <input type="text" class="idprovs" name="idprovs" value="" style="display: none"> </td>
                                                                <td><?php echo $value->nombre ?></td>
                                                                <td><?php echo ($value->precio >0) ? '$ '.number_format($value->precio,2,'.',',') : '' ?></td>
                                                                <td><?php echo ($value->precio_promocion >0) ? '$ '.number_format($value->precio_promocion,2,'.',',') : '' ?></td>
                                                                <td><?php echo ($value->descuento > 0) ? number_format($value->descuento,0,'.',',').' %' : ''  ?></td>
                                                                <td><?php echo $value->num_one ?></td>
                                                                <td><?php echo $value->num_two ?></td>
                                                                <td><?php echo $value->observaciones ?></td>
                                                                <td>
                                                                    <a data-id-prod="<?php echo $value->id_cotizacion ?>" data-target="" id="editCotiza20" data-toggle="modal" title="Editar Cotización" class="btn btn-sm btn-clean btn-icon mr-2">
                                                                        <span class="svg-icon svg-icon-md"><i class="la la-edit"></i></span>
                                                                    </a>
                                                                    <a data-id-prod="<?php echo $value->id_cotizacion ?>" data-target="" id="elimCotiza20" data-toggle="modal" title="Eliminar Cotización" class="btn btn-sm btn-clean btn-icon mr-2">
                                                                        <span class="svg-icon svg-icon-md"><i class="la la-trash"></i></span>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach;endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!--end: Form Wizard Step 7-->

                                    <!--begin: COTIZACION GENERAL-->
                                    <div class="kt-wizard-v4__content" data-ktwizard-type="step-content">
                                        <div class="kt-portlet__head kt-portlet__head--lg">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon">
                                                    <i class="kt-font-brand flaticon2-line-chart"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">
                                                    Cotizacion General
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="padding:20px">
                                            <label class="col-form-label col-lg-2 col-sm-12">
                                                <a href="Cotizaciones/fill_excel">
                                                    <button type="button" class="btn btn-outline-warning">
                                                        <i class="flaticon-download"></i>
                                                        Descargar Excel Cotizaciones (3 proveedores)
                                                    </button>
                                                </a>
                                            </label>
                                            <label class="col-form-label col-lg-2 col-sm-12">
                                                <a href="Cotizaciones/fill_cotize">
                                                    <button type="button" class="btn btn-outline-brand">
                                                        <i class="flaticon-download"></i>
                                                        FORMATO SR. COSME 
                                                        (TODOS LOS PROVEEDORES)
                                                    </button>   
                                                </a>
                                            </label>
                                            <label class="col-form-label col-lg-12 col-sm-12">
                                            <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                                <div class="kt-input-icon kt-input-icon--left">
                                                    <input type="text" class="form-control" placeholder="Buscar..." id="buscaCOT" onkeyup="buscaCOT()">
                                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                                        <span><i class="la la-search"></i></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 kt-margin-b-20-tablet-and-mobile" style="overflow-x:scroll;">
                                            <table class="table table-striped table-bordered table-hover" id="table_cotizaciones">
                                                <thead>
                                                    <tr class="trcotizaciones">
                                                        <th>CÓDIGO</th>
                                                        <th>DESCRIPCIÓN</th>
                                                        <th>SISTEMA</th>
                                                        <th>PRECIO 4</th>
                                                        <th>FACTURA</th>
                                                        <th>C/PROMOCIÓN</th>
                                                        <th>PROVEEDOR</th>
                                                        <th>OBSERVACIÓN</th>
                                                        <th>PRECIO MAXIMO</th>
                                                        <th>PRECIO PROMEDIO</th>
                                                        <th>FACTURA</th>
                                                        <th>C/PROMOCIÓN</th>
                                                        <th>2DO PROVEEDOR</th>
                                                        <th>2DA OBSERVACIÓN</th>
                                                        <th>FACTURA</th>
                                                        <th>C/PROMOCIÓN</th>
                                                        <th>3ER PROVEEDOR</th>
                                                        <th>3ER OBSERVACIÓN</th>
                                                        <th>ACCIÓN</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tableCotizaciones">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!--end: COTIZACION GENERAL-->

                                    <!--begin: Form Wizard Step 6-->
                                    <div class="kt-wizard-v4__content" data-ktwizard-type="step-content">
                                        <div class="kt-heading kt-heading--md">Existencia Sucursales</div>
                                        <div class="kt-form__section kt-form__section--first">
                                            <div class="kt-wizard-v4__form">
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <!--end: Form Wizard Step 6-->

                                    <!--begin: Form Wizard Step 6-->
                                    <div class="kt-wizard-v4__content" data-ktwizard-type="step-content">
                                        <div class="kt-heading kt-heading--md">Formatos Existencias</div>
                                        <div class="kt-form__section kt-form__section--first">
                                            <div class="kt-wizard-v4__form">
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <!--end: Form Wizard Step 6-->

                                    <!--begin: Form Actions -->
                                    <div class="kt-form__actions">
                                        
                                    </div>

                                    <!--end: Form Actions -->
                                </div>

                                <!--end: Form Wizard Form-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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


<!--begin::Modal Editar-->
<div class="modal fade" id="kt_agregar_faltante" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <?php echo form_open("", array("id"=>'form_faltante_new')); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Faltante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="prodFalta">Seleccione producto</label>
                            <select name="prodFalta" class="form-control chosen-select" id="prodFalta">
                                <?php if ($productos):foreach ($productos as $key => $value): ?>
                                    <option value="<?php echo $value->id_producto ?>"><?php echo $value->nombre ?></option>
                                <?php endforeach; endif ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="faltante"># Semanas</label>
                            <input id="faltante" type="number" name="faltante" value="" class="form-control" placeholder="">
                        </div>
                    </div>  
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary new_falta">Agregar Faltnate</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!--end::Modal-->

<!--begin::Modal Eliminar Cotización-->
<div class="modal fade" id="kt_eliminar_cotización" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Desea eliminar la cotización?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Sí esta completamente seguro de eliminar la cotización de la semana <br> por favor haga clic en el botón eliminar. <br><span style="font-weight:bold;color:red;">Esta acción no se puede deshacer</span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger end_cotizacion">Eliminar</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->

<!--begin::Modal Volúmen-->
<div class="modal fade" id="kt_modal_lastCotiz" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Última cotización</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" style="display:inline-flex;">
                <div class="kt-portlet kt-portlet--mobile col-xl-12">

                    <div class="kt-portlet__body">
                        <h2>Cotizaciones</h2>
                        <!--begin: Search Form -->
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>PRECIO C/PROMOCIÓN</th>
                                    <th>PRECIO</th>
                                    <th># EN #</th>
                                    <th>DESCUENTO</th>
                                    <th>OBSERVACIONES</th>
                                </tr>
                            </thead>
                            <tbody id="bodyRepite">
                                
                            </tbody>
                        </table>

                        <!--end: Search Form -->
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fit">
                        <!--begin: Datatable -->
                        <div class="kt-datatable datatable datatable-bordered datatable-head-custom" id="lastcotz"></div>
                        <!--end: Datatable -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="RepCotz" data-id-user="none">Repetir Cotización</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->


<!--begin::Modal Volúmen-->
<div class="modal fade" id="kt_modal_fastEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualizar cotización</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" style="display:inline-flex;">
                <div class="kt-portlet kt-portlet--mobile col-xl-12">

                    <div class="kt-portlet__body">
                        <h2>Cotizaciones</h2>
                        <!--begin: Search Form -->
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>PRECIO C/PROMOCIÓN</th>
                                    <th>PRECIO</th>
                                    <th># EN #</th>
                                    <th>DESCUENTO</th>
                                    <th>OBSERVACIONES</th>
                                </tr>
                            </thead>
                            <tbody id="bodyRepite">
                                
                            </tbody>
                        </table>

                        <!--end: Search Form -->
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fit">
                        <!--begin: Datatable -->
                        <div class="kt-datatable datatable datatable-bordered datatable-head-custom" id="lastcotz"></div>
                        <!--end: Datatable -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="RepCotz" data-id-user="none">Repetir Cotización</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->

</div>