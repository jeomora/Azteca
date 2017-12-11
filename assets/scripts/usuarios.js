$(function($) {
	$("#table_usuarios").dataTable({
		responsive: true,
		pageLength  : 10,
		"order": [[0, 'DESC']],
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
