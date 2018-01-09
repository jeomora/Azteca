$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	$("#table_cotizaciones").dataTable({
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
				title: 'Cotizaciones',
			},
		]
	});
});

$(document).off("click", "#new_cotizacion").on("click", "#new_cotizacion", function(event) {
	event.preventDefault();
	getModal("Cotizaciones/add_cotizacion", function (){
		datePicker();
		getChosen();
		$(".number").inputmask("currency", {radixPoint: ".", prefix: ""});
		$(".numeric").number(true, 0);

		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_cotizacion_new").validate({
				rules: {
					id_producto: {required: true, min:0},
					precio: {required: true}
				}
			});

			jQuery.extend(jQuery.validator.messages, {
				required: "Este campo es requerido",
				min: jQuery.validator.format("Este campo es requerido"),
			});
		});
	});
});

$(document).off("click", ".promocion").on("click", ".promocion", function() {
	if($(this).is(":checked")){
		$("#num_one").removeAttr('readonly').attr('name', 'num_one');
		$("#num_two").removeAttr('readonly').attr('name', 'num_two');
		$("#precio_factura").removeAttr('readonly').attr('name', 'precio_factura');
		$(".descuento").removeAttr("checked");
	}
});

$(document).off("click", ".descuento").on("click", ".descuento", function() {
	if($(this).is(":checked")) {
		$("#num_one").attr('readonly','readonly').removeAttr('name').val('');
		$("#num_two").attr('readonly','readonly').removeAttr('name').val('');
		$("#porcentaje").removeAttr('readonly').attr('name', 'porcentaje');
		$("#precio_factura").removeAttr('readonly').attr('name', 'precio_factura');
		$(".promocion").removeAttr("checked");
	}
});

$(document).off("click", ".new_cotizacion").on("click", ".new_cotizacion", function(event) {
	if($("#form_cotizacion_new").valid()){
		sendForm("Cotizaciones/save", $("#form_cotizacion_new"), "");
	}
});

$(document).off("keyup", "#precio_factura").on("keyup", "#precio_factura", function() {
	var total =0;
	var descuento = $("#porcentaje").val().replace(/[^0-9\.]+/g,"");
	var precio_factura = Number($(this).val().replace(/[^0-9\.]+/g,""));
	if($(".descuento").is(":checked")){
		descuento = Number('0.0'+descuento);
		total = (precio_factura - (precio_factura * descuento));
		$("#precio").val(total);
	}else{
		var num_1 = Number($("#num_one").val().replace(/[^0-9\.]+/g,""));
		var num_2 = Number($("#num_two").val().replace(/[^0-9\.]+/g,""));
		total = ((precio_factura * num_2) / (num_1 + num_2));
		$("#precio").val(total);
	}
});

$(document).off("click", "#update_cotizacion").on("click", "#update_cotizacion", function(event){
	event.preventDefault();
	var id_cotizacion = $(this).closest("tr").find("#update_cotizacion").data("idPromocion");
	getModal("Cotizaciones/get_update/"+ id_cotizacion, function (){
		datePicker();
		$(".number").inputmask("currency", {radixPoint: ".", prefix: ""});
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_cotizacion_edit").validate({
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

$(document).off("click", ".update_cotizacion").on("click", ".update_cotizacion", function(event) {
	event.preventDefault();
	if($("#form_cotizacion_edit").valid()){
		sendForm("Cotizaciones/update", $("#form_cotizacion_edit"), "");
	}
});

$(document).off("click", "#delete_cotizacion").on("click", "#delete_cotizacion", function(event){
	event.preventDefault();
	var id_cotizacion = $(this).closest("tr").find("#delete_cotizacion").data("idPromocion");
	getModal("Cotizaciones/get_delete/"+ id_cotizacion, function (){ });
});

$(document).off("click", ".delete_cotizacion").on("click", ".delete_cotizacion", function(event) {
	event.preventDefault();
	sendForm("Cotizaciones/delete", $("#form_cotizacion_delete"), "");
});