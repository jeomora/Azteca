$(function($) {
	$('#home').tab('show');
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
			buscaCodigos(JSON.stringify(values,values2)).done(function(resp){
				if(resp){
					$.each(resp,function(indx,vals){
						str += "<tr><td>"+vals.codigo+"</td><td>"+vals.nombre+"</td><td>"+vals.proveedor+"</td><td>"+vals.codigo_factura+"</td><td>"+vals.descripcion+"</td><td>"+
						"<button class='btn btn-info' name='excel' type='button' data-id-code='"+vals.codigo+"'><i class='fa fa-edit'></i></button></td></tr>";
					});
					$("#tbodys").html(str);
				}else{
					$("#tbodys").html("<tr><td colspan='6'>Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td></tr>")
				}
			})
		},1000)
	}else if(producto.val().length <= 3 && provs.val() !== "0"){
		setTimeout(function() {
			$("#tbodys").html("");
			buscaCodigos(values).done(function(resp){
				if(resp){
					$.each(resp,function(indx,vals){
						str += "<tr><td>"+vals.codigo+"</td><td>"+vals.nombre+"</td><td>"+vals.proveedor+"</td><td>"+vals.codigo_factura+"</td><td>"+vals.descripcion+"</td><td>"+
						"<button class='btn btn-info' name='excel' type='button' data-id-code='"+vals.codigo+"'><i class='fa fa-edit'></i></button></td></tr>";
					});
					$("#tbodys").html(str);
				}else{
					$("#tbodys").html("<tr><td colspan='6'>Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td></tr>")
				}
			})
		},1000)
	}else{
		$("#tbodys").html("<tr><td colspan='6'>Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td></tr>");
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
			buscaCodigos(JSON.stringify(values,values2)).done(function(resp){
				if(resp){
					$.each(resp,function(indx,vals){
						str += "<tr><td>"+vals.codigo+"</td><td>"+vals.nombre+"</td><td>"+vals.proveedor+"</td><td>"+vals.codigo_factura+"</td><td>"+vals.descripcion+"</td><td>"+
						"<button class='btn btn-info' name='excel' type='button' data-id-code='"+vals.codigo+"'><i class='fa fa-edit'></i></button></td></tr>";
					});
					$("#tbodys").html(str);
				}else{
					$("#tbodys").html("<tr><td colspan='6'>Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td></tr>")
				}
			})
		},1000)
	}else{
		$("#tbodys").html("<tr><td colspan='6'>Ingrese una palabra clave o seleccione un proveedor para iniciar la busqueda</td></tr>");
	} 
	
})

function buscaCodigos(values,values2){
    return $.ajax({
        url: site_url+"Productos/buscaCodigos",
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
		uploadProductos(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name)
				//setTimeout("location.reload()", 1700, toastr.error(resp.desc, user_name), "");
			}else{
				unblockPage();
				toastr.success(resp.desc, user_name)
				//setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
	}
});


function uploadProductos(formData) {
	return $.ajax({
		url: site_url+"Productos/upload_codigos",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}