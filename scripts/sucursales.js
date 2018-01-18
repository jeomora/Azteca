$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	fillDataTable("table_sucursales", 10);
});

$(document).off("click", "#new_sucursal").on("click", "#new_sucursal", function(event) {
	event.preventDefault();
	getModal("Sucursales/add_sucursal", function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_sucursal_new").validate({
				rules: {
					nombre: {required: true},
					telefono: {required: true}
				}
			});
			jQuery.extend(jQuery.validator.messages,{
				required: "Este campo es requerido",
			});
		});
	});
});

$(document).off("click", ".new_sucursal").on("click", ".new_sucursal", function(event) {
	event.preventDefault();
	if($("#form_sucursal_new").valid()){
		sendForm("Sucursales/accion/I", $("#form_sucursal_new"), "");
	}
});

$(document).off("click", "#update_sucursal").on("click", "#update_sucursal", function(event){
	event.preventDefault();
	var id_sucursal = $(this).closest("tr").find("#update_sucursal").data("idSucursal");
	getModal("Sucursales/get_update/"+ id_sucursal, function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_sucursal_edit").validate({
				rules: {
					nombre: {required: true},
					telefono: {required: true}
				}
			});
			jQuery.extend(jQuery.validator.messages,{
				required: "Este campo es requerido",
			});
		});
	});
});

$(document).off("click", ".update_sucursal").on("click", ".update_sucursal", function(event) {
	event.preventDefault();
	if($("#form_sucursal_edit").valid()){
		sendForm("Sucursales/accion/U", $("#form_sucursal_edit"), "");
	}
});

$(document).off("click", "#delete_sucursal").on("click", "#delete_sucursal", function(event){
	event.preventDefault();
	var id_sucursal = $(this).closest("tr").find("#delete_sucursal").data("idSucursal");
	getModal("Sucursales/get_delete/"+ id_sucursal, function (){ });
});

$(document).off("click", ".delete_sucursal").on("click", ".delete_sucursal", function(event) {
	event.preventDefault();
	sendForm("Sucursales/accion/D", $("#form_sucursal_delete"), "");
});