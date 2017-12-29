$(function($) {
	$("#table_prod_proveedor").dataTable({
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
					columns: [0,1,2,3,4,5,6]
				},
				title: 'Cotizaciones',
			},
		]
	});
});

$(document).off("click", "#new_asignacion").on("click", "#new_asignacion", function(event) {
	event.preventDefault();
	getModal("Productos_proveedor/add_asignacion", function (){
		$(".numeric").inputmask("currency", {radixPoint: ".", prefix: ""});
		$(".descuento").number(true, 0);
	});
});

var marcados =0;

$(document).off("change", ".id_producto").on("change", ".id_producto", function() {
	var tr = $(this).closest("tr");
	if($(this).is(":checked")) {
		marcados = marcados + 1;
		tr.find(".id_producto").attr('name', 'id_producto[]');
		tr.find(".precio").removeAttr('readonly').attr('name', 'precio[]');
		tr.find(".descuento").removeAttr('readonly').attr('name', 'descuento[]');
		tr.find(".total").attr('name', 'total[]');
	}else{
		$(this).removeAttr("checked");
		marcados = marcados - 1;
		tr.find(".id_producto").removeAttr('name').val('');
		tr.find(".precio").attr('readonly', 'readonly').removeAttr('name').val('');
		tr.find(".descuento").attr('readonly', 'readonly').removeAttr('name').val('');
		tr.find(".total").removeAttr('name').val('');
	}
});

$(document).off("keyup", ".descuento").on("keyup", ".descuento", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var descuento = $(this).val();

	if(descuento.replace(/[^0-9\.]+/g,"") > 0){
		if($(this).val().length > 1){
			$(this).val('');
		}else{
			descuento = '0.0'+descuento;
			tr.find(".total").val(precio - (precio * parseFloat(descuento)));
		}
	}
});

$(document).off("click", ".new_asignacion").on("click", ".new_asignacion", function (argument) {
	if(marcados > 0){
		if(validar() == true){
			save($("#form_asignacion_new").serializeArray())
				.done(function (res) {
					if(res.type == 'error') {
						toastr.error(res.desc, $("#name_user").val());
					}else{
						toastr.success(res.desc, $("#name_user").val());
						location.reload();
					}
				})
				.fail(function(res) {
					console.log("Error en la respuesta");
				});
			}
		}else{
			toastr.warning("Marque 1 producto como mínimo", $("#name_user").val());
		}
});

function validar(){
	var ichecks = $(".id_producto");
	var input = "";
	var errors = 0;
	$.each(ichecks, function(){
		if($(this).is(":checked")){
			input = $(this).closest("tr").find(".precio");
			if(!input.val()){
				errors++;
				return false;
			}
		}
	});
	if(errors > 0 ){
		toastr.warning("Faltan algunos precios por llenar", $("#name_user").val());
		return false;
	}
	return true;
}

function save(argument) {
	return $.ajax({
		url: site_url+'Productos_proveedor/save_asignados',
		type: 'POST',
		async:false,
		data: argument
	});
}

$(document).off("click", "#update_asignacion").on("click", "#update_asignacion", function(event) {
	event.preventDefault();
	var id_prod_proveedor = $(this).closest("tr").find("#update_asignacion").data("idProd_proveedor");
	getModal("Productos_proveedor/get_update/"+id_prod_proveedor, function (){
		$(".numeric").inputmask("currency", {radixPoint: ".", prefix: ""});
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_asignacion_edit").validate({
				rules: {
					precio: {required: true}
				}
			});
			jQuery.extend(jQuery.validator.messages,{
				required: "Este campo es requerido"
			});
		});
	});
});

$(document).off("click", ".update_asignacion").on("click", ".update_asignacion", function(event) {
	event.preventDefault();
	if($("#form_asignacion_edit").valid()){
		sendForm("Productos_proveedor/accion/U", $("#form_asignacion_edit"), "");
	}
});

$(document).off("click", "#delete_asignacion").on("click", "#delete_asignacion", function(event) {
	event.preventDefault();
	var id_prod_proveedor = $(this).closest("tr").find("#delete_asignacion").data("idProd_proveedor");
	getModal("Productos_proveedor/get_delete/"+id_prod_proveedor, function (){
		$("#mybotton").html('&nbsp;Aceptar').addClass('btn btn-warning');
		$("#mybotton").find('bold').removeClass('fa-floppy-o');
		$("#mybotton").addClass('fa fa-trash');
	});
});

$(document).off("click", ".delete_asignacion").on("click", ".delete_asignacion", function(event) {
	event.preventDefault();
	sendForm("Productos_proveedor/accion/D", $("#form_asignacion_delete"), "");
});

