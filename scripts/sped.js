$(function($) {
	$('#home').tab('show');
	$('#kt_modal_2').modal()
});

$(document).off("keyup", "#producto").on("keyup", "#producto", function (){
	event.preventDefault();
	var producto = $("#producto");
	var provs = $("#provs option:selected");
	var values = {"producto":producto.val()};
	var values2 = {"provs":provs.val()};
	var str = "";
	console.log(provs.val())
	if (producto.val().length > 3 ) {
		setTimeout(function() {
			$("#tbodys").html("");
			buscaCodigos(JSON.stringify(values),JSON.stringify(values2)).done(function(resp){
				if(resp){
					$.each(resp,function(indx,vals){
						vals.promocion = vals.promocion == null ? "" : vals.promocion;
						str += "<tr style='font-weight:bold'><td>"+vals.codigo+"</td><td>"+vals.nombre+"</td><td>$ "+vals.costo+"</td><td>"+vals.proveedor+
						"</td><td>"+formatMoney(vals.cedis,0)+"</td><td>"+formatMoney(vals.abarrotes)+"</td><td>"+formatMoney(vals.villas)+
						"</td><td>"+formatMoney(vals.tienda)+"</td><td>"+formatMoney(vals.ultra)+"</td><td>"+formatMoney(vals.trincheras)+
						"</td><td>"+formatMoney(vals.mercado)+"</td><td>"+formatMoney(vals.tenencia)+"</td><td>"+formatMoney(vals.tijeras)+
						"</td><td>"+vals.promocion+"</td></tr>";
					});
					$("#tbodys").html(str);
				}else{
					$("#tbodys").html("<tr><td colspan='14'>Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td></tr>")
				}
			})
		},1000)
	}else if(producto.val().length <= 3 && provs.val() !== "0"){
		setTimeout(function() {
			$("#tbodys").html("");
			buscaCodigos(JSON.stringify(values),JSON.stringify(values2)).done(function(resp){
				if(resp){
					$.each(resp,function(indx,vals){
						vals.promocion = vals.promocion == null ? "" : vals.promocion;
						str += "<tr style='font-weight:bold'><td>"+vals.codigo+"</td><td>"+vals.nombre+"</td><td>$ "+vals.costo+"</td><td>"+vals.proveedor+
						"</td><td>"+formatMoney(vals.cedis,0)+"</td><td>"+formatMoney(vals.abarrotes)+"</td><td>"+formatMoney(vals.villas)+
						"</td><td>"+formatMoney(vals.tienda)+"</td><td>"+formatMoney(vals.ultra)+"</td><td>"+formatMoney(vals.trincheras)+
						"</td><td>"+formatMoney(vals.mercado)+"</td><td>"+formatMoney(vals.tenencia)+"</td><td>"+formatMoney(vals.tijeras)+
						"</td><td>"+vals.promocion+"</td></tr>";
					});
					$("#tbodys").html(str);
				}else{
					$("#tbodys").html("<tr><td colspan='6'>Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td></tr>")
				}
			})
		},1000)
	}else{
		$("#tbodys").html("<tr><td colspan='14'>Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td></tr>");
	}
	
})

$(document).off("change", "#provs").on("change", "#provs", function (){
	event.preventDefault();
	var producto = $("#producto");
	var provs = $("#provs option:selected");
	var values = {"producto":producto.val()};
	var values2 = {"provs":provs.val()};

	var str = "";
	if (provs.val() !== 0 || producto.val().length > 3) {
		setTimeout(function() {
			$("#tbodys").html("");
			buscaCodigos(JSON.stringify(values),JSON.stringify(values2)).done(function(resp){
				if(resp){
					$.each(resp,function(indx,vals){
						vals.promocion = vals.promocion == null ? "" : vals.promocion;
						str += "<tr style='font-weight:bold'><td>"+vals.codigo+"</td><td>"+vals.nombre+"</td><td>$ "+vals.costo+"</td><td>"+vals.proveedor+
						"</td><td>"+formatMoney(vals.cedis,0)+"</td><td>"+formatMoney(vals.abarrotes)+"</td><td>"+formatMoney(vals.villas)+
						"</td><td>"+formatMoney(vals.tienda)+"</td><td>"+formatMoney(vals.ultra)+"</td><td>"+formatMoney(vals.trincheras)+
						"</td><td>"+formatMoney(vals.mercado)+"</td><td>"+formatMoney(vals.tenencia)+"</td><td>"+formatMoney(vals.tijeras)+
						"</td><td>"+vals.promocion+"</td></tr>";
					});
					$("#tbodys").html(str);
				}else{
					$("#tbodys").html("<tr><td colspan='14'>Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td></tr>")
				}
			})
		},1000)
	}else{
		$("#tbodys").html("<tr><td colspan='14'>Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td></tr>");
	} 
	
})

function buscaCodigos(values,values2){
    return $.ajax({
        url: site_url+"Facturas/buscaCodigos",
        type: "POST",
        dataType: "JSON",
        data: {
            values : values,
            values2:values2
        },
    });
}


$(document).off("change", "#file_codigos").on("change", "#file_codigos", function(event) {
	event.preventDefault();
	if ($("#file_codigos").val() !== ""){
		blockPage();
		var fdata = new FormData($("#upload_codigos")[0]);
		uploadPedidos(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
				$("#file_codigos").val("");
				//setTimeout("location.reload()", 1700, toastr.error(resp.desc, user_name), "");
			}else{
				unblockPage();
				toastr.success(resp.desc, user_name);
				$("#file_codigos").val("");
				//setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
	}
});


function uploadPedidos(formData) {
	return $.ajax({
		url: site_url+"Facturas/uploadPedidos",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

$(document).off("click", ".elimPed").on("click", ".elimPed", function (){
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_codigos")[0]);
	deletePedidos(fdata)
	.done(function (resp) {
		if (resp.type == 'error'){
			toastr.error(resp.desc, user_name);
		}else{
			unblockPage();
			toastr.success(resp.desc, user_name);
		}
	});
})

function deletePedidos(formData) {
	return $.ajax({
		url: site_url+"Facturas/deletePedidos",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}


