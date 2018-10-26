$(document).off("change", "#file_p").on("change", "#file_p", function(event) {
	event.preventDefault();
	//blockPage();
	var fdata = new FormData($("#reporte_sat")[0]);
	uploadProductos(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				setTimeout("location.reload()", 1700, toastr.error(resp.desc, user_name), "");
			}else{
				unblockPage();
				setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});




function uploadProductos(formData) {
	return $.ajax({
		url: site_url+"Productos/upload_productos2",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

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
			lnaguage: {
	            processing: '<div style="width:80rem;height:50rem;background-image:url(assets/img/clock.gif)"><h1 style="margin:0;padding-top:5rem">Cargando cotizaciones</h1>'+
		'<img src="assets/img/waits.gif" /></div>'},
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
	$('#proveedorCotz[rel=external-new-window]').click(function(){
	    window.open(this.href, "myWindowName", "width=800, height=600");
	    return false;
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
}//<div id="myBar"></div></div><span style="font-size:3rem;">Cargando...</span>

function setAdminTable(){
	event.preventDefault();
	$("html").block({
		centerY: 0,
		message: '<div style="width:80rem;height:50rem;background-image:url(assets/img/clock.gif)"><h1 style="margin:0;padding-top:5rem">Cargando cotizaciones</h1>'+
		'<img src="assets/img/waits.gif" /></div>',
		overlayCSS: { backgroundColor: '#000000' },
		css: { position: 'absolute',
	    top: '2rem',
	    left: '45rem',
	    background: 'rgba(255,255,255,0.5)',
	    color: '#FF6805',
	    width: "80rem",
	    height: "50rem",
	    border: '0',
		padding:'0'},
	});
	//move();
	var tableAdmin = [];
	$(".tableAdmin").html();
	getAdminTable()
		.done(function (resp) {
			$.each(resp.cotizaciones, function(indx, value){
				value.precio_next = value.precio_next == null ? 0 : value.precio_next;
				value.precio_four = value.precio_four == null ? 0 : value.precio_four;
				value.precio_sistema = value.precio_sistema == null ? 0 : value.precio_sistema;
				value.precio_first = value.precio_first == null ? 0 : value.precio_first;
				value.precio_firsto = value.precio_firsto == null ? 0 : value.precio_firsto;
				value.precio_next = value.precio_next == null ? 0 : value.precio_next;
				value.precio_nexto = value.precio_nexto == null ? 0 : value.precio_nexto;
				value.precio_nxts = value.precio_nxts == null ? 0 : value.precio_nxts;
				value.precio_nxtso = value.precio_nxtso == null ? 0 : value.precio_nxtso;
				value.proveedor_first = value.proveedor_first == null ? "" : value.proveedor_first;
				value.proveedor_next = value.proveedor_next == null ? "" : value.proveedor_next;
				value.proveedor_nxts = value.proveedor_nxts == null ? "" : value.proveedor_nxts;
				value.promocion_first = value.promocion_first == null ? "" : value.promocion_first;
				value.promocion_next = value.promocion_next == null ? "" : value.promocion_next;
				value.promocion_nxts = value.promocion_nxts == null ? "" : value.promocion_nxts;

				value.precio_promedio = value.precio_promedio == null ? 0 : value.precio_promedio;
				value.precio_maximo = value.precio_maximo == null ? 0 : value.precio_maximo;
				tableAdmin.push('<tr>');
				if(value.color == "#92CEE3"){
					tableAdmin.push('<td style="background-color: #92CEE3">'+value.codigo+'</td>');
				}else{
					tableAdmin.push('<td>'+value.codigo+'</td>');
				}
				if(value.estatus == 2){
					tableAdmin.push('<td style="background-color: #00b0f0">'+value.producto+'</td>');
				}else if(value.status == 3){
					tableAdmin.push('<td style="background-color: #fff900">'+value.codigo+'</td><td style="background-color: #fff900">'+value.producto+'</td>');
				}else{
					tableAdmin.push('<td>'+value.producto+'</td>');
				}

				if(value.colorp == 1){
					tableAdmin.push('<td style="background-color: #D6DCE4"><div class="input-group m-b"><span class="input-group-addon"><i class="fa fa-dollar"></i></span><input type="text" value="'+formatNumber(parseFloat(value.precio_sistema), 2)+'" class="form-control precio_sistema numeric">'+
							'</div><button id="update_cotizacion" style="display:none" data-toggle="tooltip" title="Editar" data-id-cotizacion="'+value.id_cotizacion+'">'+
							'<i class="fa fa-pencil"></i></button></td><td style="background-color: #D6DCE4"><div class="input-group m-b"><span class="input-group-addon"><i class="fa fa-dollar"></i></span><input type="text" value="'+formatNumber(parseFloat(value.precio_four), 2)+'" class="form-control precio_four numeric"></div></td>');
				}else{
					tableAdmin.push('<td><div class="input-group m-b"><span class="input-group-addon"><i class="fa fa-dollar"></i></span><input type="text" value="'+formatNumber(parseFloat(value.precio_sistema), 2)+'" class="form-control precio_sistema numeric">'+
							'</div><button id="update_cotizacion" style="display:none" data-toggle="tooltip" title="Editar" data-id-cotizacion="'+value.id_cotizacion+'">'+
							'<i class="fa fa-pencil"></i></button></td><td><div class="input-group m-b"><span class="input-group-addon"><i class="fa fa-dollar"></i></span><input type="text" value="'+formatNumber(parseFloat(value.precio_four), 2)+'" class="form-control precio_four numeric"></div></td>');
				}
				

				tableAdmin.push('<td>$ '+formatNumber(parseFloat(value.precio_firsto), 2)+'</td>');
				if(value.precio_first >= value.precio_sistema){
					tableAdmin.push('<td><div class="preciomas">$ '+formatNumber(parseFloat(value.precio_first), 2)+'</div></td>');
				}else{
					tableAdmin.push('<td><div class="preciomenos">$ '+formatNumber(parseFloat(value.precio_first), 2)+'</div></td>');
				}
				tableAdmin.push('<td>'+value.proveedor_first+'</td><td>'+value.promocion_first+'</td>'+
							'<td>$ '+formatNumber(parseFloat(value.precio_maximo), 2)+'</td><td>$ '+formatNumber(parseFloat(value.precio_promedio), 2)+'</td>');

				tableAdmin.push(value.precio_nexto == 0 ? '<td></td>' :'<td>$ '+formatNumber(parseFloat(value.precio_nexto), 2)+'</td>');					
				if(value.precio_next >= value.precio_sistema){
					tableAdmin.push(value.precio_next > 0 ? '<td><div class="preciomas">$ '+formatNumber(parseFloat(value.precio_next), 2)+'</div></td>' : '<td></td>');
				}else{
					tableAdmin.push(value.precio_next > 0 ? '<td><div class="preciomenos">$ '+formatNumber(parseFloat(value.precio_next), 2)+'</div></td>' : '<td></td>');
				}
				tableAdmin.push('<td>'+value.proveedor_next+'</td><td>'+value.promocion_next+'</td>');

				tableAdmin.push(value.precio_nxtso == 0 ? '<td></td>' :'<td>$ '+formatNumber(parseFloat(value.precio_nxtso), 2)+'</td>');				
				if(value.precio_nxts >= value.precio_sistema){
					tableAdmin.push(value.precio_nxts > 0 ? '<td><div class="preciomas">$ '+formatNumber(parseFloat(value.precio_nxts), 2)+'</div></td>' : '<td></td>');
				}else{
					tableAdmin.push(value.precio_nxts > 0 ? '<td><div class="preciomenos">$ '+formatNumber(parseFloat(value.precio_nxts), 2)+'</div></td>' : '<td></td>');
				}
				tableAdmin.push('<td>'+value.proveedor_nxts+'</td><td>'+value.promocion_nxts+'</td>');


				tableAdmin.push('<td><button id="detallazos" class="btn btn-success" data-toggle="tooltip" title="Detalles" data-id-cotizacion="'+value.id_cotizacion+'">'+
							'<i class="fa fa-eye"></i></button><button id="update_cotizacion" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-cotizacion="'+value.id_cotizacion+'">'+
							'<i class="fa fa-pencil"></i></button><button id="delete_cotizacion" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-cotizacion="'+value.id_cotizacion+'">'+
							'<i class="fa fa-trash"></i></button></td></tr>');
			});	
			$('.tableAdmin').append(tableAdmin.join(""));
			fillDataTable("table_cot_admin", 50);
			$(".cuatro").inputmask("currency", {radixPoint: ".", prefix: ""});
			$(".sistema").inputmask("currency", {radixPoint: ".", prefix: ""});
			$("html").unblock();
		});
	

}

function move() {
  var elem = document.getElementById("myBar");   
  var width = 1;
  var id = setInterval(frame, 200);
  function frame() {
    if (width >= 100) {
      clearInterval(id);
    } else {
      width++; 
      elem.style.width = width + '%'; 
    }
  }
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
		sendForm("Cotizaciones/save/0", $("#form_cotizacion_new"), "");
	}else{
		toastr.warning("Seleccione un artículo de la lista", user_name);
	}
});

$(document).off("keyup", "#precio").on("keyup", "#precio", function() {
	var total =0;
	var descuento = $("#porcentaje").val().replace(/[^0-9\.]+/g,"");
	var precio = Number($(this).val().replace(/[^0-9\.]+/g,""));
	total = (precio - (precio * descuento));
	var num_1 = Number($("#num_one").val().replace(/[^0-9\.]+/g,""));
	var num_2 = Number($("#num_two").val().replace(/[^0-9\.]+/g,""));
	if(num_1 > 0 ){
		total = ((precio * num_2) / (num_1 + num_2));
	}
	$("#precio_promocion").val(total);
});

$(document).off("keyup", "#precio").on("keyup", "#precio", function() {
	var total =0;
	var descuento = $("#porcentaje").val().replace(/[^0-9\.]+/g,"");
	var precio = Number($(this).val().replace(/[^0-9\.]+/g,""));
	total = (precio - (precio * descuento));
	var num_1 = Number($("#num_one").val().replace(/[^0-9\.]+/g,""));
	var num_2 = Number($("#num_two").val().replace(/[^0-9\.]+/g,""));
	if(num_1 > 0 ){
		total = ((precio * num_2) / (num_1 + num_2));
	}
	$("#precio_promocion").val(total);
});

$(document).off("keyup", "#porcentaje").on("keyup", "#porcentaje", function () {
	var total =0;
	var descuento = $("#porcentaje").val().replace(/[^0-9\.]+/g,"");
	var precio = $("#precio").val().replace(/[^0-9\.]+/g,"");
	total = (precio - (precio * descuento/100));
	$("#precio_promocion").val(total);
});

$(document).off("keyup", "#num_one").on("keyup", "#num_one", function () {
	var total =0;
	var num_2 = parseInt($("#num_two").val());
	var num_1 = parseInt($("#num_one").val());
	if(num_2 > 0 && num_1 > 0){
		var precio = $("#precio").val();
		total = ((precio * num_2) / (num_1 + num_2));
		console.log(precio * num_2);
		console.log(num_1 + num_2);
		$("#precio_promocion").val(total);
	}else{
		$("#precio_promocion").val($("#precio").val().replace(/[^0-9\.]+/g,""));
	}
});

$(document).off("keyup", "#num_two").on("keyup", "#num_two", function () {
	var total =0;
	var num_1 = parseInt($("#num_one").val());
	var num_2 = parseInt($("#num_two").val());
	if(num_1 > 0){
		var precio = $("#precio").val();
		total = ((precio * num_2) / (num_1 + num_2));
		$("#precio_promocion").val(total);
	}else{
		$("#precio_promocion").val($("#precio").val().replace(/[^0-9\.]+/g,""));
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

$(document).off("click", "#detallazos").on("click", "#detallazos", function(event){
	event.preventDefault();
	var id_cotizacion = $(this).closest("tr").find("#detallazos").data("idCotizacion");
	getModal("Cotizaciones/detallazos/"+ id_cotizacion, function (){ });
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
$(document).off("keyup", ".precio").on("keyup", ".precio", function() {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var descuento = tr.find(".descuento").val().replace(/[^0-9\.]+/g,"");
	var num_one = tr.find('.num_one').val().replace(/[^0-9\.]+/g,"");
	var num_two = tr.find('.num_two').val().replace(/[^0-9\.]+/g,"");
	if(num_two > 0 && num_one > 0){
		var total = (precio * num_two) / (parseFloat(num_one) + parseFloat(num_two));
		tr.find(".precio_promocion").val(total);
	}else if(descuento > 0){
		tr.find(".precio_promocion").val(precio - (precio * (descuento / 100)));
	}else{
		tr.find(".precio_promocion").val(precio);
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

$(document).off("change", "#file_otizaciones").on("change", "#file_otizaciones", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_cotizaciones")[0]);
	uploadCotizaciones(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				setTimeout("location.reload()", 1700, toastr.error(resp.desc, user_name), "");
			}else{
				unblockPage();
				setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});
$(document).off("click", "#cambcontra").on("click", "#cambcontra", function(event){
	event.preventDefault();
	getModal("Main/getNotCotizo/", function (){ });
});

function uploadCotizaciones(formData) {
	return $.ajax({
		url: site_url+"Cotizaciones/upload_cotizaciones/0",
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
	var ides = $(this).attr("data-id-producto");
	getModal("Main/CotzUsuario/"+ides, function (){ });
});




$(document).off("change", "#id_pro").on("change", "#id_pro", function() {
	event.preventDefault();
	$(".searchboxs").css("display","none")
	var proveedor = $("#id_pro option:selected").val();
	$(".cot-prov").html("");
	getProveedorCot(proveedor)
	.done(function (resp){
		if(resp.cotizaciones){
			$.each(resp.cotizaciones, function(indx, value){
				value.observaciones = value.observaciones == null ? "" : value.observaciones;
				$(".cot-prov").append('<tr><td>'+value.producto+'</td><td>'+value.codigo+'</td><td>'+value.precio+'</td><td>'+value.precio_promocion
					+'</td><td>'+value.observaciones+'</td></tr>')
			});
		}
		$(".searchboxs").css("display","block")
	});

});

function myFunction() {
  // Declare variables 
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_prov_cots");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}
function getProveedorCot(id_prov) {
	return $.ajax({
		url: site_url+"/Cotizaciones/getProveedorCot/"+id_prov,
		type: "POST",
		dataType: "JSON"
	});
}

$(document).off("focusout", ".precio_sistema").on("focusout", ".precio_sistema", function () {
	var tr = $(this).closest("tr");
	var cantidad = $(this).val();
	guardaPrecios(tr,"sistema",cantidad);
});
$(document).off("focusout", ".precio_four").on("focusout", ".precio_four", function () {
	var tr = $(this).closest("tr");
	var cantidad = $(this).val();
	guardaPrecios(tr,"cuatro",cantidad);
});

function guardaPrecios(tr, tipo,cantidad){
	var id_cotizacion = tr.find("#update_cotizacion").data("idCotizacion");
	var sistema = tipo == "sistema" ? cantidad : tr.find(".precio_sistema").val();
	var cuatro = tipo == "cuatro" ? cantidad : tr.find(".precio_four").val();
	sistema = sistema == "" ? 0 : sistema;
	cuatro = cuatro == "" ? 0 : cuatro;
	var values = {'id_cotizacion': id_cotizacion,'sistema': sistema,'cuatro': cuatro};
	return $.ajax({
		url: site_url+"Pedidos/guardaSistema",
		type: "POST",
		dataType: 'JSON',
		data: {values : JSON.stringify(values)}
	});
}

$(document).off("click", "#cambcontra").on("click", "#cambcontra", function(event) {
	event.preventDefault();
	var id_usuarios = $("#cambcontra").data("idUsuario");
	getModal("Main/cambioContra/"+id_usuarios, function (){
		loadScript(base_url+"assets/js/plugins/validate/jquery.validate.min.js", function (argument) {
			$("#form_usuario_edit").validate({
				rules: {
					password: {required: true, minlength: 8}
				}
			});
			jQuery.extend(jQuery.validator.messages,{
				minlength: jQuery.validator.format("Por favor ingresa más de {0} caracteres.")
			});
		});
	});
});

$(document).off("click", ".update_usuario").on("click", ".update_usuario", function(event) {
	event.preventDefault();
	if($("#form_usuario_edit").valid()){
		sendForm("Main/update_user", $("#form_usuario_edit"), "");
	}
});
