$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
});

function renderTable(proveedor){
	$(".cot-prov").html("");
	getProveedorCot(proveedor)
	.done(function (resp){
		if(resp.cotizaciones){
			$.each(resp.cotizaciones, function(indx, value){
				value.no_semanas = value.no_semanas == null ? "" : value.no_semanas;
				value.fecha_termino = value.fecha_termino== null ? "" : getweekdays(value.fecha_termino);
				value.observaciones = value.observaciones == null ? "" : value.observaciones;
				$(".cot-prov").append('<tr><td>'+value.codigo+'</td><td>'+value.producto+'</td><td>'+value.no_semanas+'</td><td>'+value.fecha_termino+'</td>'+
										'<td><button id="update_cotizacion" type="button" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-cotizacion="'+value.id_faltante+'">'+
										'<i class="fa fa-pencil"></i></button><button type="button" id="delete_cotizacion" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-cotizacion="'+value.id_faltante+'">'+
										'<i class="fa fa-trash"></i></button></td></tr>')
			});
			$(".searchboxs").css("display","block");
		}else {
			$(".cot-prov").html("<tr><td colspan='5'><h2 style='text-align:center'>PROVEEDOR SIN FALTANTES</td></tr>");
			$(".searchboxs").css("display","none");
		}
	});

}


$(document).off("change", "#id_pro").on("change", "#id_pro", function() {
	event.preventDefault();
	var proveedor = $("#id_pro option:selected").val();
	if(proveedor != "nope"){
		$("#id_pro").prop('disabled', 'disabled');
		$("#camb").css("display","block");
		$("#nuevo_fal").css("display","block");
		$("#falts").css("display","block");
		$(".searchboxs").css("display","block");
		renderTable(proveedor);
	}else{
		$("#camb").css("display","none");
		$("#nuevo_fal").css("display","none");
		$("#falts").css("display","none");
		$(".searchboxs").css("display","none");
		$(".cot-prov").html("");
	}


});

$(document).off("click", "#camb").on("click", "#camb", function(event) {
	event.preventDefault();
	$("#id_pro").removeAttr("disabled");
	$("#camb").css("display","none");
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
    td = tr[i].getElementsByTagName("td")[1];
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

$(document).off("change", ".id_producto").on("change", ".id_producto", function() {
	var tr = $(this).closest("tr");
	if($(this).is(":checked")) {
		tr.find(".id_producto ").removeAttr('readonly');
		tr.find(".no_semanas").removeAttr('readonly');
		tr.find(".id_producto ").attr('name', 'id_producto[]');
		tr.find(".no_semanas").attr('name', 'no_semanas[]');
	}else{
		$(this).removeAttr("checked");
		tr.find(".no_semanas").attr('readonly', 'readonly');
		tr.find(".id_producto ").removeAttr('name');
	}
});

$(document).off("click", ".save_faltante").on("click", ".save_faltante", function(event) {
	event.preventDefault();
	sendForm("Cotizaciones/registro_fltnts", $("#form_faltantes"), "");
});

function getweekdays(fecha){
	fecha = new Date(fecha);
	var weekday = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sabado"];
	var month = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","","Noviembre","Diciembre"];
	var fec = weekday[fecha.getDay()]+' '+fecha.getDate()+' de '+month[fecha.getMonth()]+' del '+fecha.getFullYear();
	return fec;
}

$(document).off("change", "#file_faltantes").on("change", "#file_faltantes", function(event) {
	event.preventDefault();
	var proveedor = $("#id_pro option:selected").val();
	var fdata = new FormData($("#upload_faltantes")[0]);
	if(proveedor != "nope"){
		blockPage();
		uploadFaltantes(fdata,proveedor)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
			}else{
				var proveedor = $("#id_pro option:selected").val();
				renderTable(proveedor);
				$(".faltsc").html('<input class="btn btn-info" type="file" id="file_faltantes" name="file_faltantes" value="" size="20" />')
				unblockPage();
			}
		});
	}
});

function uploadFaltantes(formData,proveedor) {
	return $.ajax({
		url: site_url+"Cotizaciones/upload_faltantes/"+proveedor,
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

$(document).off("click", "#nuevo_fal").on("click", "#nuevo_fal", function(event) {
	event.preventDefault();
	var proveedor = $("#id_pro option:selected").val();
	getModal("Cotizaciones/add_faltante/"+proveedor, function (){
		datePicker();
		getChosen();
	});
});

$(document).off("click", ".new_falta").on("click", ".new_falta", function(event) {
	if($("#id_producto").val() !== ''){
		if ($("#semanas").val() !== '') {
			sendFormos("Cotizaciones/save_falta/0", $("#form_faltante_new"), "");
		}else{
				toastr.warning("Agregue un numero de semanas", user_name);
		}
	}else{
		toastr.warning("Seleccione un artículo de la lista", user_name);
	}
});

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
					var proveedor = $("#id_pro option:selected").val();
					renderTable(proveedor);
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
