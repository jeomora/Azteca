$(function($) {
	$("[data-tooltip='tooltip']").tooltip({
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
					apellido: {required: true},
					correo: {required: true},
					password: {required: true},
					id_grupo: {required: true, min: 0},

				}
			});
			jQuery.extend(jQuery.validator.messages,{
				required: "Este campo es requerido",
				min: "Este campo es requerido",
			});
		});
	});
});

$(document).off("click", ".new_usuario").on("click", ".new_usuario", function(event) {
	event.preventDefault();
	if($("#form_usuario_new").valid()){
		sendForm("Welcome/accion/I", $("#form_usuario_new"), "");
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
					apellido: {required: true},
					correo: {required: true},
					password: {required: true},
					id_grupo: {required: true, min: 0},

				}
			});
			jQuery.extend(jQuery.validator.messages,{
				required: "Este campo es requerido",
				min: "Este campo es requerido",
			});
		});
	});
});

$(document).off("click", ".update_usuario").on("click", ".update_usuario", function(event) {
	event.preventDefault();
	if($("#form_usuario_edit").valid()){
		sendForm("Welcome/accion/U", $("#form_usuario_edit"), "");
	}
});

$(document).off("click", "#delete_usuario").on("click", "#delete_usuario", function(event) {
	event.preventDefault();
	var id_usuarios = $(this).closest("tr").find("#delete_usuario").data("idUsuario");
	getModal("Welcome/get_delete/"+id_usuarios, function (){ });
});

$(document).off("click", ".delete_usuario").on("click", ".delete_usuario", function(event) {
	event.preventDefault();
	sendForm("Welcome/accion/D", $("#form_usuario_delete"), "");
});