$(function($) {
	$("#table_precios_bajos").dataTable({
		responsive: true,
		pageLength: 50,
		order: [[0, 'ASC']],
		dom: 'Bfrtip',
		lengthMenu: [
			[ 10, 30, 50, -1 ],
			[ '10 registros', '30 registros', '50 registros', 'Mostrar todos']
		],
		buttons: [
			{ extend: 'pageLength' },
			{
				extend: 'excel',
				exportOptions: {
					columns: [0,1,2,3,4,5,6,7,8,9,10]
				},
				title: 'Precios_bajos',
			},
		]
	});

	$("#table_precios_iguales").dataTable({
		responsive: true,
		pageLength: 50,
		order: [[0, 'ASC']],
		dom: 'Bfrtip',
		lengthMenu: [
			[ 10, 30, 50, -1 ],
			[ '10 registros', '30 registros', '50 registros', 'Mostrar todos']
		],
		buttons: [
			{ extend: 'pageLength' },
			{
				extend: 'excel',
				exportOptions: {
					columns: [0,1,2,3,4,5,6,7,8,9]
				},
				title: 'Precios_iguales',
			},
		]
	});

	$("#table_compara_precios").dataTable({
		responsive: true,
		pageLength: 50,
		order: [[0, 'ASC']],
		dom: 'Bfrtip',
		lengthMenu: [
			[ 10, 30, 50, -1 ],
			[ '10 registros', '30 registros', '50 registros', 'Mostrar todos']
		],
		buttons: [
			{ extend: 'pageLength' },
			{
				extend: 'excel',
				exportOptions: {
					columns: [0,1,2,3,4,5,6,7]
				},
				title: 'Precios_iguales',
			},
		]
	});
	

});

