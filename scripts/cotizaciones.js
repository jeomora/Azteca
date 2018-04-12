$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
		/*$("#table_cot_admin").dataTable({
			ajax: {
				url: site_url +"Cotizaciones/cotizaciones_dataTable",
				type: "POST"
			},
			processing: true,
			language: {
	            processing: '<div class="spinns"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span style="font-size:3rem;">Cargando...</span></div> '},
			serverSide: true,
			responsive: true,
			pageLength: 50,
			dom: 'Bfrtip',
			bSort : false,
			lengthMenu: [
				[ 10, 30, 50, -1 ],
				[ '10 registros', '30 registros', '50 registros', 'Mostrar todos']
			],
			buttons: [
				{ extend: 'pageLength' },
			]
		});*/
		
	
	getGrupo()
	.done(function (resp){
		console.log(resp)
		if(resp.ides != 2){
			setAdminTable();
		}
	});
	
	fillDataTable("table_cot_proveedores", 50);
});

function getAdminTable() {
	return $.ajax({
		url: site_url+"/Cotizaciones/getAdminTable",
		type: "POST",
		dataType: "JSON"
	});
}

function getGrupo() {
	return $.ajax({
		url: site_url+"/Cotizaciones/getGrupo",
		type: "POST",
		dataType: "JSON"
	});
}

function setAdminTable(){
	event.preventDefault();
	$("html").block({
		centerY: 0,
		message: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span style="font-size:3rem;">Cargando...</span>',
		overlayCSS: { backgroundColor: '#DDFF33' },
		css: { position: 'absolute',
	    top: '25rem',
	    left: '45rem',
	    background: 'rgba(255,255,255,0.5)',
	    padding: '10rem',
	    color: '#FF6805',
	    border: '2px solid #FF6805'},
	});
	setTimeout(function(){ $(".spinns").css("display","none");$("html").unblock(); }, 16000);
	var tableAdmin = "";
	getAdminTable()
		.done(function (resp) {
			$.each(resp.cotizaciones, function(indx, value){
					value.precio_next = value.precio_next == null ? 0 : value.precio_next;
					value.precio_four = value.precio_four == null ? 0 : value.precio_four;
					value.precio_sistema = value.precio_sistema == null ? 0 : value.precio_sistema;
					value.precio_first = value.precio_first == null ? 0 : value.precio_first;
					value.precio_next = value.precio_next == null ? 0 : value.precio_next;
					value.precio_nexto = value.precio_nexto == null ? 0 : value.precio_nexto;
					value.proveedor_next = value.proveedor_next == null ? "" : value.proveedor_next;
					value.promocion_first = value.promocion_first == null ? "" : value.promocion_first;
					value.promocion_next = value.promocion_next == null ? "" : value.promocion_next;
					tableAdmin += '<tr><td>'+value.familia+'</td>';
					if(value.estatus == 2){
						tableAdmin += '<td style="background-color: #00b0f0">'+value.codigo+'</td><td style="background-color: #00b0f0">'+value.producto+'</td>';
					}else if(value.status == 3){
						tableAdmin += '<td style="background-color: #fff900">'+value.codigo+'</td><td style="background-color: #fff900">'+value.producto+'</td>';
					}else{
						tableAdmin += '<td>'+value.codigo+'</td><td>'+value.producto+'</td>';
					}
					tableAdmin += '<td>$ '+formatNumber(parseFloat(value.precio_sistema), 2)+'</td><td>$ '+formatNumber(parseFloat(value.precio_four), 2)+'</td>'+
								'<td>$ '+formatNumber(parseFloat(value.precio_firsto), 2)+'</td>';
					if(value.precio_first >= value.precio_sistema){
						tableAdmin += '<td><div class="preciomas">$ '+formatNumber(parseFloat(value.precio_first), 2)+'</div></td>';
					}else{
						tableAdmin += '<td><div class="preciomenos">$ '+formatNumber(parseFloat(value.precio_first), 2)+'</div></td>'
					}
					tableAdmin += '<td>'+value.proveedor_first+'</td><td>'+value.promocion_first+'</td>'+
								'<td>$ '+formatNumber(parseFloat(value.precio_maximo), 2)+'</td><td>$ '+formatNumber(parseFloat(value.precio_promedio), 2)+'</td>';
					tableAdmin += value.precio_nexto == 0 ? '<td></td>' :'<td>$ '+formatNumber(parseFloat(value.precio_nexto), 2)+'</td>'					
					if(value.precio_next >= value.precio_sistema){
						tableAdmin += value.precio_next > 0 ? '<td><div class="preciomas">$ '+formatNumber(parseFloat(value.precio_next), 2)+'</div></td>' : '<td></td>';
					}else{
						tableAdmin += value.precio_next > 0 ? '<td><div class="preciomenos">$ '+formatNumber(parseFloat(value.precio_next), 2)+'</div></td>' : '<td></td>';
					}
					tableAdmin += '<td>'+value.proveedor_next+'</td><td>'+value.promocion_next+'</td><td>'+
								'<button id="update_cotizacion" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-cotizacion="'+value.id_cotizacion+'">'+
								'<i class="fa fa-pencil"></i></button><button id="delete_cotizacion" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-cotizacion="'+value.id_cotizacion+'">'+
								'<i class="fa fa-trash"></i></button></td></tr>';
			});	
			$(".tableAdmin").html(tableAdmin);
			fillDataTable("table_cot_admin", 50);
		});
	

}

$(document).off("click", "#no_cotizados").on("click", "#no_cotizados", function(event){
	event.preventDefault();
	getModal("Main/getNotCotizados/", function (){ });
});

$(window).on("load", function (event) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
});
$(document).off("click", "#ths").on("click", "#ths", function(event) {
	console.log("jkdjkds");
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("table_cot_admin");
  switching = true;
  dir = "asc"; 
  while (switching) {
    switching = false;
    rows = table.getElementsByTagName("TR");
    for (i = 1; i < (rows.length - 1); i++) {
      shouldSwitch = false;
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          shouldSwitch= true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      switchcount ++;      
    } else {
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
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
		toastr.warning("Seleccione un artículo de la lista", user_name);
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
	var id_cotizacion = $(this).closest("tr").find("#update_cotizacion").data("idCotizacion");
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
	var id_cotizacion = $(this).closest("tr").find("#delete_cotizacion").data("idCotizacion");
	getModal("Cotizaciones/get_delete/"+ id_cotizacion, function (){ });
});

$(document).off("click", ".delete_cotizacion").on("click", ".delete_cotizacion", function(event) {
	event.preventDefault();
	sendForm("Cotizaciones/delete", $("#form_cotizacion_delete"), "");
});

$(document).off("click", "#new_pedido").on("click", "#new_pedido", function(event){
	event.preventDefault();
	var id_cotizacion = $(this).closest("tr").find("#new_pedido").data("idCotizacion");
	getModal("Cotizaciones/set_pedido/"+ id_cotizacion, function (){ });
});

$(document).off("click", ".new_pedido").on("click", ".new_pedido", function(event) {
	event.preventDefault();
	sendForm("Cotizaciones/hacer_pedido", $("#form_pedido_new"), "");
});



$(document).off("change", "#id_proves").on("change", "#id_proves", function() {
	event.preventDefault();
	var id_cotizacion = $("#id_proves option:selected").val();
	var proveedor = $("#id_proves option:selected").text();
	if(id_cotizacion != "nope"){
		$(".fill_form").css("display","block");
		$("#id_proves2").val(proveedor);
	}else{
		$(".fill_form").css("display","none");
	}
});

$(document).off("click", ".btsrch").on("click", ".btsrch", function(event){
	event.preventDefault();
	var id_cotizacion = $("#slct2 option:selected").val();
	var proveedor = $("#slct2 option:selected").text();
	getModal("Cotizaciones/set_pedido_prov/"+ id_cotizacion, function (){
		$("#table_provs").dataTable({
		ajax: {
			url: site_url +"Cotizaciones/set_pedido_provs/"+id_cotizacion+"",
			type: "POST"
		},
		processing: true,
		language: {
            processing: '<div class="spinns"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span style="font-size:3rem;">Cargando...</span></div> '
        },
        bSort : false,
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
	$("th").removeClass('sorting');
	});
	$(".numeric").inputmask("currency", {radixPoint: ".", prefix: ""});
});

function getProductos(id_prov) {
	return $.ajax({
		url: site_url+"/Pedidos/get_productos",
		type: "POST",
		dataType: "JSON",
		data: {id_proveedor: id_prov},
	});
}

$(document).off("click", "#add_me").on("click", "#add_me", function() {
	var tr = $(this).closest("tr");
	if(tr.find(".cantidad").val() !== ""){
		var table = $('#table_provss').DataTable();
		var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
		table.row.add( [ tr.children('td:first').text(),tr.find(".precio").val(), tr.find(".cantidad").val(),"$ "+(tr.find(".cantidad").val() * precio).toFixed(2)] ).draw();
		var total = $("#totals").val();
		console.log(jQuery.type(total));
		total = parseFloat(total) + (tr.find(".cantidad").val() * precio);
		console.log(tr.find(".cantidad").val() * precio);
		$("#totals").val((total*1).toFixed(2));
		tr.remove();
	}

});

$(document).off("click", "#remove_me").on("click", "#remove_me", function() {
	var tr = $(this).closest("tr");
	if (confirm("¿Estas seguro de eliminar "+tr.children('td:first').text()+"?") == true) {
         tr.remove();
     }
});

//Editar cotizaciones 
$(document).off("change", ".id_cotz").on("change", ".id_cotz", function() {
	var tr = $(this).closest("tr");
	$(".numeric").inputmask("currency", {radixPoint: ".", prefix: ""});
	if($(this).is(":checked")) {
		tr.find(".id_cotz ").removeAttr('readonly');
		tr.find(".precio").removeAttr('readonly');
		tr.find(".num_one").removeAttr('readonly');
		tr.find(".num_two").removeAttr('readonly');
		tr.find(".descuento").removeAttr('readonly');
		tr.find(".observaciones").removeAttr('readonly');
		tr.find(".id_cotz ").attr('name', 'id_cotz[]');
		tr.find(".precio").attr('name', 'precio[]');
		tr.find(".precio_promocion").attr('name', 'precio_promocion[]');
		tr.find(".num_one").attr('name', 'num_one[]');
		tr.find(".num_two").attr('name', 'num_two[]');
		tr.find(".descuento").attr('name', 'descuento[]');
		tr.find(".observaciones").attr('name', 'observaciones[]');
	}else{
		$(this).removeAttr("checked");
		tr.find(".precio").attr('readonly', 'readonly');
		tr.find(".num_one").attr('readonly', 'readonly');
		tr.find(".num_two").attr('readonly', 'readonly');
		tr.find(".descuento").attr('readonly', 'readonly');
		tr.find(".observaciones").attr('readonly', 'readonly');

		tr.find(".id_cotz ").removeAttr('name');
		tr.find(".precio").removeAttr('name');
		tr.find(".precio_promocion").removeAttr('name');
		tr.find(".num_one").removeAttr('name');
		tr.find(".num_two").removeAttr('name');
		tr.find(".descuento").removeAttr('name');
		tr.find(".observaciones").removeAttr('name');
	}
});

$(document).off("keyup", ".descuento").on("keyup", ".descuento", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var descuento = tr.find(".descuento").val().replace(/[^0-9\.]+/g,"");
	if($(this).val().replace(/[^0-9\.]+/g,"") > 0){
		tr.find(".precio_promocion").val(precio - (precio * (descuento / 100)));
	}
});
$(document).off("keyup", ".num_one").on("keyup", ".num_one", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var num_one = tr.find('.num_one').val().replace(/[^0-9\.]+/g,"");
	var num_two = tr.find('.num_two').val().replace(/[^0-9\.]+/g,"");
	if(num_two > 0 && num_one > 0){
		var total = (precio * num_two) / (parseFloat(num_one) + parseFloat(num_two));
		tr.find(".precio_promocion").val(total);
	}else{
		tr.find(".precio_promocion").val(precio);
	}
});
$(document).off("keyup", ".num_two").on("keyup", ".num_two", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var num_one = tr.find('.num_one').val().replace(/[^0-9\.]+/g,"");
	var num_two = tr.find('.num_two').val().replace(/[^0-9\.]+/g,"");
	if(num_two > 0 && num_one > 0){
		var total = (precio * num_two) / (parseFloat(num_one) + parseFloat(num_two));
		tr.find(".precio_promocion").val(total);
	}else{
		tr.find(".precio_promocion").val(precio);
	}
});

$(document).off("change", ".id_producto").on("change", ".id_producto", function() {
	var tr = $(this).closest("tr");
	$(".numeric").inputmask("currency", {radixPoint: ".", prefix: ""});
	if($(this).is(":checked")) {
		tr.find(".cantidad").removeAttr('readonly');
		tr.find(".id_producto ").attr('name', 'id_producto[]');
		tr.find(".precio ").attr('name', 'precio[]');
		tr.find(".cantidad ").attr('name', 'cantidad[]');
		tr.find(".importe ").attr('name', 'importe[]');
	}else{
		$(this).removeAttr("checked");
		tr.find(".cantidad").attr('readonly', 'readonly');
		tr.find(".cantidad").removeAttr('name').val('');
		tr.find(".importe").removeAttr('name').val('');
		tr.find(".id_producto ").removeAttr('name');
	}
});

$(document).off("keyup", ".cantidad").on("keyup", ".cantidad", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var cantidad = tr.find(".cantidad").val().replace(/[^0-9\.]+/g,"");

	if($(this).val().replace(/[^0-9\.]+/g,"") > 0){
		tr.find(".importe").val(precio * cantidad);
		$("#total").val((calculaTotales()));
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

$(document).off("change", "#file_cotizaciones").on("change", "#file_cotizaciones", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_cotizaciones")[0]);
	uploadCotizaciones(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
			}else{
				unblockPage();
				setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
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
function uploadallCotizaciones(formData) {
	return $.ajax({
		url: site_url+"Cotizaciones/upload_allcotizaciones",
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
	blockPage();
	var formdata = new FormData($("#upload_precios")[0]);
	uploadPrecios(formdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
			}else{
				unblockPage();
				setTimeout("location.reload()", 1300, toastr.success(resp.desc, user_name), "");
			}
		});
});



$(document).off("change","#slct").on("change","#slct", function (){
	var rows = "";
	var opcion = $("#slct option:selected").val();
	if(opcion == "Bajos"){
		fillTablaBajos();
		$("#slct2").css("display","none");
		$(".btsrch").css("display","none");
	}else if (opcion !== "Seleccionar...") {
		$("#slct2").css("display","block");
		$(".btsrch").css("display","none")
		getDatas(opcion)
		.done(function(response){
			var size = response.length;
			if (jQuery.isEmptyObject(response)) {
				toastr.warning("Sin Resultados", user_name);
				$("#slct2").attr('readonly', 'readonly');
			}else{
				rows += '<option value="Seleccionar...">Seleccionar...</option>'
				$.each(response, function(index, val){
					rows += '<option value="'+val.ides+'">'+val.names+'</option>'
				});
				$("#slct2").removeAttr('readonly');
				$("#slct2").html(rows);
			}
		});
	}else{
		$("#slct2").css("display","none");
		$(".btsrch").css("display","none");
	}
});

$(document).off("change","#slct2").on("change","#slct2", function (){
	var rows = "";
	var opcion = $("#slct option:selected").val();
	var ides = $("#slct2 option:selected").val();
	if(opcion == "Familia"){
		fillTablaFamilia(ides);
	}else if (opcion == "Proveedor") {
		fillTablaProveedor(ides);
		$(".btsrch").css("display","block")
	}else if (opcion == "Producto") {
		fillTablaProducto(ides);
	}

});

function getDatas(searchs) {
	return $.ajax({
		url: site_url+"Cotizaciones/get"+searchs+"",
		type: "POST",
		dataType: "JSON"
	});
}

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

$(document).off("click", "#no_cotizo").on("click", "#no_cotizo", function(event){
	event.preventDefault();
	getModal("Main/getNotCotizo/", function (){ });
});