$(function($){
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	fillDataTable("table_familias", 10);
});

$(document).off("click", "#new_familia").on("click", "#new_familia", function(event) {
	event.preventDefault();
	getModal("Familias/add_familia", function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_familia_new").validate({
				rules: {
					nombre: {required: true}
				}
			});
			jQuery.extend(jQuery.validator.messages,{
				required: "Este campo es requerido",
			});
		});
	});
});

$(document).off("click", ".new_familia").on("click", ".new_familia", function(event) {
	event.preventDefault();
	if($("#form_familia_new").valid()){
		sendForm("Familias/accion/I", $("#form_familia_new"), "");
	}
});

$(document).off("click", "#update_familia").on("click", "#update_familia", function(event){
	event.preventDefault();
	var id_familia = $(this).closest("tr").find("#update_familia").data("idFamilia");
	getModal("Familias/get_update/"+ id_familia, function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_familia_edit").validate({
				rules: {
					nombre: {required: true}
				}
			});
			jQuery.extend(jQuery.validator.messages,{
				required: "Este campo es requerido",
			});
		});
	});
});

$(document).off("click", ".update_familia").on("click", ".update_familia", function(event) {
	event.preventDefault();
	if($("#form_familia_edit").valid()){
		sendForm("Familias/accion/U", $("#form_familia_edit"), "");
	}
});

$(document).off("click", "#delete_familia").on("click", "#delete_familia", function(event){
	event.preventDefault();
	var id_familia = $(this).closest("tr").find("#delete_familia").data("idFamilia");
	getModal("Familias/get_delete/"+ id_familia, function (){ });
});

$(document).off("click", ".delete_familia").on("click", ".delete_familia", function(event) {
	event.preventDefault();
	sendForm("Familias/accion/D", $("#form_familia_delete"), "");
});