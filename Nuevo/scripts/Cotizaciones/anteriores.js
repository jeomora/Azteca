jQuery(document).ready(function() {
    $("#titlePrincipal").html("Cotizaciones");
});

$(document).off("click", "#RepCotz").on("click", "#RepCotz", function(event){
    event.preventDefault();
    blockPageBlocks();
    $('#kt_modal_lastCotiz').modal('toggle');
    var ides = $(this).data("idCotiz");
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

$(document).off("click", "#verCotz").on("click", "#verCotz", function(event){
    event.preventDefault();
    $("#bodycotiz").html("");
    $("#RepCotz").attr("data-id-cotiz",$(this).data("idCotiz"));
    getCotizacion($(this).data("idCotiz")).done(function(resp){
        $.each(resp,function(index,value){
            value.observaciones = value.observaciones == null ? "Sin observaciones" : value.observaciones;
            value.num_one = value.num_one == null ? " " : value.num_one+" en "+value.num_two;
            value.descuento = value.descuento == null ? "" : value.descuento;
            $("#bodycotiz").append("<tr><td>"+(index+1)+"</td><td>"+value.nombre+"</td><td>$ "+formatMoney(value.precio_promocion,2)+"</td><td>$ "+formatMoney(value.precio,2)+"</td><td>"+value.num_one+"</td><td>"+value.descuento+"</td><td>"+value.observaciones+"</td></tr>");
        })
    })
});

function getCotizacion(id_proveedor) {
    return $.ajax({
        url: site_url+"Cotizaciones/getLastCot/"+id_proveedor,
        type: "POST",
        dataType: "JSON",
    });
}