$(function($) {
	$("#table_prod_proveedor").dataTable({
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

var marcados =0;

$(document).off("change", ".id_producto").on("change", ".id_producto", function() {

	$(".precio").inputmask("currency", {radixPoint: ".", prefix: ""});
	
	if($(this).is(":checked")) {
		marcados = marcados + 1;
		$(this).closest("tr").find(".precio ").removeAttr('readonly');
		$(this).closest("tr").find(".id_producto ").attr('name', 'id_producto[]');
		$(this).closest("tr").find(".precio ").attr('name', 'precio[]');
	}else{
		$(this).removeAttr("checked");
		marcados = marcados - 1;
		$(this).closest("tr").find(".precio ").attr('readonly', 'readonly');
		$(this).closest("tr").find(".precio .id_producto").removeAttr('name').val('');
		$(this).closest("tr").find(".id_producto ").removeAttr('name');
	}
});

$(document).off("click", ".save").on("click", ".save", function (argument) {
	if(marcados > 0){
		if(validar() == true){
			save($("#form_asignacion_new").serializeArray())
				.done(function (res) {
					if(res.type == 'error') {
						toastr.error(res.desc, $("#name_user").val());
					}else{
						toastr.success(res.desc, $("#name_user").val());
						$("#main_container").load(site_url+"Productos_proveedor/productos_proveedor_view");
						cleanModal();
						$("#myModal").modal("hide");
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

$(document).off("click", ".update").on("click", ".update", function(event) {
	event.preventDefault();
	sendDatos("Productos_proveedor/accion/U/",$("#form_asignacion_edit"),"Productos_proveedor/productos_proveedor_view");
});

$(document).off("click", ".delete").on("click", ".delete", function(event) {
	event.preventDefault();
	sendDatos("Productos_proveedor/accion/D", $("#form_asignacion_delete"),"Productos_proveedor/productos_proveedor_view");
});
