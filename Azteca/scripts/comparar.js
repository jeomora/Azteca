$(document).off("change", "#id_pro").on("change", "#id_pro", function() {
	event.preventDefault();
	$(".float-e-margins").css("display","none");
	$(".searchboxs").css("display","none")
	var proveedor = $("#id_pro option:selected").val();
	$(".cot-prov").html("");
	$(".downcomp").css("display","none");
	diffes = 0;
	if(proveedor != "nope"){
		getProveedorCot(proveedor)
		.done(function (resp) {
			if(resp){
				$.each(resp.cotizaciones, function(indx, value){
					value.observaciones = value.observaciones == null ? "" : value.observaciones;
					value.proves_obs = value.proves_obs == null ? "" : value.proves_obs;
					value.descuento = value.descuento == null ? "" : value.descuento;
					value.precio_first = value.precio_first == null ? 0 : value.precio_first;
					value.observaciones_first = value.observaciones_first == null ? "" : value.observaciones_first;
					
					value.proveedor_first =  value.proveedor_first == null ? "" : value.proveedor_first;
					value.proveedor_next =  value.proveedor_next == null ? "" : value.proveedor_next;
					value.precio_next = value.precio_next == null ? 0 : value.precio_next;
					value.observaciones_next = value.observaciones_next == null ? "" : value.observaciones_next;
					if(value.proves === value.proveedor_first){
						diffes = value.proves_promo - value.precio_next;
						$(".cot-prov").append('<tr><td>'+value.producto+'</td><td>'+value.codigo+'</td>'+
							'<td>$ '+formatNumber(parseFloat(value.proves_promo), 2)+'</td><td>'+value.proves_obs+'</td><td>$ '+formatNumber(parseFloat(value.precio_sistema), 2)+'</td>'+
							'<td>$ '+formatNumber(parseFloat(value.precio_four), 2)+'</td><td style="background-color:#FF0066">$ '+formatNumber(parseFloat(diffes), 2)+'</td><td>'+value.proveedor_next+'</td>'+
							'><td>$ '+formatNumber(parseFloat(value.precio_next), 2)+'</td><td>'+value.promocion_next+'</td></tr>');
					}else if(value.proves_promo >= value.precio_first){
						diffes = value.proves_promo - value.precio_first;
						$(".cot-prov").append('<tr><td>'+value.producto+'</td><td>'+value.codigo+'</td>'+
							'<td>$ '+formatNumber(parseFloat(value.proves_promo), 2)+'</td><td>'+value.proves_obs+'</td><td>$ '+formatNumber(parseFloat(value.precio_sistema), 2)+'</td>'+
							'<td>$ '+formatNumber(parseFloat(value.precio_four), 2)+'</td><td style="background-color:#FFE6F0">$ '+formatNumber(parseFloat(diffes), 2)+'</td><td>'+value.proveedor_first+'</td>'+
							'><td>$ '+formatNumber(parseFloat(value.precio_first), 2)+'</td><td>'+value.promocion_first+'</td></tr>');
					}
				});
			}
			$(".downcomp").css("display","block");
			$(".searchboxs").css("display","block");
			$(".float-e-margins").css("display","block");
		});
	}
});


function getProveedorCot(id_prov) {
	return $.ajax({
		url: site_url+"/Cotizaciones/getProveedorBajos/"+id_prov,
		type: "POST",
		dataType: "JSON"
	});
}
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

