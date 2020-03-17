$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
});

$(window).on("load", function (event) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
});

$(document).off("click", "#new_cotizacion").on("click", "#new_cotizacion", function(event) {
	event.preventDefault();
	getModal("Cotizaciones/add_cotizacion", function (){
		datePicker();
		getChosen();
		$(".number").inputmask("currency", {radixPoint: ".", prefix: ""});
		$(".numeric").number(true, 0);
	});
});

$(document).off("click", ".new_cotizacion").on("click", ".new_cotizacion", function(event) {
	if($("#id_producto").val() !== ''){
		sendForm("Cotizaciones/saveP/0", $("#form_cotizacion_new"), "");
	}else{
		toastr.warning("Seleccione un artículo de la lista", user_name);
	}
});

$(document).off("keyup", "#precio").on("keyup", "#precio", function() {
	var total =0;
	var descuento = $("#porcentaje").val().replace(/[^0-9\.]+/g,"");
	var precio = Number($(this).val().replace(/[^0-9\.]+/g,""));
	total = (precio - (precio * descuento));
	var num_1 = Number($("#num_one").val().replace(/[^0-9\.]+/g,""));
	var num_2 = Number($("#num_two").val().replace(/[^0-9\.]+/g,""));
	if(num_1 > 0 ){
		total = ((precio * num_2) / (num_1 + num_2));
	}
	$("#precio_promocion").val(total);
});

$(document).off("keyup", "#precio").on("keyup", "#precio", function() {
	var total =0;
	var descuento = $("#porcentaje").val().replace(/[^0-9\.]+/g,"");
	var precio = Number($(this).val().replace(/[^0-9\.]+/g,""));
	total = (precio - (precio * descuento));
	var num_1 = Number($("#num_one").val().replace(/[^0-9\.]+/g,""));
	var num_2 = Number($("#num_two").val().replace(/[^0-9\.]+/g,""));
	if(num_1 > 0 ){
		total = ((precio * num_2) / (num_1 + num_2));
	}
	$("#precio_promocion").val(total);
});

$(document).off("keyup", "#porcentaje").on("keyup", "#porcentaje", function () {
	var total =0;
	var descuento = $("#porcentaje").val().replace(/[^0-9\.]+/g,"");
	var precio = $("#precio").val().replace(/[^0-9\.]+/g,"");
	total = (precio - (precio * descuento/100));
	$("#precio_promocion").val(total);
});

$(document).off("keyup", "#num_one").on("keyup", "#num_one", function () {
	var total =0;
	var num_2 = parseInt($("#num_two").val());
	var num_1 = parseInt($("#num_one").val());
	if(num_2 > 0 && num_1 > 0){
		var precio = $("#precio").val();
		total = ((precio * num_2) / (num_1 + num_2));
		console.log(precio * num_2);
		console.log(num_1 + num_2);
		$("#precio_promocion").val(total);
	}else{
		$("#precio_promocion").val($("#precio").val().replace(/[^0-9\.]+/g,""));
	}
});

$(document).off("keyup", "#num_two").on("keyup", "#num_two", function () {
	var total =0;
	var num_1 = parseInt($("#num_one").val());
	var num_2 = parseInt($("#num_two").val());
	if(num_1 > 0){
		var precio = $("#precio").val();
		total = ((precio * num_2) / (num_1 + num_2));
		$("#precio_promocion").val(total);
	}else{
		$("#precio_promocion").val($("#precio").val().replace(/[^0-9\.]+/g,""));
	}
});

function calculaTotales() {
	var total =0;
	$.each($(".importe"), function(index, val) {
		if (Number($(this).val().replace(/[^0-9\.]+/g,"")) !== '') {
			total += Number($.trim($(val).val().replace(/[^0-9\.]+/g,"")));
		}
	});
	return total;
}

$(document).off("change", "#file_otizaciones").on("change", "#file_otizaciones", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_cotizaciones")[0]);
	uploadCotizaciones(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				setTimeout("location.reload()", 1700, toastr.error(resp.desc, user_name), "");
			}else{
				unblockPage();
				setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});

function uploadCotizaciones(formData) {
	return $.ajax({
		url: site_url+"Cotizaciones/upload_cotizacionesP/0",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}
