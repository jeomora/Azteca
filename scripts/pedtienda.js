$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
});


$(document).off("change", "#file_verduras").on("change", "#file_verduras", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_verduras")[0]);
	uploadVerduras(fdata).done(function (resp) {
		if (resp.type == 'error'){
			toastr.error(resp.desc, user_name);
			unblockPage();
		}else{
			unblockPage();
			setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
		}
	});	
});

function uploadVerduras(formData) {
	return $.ajax({
		url: site_url+"Verduras/upload_pedidos",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}


$(document).off("change", "#file_cotizaciones").on("change", "#file_cotizaciones", function(event) {
	event.preventDefault();
	if ($("#id_proves4").val() == "nope") {
		toastr.error("Por favor, seleccione un tipo de formato en la opción de la izquierda el botón 'Seleccionar archivo'", user_name);
		$(this).val("");
	}else{
		if ($("#id_proves4").val() == "LUNES") {
			blockPage();
			var fdata = new FormData($("#upload_pedidos")[0]);
			uploadPedidosLunes(fdata).done(function (resp) {
				if (resp.type == 'error'){
					toastr.error(resp.desc, user_name);
					unblockPage();
				}else{
					unblockPage();
					setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
				}
			});	
			$(this).val("");
		} else {
			blockPage();
			var fdata = new FormData($("#upload_pedidos")[0]);
			uploadPedidos(fdata).done(function (resp) {
				if (resp.type == 'error'){
					toastr.error(resp.desc, user_name);
					unblockPage();
				}else{
					unblockPage();
					setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
				}
			});	
			$(this).val("");
		}
	}
	
});

function uploadPedidosLunes(formData) {
	return $.ajax({
		url: site_url+"Lunes/upload_pedidos",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

function uploadPedidos(formData) {
	return $.ajax({
		url: site_url+"Cotizaciones/upload_pedid/0",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

$(document).off("click", "#luninfo").on("click", "#luninfo", function(event) {
	event.preventDefault();
	getModal("Lunes/lunpedido", function (){
		setTimeout(function() {
			fillDataTable("exislun", 10)
			fillDataTable("exislunnot", 10)
		}, 1000);
		
	});
});
$(document).off("click", "#volinfo").on("click", "#volinfo", function(event) {
	event.preventDefault();
	getModal("Lunes/volpedido", function (){
		setTimeout(function() {
			fillDataTable("exislun", 10)
			fillDataTable("exislunnot", 10)
		}, 1000);
		
	});
});

$(document).off("click", "#allinfo").on("click", "#allinfo", function(event) {
	event.preventDefault();
	getModal("Lunes/allpedido", function (){
		setTimeout(function() {
			fillDataTable("exislun", 10)
			fillDataTable("exislunnot", 10)
		}, 1000);
		
	});
});

$(document).off("change", "#file_frutas").on("change", "#file_frutas", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_frutas")[0]);
	uploadFrutas(fdata).done(function (resp) {
		if (resp.type == 'error'){
			toastr.error(resp.desc, user_name);
			unblockPage();
		}else{
			unblockPage();
			setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
		}
	});	
});

function uploadFrutas(formData) {
	return $.ajax({
		url: site_url+"Frutas/upload_pedidos",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}
