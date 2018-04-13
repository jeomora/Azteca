$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	fillDataTable("table_usuarios", 10);
});

$(document).off("click", "#new_usuario").on("click", "#new_usuario", function(event) {
	event.preventDefault();
	getModal("Welcome/new_usuario", function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_usuario_new").validate({
				rules: {
					nombre: {required: true},
					correo: {email:true, required: true},
					password: {required: true, minlength: 8},
					id_grupo: {required: true, min: 0},

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

$(document).off("click", ".new_usuario").on("click", ".new_usuario", function(event) {
	event.preventDefault();
	if($("#form_usuario_new").valid()){
		sendForm("Welcome/save_user", $("#form_usuario_new"), "");
	}
});

$(document).off("click", "#update_usuario").on("click", "#update_usuario", function(event) {
	event.preventDefault();
	var id_usuarios = $(this).closest("tr").find("#update_usuario").data("idUsuario");
	getModal("Welcome/get_update/"+id_usuarios, function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_usuario_edit").validate({
				rules: {
					nombre: {required: true},
					correo: {email:true, required: true},
					password: {required: true, minlength: 8},
					id_grupo: {required: true, min: 0},

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

$(document).off("click", ".update_usuario").on("click", ".update_usuario", function(event) {
	event.preventDefault();
	if($("#form_usuario_edit").valid()){
		sendForm("Welcome/update_user", $("#form_usuario_edit"), "");
	}
});

$(document).off("click", "#delete_usuario").on("click", "#delete_usuario", function(event) {
	event.preventDefault();
	var id_usuarios = $(this).closest("tr").find("#delete_usuario").data("idUsuario");
	getModal("Welcome/get_delete/"+id_usuarios, function (){ });
});

$(document).off("click", ".delete_usuario").on("click", ".delete_usuario", function(event) {
	event.preventDefault();
	sendForm("Welcome/delete_user", $("#form_usuario_delete"), "");
});

$(document).off("click", "#show_usuario").on("click", "#show_usuario", function(event){
	event.preventDefault();
	var id_usuario = $(this).closest("tr").find("#show_usuario").data("idUsuario");
	getModal("Welcome/get_usuario/"+ id_usuario, function (){ });
});

