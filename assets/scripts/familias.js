jQuery(document).ready(function($) {

	$(document).off("click", ".save").on("click", ".save", function(event) {
		event.preventDefault();
		sendDatos("Familias_control/accion/I/",$("#form_familia_new"));

	});

});