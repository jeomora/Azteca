$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});

	datePicker();

});


$(document).off("click", "#filter_show").on("click", "#filter_show", function(event) {
	event.preventDefault();
	$("html").block({
		centerY: 0,
		message: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span style="font-size:3rem;">Cargando...</span>',
		overlayCSS: { backgroundColor: '#DDFF33' },
		css: { position: 'absolute',
	    top: '25rem',
	    left: '45rem',
	    background: 'rgba(255,255,255,0.5)',
	    padding: '10rem',
	    color: '#FF6805',
	    border: '2px solid #FF6805'},
	});
	setTimeout(function(){ $(".spinns").css("display","none");$("html").unblock(); }, 16000);
	var tableAdmin = "";
	var fech = $("#fecha_registro").val();
	$(".tblm").html('<table class="table table-striped table-bordered table-hover" border="1" id="table_anteriores">'+
						'<thead><tr><th>CÓDIGO</th><th>DESCRIPCIÓN</th><th>SISTEMA</th><th>PRECIO 4</th>'+
						'<th>1ER PRECIO</th><th>PROVEEDOR</th><th>OBSERVACIÓN</th>'+
						'<th>2DO PRECIO</th><th>2DO PROVEEDOR</th><th>OBSERVACIÓN</th><th>VER MÁS</th>'+
						'</tr></thead><tbody class="body_anteriores"></tbody></table>');
	var formData = $("#consultar_cotizaciones").serializeArray();
	get_reporte(formData).done(function(response) {
		get_rpts($("#fecha_registro").val()).done(function(response) {
				$.each(response, function(indx, vals){
						$.each(vals, function(index, value){
							value.precio_next = value.precio_next == null ? 0 : value.precio_next;
							value.precio_four = value.precio_four == null ? 0 : value.precio_four;
							value.precio_sistema = value.precio_sistema == null ? 0 : value.precio_sistema;
							value.precio_first = value.precio_first == null ? 0 : value.precio_first;
							value.precio_next = value.precio_next == null ? 0 : value.precio_next;
							value.precio_nexto = value.precio_nexto == null ? 0 : value.precio_nexto;
							value.proveedor_next = value.proveedor_next == null ? "" : value.proveedor_next;
							value.promocion_first = value.promocion_first == null ? "" : value.promocion_first;
							value.promocion_next = value.promocion_next == null ? "" : value.promocion_next;
							tableAdmin += '<tr>';
							if(value.estatus == 2){
								tableAdmin += '<td style="background-color: #00b0f0">'+value.codigo+'</td><td style="background-color: #00b0f0">'+value.producto+'</td>';
							}else if(value.status == 3){
								tableAdmin += '<td style="background-color: #fff900">'+value.codigo+'</td><td style="background-color: #fff900">'+value.producto+'</td>';
							}else{
								tableAdmin += '<td>'+value.codigo+'</td><td>'+value.producto+'</td>';
							}
							tableAdmin += '<td>$ '+formatNumber(parseFloat(value.precio_sistema), 2)+'</td><td>$ '+formatNumber(parseFloat(value.precio_four), 2)+'</td>';
							if(value.precio_first >= value.precio_sistema){
								tableAdmin += '<td><div class="preciomas">$ '+formatNumber(parseFloat(value.precio_first), 2)+'</div></td>';
							}else{
								tableAdmin += '<td><div class="preciomenos">$ '+formatNumber(parseFloat(value.precio_first), 2)+'</div></td>'
							}
							tableAdmin += '<td>'+value.proveedor_first+'</td><td>'+value.promocion_first+'</td>';					
							if(value.precio_next >= value.precio_sistema){
								tableAdmin += value.precio_next > 0 ? '<td><div class="preciomas">$ '+formatNumber(parseFloat(value.precio_next), 2)+'</div></td>' : '<td></td>';
							}else{
								tableAdmin += value.precio_next > 0 ? '<td><div class="preciomenos">$ '+formatNumber(parseFloat(value.precio_next), 2)+'</div></td>' : '<td></td>';
							}
							tableAdmin += '<td>'+value.proveedor_next+'</td><td>'+value.promocion_next+'</td><td><button id="ver_cotizacion" class="btn btn-info" data-toggle="tooltip" title="Ver más" data-id-cotizacion="'+
										value.id_cotizacion+'"><i class="fa fa-eye"></i></button><input type="text" name="fecha_registro" id="fecha_registro" value="'+fech+'" hidden="true"></td></tr>';
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

