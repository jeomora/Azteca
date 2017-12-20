	$(document).off('click', '#new_sucursal').on('click', '#new_sucursal', function(event) {
		event.preventDefault();
		getModal("Sucursales/newSucursal", function (){

		});
	});

	$(document).off('click', '.add_sucursal').on('click', '.add_sucursal', function(event) {
		event.preventDefault();
		sendDatos("Sucursales/accion/I", $("#form_sucursal_new"), "Sucursales/table_sucursales");
	});
