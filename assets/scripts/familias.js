$(function($) {
	
	fillDataTable("table_familias", 'DESC', 10);

});

$(document).off("click", ".save").on("click", ".save", function(event) {
	event.preventDefault();
	sendDatos("Familias/accion/I", $("#form_familia_new"), "Familias/familias_view");
});

$(document).off("click", ".update").on("click", ".update", function(event) {
	event.preventDefault();
	sendDatos("Familias/accion/U/",$("#form_familia_edit"), "Familias/familias_view");
});

$(document).off("click", ".delete").on("click", ".delete", function(event) {
	event.preventDefault();
	sendDatos("Familias/accion/D", $("#form_familia_delete"), "Familias/familias_view");
});
