$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});

	fillDataTable("table_codis", 50);
});

$(document).off("click", "#update_prods").on("click", "#update_prods", function(event){
	event.preventDefault();
	var id_prods = $(this).closest("tr").find("#update_prods").data("idProds");
	var tr = $(this).closest("tr");
	var codigo_factura = tr.find(".codigo_factura");var descripcion = tr.find(".descripcion");var clave = tr.find(".clave");var codigo = tr.find(".codigo");
	codigo_factura.removeAttr('disabled');
	descripcion.removeAttr('disabled');
	clave.removeAttr('disabled');
	codigo.removeAttr('disabled');
	tr.css('background-color', 'red');
	tr.find("#edit_prods").css("display","block");
	tr.find("#update_prods").css("display","none");
});

$(document).off("click", "#edit_prods").on("click", "#edit_prods", function(event){
	event.preventDefault();
	var id_prods = $(this).closest("tr").find("#edit_prods").data("idProds");
	var tr = $(this).closest("tr");
	var codigo_factura = tr.find(".codigo_factura");var descripcion = tr.find(".descripcion");var clave = tr.find(".clave");var codigo = tr.find(".codigo");
	codigo_factura.prop('disabled', 'true')
	descripcion.prop('disabled', 'true')
	clave.prop('disabled', 'true')
	codigo.prop('disabled', 'true')
	tr.css('background-color', 'white');
	tr.find("#edit_prods").css("display","none");
	tr.find("#update_prods").css("display","block");
	if (codigo.val() != "") {
		var values = {'codigo_factura': codigo_factura.val(),'descripcion': descripcion.val(),'clave':clave.val(),'codigo':codigo.val(),'id_prodcaja':id_prods};
		updateProds(JSON.stringify(values)).done(function(resp){
			
		});
	}
});

function updateProds(values) {
	return $.ajax({
        url: site_url+"/Reportes/edit_prods",
        type: "POST",
        dataType: "JSON",
        data: {
            values : values
        },
    });
}