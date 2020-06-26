'use strict';
// Class definition
var dataJSONArray = "";
var dataJSONArray2 = "";
var KTDatatableDataLocalDemo = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getUsuarios().done(function(resp){
            if (resp.existencias.length > 0) {
                var yeison = "[";
                $.each(resp.existencias,function(index, value){
                    value.descripcion = value.descripcion.replace(/"/g, "");
                    yeison = yeison + '{"RecordID":'+value.id_pedido+',"Codigo":"'+value.codigo+'","Descripcion":"'+value.descripcion+
                    '","Cajas":"'+value.cajas+'","Piezas":"'+value.piezas+'","Pedido":"'+value.pedido+'"},\n'

                });
                console.log(yeison)
               dataJSONArray = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
            } else {
                dataJSONArray = null;
            }
        });


        setTimeout(function(){
            var datatable = $('#kt-datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'local',
                source: dataJSONArray,
                pageSize: 20,
            },

            // layout definition
            layout: {
                scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
                // height: 450, // datatable's body's fixed height
                footer: false, // display/hide footer
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [
                {
                    field: 'RecordID',
                    title: 'ID',
                    width: 0,
                }, {
                    field: 'Codigo',
                    title: 'Código',
                }, {
                    field: 'Descripcion',
                    title: 'Descripción',
                }, {
                    field: 'Cajas',
                    title: 'Cajas',
                },{
                    field: 'Piezas',
                    title: 'Piezas',
                },{
                    field: 'Pedido',
                    title: 'Pedido',
                    template: function(row) {
                        var status = {
                            1: {'title': 'Activo', 'class': 'kt-badge--success'},
                            0: {'title': 'Eliminado', 'class': ' kt-badge--danger'},//ROSA
                            3: {'title': 'Canceled', 'class': ' kt-badge--primary'},//AZÚL
                            4: {'title': 'Success', 'class': ' kt-badge--success'},//VERDE
                            5: {'title': 'Info', 'class': ' kt-badge--info'},//AZÚL
                            6: {'title': 'Danger', 'class': ' kt-badge--danger'},//ROSA
                            7: {'title': 'Warning', 'class': ' kt-badge--warning'},//AMARILLO
                        };
                        return '<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill">' + row.Pedido + '</span>';
                    },
                }],
        });

        $('#kt_form_status').on('change', function() {
            datatable.search($(this).val().toLowerCase(), 'Status');
        });

        $('#kt_form_type').on('change', function() {
            datatable.search($(this).val().toLowerCase(), 'Type');
        });

        $('#kt_form_status,#kt_form_type').selectpicker();
        },1000)

        

    };

    return {
        // Public functions
        init: function() {
            // init dmeo
            demo();
        },
    };
}();

jQuery(document).ready(function() {
    $(".kt-subheader__title").html("Agregar Existencias Perfumería y Farmacia");
    $(".kt-subheader__breadcrumbs-link").html("Existencias")
    $(".kt-subheader__breadcrumbs-link").attr("href",site_url+"Inicio")
    KTDatatableDataLocalDemo.init();
    KTDatatableDataLocalDemo2.init();
    
});

function getUsuarios() {
    return $.ajax({
        url: site_url+"Inicio/allpedido",
        type: "POST",
        dataType: "JSON",
    });
}

$(document).off("change", "#customFileLang").on("change", "#customFileLang", function(event) {
    event.preventDefault();
    blockPage();
    var fdata = new FormData($("#upload_existencias")[0]);
    uploadPedidos(fdata).done(function (resp) {
        if (resp.type == 'error'){
            toastr.error(resp.desc, user_name);
            unblockPage();
        }else{
            unblockPage();
            setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
        }
    }); 
    $(this).val("");
    
});

function uploadPedidos(formData) {
    return $.ajax({
        url: site_url+"Pedidos/upload_pedid/0",
        type: "POST",
        cache: false,
        contentType: false,
        processData:false,
        dataType:"JSON",
        data: formData,
    });
}

var KTDatatableDataLocalDemo2 = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getUsuarios().done(function(resp){
            if (resp.existencias2.length > 0) {
                var yeison = "[";
                $.each(resp.existencias2,function(index, value){
                    value.descripcion = value.descripcion.replace(/"/g, "");
                    yeison = yeison + '{"RecordID":'+value.id_pedido+',"Codigo":"'+value.codigo+'","Descripcion":"'+value.descripcion+
                    '","Cajas":"'+value.cajas+'","Piezas":"'+value.piezas+'","Pedido":"'+value.pedido+'"},\n'

                });
                console.log(yeison)
               dataJSONArray2 = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
            } else {
                dataJSONArray2 = null;
            }
        });


        setTimeout(function(){
            var datatable = $('#kt-datatable2').KTDatatable({
            // datasource definition
            data: {
                type: 'local',
                source: dataJSONArray2,
                pageSize: 20,
            },

            // layout definition
            layout: {
                scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
                // height: 450, // datatable's body's fixed height
                footer: false, // display/hide footer
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#generalSearch2'),
            },

            // columns definition
            columns: [
                {
                    field: 'Codigo',
                    title: 'Código',
                }, {
                    field: 'Descripcion',
                    title: 'Descripción',
                }, {
                    field: 'Cajas',
                    title: 'Cajas',
                },{
                    field: 'Piezas',
                    title: 'Piezas',
                },{
                    field: 'Pedido',
                    title: 'Pedido',
                    template: function(row) {
                        var status = {
                            1: {'title': 'Activo', 'class': 'kt-badge--success'},
                            0: {'title': 'Eliminado', 'class': ' kt-badge--danger'},//ROSA
                            3: {'title': 'Canceled', 'class': ' kt-badge--primary'},//AZÚL
                            4: {'title': 'Success', 'class': ' kt-badge--success'},//VERDE
                            5: {'title': 'Info', 'class': ' kt-badge--info'},//AZÚL
                            6: {'title': 'Danger', 'class': ' kt-badge--danger'},//ROSA
                            7: {'title': 'Warning', 'class': ' kt-badge--warning'},//AMARILLO
                        };
                        return '<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill">' + row.Pedido + '</span>';
                    },
                }],
        });

        $('#kt_form_status').on('change', function() {
            datatable.search($(this).val().toLowerCase(), 'Status');
        });

        $('#kt_form_type').on('change', function() {
            datatable.search($(this).val().toLowerCase(), 'Type');
        });

        $('#kt_form_status,#kt_form_type').selectpicker();
        },1000)

        

    };

    return {
        // Public functions
        init: function() {
            // init dmeo
            demo();
        },
    };
}();

$(document).off("change", "#customFileLang2").on("change", "#customFileLang2", function(event) {
    event.preventDefault();
    blockPage();
    var fdata = new FormData($("#upload_existencias2")[0]);
    uploadPedidos2(fdata).done(function (resp) {
        if (resp.type == 'error'){
            toastr.error(resp.desc, user_name);
            unblockPage();
        }else{
            unblockPage();
            setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
        }
    }); 
    $(this).val("");
    
});

function uploadPedidos2(formData) {
    return $.ajax({
        url: site_url+"Pedidos/upload_pedid2/0",
        type: "POST",
        cache: false,
        contentType: false,
        processData:false,
        dataType:"JSON",
        data: formData,
    });
}