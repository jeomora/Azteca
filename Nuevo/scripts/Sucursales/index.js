var dataJSONArray ="";
var dataJSONArray2 ="";

var dataJSONArray3 ="";
var dataJSONArray4 ="";

var dataJSONArray6 ="";
var dataJSONArray5 ="";

//************ TABLAS LUNES
var KTDatatableDataLocalDemo = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getExistenciasLS().done(function(resp){
            var yeison = "[";
            if (resp) {
                $.each(resp,function(index, value){
                    value.descripcion = value.descripcion.replace(/"/g, "'");
                    yeison = yeison + '{"RecordID":"'+value.id_existencia+'","Descripcion":"'+value.descripcion+'","Codigo":"'+value.codigo+
                    '","Actions":null},\n'
                });
                dataJSONArray = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
            }

            setTimeout(function(){
                var datatable = $('#lunessin').KTDatatable({
                // datasource definition
                data: {
                    type: 'local',
                    source: dataJSONArray,
                    pageSize: 10,
                }, 

                // layout definition
                layout: {
                    scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
                    // height: 450, // datatable's body's fixed height
                    footer: false, // display/hide footer
                },

                // column sorting
                sortable: true,

                pagination: true,

                search: {
                    input: $('#generalSearch2'),
                },
                rows:{autoHide:!1},

                // columns definition
                columns: [
                    {
                        field: 'Codigo',
                        title: 'Codigo',
                        width: 150,
                    }, {
                        field: 'Descripcion',
                        title: 'Descripción',
                        template:function(row){
                            return '<div class="kt-user-card-v2">' +
                                    '<div class="kt-user-card-v2__details">' +
                                    '<a class="kt-user-card-v2__name">' + row.Descripcion + '</a>' +
                                    '</div>' +
                                    '</div>'
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
        });
    };

    return {
        // Public functions
        init: function() {
            // init dmeo
            demo();
        },
    };
}();

var KTDatatableDataLocalDemo2 = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getExistenciasLC().done(function(resp){
            var yeison = "[";
            if (resp) {
                $.each(resp,function(index, value){
                    value.piezas = value.piezas === null ? 0 : value.piezas;
                    value.cajas = value.cajas === null ? 0 : value.cajas;
                    value.pedido = value.pedido === null ? 0 : value.pedido;
                    value.descripcion = value.descripcion.replace(/"/g, "'");
                    yeison = yeison + '{"RecordID":"'+value.id_existencia+'","Descripcion":"'+value.descripcion+'","Codigo":"'+value.codigo+
                    '","Piezas":"'+value.piezas+'","Cajas":"'+value.cajas+'","Pedido":"'+value.pedido+'","Actions":null},\n'
                });
                dataJSONArray2 = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
            }
            setTimeout(function(){
                var datatable = $('#lunescon').KTDatatable({
                // datasource definition
                data: {
                    type: 'local',
                    source: dataJSONArray2,
                    pageSize: 5,
                }, 

                // layout definition
                layout: {
                    scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
                    // height: 450, // datatable's body's fixed height
                    footer: false, // display/hide footer
                },

                // column sorting
                sortable: true,

                pagination: true,

                search: {
                    input: $('#generalSearch3'),
                },
                rows:{autoHide:!1},

                // columns definition
                columns: [
                    {
                        field: 'Codigo',
                        title: 'Codigo',
                        // callback function support for column rendering
                        template: function(data, i) {
                            var output = '\
                                <div class="kt-user-card-v2">\
                                    <div class="kt-user-card-v2__details">\
                                        <a class="kt-user-card-v2__name">' + data.Codigo + '</a>\
                                    </div>\
                                </div>';

                            return output;
                        }
                    }, {
                        field: 'Descripcion',
                        title: 'Descripción',
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
                                    '<a class="kt-user-card-v2__name">' + row.Descripcion + '</a>' +
                                    '</div>' +
                                    '</div>'
                        },
                    }, {
                        field: 'Cajas',
                        title: 'Cajas',
                        width: 60,
                        autoHide: false,
                        // callback function support for column rendering
                        template: function(data, i) {
                            var output = '\
                                <div class="kt-user-card-v2">\
                                    <div class="kt-user-card-v2__details">\
                                        <a class="kt-user-card-v2__name">' + data.Cajas + '</a>\
                                    </div>\
                                </div>';

                            return output;
                        }
                    }, {
                        field: 'Piezas',
                        title: 'Piezas',
                        width: 60,
                        autoHide: false,
                        // callback function support for column rendering
                        template: function(data, i) {
                            var output = '\
                                <div class="kt-user-card-v2">\
                                    <div class="kt-user-card-v2__details">\
                                        <a class="kt-user-card-v2__name">' + data.Piezas + '</a>\
                                    </div>\
                                </div>';

                            return output;
                        }
                    }, {
                        field: 'Pedido',
                        title: 'Pedido',
                        width: 60,
                        autoHide: false,
                        // callback function support for column rendering
                        template: function(data, i) {
                            var output = '\
                                <div class="kt-user-card-v2">\
                                    <div class="kt-user-card-v2__details">\
                                        <a class="kt-user-card-v2__name">' + data.Pedido + '</a>\
                                    </div>\
                                </div>';

                            return output;
                        }
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
        });

    };

    return {
        // Public functions
        init: function() {
            // init dmeo
            demo();
        },
    };
}();

function getExistenciasLS() {
    return $.ajax({
        url: site_url+"Inicio/getLunesSin",
        type: "POST",
        dataType: "JSON",
    });
}

function getExistenciasLC() {
    return $.ajax({
        url: site_url+"Inicio/getLunesCon",
        type: "POST",
        dataType: "JSON",
    });
}


//************ TABLAS GENERAL

var KTDatatableDataLocalDemo3 = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getExistenciasGS().done(function(resp){
            var yeison = "[";
            if (resp) {
                $.each(resp,function(index, value){
                    value.descripcion = value.descripcion === null ? "" : value.descripcion;
                    value.descripcion = value.descripcion.replace(/"/g, "'");
                    value.descripcion = value.descripcion.replace(/"/g, "'");
                    value.descripcion = value.descripcion.replace(/\t/g, '');
                    yeison = yeison + '{"RecordID":"'+value.id_existencia+'","Descripcion":"'+value.descripcion+'","Codigo":"'+value.codigo+
                    '","Actions":null},\n'
                });
                dataJSONArray3 = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
            }

            setTimeout(function(){
                var datatable = $('#generalsin').KTDatatable({
                // datasource definition
                data: {
                    type: 'local',
                    source: dataJSONArray3,
                    pageSize: 10,
                }, 

                // layout definition
                layout: {
                    scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
                    // height: 450, // datatable's body's fixed height
                    footer: false, // display/hide footer
                },

                // column sorting
                sortable: true,

                pagination: true,

                search: {
                    input: $('#generalSearch3'),
                },
                rows:{autoHide:!1},

                // columns definition
                columns: [
                    {
                        field: 'Codigo',
                        title: 'Codigo',
                        width: 150,
                    }, {
                        field: 'Descripcion',
                        title: 'Descripción',
                        template:function(row){
                            return '<div class="kt-user-card-v2">' +
                                    '<div class="kt-user-card-v2__details">' +
                                    '<a class="kt-user-card-v2__name">' + row.Descripcion + '</a>' +
                                    '</div>' +
                                    '</div>'
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
        });
    };

    return {
        // Public functions
        init: function() {
            // init dmeo
            demo();
        },
    };
}();

var KTDatatableDataLocalDemo4 = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getExistenciasGC().done(function(resp){
            var yeison = "[";
            if (resp) {
                $.each(resp,function(index, value){
                    value.piezas = value.piezas === null ? 0 : value.piezas;
                    value.cajas = value.cajas === null ? 0 : value.cajas;
                    value.pedido = value.pedido === null ? 0 : value.pedido;
                    value.descripcion = value.descripcion === null ? "" : value.descripcion;
                    value.descripcion = value.descripcion.replace(/"/g, "'");
                    value.descripcion = value.descripcion.replace(/"/g, "'");
                    value.descripcion = value.descripcion.replace(/\t/g, '');
                    yeison = yeison + '{"RecordID":"'+value.id_existencia+'","Descripcion":"'+value.descripcion+'","Codigo":"'+value.codigo+
                    '","Piezas":"'+value.piezas+'","Cajas":"'+value.cajas+'","Pedido":"'+value.pedido+'","Actions":null},\n'
                });
                dataJSONArray4 = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
            }

            setTimeout(function(){
                var datatable = $('#generalcon').KTDatatable({
                // datasource definition
                data: {
                    type: 'local',
                    source: dataJSONArray4,
                    pageSize: 5,
                }, 

                // layout definition
                layout: {
                    scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
                    // height: 450, // datatable's body's fixed height
                    footer: false, // display/hide footer
                },

                // column sorting
                sortable: true,

                pagination: true,

                search: {
                    input: $('#generalSearch4'),
                },
                rows:{autoHide:!1},

                // columns definition
                columns: [
                    {
                        field: 'Codigo',
                        title: 'Codigo',
                        // callback function support for column rendering
                        template: function(data, i) {
                            var output = '\
                                <div class="kt-user-card-v2">\
                                    <div class="kt-user-card-v2__details">\
                                        <a class="kt-user-card-v2__name">' + data.Codigo + '</a>\
                                    </div>\
                                </div>';

                            return output;
                        }
                    }, {
                        field: 'Descripcion',
                        title: 'Descripción',
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
                                    '<a class="kt-user-card-v2__name">' + row.Descripcion + '</a>' +
                                    '</div>' +
                                    '</div>'
                        },
                    }, {
                        field: 'Cajas',
                        title: 'Cajas',
                        width: 60,
                        autoHide: false,
                        // callback function support for column rendering
                        template: function(data, i) {
                            var output = '\
                                <div class="kt-user-card-v2">\
                                    <div class="kt-user-card-v2__details">\
                                        <a class="kt-user-card-v2__name">' + data.Cajas + '</a>\
                                    </div>\
                                </div>';

                            return output;
                        }
                    }, {
                        field: 'Piezas',
                        title: 'Piezas',
                        width: 60,
                        autoHide: false,
                        // callback function support for column rendering
                        template: function(data, i) {
                            var output = '\
                                <div class="kt-user-card-v2">\
                                    <div class="kt-user-card-v2__details">\
                                        <a class="kt-user-card-v2__name">' + data.Piezas + '</a>\
                                    </div>\
                                </div>';

                            return output;
                        }
                    }, {
                        field: 'Pedido',
                        title: 'Pedido',
                        width: 60,
                        autoHide: false,
                        // callback function support for column rendering
                        template: function(data, i) {
                            var output = '\
                                <div class="kt-user-card-v2">\
                                    <div class="kt-user-card-v2__details">\
                                        <a class="kt-user-card-v2__name">' + data.Pedido + '</a>\
                                    </div>\
                                </div>';

                            return output;
                        }
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
        });
    };

    return {
        // Public functions
        init: function() {
            // init dmeo
            demo();
        },
    };
}();

function getExistenciasGS() {
    return $.ajax({
        url: site_url+"Inicio/getGeneralSin",
        type: "POST",
        dataType: "JSON",
    });
}

function getExistenciasGC() {
    return $.ajax({
        url: site_url+"Inicio/getGeneralCon",
        type: "POST",
        dataType: "JSON",
    });
}


//************ TABLAS GENERAL

var KTDatatableDataLocalDemo7 = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getExistenciasVS().done(function(resp){
            var yeison = "[";
            if (resp) {
                $.each(resp,function(index, value){
                    value.descripcion = value.descripcion === null ? "" : value.descripcion;
                    value.descripcion = value.descripcion.replace(/"/g, "'");
                    value.descripcion = value.descripcion.replace(/"/g, "'");
                    value.descripcion = value.descripcion.replace(/\t/g, '');
                    yeison = yeison + '{"RecordID":"'+value.id_existencia+'","Descripcion":"'+value.descripcion+'","Codigo":"'+value.codigo+
                    '","Actions":null},\n'
                });
                dataJSONArray5 = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
            }

            setTimeout(function(){
                var datatable = $('#volumensin').KTDatatable({
                // datasource definition
                data: {
                    type: 'local',
                    source: dataJSONArray5,
                    pageSize: 10,
                }, 

                // layout definition
                layout: {
                    scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
                    // height: 450, // datatable's body's fixed height
                    footer: false, // display/hide footer
                },

                // column sorting
                sortable: true,

                pagination: true,

                search: {
                    input: $('#generalSearch6'),
                },
                rows:{autoHide:!1},

                // columns definition
                columns: [
                    {
                        field: 'Codigo',
                        title: 'Codigo',
                        width: 150,
                    }, {
                        field: 'Descripcion',
                        title: 'Descripción',
                        template:function(row){
                            return '<div class="kt-user-card-v2">' +
                                    '<div class="kt-user-card-v2__details">' +
                                    '<a class="kt-user-card-v2__name">' + row.Descripcion + '</a>' +
                                    '</div>' +
                                    '</div>'
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
        });
    };

    return {
        // Public functions
        init: function() {
            // init dmeo
            demo();
        },
    };
}();

var KTDatatableDataLocalDemo6 = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getExistenciasVC().done(function(resp){
            var yeison = "[";
            if (resp) {
                $.each(resp,function(index, value){
                    value.piezas = value.piezas === null ? 0 : value.piezas;
                    value.cajas = value.cajas === null ? 0 : value.cajas;
                    value.pedido = value.pedido === null ? 0 : value.pedido;
                    value.descripcion = value.descripcion === null ? "" : value.descripcion;
                    value.descripcion = value.descripcion.replace(/"/g, "'");
                    value.descripcion = value.descripcion.replace(/"/g, "'");
                    value.descripcion = value.descripcion.replace(/\t/g, '');
                    yeison = yeison + '{"RecordID":"'+value.id_existencia+'","Descripcion":"'+value.descripcion+'","Codigo":"'+value.codigo+
                    '","Piezas":"'+value.piezas+'","Cajas":"'+value.cajas+'","Pedido":"'+value.pedido+'","Actions":null},\n'
                });
                dataJSONArray6 = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
            }

            setTimeout(function(){
                var datatable = $('#volumencon').KTDatatable({
                // datasource definition
                data: {
                    type: 'local',
                    source: dataJSONArray6,
                    pageSize: 5,
                }, 

                // layout definition
                layout: {
                    scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
                    // height: 450, // datatable's body's fixed height
                    footer: false, // display/hide footer
                },

                // column sorting
                sortable: true,

                pagination: true,

                search: {
                    input: $('#generalSearch7'),
                },
                rows:{autoHide:!1},

                // columns definition
                columns: [
                    {
                        field: 'Codigo',
                        title: 'Codigo',
                        // callback function support for column rendering
                        template: function(data, i) {
                            var output = '\
                                <div class="kt-user-card-v2">\
                                    <div class="kt-user-card-v2__details">\
                                        <a class="kt-user-card-v2__name">' + data.Codigo + '</a>\
                                    </div>\
                                </div>';

                            return output;
                        }
                    }, {
                        field: 'Descripcion',
                        title: 'Descripción',
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
                                    '<a class="kt-user-card-v2__name">' + row.Descripcion + '</a>' +
                                    '</div>' +
                                    '</div>'
                        },
                    }, {
                        field: 'Cajas',
                        title: 'Cajas',
                        width: 60,
                        autoHide: false,
                        // callback function support for column rendering
                        template: function(data, i) {
                            var output = '\
                                <div class="kt-user-card-v2">\
                                    <div class="kt-user-card-v2__details">\
                                        <a class="kt-user-card-v2__name">' + data.Cajas + '</a>\
                                    </div>\
                                </div>';

                            return output;
                        }
                    }, {
                        field: 'Piezas',
                        title: 'Piezas',
                        width: 60,
                        autoHide: false,
                        // callback function support for column rendering
                        template: function(data, i) {
                            var output = '\
                                <div class="kt-user-card-v2">\
                                    <div class="kt-user-card-v2__details">\
                                        <a class="kt-user-card-v2__name">' + data.Piezas + '</a>\
                                    </div>\
                                </div>';

                            return output;
                        }
                    }, {
                        field: 'Pedido',
                        title: 'Pedido',
                        width: 60,
                        autoHide: false,
                        // callback function support for column rendering
                        template: function(data, i) {
                            var output = '\
                                <div class="kt-user-card-v2">\
                                    <div class="kt-user-card-v2__details">\
                                        <a class="kt-user-card-v2__name">' + data.Pedido + '</a>\
                                    </div>\
                                </div>';

                            return output;
                        }
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
        });
    };

    return {
        // Public functions
        init: function() {
            // init dmeo
            demo();
        },
    };
}();

function getExistenciasVS() {
    return $.ajax({
        url: site_url+"Inicio/getVolumenSin",
        type: "POST",
        dataType: "JSON",
    });
}

function getExistenciasVC() {
    return $.ajax({
        url: site_url+"Inicio/getVolumenCon",
        type: "POST",
        dataType: "JSON",
    });
}


jQuery(document).ready(function() {
    $("#titlePrincipal").html("Mis Existencias");
    KTDatatableDataLocalDemo.init();
    KTDatatableDataLocalDemo2.init();

    KTDatatableDataLocalDemo3.init();
    KTDatatableDataLocalDemo4.init();

    KTDatatableDataLocalDemo7.init();
    KTDatatableDataLocalDemo6.init();
});

var theDate = new Date().getTime();
Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("div#kt_dropzone_sucursal", {
    paramName: "file_existencias",
    maxFiles: 1,
    maxFilesize: 200, // MB
    timeout: 1800000,
    renameFilename: function (filename) {
        return theDate + '_' + filename;
    },
    url: site_url+"Sucursales/upload_existencias",                      
    autoProcessQueue: true,
    queuecomplete: function (resp) {
        blockPage()
    },
    success: function(file, response){
        myDropzone.removeAllFiles()
        setTimeout(function(){
            location.reload();
        },1000);        
    }
});
