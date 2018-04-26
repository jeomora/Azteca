$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
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
				value.no_semanas = value.no_semanas == null ? "" : value.no_semanas;
				value.fecha_termino = value.fecha_termino== null ? "" : getweekdays(value.fecha_termino);
				value.observaciones = value.observaciones == null ? "" : value.observaciones;
				$(".cot-prov").append('<tr><td><input type="checkbox" value="'+value.id_producto+'" class="id_producto"></td><td>'+value.producto+'</td><td>'+value.codigo+'</td><td>'+value.precio+'</td><td>'+value.precio_promocion
					+'</td><td><div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" value="'+value.no_semanas+'" class="form-control no_semanas numeric" '+
					'readonly="" placeholder="No. Semanas"></div></td><td>'+value.fecha_termino+'</td></tr>')
			});
		}
		$(".searchboxs").css("display","block");
		

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