$(document).off("change", "#id_pro").on("change", "#id_pro", function() {
	event.preventDefault();
	var proveedor = $("#id_pro option:selected").val();
	if(proveedor != "nope"){
		renderTable();
		$("#id_pro").prop('disabled', 'disabled');
		$("#camb").css("display","block");
    $(".dude").css("display","block");
	}else{
		$("#camb  ").css("display","none");
    $(".dude").css("display","none");
    $(".float-e-margins").css("display","none");
	}

});

function renderTable(){
	$(".cot-prov").html();
	$(".float-e-margins").css("display","none");
	$(".searchboxs").css("display","none")
	var proveedor = $("#id_pro option:selected").val();
	$(".cot-prov").html("");
	getProveedorCot(proveedor)
	.done(function (resp) {
		if(resp){

			$.each(resp, function(indx, value){
				value.observaciones = value.observaciones == null ? "" : value.observaciones;
				value.descuento = value.descuento == null ? "" : value.descuento;
				value.num_one = value.num_one == null ? "" : value.num_one;
				value.num_two = value.num_two == null ? "" : value.num_two;

				$(".cot-prov").append('<tr><td>'+value.producto+'</td><td>'+value.codigo+'</td><td>'+value.fecha_registro +'</td><td>'+formatNumber(parseFloat(value.precio), 2)+
					'</td><td>$ '+formatNumber(parseFloat(value.precio_promocion), 2)+'</td><td>% '+value.descuento +'</td><td>'+value.num_one+'</td><td>'+value.num_two+
					'</td><td>'+value.observaciones+'</td>'+
					'<td><button id="update_cotizacion" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-cotizacion="'+value.id_cotizacion+'">'+
					'<i class="fa fa-pencil"></i></button><button id="delete_cotizacion" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" '+
					'data-id-cotizacion="'+value.id_cotizacion+'"><i class="fa fa-trash"></i></button></td></tr>')
			});
		}
		$(".searchboxs").css("display","block");
		$(".float-e-margins").css("display","block");
	});
}

$(document).off("change", "#file_otizaciones").on("change", "#file_otizaciones", function(event) {
	event.preventDefault();
	blockPage();
	var proveedor = $("#id_pro option:selected").val();
	var fdata = new FormData($("#upload_cotizaciones")[0]);
	uploadCotizaciones(fdata,proveedor)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
			}else{
				unblockPage();
				renderTable();
        $(".formas").html('<input class="btn btn-info" type="file" id="file_otizaciones" name="file_otizaciones" value="" size="20" />');
			}
		});
});
function uploadCotizaciones(formData,ides) {
	return $.ajax({
		url: site_url+"Cotizaciones/upload_expos/"+ides,
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

function getProveedorCot(id_prov) {
	return $.ajax({
		url: site_url+"/Cotizaciones/proveedorExpos/"+id_prov,
		type: "POST",
		dataType: "JSON"
	});
}
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_cot_proveedores");
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
$(document).off("click", "#update_cotizacion").on("click", "#update_cotizacion", function(event){
	event.preventDefault();
	var id_cotizacion = $(this).closest("tr").find("#update_cotizacion").data("idCotizacion");
	var proveedor = $("#id_pro option:selected").val();
	getModal("Cotizaciones/get_update2/"+ id_cotizacion+"/"+proveedor, function (){
		datePicker();
		$(".number").inputmask("currency", {radixPoint: ".", prefix: ""});
	});
});
$(document).off("click", "#delete_cotizacion").on("click", "#delete_cotizacion", function(event){
	event.preventDefault();
	var proveedor = $("#id_pro option:selected").val();
	var id_cotizacion = $(this).closest("tr").find("#delete_cotizacion").data("idCotizacion");
	getModal("Cotizaciones/get_delete2/"+ id_cotizacion+"/"+proveedor, function (){ });
});

$(document).off("click", ".update_cotizacion").on("click", ".update_cotizacion", function(event) {
	event.preventDefault();
	sendFormos("Cotizaciones/update2", $("#form_cotizacion_edit"), "");
});
$(document).off("click", ".delete_cotizacion").on("click", ".delete_cotizacion", function(event) {
	event.preventDefault();
	sendFormos("Cotizaciones/delete2", $("#form_cotizacion_delete"), "");
});
$(document).off("change", ".id_producto").on("change", ".id_producto", function() {
	var tr = $(this).closest("tr");
	$(".numeric").inputmask("currency", {radixPoint: ".", prefix: ""});
	if($(this).is(":checked")) {
		tr.find(".precio").removeAttr('readonly');
		tr.find(".id_producto").attr('name', 'id_producto[]');
		tr.find(".precio").attr('name', 'precio[]');
		tr.find(".num_one").attr('name', 'num_one[]');
		tr.find(".num_two").attr('name', 'num_two[]');
		tr.find(".descuento").attr('name', 'descuento[]');
		tr.find(".observaciones").attr('name', 'observaciones[]');
	}else{
		$(this).removeAttr("checked");
		tr.find(".precio").attr('readonly', 'readonly');
		tr.find(".precio").removeAttr('name').val('');
		tr.find(".importe").removeAttr('name').val('');
		tr.find(".id_producto ").removeAttr('name');
	}
});

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

$(document).off("click", "#new_cotizacion").on("click", "#new_cotizacion", function(event) {
	event.preventDefault();
	getModal("Cotizaciones/add_cotizacion", function (){
		datePicker();
		getChosen();
		$(".number").inputmask("currency", {radixPoint: ".", prefix: ""});
		$(".numeric").number(true, 0);
	});
});
$(document).off("click", "#camb").on("click", "#camb", function(event) {
	event.preventDefault();
	$("#id_pro").removeAttr("disabled");
	$("#camb").css("display","none");
});

$(document).off("click", ".new_cotizacion").on("click", ".new_cotizacion", function(event) {
	var proveedor = $("#id_pro option:selected").val();
	if($("#id_producto").val() !== ''){
		sendFormos("Cotizaciones/saveexpos/"+proveedor, $("#form_cotizacion_new"), "");
	}else{
		toastr.warning("Seleccione un artículo de la lista", user_name);
	}
});

$(document).off("change", "#file_cotizaciones").on("change", "#file_cotizaciones", function(event) {
	event.preventDefault();
	var proveedor = $("#id_proves4 option:selected").val();
	var fdata = new FormData($("#upload_pedidos")[0]);
	if(proveedor != "nope"){
		blockPage();
		uploadPedidos(fdata,proveedor)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
			}else{
				unblockPage();
				renderTable();
			}
		});
	}
});

$(document).off("change", "#id_proves4").on("change", "#id_proves4", function(event) {
	event.preventDefault();
	$(".sbir").css("display","none");
	var proveedor = $("#id_proves4 option:selected").val();
	if(proveedor != "nope"){
		$(".sbir").css("display","block");
	}else{
		$(".sbir").css("display","none");
	}
});

function uploadPedidos(formData,proveedor) {
	return $.ajax({
		url: site_url+"Cotizaciones/upload_pedidos/"+proveedor,
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

function sendFormos(url, formData, url_repuesta){
		url_repuesta = typeof url_repuesta === 'undefined' ? "/#" : url_repuesta;
		$.ajax({
			url: site_url + url,
			type: "POST",
			dataType: "JSON",
			data: (formData).serializeArray()
		})
		.done(function(response) {
			switch(response.type){
				case "success":
					emptyModal();
					$("#mainModal").modal("hide");
					renderTable();
				break;

				case "info":
					emptyModal();
					$("#mainModal").modal("hide");
					renderTable();
				break;

				case "warning":
					toastr.warning(response.desc, user_name);
				break;

				default:
					toastr.error(response.desc, user_name);
			}
			$("#notifications").html(response);
		})
		.fail(function(response) {
			// console.log("Error en la respuesta: ", response);
		});
	}

  function filling(id_prov) {
  	return $.ajax({
  		url: site_url+"/Cotizaciones/comparaExpo/"+id_prov,
  		type: "POST",
  		dataType: "JSON"
  	});
  }
