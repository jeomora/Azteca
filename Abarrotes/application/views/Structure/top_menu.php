<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <?php if($this->session->userdata("id_grupo") <> 2 && $this->session->userdata("id_grupo") <> 3):?>
            <h3 class="kt-subheader__title">Escritorio</h3>
        <?php else: ?>
            <h3 class="kt-subheader__title">Agregar Cotizaciones Perfumería y Farmacia</h3>
        <?php endif ?>
        <div class="kt-subheader__breadcrumbs">
            <a href="<?php echo base_url("") ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="" class="kt-subheader__breadcrumbs-link">
                Inicio </a>
        </div>
    </div>
    <div class="kt-subheader__toolbar">
        <?php if($this->session->userdata("id_grupo") <> 2 && $this->session->userdata("id_grupo") <> 3):?>
        <div class="kt-subheader__wrapper">
            <a href="#" class="btn kt-subheader__btn-secondary">
                Reportes
            </a>
            <div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="Quick actions" data-placement="top">
                <a href="#" class="btn btn-danger kt-subheader__btn-options" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acceso Rápido
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#"><i class="la la-user"></i> Nuevo Usuario</a>
                    <a class="dropdown-item" href="#"><i class="la la-plus"></i> Nuevo Producto</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#"><i class="la la-cog"></i> Configuración</a>
                </div>
            </div>
        </div>
        <?php endif ?>
    </div>
</div>

<!-- end:: Subheader -->