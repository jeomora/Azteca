$(document).off("click", "#ver_pedido").on("click", "#ver_pedido", function(event) {
	event.preventDefault();

	getModal("Lunes/verpedido/"+$("#id_proveedor").children("option:selected").val(), function (){
	});
});

$(document).off("change", "#file_otizaciones").on("change", "#file_otizaciones", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_cotizaciones")[0]);
	uploadCotizaciones(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
				unblockPage();
			}else{
				unblockPage();
				toastr.success(resp.desc, user_name);
				$("#file_otizaciones").val("");
				//setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});
function uploadCotizaciones(formData) {
	return $.ajax({
		url: site_url+"Lunes/sube_pedido/",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

$(document).off("click", "#endPedidos").on("click", "#endPedidos", function(event) {
	var proveedor = $("#id_proveedor").children("option:selected").val()
	blockPage();
	endCotizacion(proveedor)
	.done(function (resp) {
		if (resp.type == 'error'){
			toastr.error(resp.desc, user_name);
			unblockPage();
		}else{
			setTimeout("location.reload()", 1300, toastr.success(resp.desc, user_name), "");
		}
	});
});

function endCotizacion(proveedor) {
	return $.ajax({
		url: site_url+"Lunes/endPedidos/"+proveedor,
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON"
	});
}

$(document).off("change", "#file_otizaciones2").on("change", "#file_otizaciones2", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_cotizaciones2")[0]);
	uploadCotizaciones2(fdata,87)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
				unblockPage();
			}else{
				unblockPage();
				toastr.success(resp.desc, user_name);
				$("#file_otizaciones2").val("");
				//setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});
function uploadCotizaciones2(formData,tienda) {
	return $.ajax({
		url: site_url+"Lunes/sube_factura/"+tienda,
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}