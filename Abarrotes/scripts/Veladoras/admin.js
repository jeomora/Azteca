'use strict';
// Class definition
var dataJSONArray = "";
var imageName = "";
var KTDatatableDataLocalDemo = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getUsuarios().done(function(resp){
            var yeison = "[";
            if (resp) {
                $.each(resp.data,function(index, value){
                    if(value.Imagen === null){value.Imagen = "no"}
                    yeison = yeison + '{"RecordID":'+value.id_usuario+',"Country":"'+value.nombre+'","ShipName":"'+value.ShipName+'","Imagen":"'+value.Imagen+
                    '","Status":"'+value.Status+'","Image":"'+value.Actions+'"},\n'

                });
            }
           dataJSONArray = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
        });


        setTimeout(function(){
            var datatable = $('.kt-datatable').KTDatatable({
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
                    sortable: true,
                    width: 40,
                    type: 'number',
                    textAlign: 'center',
                }, {
                    field: 'Country',
                    title: 'CÓDIGO',
                    template: function(row) {
                        return '<div class="input-group">'+row.Country+
                                '</div>'
                    }
                }, {
                    field: 'ShipName',
                    title: 'FAMILIA',
                    autoHide: false,
                    // callback function support for column rendering
                    template: function(row) {
                        return '<div class="input-group" style="font-size:12px">'+row.ShipName+
                                '</div>'
                    }
                }, {
                    field: 'Status',
                    title: 'DESCRIPCIÓN',
                    width: 250,
                    template: function(row) {
                        return '<div class="input-group">'+row.Status+
                                '</div>'
                    }
                }, {
                    field: 'Image',
                    title: 'IMAGEN',
                    template: function(row) {
                        console.log(row.Imagen)
                        var imds = row.Imagen;
                        if(row.Imagen === "no"){
                            imds = "sinimagen.jpg"
                        };
                        return '<div class="input-group"><img style="max-width: 250px;" src="'+site_url+'/assets/img/productos/'+imds+'" />'+
                                '</div>'
                    },
                },{
                    field: 'Actions',
                    title: 'Editar<br>Imagen',
                    sortable: false,
                    width: 110,
                    overflow: 'visible',
                    autoHide: false,
                    template: function(data, i){
                        return '\
                        <a id="edituser" data-id-user="'+data.RecordID+'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Editar" data-toggle="modal" data-target="#kt_modal_4">\
                            <i class="la la-edit"></i>\
                        </a>\
                    ';
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
    $(".kt-header__brand-logo").removeAttr("href");
    $(".kt-subheader__title").html("Productos");
    $(".kt-subheader__breadcrumbs-link").html("Catálogo")
    $(".kt-subheader__breadcrumbs-link").attr("href",site_url+"Catalogo")
    KTDatatableDataLocalDemo.init();
});

function getUsuarios() {
    return $.ajax({
        url: site_url+"Catalogo/getProductos",
        type: "POST",
        dataType: "JSON",
    });
}

$(document).off("click", "#edituser").on("click", "#edituser", function(event) {
    event.preventDefault();

    getUser($(this).data("idUser"))
        .done(function (resp) {
            $("#id_produs").val(resp.id_producto);
            $(".imgprod").attr("src",resp.imagen);
        });
});

function getUser(formData) {
    return $.ajax({
        url: site_url+"Catalogo/getProd/"+formData,
        type: "POST",
        dataType: "JSON",
    });
}

$(document).off("click", ".update_usuario").on("click", ".update_usuario", function(event) {
    event.preventDefault();
    if($("#form_usuario_new").valid()){
        var extencion = $(".dz-filename span").html()
        var values = {"id_producto":$("#id_produs").val(),"imagen":imageName}
        
        altaVela(JSON.stringify(values)).done(function (resp){
            toastr.success("Se agrego la imagen", user_name)
            setTimeout("location.reload()", 700, toastr.success("Se actualizara la pestaña", user_name), "");
        })
    }
});
function goBack() {
  window.history.back();
}

Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("div#my-dropzone", {
    paramName: "file_otizaciones",
    maxFiles: 1,
    maxFilesize: 1000000, // MB
    url: site_url+"Catalogo/subirImg",   
    renameFilename: function (filename) {
        return  filename;
    },
    autoProcessQueue: true,
    queuecomplete:function(resp) {

    },
    success: function(file, response){
        imageName = response;
    }
});

function altaVela(values){
    return $.ajax({
        url: site_url+"/Catalogo/altaVela",
        type: "POST",
        dataType: "JSON",
        data: {
            values : values
        },
    });
}
