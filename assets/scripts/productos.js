$(function($) {
	$("#table_productos").dataTable({
		responsive: true,
		pageLength  : 10,
		"order": [[0, 'ASC']],
		dom: 'Bfrtip',
		lengthMenu: [
			[ 10, 30, 50, -1 ],
			[ '10 registros', '30 registros', '50 registros', 'Mostrar todos']
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