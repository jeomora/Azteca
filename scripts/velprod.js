$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	fillDataTable("table_usuarios", 30);
});
$("#new_producto").mousedown(function(e){
		//1: izquierda, 2: medio/ruleta, 3: derecho
	if(e.which == 3) 
		{
   		 console.log("kfnflksnlkdfnkl")
		}
	});

$(document).off("click", "#new_producto").on("click", "#new_producto", function(event) {
	event.preventDefault();
	getModal("Reyma/new_producto", function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_producto_new").validate({
				rules: {
					descripcion: {required: true},
					unidad: {required: true},
				}
			});
			jQuery.extend(jQuery.validator.messages,{
				required: "Este campo es requerido",
				min: "Este campo es requerido",
				email: "Por favor ingrese un correo valido",
				minlength: jQuery.validator.format("Por favor ingresa más de {0} caracteres.")
			});
		});
	});
});

$(document).off("click", ".new_producto").on("click", ".new_producto", function(event) {
	event.preventDefault();
	if($("#form_producto_new").valid()){
		sendForm("Reyma/save_prod", $("#form_producto_new"), "");
	}
});

$(document).off("click", "#update_producto").on("click", "#update_producto", function(event) {
	event.preventDefault();
	var id_producto = $(this).closest("tr").find("#update_producto").data("idProducto");
	getModal("Reyma/prod_update/"+id_producto, function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_producto_edit").validate({
				rules: {
					descripcion: {required: true},
					unidad: {required: true},
				}
			});
			jQuery.extend(jQuery.validator.messages,{
				required: "Este campo es requerido",
				min: "Este campo es requerido",
				email: "Por favor ingrese un correo valido",
				minlength: jQuery.validator.format("Por favor ingresa más de {0} caracteres.")
			});
		});
	});
});

$(document).off("click", ".update_producto").on("click", ".update_producto", function(event) {
	event.preventDefault();
	if($("#form_producto_edit").valid()){
		sendForm("Reyma/update_prod", $("#form_producto_edit"), "");
	}
});

$(document).off("click", "#delete_producto").on("click", "#delete_producto", function(event) {
	event.preventDefault();
	var id_producto = $(this).closest("tr").find("#delete_producto").data("idProducto");
	getModal("Reyma/prod_delete/"+id_producto, function (){ });
});

$(document).off("click", ".delete_producto").on("click", ".delete_producto", function(event) {
	event.preventDefault();
	console.log("foijseifjwoijfioj")
	sendForm("Reyma/delete_prod", $("#form_producto_delete"), "");
});

$(document).off("change", "#file_otizaciones").on("change", "#file_otizaciones", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_cotizaciones")[0]);
	uploadCotizaciones(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
			}else{
				unblockPage();
				setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});
function uploadCotizaciones(formData) {
	return $.ajax({
		url: site_url+"Reyma/upload_prods/",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}