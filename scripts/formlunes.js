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
	var sugerido = 0;
	var anterior = 0;
	var values = {"busca":buscale.val()};
	if (buscale.val().length > 3) {
		$('#tbody_exist').html("<tr><td colspan='59' style='text-align:left;font-size:24px;font-weight:bold;'>Cargando resultados</td></tr>");
		buscaProdis(JSON.stringify(values))
		.done(function (resp) {
			if (resp.prods) {
				var html = "";
				setTimeout(function(){
					$('#tbody_exist').html("");
					$.each(resp.prods, function(indx, value){
						html += "<tr><td style='width:300px !important;'>"+value.codigo+"<br>"+value.descripcion+"</td>";
						html += "<td>$ "+formatMoney(value.precio)+"</td>";
						if (value.sis == value.cur) {
							html += "<td>$ "+formatMoney(value.sistema)+"</td>";
						} else {
							html += "<td style='background-color:red'>$ "+formatMoney(value.sistema)+"</td>";
						}
						html += "<td>"+value.unidad+"</td>";
						$.each(value.existencias, function(inx,vals){							
							if (inx == 2) {
								value.exist[1].ped = value.exist[1].ped == null ? 0 : value.exist[1].ped;
								value.exist[1].cja = value.exist[1].cja == null ? 0 : value.exist[1].cja;
								value.exist[1].pzs = value.exist[1].pzs == null ? 0 : value.exist[1].pzs;
								value.exist[3].ped = value.exist[3].ped == null ? 0 : value.exist[3].ped;
								value.exist[3].cja = value.exist[3].cja == null ? 0 : value.exist[3].cja;
								value.exist[3].pzs = value.exist[3].pzs == null ? 0 : value.exist[3].pzs;
								value.existencias[1].ped = value.existencias[1].ped == null ? 0 : value.existencias[1].ped;
								value.existencias[1].cja = value.existencias[1].cja == null ? 0 : value.existencias[1].cja;
								value.existencias[1].pzs = value.existencias[1].pzs == null ? 0 : value.existencias[1].pzs;
								value.existencias[3].ped = value.existencias[3].ped == null ? 0 : value.existencias[3].ped;
								value.existencias[3].cja = value.existencias[3].cja == null ? 0 : value.existencias[3].cja;
								value.existencias[3].pzs = value.existencias[3].pzs == null ? 0 : value.existencias[3].pzs;
								
								anterior = (((parseFloat(value.exist[1].cja)+parseFloat(value.exist[1].ped))+parseFloat(value.exist[1].pzs)) / parseFloat(value.unidad))+(((parseFloat(value.exist[3].cja)+parseFloat(value.exist[3].ped))+parseFloat(value.exist[3].pzs)) / parseFloat(value.unidad));
								sugerido = ((parseFloat(anterior) * parseFloat(value.unidad)) - (((parseFloat(value.existencias[1].cja)+parseFloat(value.existencias[3].cja)) * parseFloat(value.unidad)) + (parseFloat(value.existencias[1].pzs)+parseFloat(value.existencias[3].pzs))))/ parseFloat(value.unidad);
								html +='<td style="border-left-width:5px;">'+formatMoney(anterior)+'</td><td style="background:#da9694">'+formatMoney(sugerido)+'</td><td>'+formatMon((parseFloat(value.existencias[1].cja)+parseFloat(value.existencias[3].cja)))+
								'</td><td>'+formatMon((parseFloat(value.existencias[1].pzs)+parseFloat(value.existencias[3].pzs)))+
								'</td><td style="background:#dce6f1;border-right-width:5px;">'+formatMon((parseFloat(value.existencias[1].ped)+parseFloat(value.existencias[3].ped)))+'</td>';
							}else{
								value.exist[inx].ped = value.exist[inx].ped == null ? 0 : value.exist[inx].ped;
								value.exist[inx].cja = value.exist[inx].cja == null ? 0 : value.exist[inx].cja;
								value.exist[inx].pzs = value.exist[inx].pzs == null ? 0 : value.exist[inx].pzs;
								vals.cja = vals.cja == null ? 0 : vals.cja;
								vals.pzs = vals.pzs == null ? 0 : vals.pzs;
								vals.ped = vals.ped == null ? 0 : vals.ped;
								anterior = ((parseFloat(value.exist[inx].cja)+parseFloat(value.exist[inx].ped))+parseFloat(value.exist[inx].pzs))/ parseFloat(value.unidad);
								sugerido = ((parseFloat(anterior) * parseFloat(value.unidad)) - ((parseFloat(vals.cja) * parseFloat(value.unidad)) + parseFloat(vals.pzs)))/ parseFloat(value.unidad);
								console.log(value.exist[inx].cja)
								console.log(value.exist[inx].ped)
								console.log(value.exist[inx].pzs)
								console.log("-------------------")
								html +='<td style="border-left-width:5px;">'+formatMoney(anterior)+'</td><td style="background:#da9694">'+formatMoney(sugerido)+'</td><td>'+formatMon(vals.cja)+'</td><td>'+formatMon(vals.pzs)+
								'</td><td style="background:#dce6f1;border-right-width:5px;">'+formatMon(vals.ped)+'</td>';
							}
							
						})
						html += "</tr>";
					});
					$('#tbody_exist').html(html)
				},500)
			}else{
				setTimeout(function(){
					$('#tbody_exist').html("<tr><td colspan='59' style='text-align:left;font-size:24px;font-weight:bold;'>No se encontraron resultados para "+buscale.val()+"</td></tr>");
				},500)
			}
		});
	} else {
		setTimeout(function(){
			$('#tbody_exist').html("");
			$('#tbody_exist').html("<tr><td colspan='59' style='text-align:left;font-size:24px;font-weight:bold;'>Introduzca mas de 4 caracteres en el recuadro de busqueda</td></tr>");
		},500)
	}
});


$(document).off("change", "#file_otizaciones").on("change", "#file_otizaciones", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_sistema")[0]);
	uploadSistema(fdata)
	.done(function (resp) {
		if (resp.type == 'error'){
			unblockPage();
			setTimeout("", 700, toastr.error(resp.desc, user_name), "");
		}else{
			unblockPage();
			setTimeout("", 700, toastr.success(resp.desc, user_name), "");
			setTimeout(function(){
				$('#tbody_exist').html("<tr><td colspan='36' style='font-size:24px;font-weight:bold;'>Se cargarón los precios sistema</td></tr>");
				$("#buscale").val("");
			})
		}
	});
});

function uploadSistema(formData) {
	return $.ajax({
		url: site_url+"Lunes/upload_sistema/",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

$(document).off("change", "#file_cotizaciones").on("change", "#file_cotizaciones", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_precios")[0]);
	uploadPrecios(fdata)
	.done(function (resp) {
		if (resp.type == 'error'){
			unblockPage();
			setTimeout("", 700, toastr.error(resp.desc, user_name), "");
		}else{
			unblockPage();
			setTimeout("", 700, toastr.success(resp.desc, user_name), "");
			setTimeout(function(){
				$('#tbody_exist').html("<tr><td colspan='36' style='font-size:24px;font-weight:bold;'>Se cargarón los precios proveedor</td></tr>");
				$("#buscale").val("");
			})
		}
	});
});

function uploadPrecios(formData) {
	return $.ajax({
		url: site_url+"Lunes/upload_precios/",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}
