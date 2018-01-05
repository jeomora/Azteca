$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	fillDataTable("table_pedidos", 'DESC', 10);
});

$(document).off("click", "#new_pedido").on("click", "#new_pedido", function(event) {
	event.preventDefault();
	getModal("Pedidos/add_pedido", function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_pedido_new").validate({
				rules: {
					id_proveedor: {required: true},
					id_sucursal: {required: true}
				}
			});

			jQuery.extend(jQuery.validator.messages, {
				required: "Este campo es requerido",
				min: "Este campo es requerido"
			});
		});
	});
});

$(document).off("click", ".new_pedido").on("click", ".new_pedido", function(event) {
	event.preventDefault();
	if($("#form_pedido_new").valid()){
		if(marcados > 0){
			if(validar() == true){
				savePedido($("#form_pedido_new").serializeArray())
				.done(function (res) {
					if(res.type == 'error') {
						toastr.error(res.desc, $("#name_user").val());
					}else{
						toastr.success(res.desc, $("#name_user").val());
						location.reload();
					}
				})
				.fail(function(res) { });
			}
		}else{
			toastr.warning("Marque 1 producto como mínimo", $("#name_user").val());
		}
	}
});

$(document).off("change","#id_proveedor").on("change","#id_proveedor", function () {
	var rows = "";
	var proveedor = $("#id_proveedor option:selected").text();
	getProductos($(this).val())
		.done(function (response) {
			var size = response.length;
			$("#body_response").empty();
			if (jQuery.isEmptyObject(response)) {
				toastr.warning("El Proveedor "+proveedor+" no tiene Productos cotizados", $("#name_user").val());
			}else{
				$.each(response, function(index, val) {
					rows += "<tr>"
								+"<td> <input type='checkbox' value="+val.id_producto+" class='id_producto'> </td>"
								+"<td>"+val.producto+"</td>"
								+"<td>"
									+"<div class='input-group m-b'>"
										+"<span class='input-group-addon'><i class='fa fa-dollar'></i></span>"
										+"<input type='text' value="+formatNumber(parseFloat(val.precio), 2)+" class='form-control precio' readonly=''>"
									+"</div>"
								+"</td>"
								+"<td>"
									+"<div class='input-group m-b'>"
										+"<span class='input-group-addon'><i class='fa fa-slack'></i></span>"
										+"<input type='text' value='' class='form-control cantidad numeric'  readonly=''> "
									+"</div>"
								+"</td>"
								+"<td>"
									+"<div class='input-group m-b'>"
										+"<span class='input-group-addon'><i class='fa fa-dollar'></i></span>"
										+"<input type='text' value='' class='form-control importe numeric' readonly=''>"
									+"</div>"
								+"</td>"
							+"</tr>";
				});
				$("#body_response").append(rows);
				$(".numeric").inputmask("currency", {radixPoint: ".", prefix: ""});
				toastr.success("El Proveedor "+proveedor+" tiene "+size+" Productos cotizados", $("#name_user").val());
			}
		})
		.fail(function (response) {
			// body...
		});
});

function getProductos(id_prov) {
	return $.ajax({
		url: site_url+"/Pedidos/get_productos",
		type: "POST",
		dataType: "JSON",
		data: {id_proveedor: id_prov},
	});
}

var marcados =0;

$(document).off("change", ".id_producto").on("change", ".id_producto", function() {
	var tr = $(this).closest("tr");
	if($(this).is(":checked")) {
		marcados = marcados + 1;
		tr.find(".cantidad").removeAttr('readonly');
		tr.find(".id_producto ").attr('name', 'id_producto[]');
		tr.find(".precio ").attr('name', 'precio[]');
		tr.find(".cantidad ").attr('name', 'cantidad[]');
		tr.find(".importe ").attr('name', 'importe[]');
	}else{
		$(this).removeAttr("checked");
		marcados = marcados - 1;
		tr.find(".cantidad").attr('readonly', 'readonly');
		tr.find(".cantidad").removeAttr('name').val('');
		tr.find(".precio").removeAttr('name').val('');
		tr.find(".importe").removeAttr('name').val('');
		tr.find(".id_producto ").removeAttr('name');
		$("#subtotal").val(calculaTotales());
		$("#iva").val((calculaTotales() * 0.16));
		$("#total").val((calculaTotales() * 1.16));
	}
});

$(document).off("keyup", ".cantidad").on("keyup", ".cantidad", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var cantidad = tr.find(".cantidad").val().replace(/[^0-9\.]+/g,"");

	if($(this).val().replace(/[^0-9\.]+/g,"") > 0){
		tr.find(".importe").val(precio * cantidad);
		$("#subtotal").val(calculaTotales());
		$("#iva").val((calculaTotales() * 0.16));
		$("#total").val((calculaTotales() * 1.16));
	}
});

function calculaTotales() {
	var total =0;
	$.each($(".importe"), function(index, val) {
		if (Number($(this).val().replace(/[^0-9\.]+/g,"")) !== '') {
			total += Number($.trim($(val).val().replace(/[^0-9\.]+/g,"")));
		}
	});
	return total;
}

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

function savePedido(argument) {
	return $.ajax({
		url: site_url+'Pedidos/save_pedido',
		type: 'POST',
		async:false,
		data: argument
	});
}

$(document).off("click", "#update_pedido").on("click", "#update_pedido", function(event){
	event.preventDefault();
	var id_pedido = $(this).closest("tr").find("#update_pedido").data("idPedido");
	getModal("Pedidos/get_update/"+ id_pedido, function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_pedido_edit").validate({
				rules: {
					nombre: {required: true}
				}
			});
			jQuery.extend(jQuery.validator.messages,{
				required: "Este campo es requerido",
			});
		});
	});
});

$(document).off("click", ".update_pedido").on("click", ".update_pedido", function(event) {
	event.preventDefault();
	if($("#form_pedido_edit").valid()){
		sendForm("Pedidos/update", $("#form_pedido_edit"), "");
	}
});

$(document).off("click", "#delete_pedido").on("click", "#delete_pedido", function(event){
	event.preventDefault();
	var id_pedido = $(this).closest("tr").find("#delete_pedido").data("idPedido");
	getModal("Pedidos/get_delete/"+ id_pedido, function (){ });
});

$(document).off("click", ".delete_pedido").on("click", ".delete_pedido", function(event) {
	event.preventDefault();
	sendForm("Pedidos/delete", $("#form_pedido_delete"), "");
});

$(document).off("click", "#show_pedido").on("click", "#show_pedido", function(event){
	event.preventDefault();
	var id_pedido = $(this).closest("tr").find("#show_pedido").data("idPedido");
	getModal("Pedidos/get_detalle/"+ id_pedido, function (){ });
});