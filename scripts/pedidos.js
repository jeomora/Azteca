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

$(document).off("focusout", ".cajas").on("focusout", ".cajas", function () {
	var tr = $(this).closest("tr");
	var cantidad = $(this).val();
	guardaPedidos(tr,"cajas",cantidad);
});
$(document).off("focusout", ".piezas").on("focusout", ".piezas", function () {
	var tr = $(this).closest("tr");
	var cantidad = $(this).val();
	guardaPedidos(tr,"piezas",cantidad);
});
$(document).off("focusout", ".pedido").on("focusout", ".pedido", function () {
	var tr = $(this).closest("tr");
	var cantidad = $(this).val();
	guardaPedidos(tr,"pedido",cantidad);
});

function guardaPedidos(tr, tipo,cantidad){
	var producto = tr.find(".producto").val();
	var idpedido = tr.find(".idpedido").val();
	var cajas = tipo == "cajas" ? cantidad : tr.find(".cajas").val();
	var piezas = tipo == "piezas" ? cantidad : tr.find(".piezas").val();
	var pedido = tipo == "pedido" ? cantidad : tr.find(".pedido").val();
	cajas = cajas == "" ? 0 : cajas;
	piezas = piezas == "" ? 0 : piezas;
	pedido = pedido == "" ? 0 : pedido;
	var values = {'id_producto': producto,'pedido': pedido,'piezas': piezas,'id_pedido': idpedido,'cajas': cajas};
	return $.ajax({
		url: site_url+"Pedidos/guardaPedido",
		type: "POST",
		dataType: 'JSON',
		data: {values : JSON.stringify(values)}
	});
}

function tablePedidoTienda(response,colors,sucur){
	var table_contain = "";
	var flag = "";
	$.each(response, function(index, value){
		table_contain += '<tr></td><td colspan="1"></td><td colspan="1" class="td2Form">'+value.familia+'<td colspan="9"></td></tr>';
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
								+'<input type="text" value='+vl.pedido+' class="form-control pedido numeric"></div>'+
						'</td><td style="display:none">'+
							'<div class="input-group m-b">'
								+'<input type="text" value='+vl.id_producto+' class="form-control producto numeric"></div>'+
						'</td><td style="display:none">'+
							'<div class="input-group m-b">'
								+'<input type="text" value='+vl.id_pedido+' class="form-control idpedido numeric"></div>'+
						'</td></tr>'

		});
	});
	table_contain = '<div class="ibox float-e-margins"><div class="ibox-title"><h5>PEDIDOS A '+flag+' '+getFech()+'</h5></div>'+
					'<div class="ibox-content"><div class="table-responsive"><table class="table table-striped table-bordered table-hover" id="table_pedidos" style="text-align:  center;"">'+
					'<thead><tr><th colspan="8">PRODUCTO</th><th style="background-color: '+colors+'" colspan="3">'+sucur+'</th></tr></thead>'+
					'<tbody><tr><td class="td2Form">CÓDIGO</td><td class="td2Form">DESCRIPCIÓN</td><th colspan="6" class="td2Form"></th>'+
					'<td class="td2Form" colspan="3">EXISTENCIAS</td></tr><tr><td colspan="2" class="td2Form"></td><td class="td2Form">COSTO</td><td class="td2Form">PROMOCIÓN</td>'+
					'<td class="td2Form">SISTEMA</td><td class="td2Form">PRECIO 4</td><td class="td2Form">2DO</td><td class="td2Form">PROVEEDOR</td>'+
					'<td class="td2Form">CAJAS</td><td class="td2Form">PIEZAS</td><td class="td2Form">PEDIDO</td></tr>'+table_contain+'</tbody></table></div></div></div>';
					
	$(".wonder").append(table_contain);
	$(".spinns").css("display","none")
}

function tablePedidoAll(response,colors,sucur){
	var table_contain = "";
	var flag = "";
	$.each(response, function(index, value){
		table_contain += '<tr></td><td colspan="1"></td><td colspan="1" class="td2Form">'+value.familia+'<td colspan="27"></td></tr>';
		$.each(value.articulos, function(inex, vl) {
			vl.precio_next = vl.precio_next == null ? 0 : vl.precio_next;
			vl.precio_four = vl.precio_four == null ? 0 : vl.precio_four;
			vl.precio_sistema = vl.precio_sistema == null ? 0 : vl.precio_sistema;
			if(vl.estatus == 2){
				vl.codigo = '<td style="background-color: #00b0f0">'+vl.codigo+'</td>';
				vl.producto = '<td style="background-color: #00b0f0">'+vl.producto+'</td>';
				flag = "VOLÚMENES";
			}else if(vl.estatus == 3){
				vl.codigo = '<td style="background-color:  #fff900">'+vl.codigo+'</td>';
				vl.producto = '<td style="background-color: #fff900">'+vl.producto+'</td>';
				flag = "AMARILLOS";
			}else{
				vl.codigo = '<td>'+vl.codigo+'</td>';
				vl.producto = '<td>'+vl.producto+'</td>';
				flag = 'PEDIDOS A '+vl.proveedor_first;
			}
			
			vl.precio_first = vl.precio_first >= vl.precio_sistema ? '<div class="preciomas">$ '+formatNumber(parseFloat(vl.precio_first), 2)+'</div>' : '<div class="preciomenos">$ '+formatNumber(parseFloat(vl.precio_first), 2)+'</div>';
			vl.proveedor_next = vl.proveedor_next == null ? "" : vl.proveedor_next;
			vl.promocion_first = vl.promocion_first == null ? "" : vl.promocion_first;
			vl.cajas = vl.cajas == null ? '0' : vl.cajas;
			vl.piezas = vl.piezas == null ? '0' : vl.piezas;
			vl.pedido = vl.pedido == null ? '0' : vl.pedido;
			table_contain += '<tr>'+vl.codigo+''+vl.producto+'<td>'+vl.precio_first+'</td><td>'+vl.promocion_first+'</td>'+
						'<td>$ '+formatNumber(parseFloat(vl.precio_sistema), 2)+'</td><td>$ '+formatNumber(parseFloat(vl.precio_four), 2)+'</td><td>$ '+formatNumber(parseFloat(vl.precio_next), 2)+'</td>'+
						'<td>'+vl.proveedor_next+'</td><td>'+vl.caja0+'</td><td>'+vl.pz0+'</td><td style="background-color: #afafff;">'+vl.ped0+'</td>'+
						'<td>'+vl.caja1+'</td><td>'+vl.pz1+'</td><td style="background-color: #afafff;">'+vl.ped1+'</td>'+
						'<td>'+vl.caja2+'</td><td>'+vl.pz2+'</td><td style="background-color: #afafff;">'+vl.ped2+'</td>'+
						'<td>'+vl.caja3+'</td><td>'+vl.pz3+'</td><td style="background-color: #afafff;">'+vl.ped3+'</td>'+
						'<td>'+vl.caja4+'</td><td>'+vl.pz4+'</td><td style="background-color: #afafff;">'+vl.ped4+'</td>'+
						'<td>'+vl.caja5+'</td><td>'+vl.pz5+'</td><td style="background-color: #afafff;">'+vl.ped5+'</td>'+
						'<td>'+vl.caja6+'</td><td>'+vl.pz6+'</td><td style="background-color: #afafff;">'+vl.ped6+'</td>'+
						'</tr>'

		});
	});
	table_contain = '<div class="ibox float-e-margins"><div class="ibox-title"><h5>'+flag+' '+getFech()+'</h5></div>'+
							'<div class="ibox-content"><div class="table-responsive"><table class="table table-striped table-bordered table-hover" id="table_pedidos" style="text-align:  center;"">'+
							'<thead><tr><th colspan="8">PRODUCTO</th><th style="background-color: #01B0F0" colspan="3">ABARROTES</th><th style="background-color: #E26C0B" colspan="3">TIENDA</th>'+
							'<th style="background-color: #C5C5C5" colspan="3">ULTRAMARINOS</th><th style="background-color: #92D051" colspan="3">TRINCHERAS</th><th style="background-color: #B1A0C7" colspan="3">AZT MERCADO</th>'+
							'<th style="background-color: #DA9694" colspan="3">TENENCIA</th><th style="background-color: #4CACC6" colspan="3">TIJERAS</th></tr></thead>'+
							'<tbody><tr><td class="td2Form">CÓDIGO</td><td class="td2Form">DESCRIPCIÓN</td><th colspan="6" class="td2Form"></th>'+
							'<td class="td2Form" colspan="3">EXISTENCIAS</td><td class="td2Form" colspan="3">EXISTENCIAS</td><td class="td2Form" colspan="3">EXISTENCIAS</td><td class="td2Form" colspan="3">EXISTENCIAS</td>'+
							'<td class="td2Form" colspan="3">EXISTENCIAS</td><td class="td2Form" colspan="3">EXISTENCIAS</td><td class="td2Form" colspan="3">EXISTENCIAS</td></tr><tr><td colspan="2" class="td2Form"></td><td class="td2Form">COSTO</td><td class="td2Form">PROMOCIÓN</td>'+
							'<td class="td2Form">SISTEMA</td><td class="td2Form">PRECIO 4</td><td class="td2Form">2DO</td><td class="td2Form">PROVEEDOR</td>'+
							'<td class="td2Form">CAJAS</td><td class="td2Form">PIEZAS</td><td class="td2Form">PEDIDO</td><td class="td2Form">CAJAS</td><td class="td2Form">PIEZAS</td><td class="td2Form">PEDIDO</td><td class="td2Form">CAJAS</td><td class="td2Form">PIEZAS</td><td class="td2Form">PEDIDO</td>'+
							'<td class="td2Form">CAJAS</td><td class="td2Form">PIEZAS</td><td class="td2Form">PEDIDO</td><td class="td2Form">CAJAS</td><td class="td2Form">PIEZAS</td><td class="td2Form">PEDIDO</td><td class="td2Form">CAJAS</td><td class="td2Form">PIEZAS</td><td class="td2Form">PEDIDO</td>'+
							'<td class="td2Form">CAJAS</td><td class="td2Form">PIEZAS</td><td class="td2Form">PEDIDO</td></tr>'+table_contain+'</tbody></table></div></div></div>';
	if(flag == ''){
		table_contain = "";
	}
	$(".wonder").append(table_contain);
	$(".spinns").css("display","none")
}

$(document).off("change", "#id_proves4").on("change", "#id_proves4", function() {
	event.preventDefault();
	var id_cotizacion = $("#id_proves4 option:selected").val();
	var proveedor = $("#id_proves4 option:selected").text();
	
	var table_contain = "";
	$(".wonder").html('<div class="spinns"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span style="font-size:3rem;">Cargando...</span></div>');

	if(id_cotizacion != "nope"){
		$(".fill_form").css("display","block");
		
		var sucur = "";
		getSucursal()
			.done(function (response){
				sucur = response == null ? 0 : response.nombre;
				colors = response == null ? 0 : response.color;
				var stringArray = id_cotizacion.split(",");
				var flag = "";
				if(id_cotizacion == "VARIOS1" || id_cotizacion == "VARIOS2" || id_cotizacion == "VARIOS3" || id_cotizacion == "VARIOS4"){
					getConjunto(id_cotizacion).done(function (respon){
						$.each(respon, function(index, value){
							if(sucur == 0){
								getAllPedidos(value.id_usuario)
								.done(function (response){
									tablePedidoAll(response,colors,sucur);
								});
							}else{
								getPedidosSingle(value.id_usuario)
								.done(function (response){
									tablePedidoTienda(response,colors,sucur);
								});
							}
						});
					});
				}else{
					if(sucur == 0){
						getAllPedidos(id_cotizacion)
						.done(function (response){
							tablePedidoAll(response,colors,sucur);
						});
					}else{
						console.log(id_cotizacion);
						getPedidosSingle(id_cotizacion)
						.done(function (response){
							tablePedidoTienda(response,colors,sucur);
						});
					}
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

function getConjunto(id_provs){
	return $.ajax({
		url: site_url+"/Pedidos/getConjs",
		type: "POST",
		dataType: "JSON",
		data: {id_proveedor: id_provs},
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

function getAllPedidos(id_prov) {
	return $.ajax({
		url: site_url+"/Pedidos/get_allpedidos",
		type: "POST",
		dataType: "JSON",
		data: {id_proveedor: id_prov},
	});
}

function getPedidosSingle(id_prov) {
	return $.ajax({
		url: site_url+"/Pedidos/get_pedidosingle",
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

$(document).off("change", "#file_cotizaciones").on("change", "#file_cotizaciones", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_pedidos")[0]);
	uploadPedidos(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
			}else{
				unblockPage();
				setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});


function uploadPedidos(formData) {
	return $.ajax({
		url: site_url+"Cotizaciones/upload_pedidos",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}