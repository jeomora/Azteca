$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	
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

$(document).off("change", "#id_proves").on("change", "#id_proves", function() {
	event.preventDefault();
	var id_cotizacion = $("#id_proves option:selected").val();
	var proveedor = $("#id_proves option:selected").text();
	if(id_cotizacion != "nope"){
		$(".fill_form").css("display","block");
		$("#id_proves2").val(proveedor);
		get_cotizaciones($("#id_proves option:selected").val())
			.done(function (response){
				var size = response.length;
				if (jQuery.isEmptyObject(response)) {
					toastr.warning("El Proveedor "+proveedor+" no tiene Productos cotizados", user_name);
				}else{

				}
			});
	}else{
		$(".fill_form").css("display","none");
	}
});

$(document).off("keyup", ".cajas").on("keyup", ".cajas", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var descuento = tr.find(".descuento").val().replace(/[^0-9\.]+/g,"");
	if($(this).val().replace(/[^0-9\.]+/g,"") > 0){
		tr.find(".precio_promocion").val(precio - (precio * (descuento / 100)));
	}
});

$(document).off("change", "#id_proves4").on("change", "#id_proves4", function() {
	event.preventDefault();
	var id_cotizacion = $("#id_proves4 option:selected").val();
	var proveedor = $("#id_proves4 option:selected").text();
	var table_contain = "";


	if(id_cotizacion != "nope"){
		$(".fill_form").css("display","block");
		$("#id_proves2").val(proveedor);
		var sucur = "";
		getSucursal()
			.done(function (response){
				sucur = response.nombre;
				var stringArray = id_cotizacion.split(",");
		$(".wonder").html("");
		var flag = "";
		for (var i = 0; i < stringArray.length; i++) {
			getPedidos(stringArray[i])
			.done(function (response){
				$.each(response, function(index, value){
					table_contain += '<tr></td><td colspan="1"></td><td colspan="1" class="td2Form">'+value.familia+'<td colspan="9"></td></tr>'
					$.each(value.articulos, function(inex, vl) {

						vl.precio_next = vl.precio_next == null ? 0 : vl.precio_next;
						vl.precio_four = vl.precio_four == null ? 0 : vl.precio_four;
						vl.precio_sistema = vl.precio_sistema == null ? 0 : vl.precio_sistema;
						vl.precio_first = vl.precio_first >= vl.precio_sistema ? '<div class="preciomas">$ '+formatNumber(parseFloat(vl.precio_first), 2)+'</div>' : '<div class="preciomenos">$ '+formatNumber(parseFloat(vl.precio_first), 2)+'</div>';
						vl.proveedor_next = vl.proveedor_next == null ? "" : vl.proveedor_next;
						vl.promocion_first = vl.promocion_first == null ? "" : vl.promocion_first;
						vl.cajas = vl.cajas == null ? '""' : vl.cajas;
						vl.piezas = vl.piezas == null ? '""' : vl.piezas;
						vl.pedido = vl.pedido == null ? '""' : vl.pedido;
						flag = vl.proveedor_first;
						table_contain += '<tr><td>'+vl.codigo+'</td><td>'+vl.producto+'</td><td>'+vl.precio_first+'</td><td>'+vl.promocion_first+'</td>'+
						'<td>$ '+formatNumber(parseFloat(vl.precio_sistema), 2)+'</td><td>$ '+formatNumber(parseFloat(vl.precio_four), 2)+'</td><td>$ '+formatNumber(parseFloat(vl.precio_next), 2)+'</td>'+
						'<td>'+vl.proveedor_next+'</td><td>'+
							'<div class="input-group m-b">'
								+'<input type="text" value='+vl.cajas+' class="form-control cajas numeric"></div>'+
						'</td><td>'+
							'<div class="input-group m-b">'
								+'<input type="text" value='+vl.piezas+' class="form-control piezas numeric"></div>'+
						'</td><td>'+
							'<div class="input-group m-b">'
								+'<input type="text" value='+vl.pedido+' class="form-control cajas numeric"></div>'+
						'</td></tr>'

					});
				});
				table_contain = '<div class="ibox float-e-margins"><div class="ibox-title"><h5>PEDIDOS A '+flag+' '+getFech()+'</h5></div>'+
							'<div class="ibox-content"><div class="table-responsive"><table class="table table-striped table-bordered table-hover" id="table_pedidos" style="text-align:  center;"">'+
							'<thead><tr><th colspan="8">PRODUCTO</th><th style="background-color: #01B0F0" colspan="3">'+sucur+'</th></tr></thead>'+
							'<tbody><tr><td class="td2Form">CÓDIGO</td><td class="td2Form">DESCRIPCIÓN</td><th colspan="6" class="td2Form"></th>'+
							'<td class="td2Form" colspan="3">EXISTENCIAS</td></tr><tr><td colspan="2" class="td2Form"></td><td class="td2Form">COSTO</td><td class="td2Form">PROMOCIÓN</td>'+
							'<td class="td2Form">SISTEMA</td><td class="td2Form">PRECIO 4</td><td class="td2Form">2DO</td><td class="td2Form">PROVEEDOR</td>'+
							'<td class="td2Form">CAJAS</td><td class="td2Form">PIEZAS</td><td class="td2Form">PEDIDO</td></tr>'+table_contain+'</tbody></table></div></div></div>';
					$(".wonder").append(table_contain);
					table_contain = "";
			});
		}
			});
	}else{
		$(".fill_form").css("display","none");
	}
});

function getFech(){
	var d = new Date();
	var month = d.getMonth();
	var day = d.getDate();
	var mes = ['ENERO','FEBRERO', 'MARZO', 'ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','NOVIEMBRE','DICIEMBRE'];
	var output = ((''+day).length<2 ? '0' : '') + day + ' DE ' +
	     mes[month] + ' DEL ' +
	    d.getFullYear();
	return output;
}

function get_cotizaciones(id_prov) {
	return $.ajax({
		url: site_url+"/Pedidos/get_cotizaciones",
		type: "POST",
		dataType: "JSON",
		data: {id_proves: id_prov},
	});
}

$(document).off("click", ".new_pedido").on("click", ".new_pedido", function(event) {
	event.preventDefault();
	if($("#form_pedido_new").valid()){
		if(marcados > 0){
			if(validar() == true){
				savePedido($("#form_pedido_new").serializeArray())
				.done(function (res) {
					if(res.type == 'error') {
						toastr.error(res.desc, user_name);
					}else{
						toastr.success(res.desc, user_name);
						location.reload();
					}
				})
				.fail(function(res) { });
			}
		}else{
			toastr.warning("Marque 1 producto como mínimo", user_name);
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
				toastr.warning("El Proveedor "+proveedor+" no tiene Productos cotizados", user_name);
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
				toastr.success("El Proveedor "+proveedor+" tiene "+size+" Productos cotizados", user_name);
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

function getSucursal(){
	return $.ajax({
		url: site_url+"/Sucursales/getSucursal",
		type: "POST",
		dataType: "JSON",
		data: {id_proveedor: "id_prov"},
	});
}

function getPedidos(id_prov) {
	return $.ajax({
		url: site_url+"/Pedidos/get_pedidos",
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
		toastr.warning("Faltan algunos precios por llenar", user_name);
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