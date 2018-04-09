$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});

	$("#table_precios_bajos").dataTable({
		responsive: true,
		pageLength: 50,
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
					columns: [0,1,2,3,4,5,6,7,8,9,10,11,12]
				},
				title: 'Precios_bajos',
			},
		]
	});

	$("#table_precios_iguales").dataTable({
		responsive: true,
		pageLength: 50,
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

	datePicker();

});


$(document).off("click", "#filter_show").on("click", "#filter_show", function(event) {
	event.preventDefault();
	var formData = $("#consultar_cotizaciones").serializeArray();
	get_reporte(formData).done(function(response) {
		$("#respuesta_show").html(response);
			fillDataTable("table table-bordered table-striped table-bordered table-hover font",50);
			toastr.success("Resultados de la busqueda", user_name);
		});
});


function get_reporte(formData) {
	return $.ajax({
		url: site_url+"Reportes/fill_table",
		type: "POST",
		cache: false,
		dataType:"HTML",
		data: formData,
	});
}

