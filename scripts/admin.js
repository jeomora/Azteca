var tiendas = [87,89,57,90,58,59,60,61,62,63];
$(document).off("click", "#no_cotizo").on("click", "#no_cotizo", function(event){
	event.preventDefault();
	var ides = $(this).attr("data-id-producto");
	getModal("Main/getCotzUsuario/"+ides, function (){ $(".numeric").inputmask("currency", {radixPoint: ".", prefix: ""}); })
});

$(document).off("click", "#repeat_cot").on("click", "#repeat_cot", function(event){
	event.preventDefault();
	blockPage();
	var ides = $(this).attr("data-id-cot");
	repeatCotz(ides)
		.done(function (resp) {
			if (resp.type == 'error'){
				unblockPage();
				toastr.error(resp.desc, user_name);
			}else{
				unblockPage();
				setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});

function repeatCotz(id_prov) {
	return $.ajax({
		url: site_url+"/Main/repeat_cotizacion",
		type: "POST",
		dataType: "JSON",
		data: {id_proveedor: id_prov},
	});
}
$(document).off("click", "#no_cotiz").on("click", "#no_cotiz", function(event){
	event.preventDefault();
	getModal("Main/getNotCotizo/", function (){ });
});
$(document).off("click", "#no_cotizados").on("click", "#no_cotizados", function(event){
	event.preventDefault();
	getModal("Main/getNotCotizados/", function (){ });
});

$(document).off("keyup", ".precio").on("keyup", ".precio", function() {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var descuento = tr.find(".descuento").val().replace(/[^0-9\.]+/g,"");
	var num_one = tr.find('.num_one').val().replace(/[^0-9\.]+/g,"");
	var num_two = tr.find('.num_two').val().replace(/[^0-9\.]+/g,"");
	if(num_two > 0 && num_one > 0){
		var total = (precio * num_two) / (parseFloat(num_one) + parseFloat(num_two));
		tr.find(".precio_promocion").val(total);
	}else if(descuento > 0){
		tr.find(".precio_promocion").val(precio - (precio * (descuento / 100)));
	}else{
		tr.find(".precio_promocion").val(precio);
	}
});

$(document).off("keyup", ".num_one").on("keyup", ".num_one", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var num_one = tr.find('.num_one').val().replace(/[^0-9\.]+/g,"");
	var num_two = tr.find('.num_two').val().replace(/[^0-9\.]+/g,"");
	if(num_two > 0 && num_one > 0){
		var total = (precio * num_two) / (parseFloat(num_one) + parseFloat(num_two));
		tr.find(".precio_promocion").val(total);
	}else{
		tr.find(".precio_promocion").val(precio);
	}
});
$(document).off("keyup", ".num_two").on("keyup", ".num_two", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var num_one = tr.find('.num_one').val().replace(/[^0-9\.]+/g,"");
	var num_two = tr.find('.num_two').val().replace(/[^0-9\.]+/g,"");
	if(num_two > 0 && num_one > 0){
		var total = (precio * num_two) / (parseFloat(num_one) + parseFloat(num_two));
		tr.find(".precio_promocion").val(total);
	}else{
		tr.find(".precio_promocion").val(precio);
	}
});
$(document).off("keyup", ".descuento").on("keyup", ".descuento", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var descuento = tr.find(".descuento").val().replace(/[^0-9\.]+/g,"");
	if($(this).val().replace(/[^0-9\.]+/g,"") > 0){
		tr.find(".precio_promocion").val(precio - (precio * (descuento / 100)));
	}
});

$(function($) {
	$.each(tiendas, function(indx, value){
		getPedidos(value).done(function(resp){
			if (resp.cuantas == null) {
				$("#lun"+indx).html("<a id='luninfo' data-id-cot='"+value+"'>0 de "+resp.noprod.noprod+"</a>");
			} else {
				$("#lun"+indx).html("<a id='luninfo' data-id-cot='"+value+"'>"+resp.cuantas.cuantas+" de "+resp.noprod.noprod+"</a>");
			}
			$("#gen"+indx).html("<a id='allinfo' data-id-cot='"+value+"'>"+resp.allcuantas.cuantas+" de "+resp.noall.total+"</a>");
			$("#vol"+indx).html("<a id='volinfo' data-id-cot='"+value+"'>"+resp.volcuantas.cuantas+" de "+resp.novol.total+"</a>");

		})
	});
});

function getPedidos(tienda){
	return $.ajax({
		url: site_url+"/Pedidos/getPeds/"+tienda,
		type: "POST",
		dataType: "JSON",
	});
}

$(document).off("click", "#luninfo").on("click", "#luninfo", function(event) {
	event.preventDefault();
	getModal("Lunes/lunpedid/"+$(this).data("idCot"), function (){
		setTimeout(function() {
			fillDataTable("exislun", 10)
			fillDataTable("exislunnot", 10)
		}, 2000);		
	});
});

$(document).off("click", "#volinfo").on("click", "#volinfo", function(event) {
	event.preventDefault();
	getModal("Lunes/volpedid/"+$(this).data("idCot"), function (){
		setTimeout(function() {
			fillDataTable("exislun", 10)
			fillDataTable("exislunnot", 10)
		}, 2000);		
	});
});

$(document).off("click", "#allinfo").on("click", "#allinfo", function(event) {
	event.preventDefault();
	getModal("Lunes/allpedid/"+$(this).data("idCot"), function (){
		setTimeout(function() {
			fillDataTable("exislun", 10)
			fillDataTable("exislunnot", 10)
		}, 2000);		
	});
});