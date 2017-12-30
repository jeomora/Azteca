$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	$("#table_promociones").dataTable({
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
					columns: [0,1,2,3,4,5,6,7,8,9,10,11]
				},
				title: 'Promociones',
			},
		]
	});
});

$(document).off("click", "#new_promocion").on("click", "#new_promocion", function(event) {
	event.preventDefault();
	getModal("Promociones/add_promocion", function (){
		datePicker();
		$(".number").inputmask("currency", {radixPoint: ".", prefix: ""});
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_promocion_new").validate({
				rules: {
					id_producto: {required: true, min:0},
					nombre: {required: true},
					precio_producto: {required: true}
				}
			});

			jQuery.extend(jQuery.validator.messages, {
				required: "Este campo es requerido",
				min: jQuery.validator.format("Este campo es requerido"),
			});
		});
	});
});

$(document).off("click", ".new_promocion").on("click", ".new_promocion", function(event) {
	if($("#form_promocion_new").valid()){
		sendForm("Promociones/save", $("#form_promocion_new"), "");
	}
});

$(document).off("keyup", "#porcentaje").on("keyup", "#porcentaje", function() {
	var descuento = $(this).val().replace(/[^0-9\.]+/g,"");
	var precio_producto = $("#precio_producto").val().replace(/[^0-9\.]+/g,"");
	if(precio_producto != ''){
		$("#precio_descuento").val((precio_producto - (precio_producto * descuento)));
	}
});

$(document).off("click", "#update_promocion").on("click", "#update_promocion", function(event){
	event.preventDefault();
	var id_promocion = $(this).closest("tr").find("#update_promocion").data("idPromocion");
	getModal("Promociones/get_update/"+ id_promocion, function (){
		datePicker();
		$(".number").inputmask("currency", {radixPoint: ".", prefix: ""});
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_promocion_edit").validate({
				rules: {
					id_producto: {required: true, min:0},
					precio_producto: {required: true},
				
				}
			});

			jQuery.extend(jQuery.validator.messages, {
				required: "Este campo es requerido",
				min: jQuery.validator.format("Este campo es requerido"),
			});
		});
	});
});

$(document).off("click", ".update_promocion").on("click", ".update_promocion", function(event) {
	event.preventDefault();
	if($("#form_promocion_edit").valid()){
		sendForm("Promociones/update", $("#form_promocion_edit"), "");
	}
});

$(document).off("click", "#delete_promocion").on("click", "#delete_promocion", function(event){
	event.preventDefault();
	var id_promocion = $(this).closest("tr").find("#delete_promocion").data("idPromocion");
	getModal("Promociones/get_delete/"+ id_promocion, function (){ });
});

$(document).off("click", ".delete_promocion").on("click", ".delete_promocion", function(event) {
	event.preventDefault();
	sendForm("Promociones/delete", $("#form_promocion_delete"), "");
});