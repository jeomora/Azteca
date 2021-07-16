$(function($) {
	getStocks().done(function(resp){
		if(resp){
			var codigo = "";var desc = "";var ced = 0;var aba = 0;var sup = 0;var ult = 0;var tri = 0; var mer = 0; var ten = 0;var tij = 0;
			var stocks = {"codigo":"","descripcion":"","57":0,"59":0,"60":0,"61":0,"62":0,"63":0,"89":0,"87":0}
			$.each(resp,function(index,value){
				if(stocks["codigo"] !== value.codigo && index > 0){
					$(".tbody_stocks").append('<tr><td>'+stocks["codigo"]+'</td><td>'+stocks["descripcion"]+'</td><td>'+stocks["87"]+'</td><td>'+stocks["89"]+'</td><td>'+stocks["57"]+'</td><td>'+stocks["59"]+'</td><td>'+
						stocks["60"]+'</td><td>'+stocks["61"]+'</td><td>'+stocks["62"]+'</td><td>'+stocks["63"]+'</td></tr>');stocks["codigo"] = value.codigo;
						stocks["descripcion"] = value.descripcion;
						stocks[""+value.id_tienda] = value.cantidad;
				} else {
					stocks["codigo"] = value.codigo;
					stocks["descripcion"] = value.descripcion;
					stocks[""+value.id_tienda] = value.cantidad;
				}
			})
		}
	})
});
function getStocks() {
	return $.ajax({
		url: site_url+"/Lunes/getStocks",
		type: "POST",
		dataType: "JSON"
	});
}
function uploadProductos(formData) {
	return $.ajax({
		url: site_url+"Lunes/upload_stock",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}
$(document).off("change", "#file_productos").on("change", "#file_productos", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_productos")[0]);
	uploadProductos(fdata)
	.done(function (resp) {
		if (resp.type == 'error'){
			setTimeout("location.reload()", 1700, toastr.error(resp.desc, user_name), "");
		}else{
			unblockPage();
			setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
		}
	});
});

