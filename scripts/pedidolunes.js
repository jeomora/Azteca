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
				//setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
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

$(document).off("change", "#id_proveedor").on("change", "#id_proveedor", function(event){
	var proveedor = $(this).children("option:selected").val();
	$(".disponibles").html("")
	if (proveedor === 0 || proveedor === "0") {
		$(".pedFinales").css("display","none");
		$(".factSem").css("display","none");
	} else {
		$(".pedFinales").css("display","block");
		$(".factSem").css("display","block");
		getSemFacts(proveedor).done(function(resp){
			$.each(resp,function(index,val){
				var day = "0";
				$(".disponibles").append('<div class="col-md-2 btn-dispon" data-id-cual="'+val.id_tienda+'" data-id-folio="'+val.folio+'" data-id-tienda="'+val.id_tienda+'" style="background:'+val.color+
					'80;border:2px solid '+val.color+'">'+val.folio+'<br>('+val.nombre+')</div>');
			})
		})

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
	var sameProd = 0;var codi = "";
	getFactClic(tienda,folio,cual).done(function(resp){
		$.each(resp,function(index,val){
			var subtotal = (val.precio * val.cantidad);
			var subtotal2 = (val.costo * val.cantidad)
			var color1 = "#FFF";
			var cantidad = val.cantidad;
			var bords = "";
			if (resp.length > (index+1)) {
				if (val.codigo === resp[index+1].codigo) {
					cantidad = parseFloat(resp[index+1].cantidad) + parseFloat(val.cantidad);
					sameProd = 1
					bords = "border-top:5px solid #00b8ff;border-left:5px solid #00b8ff;border-right:5px solid #00b8ff;"
				}else{
					sameProd = 0;
				}
			}
			if (cantidad > val.pedido) {
					color = "#f59191";
				}else if(cantidad < val.pedido){
					color = "#f7f782";
				}else{
					color = "#a7fda7";
				}
			if (codi === val.codigo) {
				color = "white";
				bords = "border-bottom:5px solid #00b8ff;border-left:5px solid #00b8ff;border-right:5px solid #00b8ff;";
			}

			$(".lista-body").append('<div class="col-md-12 renglon" style="padding:0;border-left:5px solid #FFF;border-right:5px solid #FFF;'+bords+'"><div class="col-md-2 lista-body-desc">'+val.codigo+' => '+val.descripcion+'</div>'+
				'<div class="col-md-1" style="padding:0"><div class="col-md-6 lista-body-piden">'+formatMoney(val.pedido,0)+'</div>'+
				'<div class="col-md-6 lista-body-llegan" style="background:'+color+'">'+formatMoney(val.cantidad,0)+'</div></div>'+
				'<div class="col-md-1 lista-body-promo">SIN PROMOCIÓN</div>'+
				'<div class="col-md-1 lista-body-factura">$ '+formatMoney(val.precio,2)+'</div>'+
				'<div class="col-md-1 lista-body-pedido">$'+formatMoney(val.costo,2)+'</div>'+
				'<div class="col-md-1 lista-body-sub">$ '+formatMoney(subtotal,2)+'</div>'+
				'<div class="col-md-1 lista-body-iva">$ '+formatMoney((subtotal * 0.16),2)+'</div>'+
				'<div class="col-md-1 lista-body-ieps"></div>'+
				'<div class="col-md-1 lista-body-total">$ '+formatMoney((subtotal * 1.16),2)+'</div>'+
				'<div class="col-md-1 lista-body-totalgen">$ '+formatMoney(subtotal2,2)+'</div>'+
				'<div class="col-md-1 lista-body-diferencia">$ '+formatMoney((subtotal - subtotal2),2)+'</div></div>')

			if (codi === val.codigo) {
				$(".lista-body").append("<div class='col-md-12' style='border:1px solid'></div>")
			}
			codi = val.codigo;
			bords = ""

		});
	});

})

function getFactClic(tienda,folio,cual){
    return $.ajax({
        url: site_url+"Lunes/getFactClic/"+tienda+"/"+folio+"/"+cual,
        type: "POST",
        dataType: "JSON",
    });
}