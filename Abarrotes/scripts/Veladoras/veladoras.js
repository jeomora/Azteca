'use strict';
// Class definition
var dataJSONArray = "";
var KTDatatableDataLocalDemo = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        getUsuarios().done(function(resp){
            var yeison = "[";
            if (resp) {
                $.each(resp.data,function(index, value){
                    yeison = yeison + '{"RecordID":'+value.id_usuario+',"Country":"'+value.nombre+'","ShipName":"'+value.ShipName+
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
                    title: 'CÓDIGO PIEZA',
                    template: function(row) {
                        return '<div class="input-group">'+row.Country+
                                '</div>'
                    }
                }, {
                    field: 'ShipName',
                    title: 'CÓDIGO CAJA',
                    width: 250,
                    autoHide: false,
                    // callback function support for column rendering
                    template: function(row) {
                        return '<div class="input-group">'+row.ShipName+
                                '</div>'
                    }
                }, {
                    field: 'Status',
                    title: 'DESCRIPCIÓN',
                    template: function(row) {
                        return '<div class="input-group">'+row.Status+
                                '</div>'
                    }
                }, {
                    field: 'Image',
                    title: 'IMAGEN',
                    template: function(row) {
                        return '<div class="input-group"><img style="height:250px;max-width:250px;" src="/assets/img/veladoras/"'+row.Image+'" />'+
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
    $(".kt-subheader__title").html("Veladoras");
    $(".kt-subheader__breadcrumbs-link").html("Catálogo")
    $(".kt-subheader__breadcrumbs-link").attr("href",site_url+"/Veladoras")
    KTDatatableDataLocalDemo.init();
    $("#form_usuario_edit").validate({
        rules: {
            nombre: {required: true},
            correo: {email:true, required: true},
            password: {required: true, minlength: 8},
            id_grupo: {required: true, min: 0},

        }
    });
    jQuery.extend(jQuery.validator.messages,{
        required: "Este campo es requerido",
        min: "Este campo es requerido",
        email: "Por favor ingrese un correo valido",
        minlength: jQuery.validator.format("Por favor ingresa más de {0} caracteres.")
    });
    $("#form_usuario_new").validate({
        rules: {
            nombres: {required: true},
            correos: {email:true, required: true},
            passwords: {required: true, minlength: 8},
            id_grupos: {required: true, min: 0},

        }
    });
    jQuery.extend(jQuery.validator.messages,{
        required: "Este campo es requerido",
        min: "Este campo es requerido",
        email: "Por favor ingrese un correo valido",
        minlength: jQuery.validator.format("Por favor ingresa más de {0} caracteres.")
    });
});

$(document).off("click", "#liusered").on("click", "#liusered", function(event) {
    event.preventDefault();

    getUser($(this).data("idUser"))
        .done(function (resp) {
            $("#spanuser").html(resp.nombre);
            $("#id_usuario").val(resp.id_usuario);
        });
});

$(document).off("click", "#edituser").on("click", "#edituser", function(event) {
    event.preventDefault();

    getUser($(this).data("idUser"))
        .done(function (resp) {
            $("#id_usuarios").val(resp.id_usuario);
            $("#nombre").val(resp.nombre);
            $("#apellido").val(resp.apellido);
            $("#correo").val(resp.email);
        });
});

function getUser(formData) {
    return $.ajax({
        url: site_url+"Usuarios/getUser/"+formData,
        type: "POST",
        dataType: "JSON",
    });
}

function getUsuarios() {
    return $.ajax({
        url: site_url+"Veladoras/getVelas",
        type: "POST",
        dataType: "JSON",
    });
}

$(document).off("click", ".delete_usuario").on("click", ".delete_usuario", function(event) {
    event.preventDefault();
    console.log("Ora perratzo")
    sendForm("Usuarios/delete_user", $("#form_usuario_delete"), "");
});

$(document).off("click", ".update_usuario").on("click", ".update_usuario", function(event) {
    event.preventDefault();
    if($("#form_usuario_edit").valid()){
        sendForm("Usuarios/update_user", $("#form_usuario_edit"), "");
    }
});
$(document).off("click", ".new_usuario").on("click", ".new_usuario", function(event) {
    event.preventDefault();
    if($("#form_usuario_new").valid()){
        sendForm("Usuarios/save_user", $("#form_usuario_new"), "");
    }
});
function goBack() {
  window.history.back();
}
