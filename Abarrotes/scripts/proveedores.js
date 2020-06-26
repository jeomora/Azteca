'use strict';
// Class definition
var dataJSONArray = "";
var dataJSONArray2 = "";
var datatable ="";
var datatable2 ="";
var prvdr = $("#kt_select2_1 option:selected").val();
var KTDatatableDataLocalDemo = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getProveedorCot().done(function(resp){
            var yeison = "[";
            if (resp.data.length > 0) {
                $.each(resp.data,function(index, value){
                    value.observaciones = value.observaciones == "" ? 'Sin observaciones' : value.observaciones;
                    value.observaciones = value.observaciones == null ? 'Sin observaciones' : value.observaciones;
                    value.num_one = value.num_one == null ? 0 : value.num_one;
                    value.num_two = value.num_two == null ? 0 : value.num_two;
                    value.descuento = value.descuento == null ? 0 : value.descuento;

                    yeison = yeison + '{"RecordID":'+value.id_cotizacion+',"Index":"'+(index+1)+'","Country":"'+value.producto+'","ShipCountry":"'+value.precio+
                    '","ShipName":"'+value.precio_promocion+'","CompanyEmail":"'+value.descuento+
                    '","CompanyName":"'+value.num_one+'","Status":'+value.num_two+',"Type":"'+value.observaciones+'","Actions":null},\n'
                });
                //console.log(resp.data)
                dataJSONArray = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
            }else{
                 dataJSONArray = null;
            }
        });


        setTimeout(function(){
            datatable = $('#kt-datatable').KTDatatable({
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
                    field: 'Index',
                    title: 'ID',
                    sortable: true,
                    width: 20,
                    type: 'number',
                    textAlign: 'left',
                }, {
                    field: 'Country',
                    title: 'Producto',
                    width: 200,
                    type: 'text',
                    textAlign: 'left',
                    template: function(row) {
                        var stateNo = KTUtil.getRandomInt(0, 6);
                        var states = [
                            'success',
                            'brand',
                            'danger',
                            'success',
                            'warning',
                            'primary',
                            'info'
                        ];
                        var state = states[stateNo];
                        return '<div class="kt-user-card-v2">' +
                                '<div class="kt-user-card-v2__details">' +
                                '<a class="kt-user-card-v2__name">' + row.Country + '</a>' +
                                '<span class="kt-user-card-v2__desc">Perfumería</span>' +
                                '</div>' +
                                '</div>'
                    },
                }, {
                    field: 'ShipName',
                    title: 'Precio promoción',
                    width: 'auto',
                    autoHide: false,
                    textAlign: 'right',
                    template: function(row) {
                        return '<span>$ '+formatMoney(row.ShipName,2)+'</span>';
                    },
                },{
                    field: 'ShipCountry',
                    title: 'Precio',
                    width: 'auto',
                    autoHide: false,
                    textAlign: 'right',
                    template: function(row) {
                        return '<span>$ '+formatMoney(row.ShipCountry,2)+'</span>';
                    },
                },{
                    field: 'CompanyEmail',
                    title: '% Descuento',
                    width: 'auto',
                    autoHide: false,
                    textAlign: 'right',
                }, {
                    field: 'Status',
                    title: 'Promoción',
                    textAlign: 'right',
                    // callback function support for column rendering
                    template: function(row) {
                        return '<span>'+row.CompanyName+' en '+row.Status+'</span>';
                    },
                },{
                    field: 'Type',
                    title: 'Observaciones',
                    width: 'auto',
                    autoHide: false,
                    textAlign: 'right',
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
    $(".kt-subheader__title").html(user_name);
    $(".kt-subheader__breadcrumbs-link").html("Agregar Cotizaciones Perfumería y Farmacia")
    $(".kt-subheader__breadcrumbs-link").attr("href",site_url+"/Inicio"); 
    KTDatatableDataLocalDemo.init();
    KTDatatableDataLocalDemo2.init();
});

function getProveedorCot(id_prov) {
    return $.ajax({
        url: site_url+"/CotizacionesP/proveedorCots/0",
        type: "POST",
        dataType: "JSON"
    });
}

$(document).off("change", "#customFileLang").on("change", "#customFileLang", function(event) {
    event.preventDefault();
    var fdata = new FormData($("#upload_cotizacionesP")[0]);
    blockPage();
    uploadExistencias(fdata).done(function(resp){
        if (resp.type == 'error'){
            toastr.error("No se actualizaron correctamente las Cotizaciones", user_name);
            unblockPage();
            $("#customFileLang").val("");
        }else{
            unblockPage();
            $("#customFileLang").val("");
            location.reload();
        }
    })
});

function uploadExistencias(formData) {
    return $.ajax({
        url: site_url+"CotizacionesP/upload_procotizaciones/0",
        type: "POST",
        cache: false,
        contentType: false,
        processData:false,
        dataType:"JSON",
        data: formData,
    });
}

$(document).off("change", "#customFile").on("change", "#customFile", function(event) {
    event.preventDefault();
    var fdata = new FormData($("#upload_cotizacionesF")[0]);
    blockPage();
    uploadExisten(fdata).done(function(resp){
        if (resp.type == 'error'){
            toastr.error("No se actualizaron correctamente las Cotizaciones", user_name);
            unblockPage();
            $("#customFileLang").val("");
        }else{
            unblockPage();
            $("#customFileLang").val("");
            location.reload();
        }
    })
});

function uploadExisten(formData) {
    return $.ajax({
        url: site_url+"CotizacionesF/upload_procotizaciones/0",
        type: "POST",
        cache: false,
        contentType: false,
        processData:false,
        dataType:"JSON",
        data: formData,
    });
}

function getProveedorCotF(id_prov) {
    return $.ajax({
        url: site_url+"/CotizacionesF/proveedorCots/0",
        type: "POST",
        dataType: "JSON"
    });
}

var KTDatatableDataLocalDemo2 = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getProveedorCotF().done(function(resp){
            var yeison = "[";
            if (resp.data.length > 0) {
                $.each(resp.data,function(index, value){
                    value.observaciones = value.observaciones == "" ? 'Sin observaciones' : value.observaciones;
                    value.observaciones = value.observaciones == null ? 'Sin observaciones' : value.observaciones;
                    value.num_one = value.num_one == null ? 0 : value.num_one;
                    value.num_two = value.num_two == null ? 0 : value.num_two;
                    value.descuento = value.descuento == null ? 0 : value.descuento;

                    yeison = yeison + '{"RecordID":'+value.id_cotizacion+',"Index":"'+(index+1)+'","Country":"'+value.producto+'","ShipCountry":"'+value.precio+
                    '","ShipName":"'+value.precio_promocion+'","CompanyEmail":"'+value.descuento+
                    '","CompanyName":"'+value.num_one+'","Status":'+value.num_two+',"Type":"'+value.observaciones+'","Actions":null},\n'
                });
                //console.log(resp.data)
                dataJSONArray2 = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
            }else{
                dataJSONArray2 = null;
            }
        });


        setTimeout(function(){
            datatable2= $('#kt-datatable2').KTDatatable({
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
                    field: 'Index',
                    title: 'ID',
                    sortable: true,
                    width: 20,
                    type: 'number',
                    textAlign: 'left',
                },{
                    field: 'Country',
                    title: 'Producto',
                    width: 200,
                    autoHide: false,
                    type: 'text',
                    textAlign: 'left',
                    template: function(row) {
                        var stateNo = KTUtil.getRandomInt(0, 6);
                        var states = [
                            'success',
                            'brand',
                            'danger',
                            'success',
                            'warning',
                            'primary',
                            'info'
                        ];
                        var state = states[stateNo];
                        return '<div class="kt-user-card-v2">' +
                                '<div class="kt-user-card-v2__details">' +
                                '<a class="kt-user-card-v2__name">' + row.Country + '</a>' +
                                '<span class="kt-user-card-v2__desc">Farmacia</span>' +
                                '</div>' +
                                '</div>'
                    },
                }, {
                    field: 'ShipName',
                    title: 'Precio promoción',
                    autoHide: false,
                    textAlign: 'right',
                    template: function(row) {
                        return '<span>$ '+formatMoney(row.ShipName,2)+'</span>';
                    },
                },{
                    field: 'ShipCountry',
                    title: 'Precio',
                    autoHide: false,
                    textAlign: 'right',
                    template: function(row) {
                        return '<span>$ '+formatMoney(row.ShipCountry,2)+'</span>';
                    },
                },{
                    field: 'CompanyEmail',
                    title: '% Descuento',
                    autoHide: false,
                    textAlign: 'right',
                }, {
                    field: 'Status',
                    title: 'Promoción',
                    textAlign: 'right',
                    // callback function support for column rendering
                    template: function(row) {
                        return '<span>'+row.CompanyName+' en '+row.Status+'</span>';
                    },
                },{
                    field: 'Type',
                    title: 'Observaciones',
                    width: 'auto',
                    autoHide: false,
                    textAlign: 'right',
                }],
        });

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