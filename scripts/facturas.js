$(window).on('beforeunload', function(){
	var c=confirm();
	if( $(".checkhim").css('display') == 'block'){
		if(c){
	  		return true;
		}else
			return false;
	}
	
});
var obj = [];
var folis = "";
var tiendis =  "";

var tiendas = {87:"cedis",57:"abarrotes",90:"villas",58:"tienda",59:"ultra",60:"trincheras",61:"mercado",61:"tenencia",63:"tijeras"}
$(document).off("change", "#proveedor").on("change", "#proveedor", function (){
	event.preventDefault();
	var provs = $("#proveedor option:selected");
	var values = {"proveedor":provs.val()};
	var pedido = "";
	if (provs !== "0" || provs !== 0) {
		getPedidos(JSON.stringify(values)).done(function(resp){
			if (resp) {
				$.each(resp,function (indx,val) {
					val.promocion = val.promocion == null ? "" : val.promocion; 
					pedido += "<div class='divcont divcont"+val.codigo+" d"+indx+"' data-id-codigo='"+val.codigo+"'><div class='divcontaindiv' style='width:5%'>"+(indx+1)+
								"</div><div class='divcontaindiv' style='width:15%'>"+val.codigo+"</div><div class='divcontaindiv' style='width:50%'>"+val.nombre+
								"</div><div class='divcontaindiv' style='width:10%'>"+formatMoney(val.totalp,0)+"</div><div class='divcontaindiv' style='width:20%'>"+
								val.promocion+"</div></div>"
				})
				$(".bbtotal").html("NÚMERO DE PEDIDOS "+resp.length);
				$(".tablepeds-body").html(pedido);
			}else{
				toastr.error("Seleccione otro proveedor", "Proveedor sin pedidos");
				$(".bbtotal").html("PROVEEDOR SIN PEDIDOS ");
			}
			
		})
	}
})


function getPedidos(values){
    return $.ajax({
        url: site_url+"Facturas/getPedidos",
        type: "POST",
        dataType: "JSON",
        data: {
            values : values,
        },
    });
}


$(document).off("change", "#file_factura").on("change", "#file_factura", function(event) {
	event.preventDefault();
	if ($(this).val() !== ""){
		blockPage();
		tiendis = $(this).data("idTienda");
		var tienda = $(this).data("idTienda");
		var fdata = new FormData($("#upload_facturas"+tienda)[0]);
		uploadFactura(fdata,$("#proveedor option:selected").val(),tienda,tiendas[tienda])
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name)
				
			}else{
				unblockPage();
				$(".checkhim").css("display","block");
				toastr.success(resp.desc, user_name)
				folis = resp[3];
				$(".h1folio").html("RESULTADOS DE LA FACTURA CON FOLIO "+resp[3]);
				var bod = "";var bods = "";
				$.each(resp[0],function(indx,val) {
					console.log(val);
					val.codigo = val.codigo == null ? "" : val.codigo;
					val.nombre = val.nombre == null ? "" : val.nombre;
					val.costo  = val.costo == null ? "" : val.costo;
					val.total = val.total == null ? "" : val.total;
					val.promocion = val.promocion == null ? "" : val.promocion;
					if (val.costo == "") {
						bod+= '<div class="col-md-12 col-lg-12 cuerpodiv" id="cuerpodiv'+indx+'" style="padding:0;display:inline-flex;"><div class="devolucion"><i '+
						'class="fa fa-retweet" aria-hidden="true" id="idev"></i><input type="text" name="difis" id="difis" value="" /></div><div class="col-md-2 col-lg-2 body2">'+val.factu+
						'</div><div class="col-md-3 col-lg-3 body3">'+val.descripcion+'</div><div class="col-md-2 col-lg-2 body2" style="font-size:20px;font-weight:bold;" id="precio">$ '+
						formatMoney(val.precio,2)+'</div><div class="col-md-1 col-lg-1 body1" style="font-size:20px;font-weight:bold;">'+formatMoney(val.cantidad,1)+
						'</div><div class="col-md-4 col-lg-4 body4" ondrop="drop(event)" ondragover="allowDrop(event)" id="pedidodiv">SOLTAR RECUADRO AQUÍ</div></div>';
					} else {
						var col1 = "#FFF";var col2 = "#FFF";
						col1 = cantidades(parseFloat(val.cantidad),parseFloat(val.total));
						col2 = costos(parseFloat(val.precio),parseFloat(val.costo));
						
						bod+= '<div class="col-md-12 col-lg-12 cuerpodiv" id="cuerpodiv'+indx+'" style="padding:0;display:inline-flex;"><div class="devolucion"><i '+
						'class="fa fa-retweet" aria-hidden="true" id="idev"></i><input type="text" name="difis" id="difis" value=""></div><div class="col-md-2 col-lg-2 body2">'+
						val.factu+'</div><div class="'+
						'col-md-3 col-lg-3 body3">'+val.descripcion+'</div><div class="col-md-2 col-lg-2 body2" style="font-size:20px;font-weight:bold;background:'+col2+
						';" id="precio">$ '+formatMoney(val.precio)+'</div><div class="col-md-1 col-lg-1 body1" style="font-size:20px;font-weight:bold;background:'+col1+
						';">'+formatMoney(val.cantidad,1)+'</div><div class="col-md-4 col-lg-4 body4" ondrop="drop(event)" ondragover="allowDrop(event)"'+
						' id="pedidodiv"><div class="col-lg-12 col-md-12 pedsist" ondragstart="drag(event)" id="'+val.codigo+'" style="padding:5px"><h4>'+val.codigo+' - '+val.nombre+
						'</h4><div class="col-md-6 col-lg-6"><input class="costod" type="text" name="costo'+val.codigo+'" value="'+val.costo+'" id="costo'+val.codigo+'" style="width:100%"></div><div class='+
						'"col-md-6 col-lg-6 cantu">Cantidad: '+formatMoney(val.total,1)+'</div><div class="col-md-12 col-lg-12">Promoción: '+val.promocion+'</div><div class="cerra"'+
						' id="cerra'+val.codigo+'" style="display:block"><i class="fa fa-times" aria-hidden="true"></i></div></div></div></div>';
					}
				})
				$("#cuerpo").html(bod);
				$.each(resp[1],function(index,vals) {
					vals.promocion = vals.promocion == null ? "" : vals.promocion;
					bods+= '<div class="col-lg-12 col-md-12 pedsist cuerpo2div'+index+'" draggable="true" ondragstart="drag(event)" id="'+vals.codigo+'" style="padding:5px"><h4>'+vals.codigo+
							' - '+vals.nombre+'</h4><div class="col-md-6 col-lg-6"><input class="costod" type="text" name="costo'+vals.codigo+'" value="'+vals.costo+'" id="costo'+vals.codigo+
							'" style="width:100%"></div><div class="col-md-6 col-lg-6 cantu">Cantidad: '+formatMoney(vals.total,1)+'</div><div class="col-md-12 col-lg-12">Promoción: '
							+vals.promocion+'</div><div class="cerra" id="cerra'+vals.codigo+'"><i class="fa fa-times" aria-hidden="true"></i></div></div>'
				})
				$("#cuerpo2").html(bods);
				//setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
	}
});

function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
	ev.preventDefault();
	ev.target.innerHTML = '';
	var data = ev.dataTransfer.getData("text");
	var onj = document.getElementById(data);
	ev.target.appendChild(onj);
	onj.removeAttribute("draggable");
	ev.target.removeAttribute("ondrop");
	$("#"+data+" .cerra").css("display","block");
	var pedsist = $("#"+data+" .cerra").closest(".pedsist");
	var precio = pedsist.closest(".body4").closest(".col-md-12").find("#precio");
	var prec = precio.html().substring(2, precio.html().lenght);
	precio.css("background",costos(parseFloat(prec.replace(",","")),parseFloat($("#costo"+data).val())));

	precio.html(precio.html()+" <br><div style='color:white;background:#000;border-radius:30px'>DIF: "+formatMoney((parseFloat(prec.replace(",",""))-parseFloat($("#costo"+data).val())),2)+"</div>")
	var cantidad = pedsist.closest(".body4").closest(".col-md-12").find(".body1");
	var cantu = $("#costo"+data).closest(".pedsist").find(".cantu");
	cantidad.css("background",cantidades(parseFloat(cantidad.html()),parseFloat( cantu.html().substring(10,cantu.html().length) )));

	cantidad.html(cantidad.html()+" <br><div style='color:white;background:#000;border-radius:30px'>DIF: "+(parseFloat(cantidad.html()) - parseFloat( cantu.html().substring(10,cantu.html().length)))+"</div>")
	if ($("#costo"+data).val() === "") {
		precio.html("$ "+prec+" <br><div style='color:white;background:#000;border-radius:30px'>DIF: "+formatMoney(parseFloat(prec.replace(",","")),2)+"</div>")
		precio.css("background",costos(parseFloat(prec.replace(",","")),0));
	}
	console.log($("#cuerpo").length)
}

$(document).off("keyup", ".costod").on("keyup", ".costod", function (){
	event.preventDefault();
	var precio = $(this).closest(".pedsist").closest(".body4").closest(".col-md-12").find("#precio");
	precio.css({"background":"white","color":"black"});
	precio.css("background",costos(parseFloat(precio.html().substring(2, precio.html().length)),parseFloat($(this).val())));

	var prec = precio.html().substring(2, precio.html().lenght);
	precio.html("$ "+parseFloat(prec.replace(",",""))+" <br><div style='color:white;background:#000;border-radius:30px'>DIF: "+formatMoney((parseFloat(prec.replace(",",""))-parseFloat($(this).val())),2)+"</div>")
	if ($(this).val() === "") {
		precio.css("background","#ff000080");
		precio.html("$ "+parseFloat(prec.replace(",",""))+" <br><div style='color:white;background:#000;border-radius:30px'>DIF: "+formatMoney((parseFloat(prec.replace(",",""))),2)+"</div>")
	}
})



function uploadFactura(formData,cual,tienda,tend) {
	return $.ajax({
		url: site_url+"Facturas/uploadFacturas/"+cual+"/"+tienda+"/"+tend,
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

$(document).off("click", ".tienda").on("click", ".tienda", function (){
	if ($(this).is(":checked")) {
		$("."+$(this).attr('id')).attr("hidden",false);
		console.log($(this).attr('id').substring(6,$(this).attr('id').length))
		var numItems = $('.divcont').length
		console.log($(this).data("idCodigo"));
	}else{
		$("."+$(this).attr('id')).attr("hidden",true);
		$("#allcheck").prop('checked', false);
	}
})

$(document).off("click", ".cerra").on("click", ".cerra", function (){
	var pedsist = $(this).closest(".pedsist");
	var uno = pedsist.closest(".body4").closest(".col-md-12").find("#precio");
	var dos = pedsist.closest(".body4").closest(".col-md-12").find(".body1");
	uno.css({"background":"transparent","color":"black"})
	dos.css({"background":"transparent","color":"black"})
	pedsist.attr("draggable","true");
	pedsist.closest(".body4").attr("ondrop","drop(event)");
	pedsist.closest(".body4").html("SOLTAR RECUADRO AQUÍ");
	uno.html(uno.html().substr(0, uno.html().indexOf('<')));
	dos.html(dos.html().substr(0, dos.html().indexOf('<'))); 
	$("#cuerpo2").prepend(pedsist);
	$(this).css("display","none");
	
	pedsist.closest(".col-md-12").find(".body1").css({"background":"white !important","color":"black !important"});

})

$(document).off("click", ".divcont").on("click", ".divcont", function (){
	console.log(this)
})

function cantidades(uno,dos){
	if(uno > dos){
		return "#ff000080";
	}else{
		if(uno < dos){
			return "rgb(204,153,255)";
		}else{
			if(uno === dos){
				return "rgb(255,242,204)";
			}
		}
	}
	return "white"; 
}

function costos(uno,dos) {
	if(uno > dos){
		return "#ff000080";
	}else if(uno < dos){
		return "#00800080";
	}
}

$(document).off("click", ".btnnel").on("click", ".btnnel", function (){
	event.preventDefault();
	location.reload()	
})

$(document).off("click", ".btnsalvar").on("click", ".btnsalvar", function (){
	event.preventDefault();
	var devs = 0;var costu = null;var produ = null;var body4 = "";var devos = 0;
	obj = [];
	for (var i = 0; i < $(".cuerpodiv").length; i++) {
		if ($("#cuerpodiv"+i).css('background') === "rgba(0, 0, 0, 0) none repeat scroll 0% 0% / auto padding-box border-box" || $("#cuerpodiv"+i).css('background') === "rgb(255, 255, 255) none repeat scroll 0% 0% / auto padding-box border-box") {
			devs = 0;
			devos = 0;
		}else{
			devs = 1;
			devos = $("#cuerpodiv"+i).find(".devolucion").find("#difis").val();
		}
		
		body4 = $("#cuerpodiv"+i).find(".body4");
		if (body4.html() === "SOLTAR RECUADRO AQUÍ") {
			costo = null;
			produ = null;
		}else{
			produ = body4.find(".pedsist").attr('id');
			costu = body4.find(".pedsist").find(".costod").val();
		}	
		obj.push({
			"folio":folis,
			"factura":$("#cuerpodiv"+i).find(".body2").html(),
			"descripcion":$("#cuerpodiv"+i).find(".body3").html(),
			"producto":produ,
			"id_tienda":tiendis,
			"id_proveedor":$("#proveedor option:selected").val(),
			"costo":costu,
			"devolucion":devs,
			"devueltos":devos
		})
	}

	guardaComparacion(JSON.stringify(obj)).done(function(resp){

	})
	
});

function guardaComparacion(values){
    return $.ajax({
        url: site_url+"/Facturas/guardaComparacion",
        type: "POST",
        dataType: "JSON",
        data: {
            values : values
        },
    });
}



$(document).off("click", "#idev").on("click", "#idev", function (){
	event.preventDefault();
	var uno = $(this).closest(".devolucion").closest(".cuerpodiv").find(".body1");
	if ($(this).closest(".devolucion").closest(".cuerpodiv").find(".body4").html() == "SOLTAR RECUADRO AQUÍ"){
		uno = uno.html();
	}else{
		uno = uno.html().substr(0, uno.html().indexOf('<'));
	}
	
	if($(this).closest(".cuerpodiv").css('background') === "rgba(0, 0, 0, 0) none repeat scroll 0% 0% / auto padding-box border-box" || $(this).closest(".cuerpodiv").css('background') === "rgb(255, 255, 255) none repeat scroll 0% 0% / auto padding-box border-box"){
		$(this).closest(".cuerpodiv").css("background","#efff00");
		$(this).closest(".devolucion").find("#difis").css("display","block");
		$(this).closest(".devolucion").find("#difis").val(formatMoney(uno,0));
	}else{
		$(this).closest(".cuerpodiv").css("background","#FFF");
		$(this).closest(".devolucion").find("#difis").css("display","none");
		$(this).closest(".devolucion").find("#difis").val("");
	}
})

/*$(document).off("click", "#allcheck").on("click", "#allcheck", function (){
	if ($(this).is(":checked")) {
		$(".tienda").prop('checked', true);
		$(".ttc").attr("hidden",false);
	}else{
		$(".tienda").prop('checked', false);
		$(".ttc").attr("hidden",true);
	}
})*/