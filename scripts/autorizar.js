$(document).off("change", "#id_pro").on("change", "#id_pro", function() {
	event.preventDefault();
	var id_proveedor = $("#id_pro option:selected").val();
	var proveedor = $("#id_pro option:selected").text();
	if (id_proveedor === 0) {
		$(".cotiz").css("display","none");
	} else {
		$(".cotiz").css("display","block");
		getCotizaciones(id_proveedor).done(function(resp){
			$(".cot-prov").html("");
			$.each(resp,function(index,val){
				var promocio = "";
				val.descuento = val.descuento == null ? 0 : val.descuento;
				val.observaciones = val.observaciones == null ? "" : val.observaciones;

				if(val.num_one > 0 && val.num_two > 0){
					promocio = val.num_one+'&nbsp; EN &nbsp;'+val.num_two;
				}
				$(".cot-prov").append('<tr class="renglon'+val.id_cotizacion+'" ><td>'+val.producto.toUpperCase()+'</td>'+
										'<td>'+val.codigo+'</td>'+
										'<td>'+val.fecha_registro+'</td>'+
										'<td>$ '+formatMoney(val.precio)+'</td>'+
										'<td>$ '+formatMoney(val.precio_promocion)+'</td>'+
										'<td>% '+val.descuento+'</td>'+
										'<td>'+promocio+'</td>'+
										'<td>'+val.observaciones+'</td>'+
										'<td><button class="delCot" data-id-cotiz="'+val.id_cotizacion+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>'+
										'</tr>')
			});
		})
	}
});

$(document).off("click",".delCot").on("click",".delCot", function(){
	var renglon =  $(this).data("idCotiz");
	$(".renglon"+renglon).toggleClass("colorado");
	deleteRenglon(renglon);
});

function getCotizaciones(id_prov) {
	return $.ajax({
		url: site_url+"/Cotizaciones/get_cotzprov/"+id_prov,
		type: "POST",
		dataType: "JSON",
	});
}

function deleteRenglon(id_prov) {
	return $.ajax({
		url: site_url+"/Cotizaciones/deleteRenglon/"+id_prov,
		type: "POST",
		dataType: "JSON",
	});
}


$(document).off("click",".butProv").on("click",".butProv", function(){
	var id_proveedor = $("#id_pro option:selected").val();
	var proveedor = $("#id_pro option:selected").text();
	agregaRenglon(id_proveedor).done(function(resp){
		if (resp.type == 'error'){
				toastr.error(resp.desc, user_name)
				//setTimeout("location.reload()", 1700, toastr.error(resp.desc, user_name), "");
			}else{
				toastr.success(resp.desc, user_name)
				//setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
	})
});

function agregaRenglon(id_prov) {
	return $.ajax({
		url: site_url+"/Cotizaciones/agregaRenglon/"+id_prov,
		type: "POST",
		dataType: "JSON",
	});
}