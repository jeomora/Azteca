jQuery(document).ready(function() {
    $("#titlePrincipal").html("Productos");
    KTDatatableDataLocalDemo.init();
    toastr.options = {
          "closeButton": true,
          "debug": false,
          "newestOnTop": false,
          "progressBar": true,
          "positionClass": "toast-top-right",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "3000",
          "timeOut": "3000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
    };
});
var dataJSONArray ="";

//************ TABLA PRODUCTOS
var KTDatatableDataLocalDemo = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getProductos().done(function(resp){
            var yeison = "[";
            if (resp) {
                $.each(resp,function(index, value){
                    value.familia =  value.familia === null ? "" :  value.familia;
                    value.pieza =  value.pieza === null ? "---" :  value.pieza;
                    value.producto =  value.producto === null ? "" :  value.producto;
                    value.imagen =  value.imagen === null ? "sinimagen_thumb.png" :  value.imagen;

                    value.producto = value.producto.replace(/"/g, "'");
                    value.familia = value.familia.replace(/"/g, "'");

                    value.familia = value.familia.replace(/\t/g, "'");
                    value.producto = value.producto.replace(/\t/g, "'");
                    yeison = yeison + '{"RecordID":"'+value.id_producto+'","Descripcion":"'+value.producto+'","Codigo":"'+value.codigo+'","Pieza":"'+value.pieza+'"'+
                            ',"Estatus":"'+value.estatus+'","Color":"'+value.color+'","Colorp":"'+value.pieza+'","Unidad":"'+value.unidad+'","Familia":"'+value.familia+'"'+
                    ',"Imagen":"'+value.imagen+'","Actions":null},\n'
                });
                dataJSONArray = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
            }

            setTimeout(function(){
                var datatable = $('#prodTable').KTDatatable({
                // datasource definition
                data: {
                    type: 'local',
                    source: dataJSONArray,
                    pageSize: 10,
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
                    input: $('#productSearch'),
                },

                search: {
                    input: $('#productSearch'),
                    key: 'generalSearch'
                },

                // columns definition
                columns: [
                    {
                        field: 'RecordID',
                        title: '#',
                        width: 50,
                        type: "number"
                    },{
                        field: 'Codigo',
                        title: 'Código',
                        width: 100,
                        template: function(row) {
                            return row.Codigo;
                        },
                    }, {
                        field: 'Descripcion',
                        title: 'Descripción',
                        width: 270,
                        template: function(data) {
                            var user_img = 'background-image:url(\'assets/img/productos/' + data.Imagen + '\');cursor:pointer;';

                             output = '<div class="d-flex align-items-center">\
                                    <div class="symbol symbol-40 flex-shrink-0">\
                                        <div data-id-prod="'+data.Imagen+'" class="symbol-label" style="' + user_img + '" data-toggle="modal" data-target="#kt_imagen" id="prodimg"></div>\
                                    </div>\
                                    <div class="ml-2">\
                                        <div class="text-dark-75 font-weight-bold line-height-sm">' + data.Descripcion + '</div>\
                                        <a class="font-size-sm text-dark-50 text-hover-primary">' +
                                        data.Familia + '</a>\
                                    </div>\
                                </div>';
                            return output;
                        }
                    },{
                        field: 'Pieza',
                        title: 'Cod pza',
                        width: 100,
                        template: function(row) {
                            return row.Pieza;
                        },
                    },{
                        field: 'Unidad',
                        title: 'UM',
                        width: 20,
                        template: function(row) {
                            return row.Unidad;
                        },
                    }, {
                        field: 'Estatus',
                        title: 'Estatus',
                        width: 100,
                        // callback function support for column rendering
                        template: function(row) {
                            var status = {
                                1: {
                                    'title': 'Normal',
                                    'class': ' label-light-primary'
                                },
                                2: {
                                    'title': 'Volúmen',
                                    'class': ' label-light-dark'
                                },
                                3: {
                                    'title': 'Amarillos',
                                    'class': ' label-light-warning'
                                },
                                4: {
                                    'title': 'Moderna',
                                    'class': ' label-light-success'
                                },
                                5: {
                                    'title': 'Costeña',
                                    'class': ' label-light-success'
                                },
                                6: {
                                    'title': 'Cuetara',
                                    'class': ' label-light-success'
                                }
                            };
                            return '<span class="label font-weight-bold label-lg' + status[row.Estatus].class + ' label-inline">' + status[row.Estatus].title + '</span>';
                        },
                    }, {
                        field: 'Actions',
                        title: 'Acciones',
                        sortable: false,
                        width: 125,
                        autoHide: false,
                        overflow: 'visible',
                        template: function(row) {
                            return '\
                                <div class="dropdown dropdown-inline">\
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" title="Editar Producto" data-toggle="modal" id="editprod" data-target="#kt_edit_prod" data-id-prod="'+row.RecordID+'">\
                                    <span class="svg-icon svg-icon-md">\
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                <rect x="0" y="0" width="24" height="24"/>\
                                                <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
                                                <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
                                            </g>\
                                        </svg>\
                                    </span>\
                                </a>\
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon" title="Eliminar" data-toggle="modal" data-target="#kt_del_prod" data-id-prod="'+row.RecordID+'" id="liusered">\
                                    <span class="svg-icon svg-icon-md">\
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                <rect x="0" y="0" width="24" height="24"/>\
                                                <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>\
                                                <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>\
                                            </g>\
                                        </svg>\
                                    </span>\
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

function getProducto(id){
    return $.ajax({
        url: site_url+"Productos/getProducto/"+id,
        type: "POST",
        dataType: "JSON",
    });
}

function getProductos(){
    return $.ajax({
        url: site_url+"Productos/getProductos",
        type: "POST",
        dataType: "JSON",
    });
}

$(document).off("click", ".delete_usuario").on("click", ".delete_usuario", function(event) {
    event.preventDefault();
    blockPageDelete()
    $(".blockElement").css("background-color","rgba(177,110,41,0.8) !important")
    $('.delete_usuario').prop("disabled", true);
    sendFormas("Productos/delete_producto", $("#form_producto_delete"), "");
});

$(document).off("click", "#prodimg").on("click", "#prodimg", function(event) {
    event.preventDefault();
    $("#imgprod").attr("src","assets/img/productos/"+$(this).data("idProd").replace("_thumb",""));
});

$(document).off("click", "#liusered").on("click", "#liusered", function(event) {
    event.preventDefault();
    getProds($(this).data("idProd"))
        .done(function (resp) {
            $("#spanprodr").html(resp.nombre);
            $("#id_producto").val(resp.id_producto);
        });
});

function getProds(formData) {
    return $.ajax({
        url: site_url+"Productos/getProds/"+formData,
        type: "POST",
        dataType: "JSON",
    });
}

$(document).off("click", "#editprod").on("click", "#editprod", function(event) {
    event.preventDefault();

    getProducto($(this).data("idProd"))
        .done(function (resp) {
            $("#id_productos").val(resp.id_producto);
            $("#codigo2").val(resp.codigo);
            $("#codigo").val(resp.codigo);
            $("#pieza").val(resp.pieza);
            $("#unidad").val(resp.unidad);
            $("#nombre").val(resp.nombre);
            $("#id_familia").val(resp.id_familia);
            $("#estatus").val(resp.estatus);
            $("#colorp").val(resp.colorp);
            $("#casa").val(resp.casa);
        });
});

$(document).off("click", ".update_producto").on("click", ".update_producto", function(event) {
    event.preventDefault();

    var flag = 1;
    flag = valis($("#codigo"),$("#codigoFeed"),flag);
    flag = valis($("#unidad"),$("#unidadFeed"),flag);
    flag = valis($("#nombre"),$("#nombreFeed"),flag);
    //flag = valis($("#id_familia"),$("#familiaFeed"),flag);
    if(flag){
        $('.update_producto').prop("disabled", true);
        sendFormas("Productos/update_producto", $("#form_producto_edit"), $('.update_producto'));
    }
});


$(document).off("click", ".agregar_producto").on("click", ".agregar_producto", function(event) {
    event.preventDefault();

    var flag = 1;
    flag = valis($("#codigoA"),$("#codigoFeedA"),flag);
    flag = valis($("#unidadA"),$("#unidadFeedA"),flag);
    flag = valis($("#nombreA"),$("#nombreFeedA"),flag);
    //flag = valis($("#id_familia"),$("#familiaFeedA"),flag);
    if(flag){
        $('.agregar_producto').prop("disabled", true);
        sendFormas("Productos/agregar_producto", $("#form_agregar_producto"), $('.agregar_producto'));
    }
});

var theDate = new Date().getTime();
Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("div#my-dropzoneProd", {
    paramName: "file_productos",
    maxFiles: 1,
    maxFilesize: 1000000, // MB
    renameFilename: function (filename) {
        return theDate + '_' + filename;
    },
    url: site_url+"Productos/upload_productosum",                      
    autoProcessQueue: true,
    queuecomplete: function (resp) {
        blockPageBlocks()
        setTimeout(function() {
            unblockPage();
            myDropzone.removeAllFiles()
        },1000);
    },
    success: function(file, response){
        console.log(response)
        if(response.type === "error"){
            blockPageE()
            toastr.error("Revise su archivo e intentelo nuevamente",response.desc)
            setTimeout(function(){unblockPage;},1500)
        }else if(response.type === "warning"){
            blockPageBlocks();
            toastr.warning("Revise su archivo",response.desc)
            setTimeout(function(){location.reload();},1500)
        }else{
            toastr.options = {
                  "closeButton": true,
                  "debug": false,
                  "newestOnTop": false,
                  "progressBar": true,
                  "positionClass": "toast-top-right",
                  "preventDuplicates": false,
                  "onclick": null,
                  "showDuration": "300",
                  "hideDuration": "1000",
                  "timeOut": "1000",
                  "extendedTimeOut": "1000",
                  "showEasing": "swing",
                  "hideEasing": "linear",
                  "showMethod": "fadeIn",
                  "hideMethod": "fadeOut"
            };
            toastr.success("Listo",response.desc);
            setTimeout(function(){location.reload();},1100)
        }
    }
});