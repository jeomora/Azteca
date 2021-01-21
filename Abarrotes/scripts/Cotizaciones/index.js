'use strict';
// Class definition
jQuery(document).ready(function() {
    $(".kt-header__brand-logo").removeAttr("href");
    $(".kt-subheader__title").html("Cotización de la semana");
    $(".kt-subheader__breadcrumbs-link").html("Posibles pasos para generar cotización")
    //$(".kt-subheader__breadcrumbs-link").attr("href",site_url+"Catalogo")
    KTDatatableDataLocalDemo.init();
    KTWizard4.init();
    setTableP();
    setTableS();
});

function goBack() {
  window.history.back();
}


var KTWizard4 = function () {
    var wizardEl;
    var formEl;
    var validator;
    var wizard;
    var initWizard = function () {
        wizard = new KTWizard('kt_wizard_v4', {
            startStep: 1,
        });
        wizard.on('beforeNext', function(wizardObj) {
            if (validator.form() !== true) {
                wizardObj.stop();  // don't go to the next step
            }
        })
        wizard.on('change', function(wizard) {
            KTUtil.scrollTop(); 
        });
    }
    var initValidation = function() {
        validator = formEl.validate({
            ignore: ":hidden",
            rules: {
            },
            invalidHandler: function(event, validator) {     
                KTUtil.scrollTop();
                swal.fire({
                    "title": "", 
                });
            },
            submitHandler: function (form) {
            }
        });   
    }
    var initSubmit = function() {
        var btn = formEl.find('[data-ktwizard-type="action-submit"]');
        btn.on('click', function(e) {
            e.preventDefault();
            if (validator.form()) {
                KTApp.progress(btn);
                formEl.ajaxSubmit({
                    success: function() {
                        KTApp.unprogress(btn);
                        swal.fire({
                            "title": "", 
                        });
                    }
                });
            }
        });
    }
    return {
        init: function() {
            wizardEl = KTUtil.get('kt_wizard_v4');
            formEl = $('#kt_form');
            initWizard(); 
            initValidation();
            initSubmit();
        }
    };
}();

var dataJSONArray ="";

/************ TABLA PRODUCTOS ************/
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
                    height: 750, // datatable's body's fixed height
                    footer: false, // display/hide footer
                },

                // column sorting
                sortable: false,

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
                        width: 10,
                        type: "number"
                    },{
                        field: 'Codigo',
                        title: 'Código',
                        width: 150,
                        template: function(row) {
                            return row.Codigo;
                        },
                    }, {
                        field: 'Descripcion',
                        title: 'Descripción',
                        width: 270,
                        template: function(data) {
                            var user_img = 'background-image:url(\'assets/img/productos/' + data.Imagen + '\');cursor:pointer;';

                            var output = '<div class="d-flex align-items-center">\
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
                        field: 'Unidad',
                        title: 'UM',
                        width: 30,
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
                                    'class': 'primary'
                                },
                                2: {
                                    'title': 'Volúmen',
                                    'class': 'success'
                                },
                                3: {
                                    'title': 'Amarillos',
                                    'class': 'warning'
                                },
                                4: {
                                    'title': 'Moderna',
                                    'class': 'dark'
                                },
                                5: {
                                    'title': 'Costeña',
                                    'class': 'dark'
                                },
                                6: {
                                    'title': 'Cuetara',
                                    'class': 'dark'
                                }
                            };
                            return '<span class="kt-badge kt-badge--' + status[row.Estatus].class + ' kt-badge--inline kt-badge--pill">' + status[row.Estatus].title + '</span>';
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
                                        <i class="la la-edit"></i>\
                                    </span>\
                                </a>\
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon" title="Eliminar" data-toggle="modal" data-target="#kt_del_prod" data-id-prod="'+row.RecordID+'" id="liusered">\
                                    <span class="svg-icon svg-icon-md">\
                                        <i class="la la-trash"></i>\
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
    //blockPageDelete()
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
    url: site_url+"Productos/upload_productos",                      
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


/******************  PRODUCTOS FALTANTES  **********************/
$(document).off("change", "#id_profalta").on("change", "#id_profalta", function() {
    event.preventDefault();
    var proveedor = $("#id_profalta option:selected").val();
    if(proveedor != "nope"){
        $(".provefalta").css("display","block");
        renderTable(proveedor);
    }else{
        $(".provefalta").css("display","block");
    }


});

function renderTable(proveedor){
    $(".cot-prov").html("");
    getProveedorCot(proveedor)
    .done(function (resp){
        if(resp.cotizaciones){
            $.each(resp.cotizaciones, function(indx, value){
                value.no_semanas = value.no_semanas == null ? "" : value.no_semanas;
                value.fecha_termino = value.fecha_termino== null ? "" : getweekdays(value.fecha_termino);
                value.observaciones = value.observaciones == null ? "" : value.observaciones;
                $(".cot-prov").append('<tr><td>'+value.codigo+'</td><td>'+value.producto+'</td><td>'+value.no_semanas+'</td><td>'+value.fecha_termino+'</td></tr>')
            });
            $(".searchboxs").css("display","block");
        }else {
            $(".cot-prov").html("<tr><td colspan='5'><h2 style='text-align:center'>PROVEEDOR SIN FALTANTES</td></tr>");
            $(".searchboxs").css("display","none");
        }
    });
}

function getProveedorCot(id_prov) {
    return $.ajax({
        url: site_url+"/Cotizaciones/getProveedorCot/"+id_prov,
        type: "POST",
        dataType: "JSON"
    });
}

$(document).off("click", ".new_falta").on("click", ".new_falta", function(event) {
    if($("#prodFalta").val() !== ''){
        if ($("#faltante").val() !== '') {
            sendFormas("Cotizaciones/save_falta/"+$("#id_profalta").val(), $("#form_faltante_new"), "");
        }else{
                toastr.warning("Agregue un numero de semanas", user_name);
        }
    }else{
        toastr.warning("Seleccione un artículo de la lista", user_name);
    }
});

function getweekdays(fecha){
    fecha = new Date(fecha);
    var weekday = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sabado"];
    var month = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","","Noviembre","Diciembre"];
    var fec = weekday[fecha.getDay()]+' '+fecha.getDate()+' de '+month[fecha.getMonth()]+' del '+fecha.getFullYear();
    return fec;
}

$(document).off("click", "#del_fal").on("click", "#del_fal", function(event) {
    event.preventDefault();
    delFalt($("#id_profalta").val()).done(function(resp){
        $(".cot-prov").html("<tr><td colspan='5'><h2 style='text-align:center'>PROVEEDOR SIN FALTANTES</td></tr>");
        toastr.warning("Faltantes Eliminados", user_name);
    });
});

function delFalt(values){
    return $.ajax({
        url: site_url+"/Cotizaciones/delete_falta/"+$("#id_profalta").val(),
        type: "POST",
        dataType: "JSON",
    });
}

$(document).off("change", "#file_faltantes").on("change", "#file_faltantes", function(event) {
    event.preventDefault();
    var proveedor = $("#id_profalta option:selected").val();
    var fdata = new FormData($("#upload_faltantes")[0]);
    if(proveedor != "nope"){
        blockPage();
        uploadFaltantes(fdata,proveedor)
        .done(function (resp) {
            if (resp.type == 'error'){
                toastr.error(resp.desc, user_name);
                unblockPage();
            }else{
                var proveedor = $("#id_profalta option:selected").val();
                renderTable(proveedor);
                $(".faltsc").html('<input class="btn btn-info" type="file" id="file_faltantes" name="file_faltantes" value="" size="20" />')
                unblockPage();
            }
        });
    }
});

function uploadFaltantes(formData,proveedor) {
    return $.ajax({
        url: site_url+"Cotizaciones/upload_faltantes/"+proveedor,
        type: "POST",
        cache: false,
        contentType: false,
        processData:false,
        dataType:"JSON",
        data: formData,
    });
}



/******************  PEDIDOS PENDIENTES  **********************/


function getPendientes() {
    return $.ajax({
        url: site_url+"/Cotizaciones/getPendientes/",
        type: "POST",
        dataType: "JSON"
    });
}

function setTableP(){
    getPendientes()
    .done(function (resp) {
        if(resp.pendientes){
            $.each(resp.pendientes, function(indx, value){
                $(".tablePendv").append('<tr><td>'+value.nombre+'</td><td>'+value.codigo+'</td><td>'+value.cedis +'</td><td>'+value.abarrotes+
                    '</td><td>'+value.pedregal+'</td><td>'+value.tienda +'</td><td>'+value.ultra+'</td><td>'+value.trincheras+
                    '</td><td>'+value.mercado+'</td><td>'+value.tenencia+'</td><td>'+value.tijeras+'</td></tr>')
            });
        }
        $(".searchboxs").css("display","block");
        $(".float-e-margins").css("display","block");
        
    });
}

$(document).off("change", "#file_pendientes").on("change", "#file_pendientes", function(event) {
    event.preventDefault();
    blockPage();
    var fdata = new FormData($("#upload_pendientes")[0]);
    uploadPendientes(fdata)
        .done(function (resp) {
            if (resp.type == 'error'){
                toastr.error("No se pudieron actualizar los pendientes", user_name);
                $(".tablePendv").html("");
            }else{
                unblockPage();
                toastr.error("Se actualizaron los pendientes", user_name);
                $(".tablePendv").html("");
                setTableP()
            }
        });
});
function uploadPendientes(formData) {
    return $.ajax({
        url: site_url+"Cotizaciones/upload_pendientes/",
        type: "POST",
        cache: false,
        contentType: false,
        processData:false,
        dataType:"JSON",
        data: formData,
    });
}


function buscapendiente() {
  // Declare variables 
  var input, filter, table, tr, td, i;
  input = document.getElementById("buscapend");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_pendientes");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}


/******************  PRECIOS SISSTEMA  **********************/

function buscasistema() {
  // Declare variables 
  var input, filter, table, tr, td, i;
  input = document.getElementById("buscasis");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_sistema");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}

function getSistema() {
    return $.ajax({
        url: site_url+"/Cotizaciones/getSistema/",
        type: "POST",
        dataType: "JSON"
    });
}

function setTableS(){
    var color = "black";
    getSistema()
    .done(function (resp) {
        if(resp.sistema){
            $.each(resp.sistema, function(indx, value){
                color = "black";
                if(value.precio_sistema == 0 || value.precio_four == 0){
                    color = "red";
                    $(".tableSistema").append('<tr><td>'+value.producto+'</td><td>'+value.codigo+'</td><td style="color:'+color+'">$ '+formatMoney(value.precio_sistema,2)+'</td><td style="color:'+color+'">$ '+formatMoney(value.precio_four) +'</td></tr>')
                }
            });
        }
    });
}

$(document).off("change", "#file_precios").on("change", "#file_precios", function(event) {
    event.preventDefault();
    blockPage();
    var formdata = new FormData($("#upload_precios")[0]);
    uploadPrecios(formdata)
        .done(function (resp) {
            if (resp.type == 'error'){
                $(".tableSistema").html("");
                unblockPage();
                toastr.error("No se subieron los precios, revise su archivo e intente nuevamente", user_name);
            }else{
                setTimeout(function(){
                    $(".tableSistema").html("");
                    setTableS();
                },1000);
                unblockPage();
                toastr.success("Se subieron precios a cotizador", user_name);
            }
        }); 
});

function uploadPrecios(formData) {
    return $.ajax({
        url: site_url+"Cotizaciones/upload_precios",
        type: "POST",
        cache: false,
        contentType: false,
        processData:false,
        dataType:"JSON",
        data: formData,
    });
}

/******************  AGREGAR COTIZACIONES  **********************/


























/******************  REPETIR ANTERIORES  **********************/
$(document).off("click", "#verRepite").on("click", "#verRepite", function(event){
    event.preventDefault();
    $("#bodyRepite").html("");
    $("#RepCotz").attr("data-id-user",$(this).data("idUser"));
    getSAnteriores($(this).data("idUser")).done(function(resp){
        $.each(resp,function(index,value){
            value.observaciones = value.observaciones == null ? "Sin observaciones" : value.observaciones;
            value.num_one = value.num_one == null ? " " : value.num_one+" en "+value.num_two;
            value.descuento = value.descuento == null ? "" : value.descuento;
            $("#bodyRepite").append("<tr><td>"+(index+1)+"</td><td>"+value.nombre+"</td><td>$ "+formatMoney(value.precio_promocion,2)+"</td><td>$ "+formatMoney(value.precio,2)+"</td><td>"+value.num_one+"</td><td>"+value.descuento+"</td><td>"+value.observaciones+"</td></tr>");
        })
    })
});

function getSAnteriores(id_proveedor) {
    return $.ajax({
        url: site_url+"Cotizaciones/getLastCot/"+id_proveedor,
        type: "POST",
        dataType: "JSON",
    });
}

$(document).off("click", "#RepCotz").on("click", "#RepCotz", function(event){
    event.preventDefault();
    blockPage();
    $('#kt_modal_lastCotiz').modal('toggle');
    var ides = $(this).data("idUser");
    $(this).prop("disabled","true")
    repeatCotz(ides)
        .done(function(resp) {
            unblockPage();
            if (resp.type == 'error'){
                toastr.error(resp.desc, user_name);
                setTimeout(function(){
                    $(this).prop("disabled","false")
                },1200);
            }else{
                setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
            }
        });
});

function repeatCotz(id_prov) {
    return $.ajax({
        url: site_url+"/Cotizaciones/repeat_cotizacion/"+id_prov,
        type: "POST",
        dataType: "JSON",
        data: {id_proveedor: id_prov},
    });
}




/******************  DIFERENCIAS 20 %  **********************/

$(document).off("click", "#editCotiza20").on("click", "#editCotiza20", function(event){
    event.preventDefault();
    var tr = $(this).closest("tr");
    var proveedor = tr.find(".idprovs").val();
    var id_cotizacion = $(this).data("idProd");
    getModal("Cotizaciones/get_update/"+ id_cotizacion+"/"+proveedor, function (){
        datePicker();
        $(".number").inputmask("currency", {radixPoint: ".", prefix: ""});
    });
});