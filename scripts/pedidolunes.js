var mybutton = 0;
$(document).off("click", "#ver_pedido").on("click", "#ver_pedido", function(event) {
	event.preventDefault();

	getModal("Lunes/verpedido/"+$("#id_proveedor").children("option:selected").val(), function (){
	});
});

$(document).off("change", "#file_otizaciones").on("change", "#file_otizaciones", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_cotizaciones")[0]);
	uploadCotizaciones(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
				unblockPage();
			}else{
				unblockPage();
				toastr.success(resp.desc, user_name);
				$("#file_otizaciones").val("");
				//setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});
function uploadCotizaciones(formData) {
	return $.ajax({
		url: site_url+"Lunes/sube_pedido/",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

$(document).off("click", "#endPedidos").on("click", "#endPedidos", function(event) {
	var proveedor = $("#id_proveedor").children("option:selected").val()
	blockPage();
	endCotizacion(proveedor)
	.done(function (resp) {
		if (resp.type == 'error'){
			toastr.error(resp.desc, user_name);
			unblockPage();
		}else{
			setTimeout("location.reload()", 1300, toastr.success(resp.desc, user_name), "");
		}
	});
});

function endCotizacion(proveedor) {
	return $.ajax({
		url: site_url+"Lunes/endPedidos/"+proveedor,
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON"
	});
}

$(document).off("change", "#file_otizaciones2").on("change", "#file_otizaciones2", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_cotizaciones2")[0]);
	var tienda = $("#id_sucursal").children("option:selected").val();
	var proveedor = $("#id_proveedor").children("option:selected").val();
	$(".lista-body").html("");
	if (tienda === 0 || tienda === "0"){
		unblockPage();
		toastr.warning("SELECCIONE UNA SUCURSAL", user_name);
		$(this).val("");
	}else{
		uploadCotizaciones2(fdata,tienda,proveedor)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
				$("#file_otizaciones2").val("");
				unblockPage();
			}else{
				unblockPage();
				toastr.success(resp.desc, user_name);
				$("#file_otizaciones2").val("");
				printFacturas(proveedor)
			}
		});
	}
});

function uploadCotizaciones2(formData,tienda,proveedor) {
	return $.ajax({
		url: site_url+"Lunes/sube_factura/"+tienda+"/"+proveedor,
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

function printFacturas(proveedor){
	$(".disponibles").html("")
	getSemFacts(proveedor).done(function(resp){
		$.each(resp,function(index,val){
			var day = "0";
			$(".disponibles").append('<div class="col-md-2 btn-dispon" id="btn'+index+'" data-id-cual="'+val.id_tienda+'" data-id-folio="'+val.folio+'" data-id-tienda="'+val.id_tienda+'" style="background:'+val.color+
				'80;border:2px solid '+val.color+'">'+val.folio+'<br>('+val.nombre+')</div>');
		})
	})
}

$(document).off("change", "#id_proveedor").on("change", "#id_proveedor", function(event){
	$(".lista-body").html("");
	$(".delFactura").css("display","none");
	var proveedor = $(this).children("option:selected").val();
	$(".disponibles").html("")
	if (proveedor === 0 || proveedor === "0") {
		$(".pedFinales").css("display","none");
		$(".factSem").css("display","none");
	} else {
		$(".pedFinales").css("display","block");
		$(".factSem").css("display","block");
		printFacturas(proveedor);
	}
});

function getSemFacts(prove){
    return $.ajax({
        url: site_url+"Lunes/getSemFacts/"+prove,
        type: "POST",
        dataType: "JSON",
    });
}

$(document).off("click", ".btn-dispon").on("click", ".btn-dispon", function(event){
	$(".btn-dispon").removeClass("btnSemFactAct");
	$(this).toggleClass("btnSemFactAct");
	var tienda = $(this).data("idTienda");
	var folio = $(this).data("idFolio");
	var cual = $(this).data("idCual");
	$(".lista-body").html("");
	mybutton = $(this).attr("id");
	showFactura(tienda,folio,cual);
})

$(document).off("click", ".delFactura").on("click", ".delFactura", function(event){
	var values = {"tienda":$("#"+mybutton).data("idTienda"),"folio":$("#"+mybutton).data("idFolio"),"proveedor":$("#id_proveedor").children("option:selected").val()}
	delFactura(JSON.stringify(values)).done(function(resp){
		$(".lista-body").html("");
		$(".delFactura").css("display","none");
		$(".disponibles").html("")
		printFacturas($("#id_proveedor").children("option:selected").val());
	})
})

function delFactura(values) {
	return $.ajax({
        url: site_url+"Lunes/delFactura",
        type: "POST",
        dataType: "JSON",
        data: {
            values : values
        },
    });
}

function showFactura(tienda,folio,cual){
	var sameProd = 0;var codi = "";
	var values = {'tienda': tienda,'folio': folio,'cual': cual};
	$(".delFactura").css("display","block")
	getFactClic(JSON.stringify(values)).done(function(resp){
		if (resp[0]) {
			var flagFactura = resp[1];
			var flagPedido = resp[1];
			$.each(resp[0],function(index,val){
				var subtotal = (val.precio * val.cantidad);
				var subtotal2 = (val.costo * val.cantidad)
				var color1 = "#FFF";
				val.pedido = val.pedido == null ? 0 : val.pedido == "" ? 0 : val.pedido;
				var cantidad = val.cantidad; var pedido = formatMoney(val.pedido,0);var ieps2 = 1;
				var bords = ""; var cumplio = false;var backPromo = "background:white;";var difs = 0;
				val.observaciones = val.observaciones == null ? "" : val.observaciones;
				var valcodigo = val.id_producto;
				if (valcodigo === "" || valcodigo === null){
					valcodigo = val.codigo;
				}
				if(resp[0].length > (index+1)){
					if (valcodigo === resp[0][index+1].codigo){
						cantidad =  parseFloat(val.cantidad);//parseFloat(resp[index+1].cantidad) +
						sameProd = 1;
						bords = "";
						flagFactura[valcodigo].cantidad -= val.cantidad;
						flagFactura[valcodigo].cantidad -= parseFloat(resp[0][index+1].cantidad);
						if(val.promo === "1" || val.promo === 1){
							if (((cantidad - (cantidad % val.cuantos1)) / val.cuantos1 * val.cuantos2) === parseFloat(resp[0][index+1].cantidad) && (flagFactura[val.codigo].cantidad >= 0)){
								backPromo = "background:#c6efce;color:#006100";
							}else{
								backPromo = "background:#ffc7ce;color:#9c0005";
							}
						}
					}else{
						sameProd = 0;
					}
				}
				if (parseFloat(cantidad) > parseFloat(val.pedido)) {
					color = "background:#ffc7ce;color:#9c0005";
				}else if(parseFloat(cantidad) < parseFloat(val.pedido)){
					color = "background:#ffeb9c;color:#9c6500";
				}else{
					color = "background:#c6efce;color:#006100";
				}
				if (codi === valcodigo) {
					color = "white";
					bords = "";
					if(val.promo === "1" || val.promo === 1){
						pedido = "";//(pedido - (pedido % val.cuantos1)) / val.cuantos1 * val.cuantos2;
						val.observaciones = "";
					}else{
						bords = bords+"background:#94ceffc9";
						backPromo = "background:#94ceffc9"; 
						color = "background:#94ceffc9";
					}
					difs = "";
				}else if (pedido === "0" || pedido === null || pedido === 0){
					difs = "";
					color = "background:#FFF";
					pedido = "";
					backPromo = "padding:0"; 
					val.observaciones = "<button class='btnAsocia' data-id-catalogo='"+val.id_catalogo+"'>Asociar producto a sistema</button>"
				}else{
					difs = formatMoney((pedido-cantidad),0)
				}
				if(val.promo === "3" || val.promo === 3){
					flagFactura[valcodigo].cantidad -= cantidad;
					if ( flagFactura[valcodigo].cantidad  >= 0) {
						backPromo = "background-image:linear-gradient(to bottom right, #3dca3d 50%, #94ceffc9 50%);";
					}else{
						backPromo = "background-image:linear-gradient(to bottom right, #f56565 50%, #94ceffc9 50%);";
					}
				}
				if(val.promo === "0" || val.promo === 0 || val.promo === null){
					flagFactura[valcodigo].cantidad -= cantidad;
				}

				if(val.ieps !== "0.00" && val.ieps !== null){
					ieps2 = val.ieps;
				}else{
					ieps2 = 1;
				}

				if (parseFloat(val.precio) < 1){
					difs = "";
					color = "background:#FFF";
					pedido = "";
					backPromo = "padding:0"; 
				}



				$(".lista-body").append('<div class="col-md-12 renglon" style="padding:0;'+bords+'"><div class="col-md-2 lista-body-desc">'+val.codigo+' => '+val.descripcion+'</div>'+
					'<div class="col-md-1" style="padding:0">'+
					'<div class="col-md-4 lista-body-piden">'+pedido+'</div>'+
					'<div class="col-md-4 lista-body-llegan">'+formatMoney(val.cantidad,0)+'</div>'+
					'<div class="col-md-4 lista-body-piden" style="'+color+'">'+difs+'</div></div>'+
					'<div class="col-md-1 lista-body-promo" style="'+backPromo+'">'+val.observaciones+'</div>'+
					'<div class="col-md-1 lista-body-factura" id="factura'+(index+1)+'">$ '+formatMoney(val.precio,2)+'</div>'+
					'<div class="col-md-1 lista-body-pedido" id="pedido'+(index+1)+'">$ '+formatMoney(val.costo,2)+'</div>'+
					'<div class="col-md-1 lista-body-sub" id="sub'+(index+1)+'">$ '+formatMoney(subtotal,2)+'</div>'+
					'<div class="col-md-1 lista-body-iva" id="iva'+(index+1)+'">$ '+formatMoney((subtotal * 0.16),2)+'</div>'+
					'<div class="col-md-1 lista-body-ieps" id="ieps'+(index+1)+'">$ '+formatMoney((subtotal * val.ieps),2)+'</div>'+
					'<div class="col-md-1 lista-body-total" id="total'+(index+1)+'">$ '+formatMoney((subtotal * 1.16),2)+'</div>'+
					'<div class="col-md-1 lista-body-totalgen" id="totalgen'+(index+1)+'">$ '+formatMoney(subtotal2,2)+'</div>'+
					'<div class="col-md-1 lista-body-diferencia" id="diferencia'+(index+1)+'">$ '+formatMoney(((subtotal*1.16*ieps2) - subtotal2),2)+'</div></div>')

				
				codi = valcodigo;
				bords = ""

			});
			console.log(flagFactura);
			calculafactura();
		}
	});
}

function getFactClic(values){
    return $.ajax({
        url: site_url+"Lunes/getFactClic",
        type: "POST",
        dataType: "JSON",
        data: {
            values : values
        },
    });
}

function calculafactura(){
	var cuantos = $('.lista-body-diferencia').length;
	var diferencia = 0;var factura = 0;var pedido = 0;var sub = 0;var iva = 0;var ieps = 0;var total = 0;var totalgen = 0;
	for(var i = 1; i <= cuantos; i++) {
		if ($("#diferencia"+i).html().includes("-")) {
			diferencia -= parseFloat( $("#diferencia"+i).html().replace(/[^0-9\.]+/g,"") )
		} else {
			diferencia += parseFloat( $("#diferencia"+i).html().replace(/[^0-9\.]+/g,"") )
		}
		sub += parseFloat( $("#sub"+i).html().replace(/[^0-9\.]+/g,"") );
		iva += parseFloat( $("#iva"+i).html().replace(/[^0-9\.]+/g,"") );
		ieps += parseFloat( $("#ieps"+i).html().replace(/[^0-9\.]+/g,"") );
		total += parseFloat( $("#total"+i).html().replace(/[^0-9\.]+/g,"") );
		totalgen += parseFloat( $("#totalgen"+i).html().replace(/[^0-9\.]+/g,"") );
	}
	$(".diferenciaTotal").html( "$ "+formatMoney(diferencia,2) )
	$(".subTotal").html( "$ "+formatMoney(sub,2) )
	$(".ivaTotal").html( "$ "+formatMoney(iva,2) )
	$(".iepsTotal").html( "$ "+formatMoney(ieps,2) )
	$(".totalTotal").html( "$ "+formatMoney(total,2) )
	$(".totalgenTotal").html( "$ "+formatMoney(totalgen,2) )
}

$(document).off("click", ".btnAsocia").on("click", ".btnAsocia", function(event){
	event.preventDefault();
	getModal("Lunes/catalogoAsocia/"+$(this).data("idCatalogo"), function (){ 
		datePicker();
		getChosen();
		$(".number").inputmask("currency", {radixPoint: ".", prefix: ""});
		$(".numeric").number(true, 0);
	});
});

$(document).off("click", ".new_catalogo").on("click", ".new_catalogo", function(event) {
	var proveedor = $("#id_pro option:selected").val();
	if($("#id_producto").val() !== ''){
		sendFormos("Lunes/asociamesta ", $("#form_cotizacion_new"), "");
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
			var proveedor = $("#id_proveedor").children("option:selected").val();

			switch(response.type){
				case "success":
					emptyModal();
					$("#mainModal").modal("hide");
					toastr.success(response.desc, user_name);
					var tienda = $("#"+mybutton).data("idTienda");
					var folio = $("#"+mybutton).data("idFolio");
					var cual = $("#"+mybutton).data("idCual");
					$(".lista-body").html("");
					$(".lista-foot").html("");
					showFactura(tienda,folio,cual);
				break;

				case "info":
					emptyModal();
					$("#mainModal").modal("hide");
					printFacturas(proveedor);
				break;

				case "warning":
					toastr.warning(response.desc, user_name);
					printFacturas(proveedor);
				break;

				default:
					toastr.error(response.desc, user_name);
					printFacturas(proveedor);
			}
			$("#notifications").html(response);
		})
		.fail(function(response) {
			// console.log("Error en la respuesta: ", response);
		});
}

$(document).off("click", ".excelFact").on("click", ".excelFact", function(event) {
	var win = window.open(site_url+'Lunes/excelFacturas/'+$("#id_proveedor").children("option:selected").val(), '_blank');
	if (win) {
	    win.focus();
	} else {
	    alert('Por favor active "ventanas emergentes"');
	}
});