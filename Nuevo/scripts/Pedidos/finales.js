jQuery(document).ready(function() {
    $("#titlePrincipal").html("Pedidos");
    //KTDatatableDataLocalDemo.init();
    $('#kt_select2_1').select2({
        placeholder: "Seleccione un proveedor"
    });
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
    //$('#kt_modal_imm').modal()
});
var provee = $("#kt_select2_1 option:selected").val();
var dataJSONArray ="";
var datatable = "";

//************ TABLA PRODUCTOS
var KTDatatableDataLocalDemo = function() {
    // Private functions
    // demo initializer
    
    var demo = function() {
        dataJSONArray ="";
        getPendientes(provee).done(function(resp){
            var yeison = "[";
            if (resp) {
                $.each(resp,function(index, value){
                    value.cedis =  value.cedis === null ? "---" :  value.cedis;
                    value.abarrotes =  value.abarrotes === null ? "---" :  value.abarrotes;
                    value.Villas =  value.Villas === null ? "---" :  value.Villas;
                    value.tienda =  value.tienda === null ? "---" :  value.tienda;
                    value.ultra =  value.ultra === null ? "---" :  value.ultra;
                    value.trincheras =  value.trincheras === null ? "---" :  value.trincheras;
                    value.mercado =  value.mercado === null ? "---" :  value.mercado;
                    value.tenencia =  value.tenencia === null ? "---" :  value.tenencia;
                    value.tijeras =  value.tijeras === null ? "---" :  value.tijeras;
                    value.promocion =  value.promocion === null ? "" :  value.promocion;


                    value.imagen =  value.imagen === null ? "sinimagen_thumb.png" :  value.imagen;

                    value.nombre = value.nombre.replace(/"/g, "'");
                    value.familia = value.familia.replace(/"/g, "'");

                    value.familia = value.familia.replace(/\t/g, "'");
                    value.nombre = value.nombre.replace(/\t/g, "'");
                    yeison = yeison + '{"RecordID":"'+value.id_final+'","Descripcion":"'+value.nombre+'","Codigo":"'+value.codigo+'","Cedis":"'+value.cedis+'"'+
                            ',"Abarrotes":"'+value.abarrotes+'","Pedregal":"'+value.villas+'","Tienda":"'+value.tienda+'","Trincheras":"'+value.trincheras+'","Tenencia":"'+value.tenencia+'","Tijeras":"'+value.tijeras+
                            '","Familia":"'+value.familia+'","Fecha":"'+value.fecha_registro+'","Costo":"'+value.costo+'","Mercado":"'+value.mercado+'","Promocion":"'+value.promocion+
                            '","Ultra":"'+value.ultra+'","Actions":null},\n'
                });
                dataJSONArray = JSON.parse(yeison.slice(0,-1).slice(0,-1)+']');
            }

            setTimeout(function(){
                if (datatable !== "") {
                    datatable.destroy()
                }
                datatable = $('#prodTable').KTDatatable({
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
                                    <div class="ml-2">\
                                        <div class="text-dark-75 font-weight-bold line-height-sm">' + data.Descripcion + '</div>\
                                        <a class="font-size-sm text-dark-50 text-hover-primary">' +
                                        data.Familia + '</a>\
                                    </div>\
                                </div>';
                            return output;
                        }
                    },{
                        field: 'Costo',
                        title: 'Costo',
                        width: 100,
                        template: function(row) {
                            return "$ "+formatMoney(row.Costo);
                        },
                    },{
                        field: 'Cedis',
                        title: 'Ced',
                        width: 30,
                        template: function(row) {
                            return "<div style='text-align:center;border-bottom:2px solid rgb(192,0,0)'>"+formatMoney(row.Cedis,0)+"</div>";
                        },
                    },{
                        field: 'Abarrotes',
                        title: 'Aba',
                        width: 30,
                        template: function(row) {
                            return "<div style='text-align:center;border-bottom:2px solid rgb(1,76,240)'>"+formatMoney(row.Abarrotes,0)+"</div>";
                        },
                    },{
                        field: 'Pedregal',
                        title: 'Ped',
                        width: 30,
                        template: function(row) {
                            return "<div style='text-align:center;border-bottom:2px solid rgb(255,0,0)'>"+formatMoney(row.Pedregal,0)+"</div>";
                        },
                    },{
                        field: 'Tienda',
                        title: 'Tie',
                        width: 30,
                        template: function(row) {
                            return "<div style='text-align:center;border-bottom:2px solid rgb(226,108,11)'>"+formatMoney(row.Tienda,0)+"</div>";
                        },
                    },{
                        field: 'Ultra',
                        title: 'Ult',
                        width: 30,
                        template: function(row) {
                            return "<div style='text-align:center;border-bottom:2px solid rgb(197,197,197)'>"+formatMoney(row.Ultra,0)+"</div>";
                        },
                    },{
                        field: 'Trincheras',
                        title: 'Tri',
                        width: 30,
                        template: function(row) {
                            return "<div style='text-align:center;border-bottom:2px solid rgb(146,208,91)'>"+formatMoney(row.Trincheras,0)+"</div>";
                        },
                    },{
                        field: 'Mercado',
                        title: 'Mer',
                        width: 30,
                        template: function(row) {
                            return "<div style='text-align:center;border-bottom:2px solid rgb(177,160,199)'>"+formatMoney(row.Mercado,0)+"</div>";
                        },
                    },{
                        field: 'Tenencia',
                        title: 'Ten',
                        width: 30,
                        template: function(row) {
                            return "<div style='text-align:center;border-bottom:2px solid rgb(218,150,148)'>"+formatMoney(row.Tenencia,0)+"</div>";
                        },
                    },{
                        field: 'Tijeras',
                        title: 'Tij',
                        width: 30,
                        template: function(row) {
                            return "<div style='text-align:center;border-bottom:2px solid rgb(76,172,198)'>"+formatMoney(row.Tijeras,0)+"</div>";
                        },
                    },{
                        field: 'Promocion',
                        title: 'Promoción',
                        width: 100,
                        template: function(row) {
                            return row.Promocion;
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

            datatable.load();

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



var theDate = new Date().getTime();
Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("div#my-dropzoneProd", {
    paramName: "file_final",
    maxFiles: 1,
    maxFilesize: 1000000, // MB
    renameFilename: function (filename) {
        return theDate + '_' + filename;
    },
    url: site_url+"Pedidos/upload_finales/"+provee+"",                      
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
            setTimeout(function(){unblockPage();},1500)
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
            inicio();
            setTimeout(function(){unblockPage()},1100)
        }
    }
});

$(document).off("change", "#kt_select2_1").on("change", "#kt_select2_1", function() {
    event.preventDefault();
    provee = $("#kt_select2_1 option:selected").val();

    myDropzone.options.url = site_url+"Pedidos/upload_finales/"+provee+"";
    if(provee != "-1"){
        $("#subepedidos").css("display","block");
        $(".elimPed").css("display","block");
        inicio();
    }else{
        $("#subepedidos").css("display","none");
        $(".elimPed").css("display","none");
    }
});

function inicio(){
    KTDatatableDataLocalDemo.init();
}

function getPendientes(proves){
    return $.ajax({
        url: site_url+"Pedidos/getFinales/"+proves,
        type: "POST",
        dataType: "JSON",
    });
}

$(document).off("click", ".elimPed").on("click", ".elimPed", function (){
    event.preventDefault();
    blockPage();
    deletePedidos(provee)
    .done(function (resp) {
        toastr.success("Se eliminaron los pedidos", "Listo");
        inicio();
        unblockPage();
    });
})

function deletePedidos(formData) {
    return $.ajax({
        url: site_url+"Pedidos/deletePedidos/"+provee,
        type: "POST",
        cache: false,
        contentType: false,
        processData:false,
        dataType:"JSON",
        data: formData,
    });
}