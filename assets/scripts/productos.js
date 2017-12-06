jQuery(document).ready(function($) {

	$(document).off("click", ".save").on("click", ".save", function(event) {
		event.preventDefault();
		sendDatos("Productos/accion/I/",$("#form_producto_new"));
	});

	$(document).off("click", ".update").on("click", ".update", function(event) {
		event.preventDefault();
		sendDatos("Productos/accion/U/",$("#form_producto_edit"));
	});

	$(document).off("click", ".delete").on("click", ".delete", function(event) {
		event.preventDefault();
		sendDatos("Productos/accion/D",$("#form_producto_edit"));
	});

});