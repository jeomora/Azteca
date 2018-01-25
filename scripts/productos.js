$(function($) {
	$("#table_productos").dataTable({
		ajax: {
			url: site_url +"Productos/productos_dataTable",
			type: "POST"
		},
		processing: true,
		serverSide: true,
		responsive: true,
		pageLength: 50,
		dom: 'Bfrtip',
		lengthMenu: [
			[ 10, 30, 50, -1 ],
			[ '10 registros', '30 registros', '50 registros', 'Mostrar todos']
		],
		buttons: [
			{ extend: 'pageLength' },
		]
	});
});

$(window).on("load", function (event) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
});

$(document).off("click", "#new_producto").on("click", "#new_producto", function(event) {
	event.preventDefault();
	getModal("Productos/add_producto", function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_producto_new").validate({
				rules: {
					codigo: {required: true},
					nombre: {required: true},
					id_familia: {required: true, min:0}
				}
			});
			jQuery.extend(jQuery.validator.messages,{
				required: "Este campo es requerido",
				min: jQuery.validator.format("Este campo es requerido"),
			});
		});
	});
});

$(document).off("click", ".new_producto").on("click", ".new_producto", function(event) {
	event.preventDefault();
	if($("#form_producto_new").valid()){
		sendForm("Productos/accion/I", $("#form_producto_new"), "");
	}
});

$(document).off("click", "#update_producto").on("click", "#update_producto", function(event){
	event.preventDefault();
	var id_producto = $(this).closest("tr").find("#update_producto").data("idProducto");
	getModal("Productos/get_update/"+ id_producto, function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_producto_edit").validate({
				rules: {
					codigo: {required: true},
					nombre: {required: true},
					id_familia: {required: true, min:0}
				}
			});
			jQuery.extend(jQuery.validator.messages,{
				required: "Este campo es requerido",
				min: jQuery.validator.format("Este campo es requerido"),
			});
		});
	});
});

$(document).off("click", ".update_producto").on("click", ".update_producto", function(event) {
	event.preventDefault();
	if($("#form_producto_edit").valid()){
		sendForm("Productos/accion/U", $("#form_producto_edit"), "");
	}
});

$(document).off("click", "#delete_producto").on("click", "#delete_producto", function(event){
	event.preventDefault();
	var id_producto = $(this).closest("tr").find("#delete_producto").data("idProducto");
	getModal("Productos/get_delete/"+ id_producto, function (){ });
});

$(document).off("click", ".delete_producto").on("click", ".delete_producto", function(event) {
	event.preventDefault();
	sendForm("Productos/accion/D", $("#form_producto_delete"), "");
});