$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});

	
	fillDataTable("table_cot_proveedores", 50);
});


function fillTablaBajos() {
	$(".table-responsive").html('<table class="table table-striped table-bordered table-hover" id="table_cot_admin"></table>');
	$("#table_cot_admin").html('<thead><tr><th>FAMILIAS</th><th>CÓDIGO</th><th>DESCRIPCIÓN</th><th>SISTEMA</th>'+
								'<th>PRECIO 4</th><th>PRECIO MAXIMO</th><th>PRECIO PROMEDIO</th><th>PROVEEDOR</th>'+
								'<th>PRECIO MENOR</th><th>OBSERVACIÓN</th><th>2DO PROVEEDOR</th><th>PRECIO 2</th>'+
								'<th>OBSERVACIÓN</th><th>ACCIÓN</th></tr></thead><tbody></tbody>')
	$("#table_cot_admin").dataTable({
		ajax: {
			url: site_url +"Cotizaciones/cotizaciones_dataTable/1/1",
			type: "POST"
		},
		processing: true,
		language: {
            processing: '<div class="spinns"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span style="font-size:3rem;">Cargando...</span></div> '},
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
}
function fillTablaFamilia(param1="") {
	$(".table-responsive").html('<table class="table table-striped table-bordered table-hover" id="table_cot_admin"></table>');
	$("#table_cot_admin").html('<thead><tr><th>FAMILIAS</th><th>CÓDIGO</th><th>DESCRIPCIÓN</th><th>SISTEMA</th>'+
								'<th>PRECIO 4</th><th>PRECIO MAXIMO</th><th>PRECIO PROMEDIO</th><th>PROVEEDOR</th>'+
								'<th>PRECIO MENOR</th><th>OBSERVACIÓN</th><th>2DO PROVEEDOR</th><th>PRECIO 2</th>'+
								'<th>OBSERVACIÓN</th><th>ACCIÓN</th></tr></thead><tbody></tbody>')
	$("#table_cot_admin").dataTable({
		ajax: {
			url: site_url +"Cotizaciones/cotizaciones_dataTable/Familia/"+param1+"",
			type: "POST"
		},
		processing: true,
		language: {
            processing: '<div class="spinns"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span style="font-size:3rem;">Cargando...</span></div> '},
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
}
function fillTablaProveedor(param1="") {
	$(".table-responsive").html('<table class="table table-striped table-bordered table-hover" id="table_cot_admin"></table>');
	$("#table_cot_admin").html('<thead><tr><th>FAMILIAS</th><th>CÓDIGO</th><th>DESCRIPCIÓN</th><th>SISTEMA</th>'+
								'<th>PRECIO 4</th><th>PRECIO</th><th>OBSERVACIÓN</th>'+
								'<th>ACCIÓN</th></tr></thead><tbody></tbody>')
	$("#table_cot_admin").dataTable({
		ajax: {
			url: site_url +"Cotizaciones/cotizaciones_dataTable/Proveedor/"+param1+"",
			type: "POST"
		},
		processing: true,
		language: {
            processing: '<div class="spinns"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span style="font-size:3rem;">Cargando...</span></div> '},
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
}

function fillTablaProducto(param1="") {
	$(".table-responsive").html('<table class="table table-striped table-bordered table-hover" id="table_cot_admin"></table>');
	$("#table_cot_admin").html('<thead><tr><th>CÓDIGO</th><th>SISTEMA</th>'+
								'<th>PRECIO 4</th><th>PROVEEDOR</th><th>PRECIO</th><th>OBSERVACIÓN</th>'+
								'<th>ACCIÓN</th></tr></thead><tbody></tbody>')
	$("#table_cot_admin").dataTable({
		ajax: {
			url: site_url +"Cotizaciones/cotizaciones_dataTable/Producto/"+param1+"",
			type: "POST"
		},
		processing: true,
		language: {
            processing: '<div class="spinns"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span style="font-size:3rem;">Cargando...</span></div> '},
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
}


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

$(document).off("click", ".new_pedido").on("click", ".new_pedido", function(event) {
	event.preventDefault();
	sendForm("Cotizaciones/hacer_pedido", $("#form_pedido_new"), "");
});

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

$(document).off("change", ".id_producto").on("change", ".id_producto", function() {
	var tr = $(this).closest("tr");
	$(".numeric").inputmask("currency", {radixPoint: ".", prefix: ""});
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
				setTimeout("location.reload()", 1300, toastr.success(resp.desc, user_name), "");
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