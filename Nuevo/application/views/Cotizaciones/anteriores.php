<style>
    .blockElement{z-index: 100000000 !important}
</style>
<!--begin::Content-->
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-5 py-lg-10 gutter-b  subheader-transparent " id="kt_subheader" style="background-color: #663259; background-position: right bottom; background-size: auto 100%; background-repeat: no-repeat; background-image: url(assets/media/svg/patterns/taieri.svg)">
        <div class=" container  d-flex flex-column">
            <!--begin::Title-->
            <div class="d-flex align-items-sm-end flex-column flex-sm-row mb-5">
                <h2 class="text-white mr-5 mb-0">Repetir Cotizaciones</h2>
                <span class="text-white opacity-60 font-weight-bold">Se pueden "repetir" las cotizaciones a partir de los días Sábado</span>
            </div>
            <!--end::Title-->
        </div>
    </div>
    <!--end::Subheader-->

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class=" container ">
            <!--begin::Content-->
            <div class="flex-row-lg-fluid ml-lg-8">
                <!--begin::Row-->
                <div class="row">
                <?php $color=[0=>"primary",1=>"warning",2=>"danger",3=>"success"];$flag=-1;foreach($cotizados as $key => $value):$flag++; ?>
                <?php $inicial = ""; ?>
                <?php if( substr($value->nombre,0,1) === $inicial) {
                    
                } ?>
                <?php if ($flag === 4):$flag=0; ?>
                    </div>
                    <div class="row">
                <?php endif; ?>
                    <div class="col-xl-3">
                        <div class="card card-custom gutter-b card-stretch">
                            <!--begin::Body-->
                            <div class="card-body pt-4 d-flex flex-column justify-content-between">
                                <!--begin::User-->
                                <div class="d-flex align-items-center mb-7">
                                    <!--begin::Pic-->
                                    <div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
                                        <div class="symbol symbol-lg-75 symbol-<?php echo $color[$flag]?>">
                                            <span class="symbol-label font-size-h3 font-weight-boldest"><?php echo substr($value->nombre,0,2)?></span>
                                        </div>
                                    </div>
                                    <!--end::Pic-->
                                    <!--begin::Title-->
                                    <div class="d-flex flex-column">
                                        <a class="text-dark font-weight-bold text-hover-<?php echo $color[$flag]?> font-size-h4 mb-0">
                                            <?php if(strlen($value->nombre) >= 25): ?>
                                            <?php echo substr($value->nombre,0,21)."..."?>
                                            <?php else: ?>
                                            <?php echo $value->nombre?>
                                            <?php endif; ?>
                                        </a>
                                        <span class="text-muted font-weight-bold"><?php echo $value->email ?></span>
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::User-->
                                <!--begin::Info-->
                                <div class="mb-7">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-dark-75 font-weight-bolder mr-2">Última Cotización:</span>
                                        <a href="#" class="text-muted text-hover-<?php echo $color[$flag]?>">
                                            <?php echo $dias[date('w',strtotime($value->fecha_registro))]." ".date('d',strtotime($value->fecha_registro))." DE ".$meses[date('n',strtotime($value->fecha_registro))-1]." ".date('H:i:s', strtotime($value->fecha_registro)) ?>
                                        </a>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-cente my-1">
                                        <span class="text-dark-75 font-weight-bolder mr-2">No. Productos:</span>
                                        <a href="#" class="text-muted text-hover-<?php echo $color[$flag]?>"><?php echo $value->total ?></a>
                                    </div>
                                </div>
                                <!--end::Info-->
                                <a class="btn btn-block btn-sm btn-light-<?php echo $color[$flag]?> font-weight-bolder text-uppercase py-4" id="verCotz" data-id-cotiz="<?php echo $value->id_proveedor ?>" data-toggle="modal" data-target="#kt_modal_lastCotiz">Ver y repetir</a>
                            </div>
                            <!--end::Body-->
                        </div>
                    </div>
                <?php ?>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
<!--end::Content-->

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
                        <table class="table table-striped table-bordered table-hover" id="table_cot_admin">
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
                            <tbody id="bodycotiz">
                                
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