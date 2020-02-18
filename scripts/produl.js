$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	fillDataTable("table_usuarios", 30);
});

$(document).off("click", "#new_producto").on("click", "#new_producto", function(event) {
	event.preventDefault();
	getModal("Lunes/new_producto", function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_producto_new").validate({
				rules: {
					codigo: {required: true},
					descripcion: {required: true},
					unidad: {required: true},
					id_proveedor: {required: true, min: 0},
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
		var promo = $("#promo").children("option:selected").val();
		if (promo === 1){
			var cuantos1 = $("#cuantos1");
			var cuantos2 = $("#cuantos2");
			if (cuantos1.val() === "" || cuantos1.val() === 0) {
				cuantos1.css("border","1px solid red");
			}else if(cuantos2.val() === "" || cuantos2.val() === 0){
				cuantos1.css("border","1px solid #e5e6e7");
				cuantos2.css("border","1px solid red");
			}else{
				cuantos2.css("border","1px solid #e5e6e7");
				sendForm("Lunes/save_prod", $("#form_producto_new"), "");
			}
		}else if(promo === 2){
			var descuento = $("#descuento");
			if (descuento.val() === "" || descuento === 0) {
				descuento.css("border","1px solid red");
			}else{
				descuento.css("border","1px solid #e5e6e7");
				sendForm("Lunes/save_prod", $("#form_producto_new"), "");	
			}
		}else if(promo === 3){
			var cuantos1 = $("#cuanto1");
			var cuantos2 = $("#cuanto2");
			if (cuantos1.val() === "" || cuantos1.val() === 0) {
				cuantos1.css("border","1px solid red");
			}else if (cuantos2.val() === "" || cuantos2.val() === 0) {
				cuantos2.css("border","1px solid red");
			}else{
				sendForm("Lunes/update_prod", $("#form_producto_edit"), "");
			}
		}
		else{
			sendForm("Lunes/save_prod", $("#form_producto_new"), "");
		}
	}
});

$(document).off("click", "#update_producto").on("click", "#update_producto", function(event) {
	event.preventDefault();
	var id_producto = $(this).closest("tr").find("#update_producto").data("idProducto");
	getModal("Lunes/prod_update/"+id_producto, function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_producto_edit").validate({
				rules: {
					codigo: {required: true},
					descripcion: {required: true},
					unidad: {required: true},
					id_proveedor: {required: true, min: 0},
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
		var promo = $("#promo").children("option:selected").val();
		if (promo === 1 || promo === "1"){
			var cuantos1 = $("#cuantos1");
			var cuantos2 = $("#cuantos2");
			if (cuantos1.val() === "" || cuantos1.val() === 0) {
				cuantos1.css("border","1px solid red");
			}else if(cuantos2.val() === "" || cuantos2.val() === 0){
				cuantos1.css("border","1px solid #e5e6e7");
				cuantos2.css("border","1px solid red");
			}else{
				cuantos2.css("border","1px solid #e5e6e7");
				sendForm("Lunes/update_prod", $("#form_producto_edit"), "");
			}
		}else if(promo === 2 || promo === "2"){
			var descuento = $("#descuento");
			if (descuento.val() === "" || descuento === 0) {
				descuento.css("border","1px solid red");
			}else{
				descuento.css("border","1px solid #e5e6e7");
				sendForm("Lunes/update_prod", $("#form_producto_edit"), "");
			}
		}else if(promo === 3 || promo === "3"){
			var cuantos1 = $("#cuanto1");
			var cuantos2 = $("#cuanto2");
			if (cuantos1.val() === "" || cuantos1.val() === 0) {
				cuantos1.css("border","1px solid red");
			}else if (cuantos2.val() === "" || cuantos2.val() === 0) {
				cuantos2.css("border","1px solid red");
			}else{
				sendForm("Lunes/update_prod", $("#form_producto_edit"), "");
			}
		}
		else{
			sendForm("Lunes/update_prod", $("#form_producto_edit"), "");
		}
	}
});

$(document).off("click", "#delete_producto").on("click", "#delete_producto", function(event) {
	event.preventDefault();
	var id_producto = $(this).closest("tr").find("#delete_producto").data("idProducto");
	getModal("Lunes/prod_delete/"+id_producto, function (){ });
});

$(document).off("click", ".delete_producto").on("click", ".delete_producto", function(event) {
	event.preventDefault();
	sendForm("Lunes/delete_prod", $("#form_producto_delete"), "");
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
				setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});
function uploadCotizaciones(formData) {
	return $.ajax({
		url: site_url+"Lunes/asociar_codigos/",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

$(document).off("change", "#promo").on("change", "#promo", function(event) {
	event.preventDefault();
	var promo = $("#promo").children("option:selected").val();
	$(".promdes").css("display","none");
	if (promo !== 0) {
		$(".promodescuentos"+promo).css("display","block");
	}
});
