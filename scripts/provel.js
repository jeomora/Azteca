$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	fillDataTable("table_usuarios", 20);
});

$(document).off("click", "#new_proveedor").on("click", "#new_proveedor", function(event) {
	event.preventDefault();
	getModal("Lunes/new_proveedor", function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_proveedor_new").validate({
				rules: {
					nombre: {required: true},
					apellido: {required: true}
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

$(document).off("click", ".new_proveedor").on("click", ".new_proveedor", function(event) {
	event.preventDefault();
	if($("#form_proveedor_new").valid()){
		sendForm("Lunes/save_prove", $("#form_proveedor_new"), "");
	}
});

$(document).off("click", "#update_proveedor").on("click", "#update_proveedor", function(event) {
	event.preventDefault();
	var id_proveedor = $(this).closest("tr").find("#update_proveedor").data("idProveedor");
	getModal("Lunes/prove_update/"+id_proveedor, function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_proveedor_edit").validate({
				rules: {
					nombre: {required: true},
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

$(document).off("click", ".update_proveedor").on("click", ".update_proveedor", function(event) {
	event.preventDefault();
	if($("#form_proveedor_edit").valid()){
		sendForm("Lunes/update_prove", $("#form_proveedor_edit"), "");
	}
});

$(document).off("click", "#delete_proveedor").on("click", "#delete_proveedor", function(event) {
	event.preventDefault();
	var id_proveedor = $(this).closest("tr").find("#delete_proveedor").data("idProveedor");
	getModal("Lunes/prove_delete/"+id_proveedor, function (){ });
});

$(document).off("click", ".delete_proveedor").on("click", ".delete_proveedor", function(event) {
	event.preventDefault();
	sendForm("Lunes/delete_prove", $("#form_proveedor_delete"), "");
});