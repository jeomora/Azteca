$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});

	datePicker();

});


$(document).off("click", "#filter_show").on("click", "#filter_show", function(event) {
	event.preventDefault();
	var tableAdmin = "";
	var fech = $("#fecha_registro").val();
	var formData = $("#consultar_cotizaciones").serializeArray();
	var familia = "";
	get_reporte(formData).done(function(response) {
		$(".whodid").html(response);

		get_rpts($("#fecha_registro").val()).done(function(response) {
				$.each(response, function(indx, vals){
						$.each(vals, function(index, value){
							if(familia != value.familia){
								familia = value.familia;
								tableAdmin += '<tr><td colspan="11" style="font-size: 2rem;background-color:#000000;color:#FFFFFF;text-align:center">'+value.familia+'</td></tr>';
							}
							value.precio = value.precio == null ? 0 : value.precio;
							value.precio_promocion = value.precio_promocion == null ? 0 : value.precio_promocion;
							value.precio_sistema = value.precio_sistema == null ? 0 : value.precio_sistema;
							value.precio_four = value.precio_four == null ? 0 : value.precio_four;
							value.descuento = value.descuento == null ? "" : value.descuento;
							value.num_one = value.num_one == null ? "" : value.num_one;
							value.num_two = value.num_two == null ? "" : value.num_two;
							value.proveedor = value.proveedor == null ? "" : value.proveedor;
							value.observaciones = value.observaciones == null ? "" : value.observaciones;
							tableAdmin += '<tr>';
							if(value.estatus == 2){
								tableAdmin += '<td style="background-color: #00b0f0">'+value.codigo+'</td><td style="background-color: #00b0f0">'+value.nombre+'</td>';
							}else if(value.status == 3){
								tableAdmin += '<td style="background-color: #fff900">'+value.codigo+'</td><td style="background-color: #fff900">'+value.nombre+'</td>';
							}else{
								tableAdmin += '<td>'+value.codigo+'</td><td>'+value.nombre+'</td>';
							}
							tableAdmin += '<td>$ '+formatNumber(parseFloat(value.precio_sistema), 2)+'</td><td>$ '+formatNumber(parseFloat(value.precio_four), 2)+'</td><td>$ '+formatNumber(parseFloat(value.precio), 2)+'</td>';
							if(value.precio_promocion >= value.precio_sistema){
								tableAdmin += '<td><div class="preciomas">$ '+formatNumber(parseFloat(value.precio_promocion), 2)+'</div></td>';
							}else{
								tableAdmin += '<td><div class="preciomenos">$ '+formatNumber(parseFloat(value.precio_promocion), 2)+'</div></td>'
							}
							tableAdmin += '<td>'+value.proveedor+'</td><td>'+value.descuento+'</td>';	
							tableAdmin += '<td>'+value.num_one+'</td><td>'+value.num_two+'</td>';
							tableAdmin += '<td>'+value.observaciones+'</td></tr>';
						});
					});	
					$(".body_anteriores").html(tableAdmin);
					fillDataTable("table_anteriores", 50);
			});
	});
});


function get_reporte(formData) {
	return $.ajax({
		url: site_url+"Reportes/fill_table",
		type: "POST",
		cache: false,
		dataType:"HTML",
		data: formData,
	});
}

function get_rpts(fecha) {
	return $.ajax({
		url: site_url+"Reportes/fill_anterior",
		type: "POST",
		dataType:"JSON",
		data: {fecha},
	});
}

$(document).off("click", "#ver_cotizacion").on("click", "#ver_cotizacion", function(event){
	event.preventDefault();
	var d = new Date();
	var f = d.getMonth()+1+"";
	if( f.length == 1){
		f = "0"+f;
	}
	var fecha = d.getFullYear() + "-" + f + "-" + d.getDate();
	if ($("#fecha_registro").val().length !== 0) {
		fecha = $("#fecha_registro").val();
		fecha = fecha.split("-").reverse().join("-");
		console.log(fecha)
	}
	var id_cotizacion = $(this).closest("tr").find("#ver_cotizacion").data("idCotizacion");
	getModal("Cotizaciones/ver_cotizacion/"+id_cotizacion+"/"+fecha+"/", function (){ });
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
				$(".cot-prov").append('<tr><td><input type="checkbox" value="1" class=""></td><td>'+value.producto+'</td><td>'+value.codigo+'</td><td>'+value.precio+'</td><td>'+value.precio_promocion
					+'</td><td><div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="fecha_registro" id="fecha_registro" class="form-control datepicker" value="" placeholder="00-00-0000"></div></td><td><button id="update_cotizacion" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-cotizacion="95471"><i class="fa fa-pencil"></i></button></td></tr>')
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

function myFunction2() {
  // Declare variables 
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_anteriores");
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