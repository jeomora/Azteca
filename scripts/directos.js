$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	setAdminTable();
});

function getAdminTable() {
	return $.ajax({
		url: site_url+"/Cotizaciones/getDirTable/4",
		type: "POST",
		dataType: "JSON"
	});
}

function getTableHead() {
	return $.ajax({
		url: site_url+"/Cotizaciones/getDirProv/4",
		type: "POST",
		dataType: "JSON"
	});
}

function setAdminTable(){
	event.preventDefault();
	var tablehead = "";
	var proveedor = [];
	var tablebody = "";
	
	var flag = 0;
	var flag2 = false;
	getTableHead().done(function (resp){
		if(resp.cotizados){
			$.each(resp.cotizados, function(index, value){
				flag += 1;
				proveedor.push({id:flag, name:value.nombre})
				$(".trdirectos").append("<th colspan='2'>"+value.nombre+"</th>");
			});
		}
	});
	var producto = [];
	var vals = [];
	getAdminTable()
		.done(function (resp) {
			if(resp.cotizados){
				$.each(resp.cotizados, function(indx, value){
					for (var i = 0; i < flag; i++) {
						$.each(value.articulos, function(index, val){
							if(i == 0 && tablebody == ""){
								tablebody += "<tr><td>"+val.familia+"</td><td>"+val.codigo+"</td><td>"+val.producto+"</td><td>"+val.precio_sistema+"</td><td>"+val.precio_four+"</td>";
							}
							if(i == proveedor.findIndex(x => x.name === val.proveedor) && flag2 == false){
								flag2 = true;
								vals = val;
							}
						});
						if(flag2 == true){
							tablebody += "<td>"+vals.precio_promocion+"</td><td>"+vals.observaciones+"</td>";
							flag2 = false;
							vals = [];
						}else{
							tablebody += "<td></td><td></td>";
							vals = [];
						}
					}
					tablebody += "</tr>";
					console.log(tablebody+"\n");
					$(".tableAdminv").append(tablebody);
					tablebody = "";
				});
			}
		});
}

$(document).off("click", "#update_cotizacion").on("click", "#update_cotizacion", function(event){
	event.preventDefault();
	var id_cotizacion = $(this).closest("tr").find("#update_cotizacion").data("idCotizacion");
	getModal("Cotizaciones/get_update/"+ id_cotizacion, function (){
		datePicker();
		$(".number").inputmask("currency", {radixPoint: ".", prefix: ""});
	});
});
$(document).off("click", "#delete_cotizacion").on("click", "#delete_cotizacion", function(event){
	event.preventDefault();
	var id_cotizacion = $(this).closest("tr").find("#delete_cotizacion").data("idCotizacion");
	getModal("Cotizaciones/get_delete/"+ id_cotizacion, function (){ });
});

$(document).off("click", ".update_cotizacion").on("click", ".update_cotizacion", function(event) {
	event.preventDefault();
	sendForm("Cotizaciones/update", $("#form_cotizacion_edit"), "");
});
$(document).off("click", ".delete_cotizacion").on("click", ".delete_cotizacion", function(event) {
	event.preventDefault();
	sendForm("Cotizaciones/delete", $("#form_cotizacion_delete"), "");
});

$(document).off("click", "#new_pedido").on("click", "#new_pedido", function(event){
	event.preventDefault();
	var id_cotizacion = $(this).closest("tr").find("#new_pedido").data("idCotizacion");
	getModal("Cotizaciones/set_pedido/"+ id_cotizacion, function (){ });
});

$(document).off("click", ".new_pedido").on("click", ".new_pedido", function(event) {
	event.preventDefault();
	sendForm("Cotizaciones/hacer_pedido", $("#form_pedido_new"), "");
});


//Editar cotizaciones 
$(document).off("change", ".id_cotz").on("change", ".id_cotz", function() {
	var tr = $(this).closest("tr");
	$(".numeric").inputmask("currency", {radixPoint: ".", prefix: ""});
	if($(this).is(":checked")) {
		tr.find(".id_cotz ").removeAttr('readonly');
		tr.find(".precio").removeAttr('readonly');
		tr.find(".num_one").removeAttr('readonly');
		tr.find(".num_two").removeAttr('readonly');
		tr.find(".descuento").removeAttr('readonly');
		tr.find(".observaciones").removeAttr('readonly');
		tr.find(".id_cotz ").attr('name', 'id_cotz[]');
		tr.find(".precio").attr('name', 'precio[]');
		tr.find(".precio_promocion").attr('name', 'precio_promocion[]');
		tr.find(".num_one").attr('name', 'num_one[]');
		tr.find(".num_two").attr('name', 'num_two[]');
		tr.find(".descuento").attr('name', 'descuento[]');
		tr.find(".observaciones").attr('name', 'observaciones[]');
	}else{
		$(this).removeAttr("checked");
		tr.find(".precio").attr('readonly', 'readonly');
		tr.find(".num_one").attr('readonly', 'readonly');
		tr.find(".num_two").attr('readonly', 'readonly');
		tr.find(".descuento").attr('readonly', 'readonly');
		tr.find(".observaciones").attr('readonly', 'readonly');

		tr.find(".id_cotz ").removeAttr('name');
		tr.find(".precio").removeAttr('name');
		tr.find(".precio_promocion").removeAttr('name');
		tr.find(".num_one").removeAttr('name');
		tr.find(".num_two").removeAttr('name');
		tr.find(".descuento").removeAttr('name');
		tr.find(".observaciones").removeAttr('name');
	}
});
$(document).off("keyup", ".precio").on("keyup", ".precio", function() {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var descuento = tr.find(".descuento").val().replace(/[^0-9\.]+/g,"");
	var num_one = tr.find('.num_one').val().replace(/[^0-9\.]+/g,"");
	var num_two = tr.find('.num_two').val().replace(/[^0-9\.]+/g,"");
	if(num_two > 0 && num_one > 0){
		var total = (precio * num_two) / (parseFloat(num_one) + parseFloat(num_two));
		tr.find(".precio_promocion").val(total);
	}else if(descuento > 0){
		tr.find(".precio_promocion").val(precio - (precio * (descuento / 100)));
	}else{
		tr.find(".precio_promocion").val(precio);
	}
});
$(document).off("keyup", ".descuento").on("keyup", ".descuento", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var descuento = tr.find(".descuento").val().replace(/[^0-9\.]+/g,"");
	if($(this).val().replace(/[^0-9\.]+/g,"") > 0){
		tr.find(".precio_promocion").val(precio - (precio * (descuento / 100)));
	}
});
$(document).off("keyup", ".num_one").on("keyup", ".num_one", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var num_one = tr.find('.num_one').val().replace(/[^0-9\.]+/g,"");
	var num_two = tr.find('.num_two').val().replace(/[^0-9\.]+/g,"");
	if(num_two > 0 && num_one > 0){
		var total = (precio * num_two) / (parseFloat(num_one) + parseFloat(num_two));
		tr.find(".precio_promocion").val(total);
	}else{
		tr.find(".precio_promocion").val(precio);
	}
});
$(document).off("keyup", ".num_two").on("keyup", ".num_two", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var num_one = tr.find('.num_one').val().replace(/[^0-9\.]+/g,"");
	var num_two = tr.find('.num_two').val().replace(/[^0-9\.]+/g,"");
	if(num_two > 0 && num_one > 0){
		var total = (precio * num_two) / (parseFloat(num_one) + parseFloat(num_two));
		tr.find(".precio_promocion").val(total);
	}else{
		tr.find(".precio_promocion").val(precio);
	}
});

$(document).off("change", ".id_producto").on("change", ".id_producto", function() {
	var tr = $(this).closest("tr");
	$(".numeric").inputmask("currency", {radixPoint: ".", prefix: ""});
	if($(this).is(":checked")) {
		tr.find(".cantidad").removeAttr('readonly');
		tr.find(".id_producto ").attr('name', 'id_producto[]');
		tr.find(".precio ").attr('name', 'precio[]');
		tr.find(".cantidad ").attr('name', 'cantidad[]');
		tr.find(".importe ").attr('name', 'importe[]');
	}else{
		$(this).removeAttr("checked");
		tr.find(".cantidad").attr('readonly', 'readonly');
		tr.find(".cantidad").removeAttr('name').val('');
		tr.find(".importe").removeAttr('name').val('');
		tr.find(".id_producto ").removeAttr('name');
	}
});

$(document).off("keyup", ".cantidad").on("keyup", ".cantidad", function () {
	var tr = $(this).closest("tr");
	var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
	var cantidad = tr.find(".cantidad").val().replace(/[^0-9\.]+/g,"");

	if($(this).val().replace(/[^0-9\.]+/g,"") > 0){
		tr.find(".importe").val(precio * cantidad);
		$("#total").val((calculaTotales()));
	}
});

function calculaTotales() {
	var total =0;
	$.each($(".importe"), function(index, val) {
		if (Number($(this).val().replace(/[^0-9\.]+/g,"")) !== '') {
			total += Number($.trim($(val).val().replace(/[^0-9\.]+/g,"")));
		}
	});
	return total;
}



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
				$(".cot-prov").append('<tr><td>'+value.producto+'</td><td>'+value.codigo+'</td><td>'+value.precio+'</td><td>'+value.precio_promocion
					+'</td><td>'+value.observaciones+'</td></tr>')
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

