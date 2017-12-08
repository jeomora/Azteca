jQuery(document).ready(function($) {
	$("#table_productos").dataTable({
		responsive: true,
		pageLength  : 10,
		"order": [[0, 'ASC']],
		dom: 'Bfrtip',
		lengthMenu: [
			[ 50, 25, 10, -1 ],
			[ '50 registros', '30 registros', '10 registros', 'Mostrar todos']
		],
		buttons: [{
			extend: 'pageLength'
		}]
	});
});

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