$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
});

function buscaProdis(values){
    return $.ajax({
        url: site_url+"/Lunes/buscaProdis",
        type: "POST",
        dataType: "JSON",
        data: {
            values : values
        },
    });
}
$(document).off("keyup", "#buscale").on("keyup", "#buscale", function () {
	event.preventDefault();
	var buscale = $("#buscale");
	var values = {"busca":buscale.val()};
	if (buscale.val().length > 3) {
		$('#tbody_exist').html("<tr><td colspan='36' style='font-size:24px;font-weight:bold;'>Cargando resultados</td></tr>");
		buscaProdis(JSON.stringify(values))
		.done(function (resp) {
			if (resp.prods) {
				var html = "";
				setTimeout(function(){
					$('#tbody_exist').html("");
					$.each(resp.prods, function(indx, value){
						html+="<tr><td colspan='3'>"+value.codigo+" -<br> "+value.descripcion+"</td>";
						$.each(value.existencias, function(inx,vals){
							html+='<td>'+vals.pzs+'</td><td>'+vals.cja+'</td><td>'+vals.ped+'</td>';
						})
						html+="</tr>";
					});
					$('#tbody_exist').html(html)
				},500)
			}else{
				setTimeout(function(){
					$('#tbody_exist').html("<tr><td colspan='36' style='font-size:24px;font-weight:bold;'>No se encontraron resultados para "+buscale.val()+"</td></tr>");
				},500)
			}
		});
	} else {
		setTimeout(function(){
			$('#tbody_exist').html("");
			$('#tbody_exist').html("<tr><td colspan='36' style='font-size:24px;font-weight:bold;'>Introduzca mas de 4 caracteres en el recuadro de busqueda</td></tr>");
		},500)
	}
});


$(document).off("change", "#file_otizaciones").on("change", "#file_otizaciones", function(event) {
	event.preventDefault();
	var tienda = $(this).data("idTienda");
	blockPage();
	var fdata = new FormData($("#upload_cotizaciones"+tienda)[0]);
	uploadExistencias(fdata,tienda)
	.done(function (resp) {
		if (resp.type == 'error'){
			unblockPage();
			setTimeout("", 700, toastr.error(resp.desc, user_name), "");
		}else{
			unblockPage();
			setTimeout("", 700, toastr.success(resp.desc, user_name), "");
			setTimeout(function(){
				getCuantas(tienda).done(function (resp) {
					$("#ths"+tienda).html(resp.cuantas[0].cuantas+" de "+resp.noprod.noprod);
				})
				$('#tbody_exist').html("<tr><td colspan='36' style='font-size:24px;font-weight:bold;'>Se cargarón las existencias</td></tr>");
				$("#buscale").val("");
			})
		}
	});
});

function uploadExistencias(formData,ides) {
	return $.ajax({
		url: site_url+"Lunes/upload_existencias/"+ides,
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

function getCuantas(values){
    return $.ajax({
        url: site_url+"/Lunes/getCuantas/"+values,
        type: "POST",
        dataType: "JSON",
    });
}