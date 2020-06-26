<!-- begin:: Content -->
<style>
    ul.kt-menu__nav,.kt-header__topbar-item.kt-header__topbar-item--user,.kt-header__topbar-item.dropdown,.kt-subheader__toolbar,.kt-portlet__head-wrapper{display:none !important;}
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
                    Catalogo de veladoras
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <a onclick="goBack()" class="btn btn-clean btn-icon-sm">
                        <i class="la la-long-arrow-left"></i>
                        Regresar
                    </a>
                    &nbsp;
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-brand btn-icon-sm" id="new_usuario" data-toggle="modal" data-target="#kt_modal_2">
                            <i class="flaticon2-plus"></i> Agregar Usuario
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-form__section kt-form__section--first">
                <div class="form-group row" style="padding:20px;margin-bottom:0px;padding-bottom:0px">
                    <label class="col-form-label col-lg-2 col-sm-12">
                        <a href="Veladoras/fill_excel2" target="_blank">
                            <button type="submit" class="btn btn-outline-success"><i class="flaticon-download"></i> Descargar SIN IMAGENES</button>
                        </a>
                    </label>
                    <div class="col-lg-2 col-md-2 col-sm-12"></div>
                    <label class="col-form-label col-lg-2 col-sm-12">
                        <a href="Veladoras/fill_excel" target="_blank">
                            <button type="submit" class="btn btn-outline-brand"><i class="flaticon-download"></i> Descargar CON IMAGENES</button>
                        </a>
                    </label>
                </div>
            </div>

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
                            <!--<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                <div class="kt-form__group kt-form__group--inline">
                                    <div class="kt-form__label">
                                        <label>Estatus:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <select class="form-control bootstrap-select" id="kt_form_status">
                                            <option value="">Todos</option>
                                            <option value="1">Activo</option>
                                            <option value="0">Eliminado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                <div class="kt-form__group kt-form__group--inline">
                                    <div class="kt-form__label">
                                        <label>Tipo Usuario:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <select class="form-control bootstrap-select" id="kt_form_type">
                                            <option value="">Todos</option>
                                            <option value="1">Proveedor</option>
                                            <option value="2">Personal</option>
                                            <option value="3">Sucursal</option>
                                        </select>
                                    </div>
                                </div>
                            </div>-->
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
    <div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?php echo form_open("", array("id"=>'form_usuario_delete')); ?>
                <input type="hidden" name="id_usuario" id="id_usuario" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el usuario?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <p>Sí esta completamente seguro de eliminar el usuario <br><span style="font-weight:bold;" id="spanuser">Jeovany Mora</span>, por favor haga clic en el botón eliminar. <br><span style="font-weight:bold;color:red;">Esta acción no se puede deshacer</span></p>
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

    <!--begin::Modal-->
    <div class="modal fade" id="kt_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <?php echo form_open("", array("id"=>'form_usuario_edit')); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_usuarios" id="id_usuarios" value="">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input id="nombre" type="text" name="nombre" value="" class="form-control" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="apellido">Apellido</label>
                                <input id="apellido" type="text" name="apellido" value="" class="form-control" placeholder="Apellido">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="correo">Correo</label>
                                <input id="correo" type="text" name="correo" value="" class="form-control" placeholder="ejemplo@email.com">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="password">Contraseña</label> <!-- $password trae la contraseña desencritada -->
                                <input id="password" type="text" name="password" class="form-control" placeholder="*********">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="id_grupo">Grupos</label>
                                <select name="id_grupo" class="form-control chosen-select" id="id_grupo">
                                    <option value="-1">Seleccionar...</option>
                                    <?php if ($grupos):foreach ($grupos as $key => $value): ?>
                                        <?php if ($value->id_grupo <> 1 ): ?>
                                            <option value="<?php echo $value->id_grupo ?>"><?php echo $value->nombre ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; endif ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary update_usuario">Editar Usuario</button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <!--end::Modal-->

    <!--begin::Modal-->
    <div class="modal fade" id="kt_modal_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <?php echo form_open("", array("id"=>'form_usuario_new')); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_usuarioss" id="id_usuarioss" value="">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input id="nombres" type="text" name="nombres" value="" class="form-control" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="apellido">Apellido</label>
                                <input id="apellidos" type="text" name="apellidos" value="" class="form-control" placeholder="Apellido">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="correo">Correo</label>
                                <input id="correos" type="text" name="correos" value="" class="form-control" placeholder="ejemplo@email.com">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="password">Contraseña</label> <!-- $password trae la contraseña desencritada -->
                                <input id="passwords" type="text" name="passwords" class="form-control" placeholder="*********">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="id_grupo">Grupos</label>
                                <select name="id_grupos" class="form-control chosen-select" id="id_grupos">
                                    <option value="-1">Seleccionar...</option>
                                    <?php if ($grupos):foreach ($grupos as $key => $value): ?>
                                        <?php if ($value->id_grupo <> 1 ): ?>
                                            <option value="<?php echo $value->id_grupo ?>"><?php echo $value->nombre ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; endif ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary new_usuario">Crear Usuario</button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <!--end::Modal-->
</div>

<!-- end:: Content -->