$(document).off("click", "#no_cotizo").on("click", "#no_cotizo", function(event){
	event.preventDefault();
	var ides = $(this).attr("data-id-producto");
	getModal("Main/getCotzUsuario/"+ides, function (){ });
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