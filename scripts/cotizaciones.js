$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	$("#table_cot_admin").dataTable({
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
					columns: [0,1,2,3,4,5,6,7,8,9,10,11,12]
				},
				title: 'Cotizaciones',
			},
		]
	});

	fillDataTable("table_cot_proveedores", 'DESC', 50);
});

$(document).off("click", "#new_cotizacion").on("click", "#new_cotizacion", function(event) {
	event.preventDefault();
	getModal("Cotizaciones/add_cotizacion", function (){
		datePicker();
		getChosen();
		$(".number").inputmask("currency", {radixPoint: ".", prefix: ""});
		$(".numeric").number(true, 0);
	});
});

$(document).off("click", ".promocion").on("click", ".promocion", function() {
	if($(this).is(":checked")){
		$("#num_one").removeAttr('readonly').attr('name', 'num_one');
		$("#num_two").removeAttr('readonly').attr('name', 'num_two');
	}else{
		$("#num_one").attr('readonly','readonly').removeAttr('name').val('');
		$("#num_two").attr('readonly','readonly').removeAttr('name').val('');
		$("#precio").val('');
		$("#precio_promocion").val('');
	}
});

$(document).off("click", ".descuento").on("click", ".descuento", function() {
	if($(this).is(":checked")){
		$("#porcentaje").removeAttr('readonly').attr('name', 'porcentaje');
	}else{
		$("#porcentaje").attr('readonly', 'readonly').removeAttr('name').val('');
		$("#precio").val('');
		$("#precio_promocion").val('');
	}
});

$(document).off("click", ".new_cotizacion").on("click", ".new_cotizacion", function(event) {
	if($("#id_producto").val() !== ''){
		sendForm("Cotizaciones/save", $("#form_cotizacion_new"), "");
	}else{
		toastr.warning("Seleccione un artículo de la lista", $("#name_user").val());
	}
});

$(document).off("keyup", "#precio").on("keyup", "#precio", function() {
	var total =0;
	var descuento = $("#porcentaje").val().replace(/[^0-9\.]+/g,"");
	var precio = Number($(this).val().replace(/[^0-9\.]+/g,""));
	if($(".descuento").is(":checked")){
		descuento = Number('0.0'+descuento);
		total = (precio - (precio * descuento));
		$("#precio_promocion").val(total);
	}else{
		var num_1 = Number($("#num_one").val().replace(/[^0-9\.]+/g,""));
		var num_2 = Number($("#num_two").val().replace(/[^0-9\.]+/g,""));
		total = ((precio * num_2) / (num_1 + num_2));
		$("#precio_promocion").val(total);
	}
});

$(document).off("click", "#update_cotizacion").on("click", "#update_cotizacion", function(event){
	event.preventDefault();
	var id_cotizacion = $(this).closest("tr").find("#update_cotizacion").data("idPromocion");
	getModal("Cotizaciones/get_update/"+ id_cotizacion, function (){
		datePicker();
		$(".number").inputmask("currency", {radixPoint: ".", prefix: ""});
	});
});

$(document).off("click", ".update_cotizacion").on("click", ".update_cotizacion", function(event) {
	event.preventDefault();
	sendForm("Cotizaciones/update", $("#form_cotizacion_edit"), "");
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

$(document).off("change", "#file_cotizaciones").on("change", "#file_cotizaciones", function(event) {
	event.preventDefault();
	var fdata = new FormData($("#upload_cotizaciones")[0]);
	uploadCotizaciones(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, $("#name_user").val());
			}else{
				toastr.success(resp.desc, $("#name_user").val());
				// setTimeout("location.reload()", 1300, toastr.success(resp.desc, $("#name_user").val()), "");
			}
		});
});

function uploadCotizaciones(formData) {
	return $.ajax({
		url: site_url+"Cotizaciones/upload_cotizaciones",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

$(document).off("change", "#file_precios").on("change", "#file_precios", function(event) {
	event.preventDefault();
	var formdata = new FormData($("#upload_precios")[0]);
	uploadPrecios(formdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, $("#name_user").val());
			}else{
				toastr.success(resp.desc, $("#name_user").val());
				// setTimeout("location.reload()", 1300, toastr.success(resp.desc, $("#name_user").val()), "");
			}
		});
});

function uploadPrecios(formData) {
	return $.ajax({
		url: site_url+"Cotizaciones/upload_precios",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}