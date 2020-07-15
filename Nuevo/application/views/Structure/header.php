<!DOCTYPE html>
<html lang="en" >
    <!--begin::Head-->
    <head>
        <base href="<?php  echo base_url('') ?>">
        <meta charset="utf-8"/>
        <title>Abarrotes Azteca</title>
        <meta name="description" content="Updates and statistics"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

        <!--begin::Fonts-->
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
        <!--end::Fonts-->

        <!--begin::Page Vendors Styles(used by this page)-->
            <link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css?v=7.0.6" rel="stylesheet" type="text/css"/>
            <link href="assets/plugins/custom/leaflet/leaflet.bundle.css?v=7.0.6" rel="stylesheet" type="text/css"/>
        <!--end::Page Vendors Styles-->


        <!--begin::Global Theme Styles(used by all pages)-->
            <link href="assets/plugins/global/plugins.bundle.css?v=7.0.6" rel="stylesheet" type="text/css"/>
            <link href="assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.6" rel="stylesheet" type="text/css"/>
            <link href="assets/css/style.bundle.css?v=7.0.6" rel="stylesheet" type="text/css"/>
        <!--end::Global Theme Styles-->

        <!--begin::Layout Themes(used by all pages)-->
        <!--end::Layout Themes-->

        <script type="text/javascript">
            var base_url = "<?php echo base_url("/") ?>";//No carga el archivo index
            var site_url = "<?php echo site_url("/") ?>";//Si carga el index 
            var user_name = "<?php echo $this->session->userdata('username') ?>";//Si carga el index 
        </script>

        <!-- Favicon-->
        <link rel="shortcut icon" href="<?php echo base_url('/assets/img/abarrotes-min.ico') ?>" >

        <?php if (isset($links) && $links): ?>
            <?php foreach ($links as $link): ?>
                <link rel="stylesheet" href="<?php echo base_url($link.'.css') ?>">
            <?php endforeach ?>
        <?php endif ?>

    </head>
    <!--end::Head-->
    <style>
        .blockElement{position:fixed !important;border:0 !important;background-color:rgba(0,0,0,0.8) !important;left:0 !important;top:0 !important;width:100vw !important;bottom:0;padding-top:20vh !important;border-radius:0 !important;}
    </style>