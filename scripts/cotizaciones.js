$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});

	$("#table_cot_admin").dataTable({
		ajax: {
			url: site_url +"Cotizaciones/cotizaciones_dataTable",
			type: "POST"
		},
		processing: true,
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

	fillDataTable("table_cot_proveedores", 50);
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
	sendForm("Cotizaciones/get_delete", $("#form_cotizacion_delete"), "");
});

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