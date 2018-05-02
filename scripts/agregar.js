$(document).off("change", "#id_pro").on("change", "#id_pro", function() {
	event.preventDefault();
	$(".float-e-margins").css("display","block")
	var proveedor = $("#id_pro option:selected").val();
});

$(document).off("change", "#file_cotizaciones").on("change", "#file_cotizaciones", function(event) {
	event.preventDefault();
	blockPage();
	var proveedor = $("#id_pro option:selected").val();
	var fdata = new FormData($("#upload_cotizaciones")[0]);
	uploadCotizaciones(fdata,proveedor)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
			}else{
				unblockPage();
				setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});
function uploadCotizaciones(formData,ides) {
	return $.ajax({
		url: site_url+"Cotizaciones/upload_cotizaciones/"+ides,
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}