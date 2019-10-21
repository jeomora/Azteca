$(document).off("change", "#file_productos").on("change", "#file_productos", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_cotizaciones")[0]);
	uploadCotizaciones(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
			}else{
				unblockPage();
				//setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});
function uploadCotizaciones(formData) {
	return $.ajax({
		url: site_url+"Verduras/upload_productos/",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}

$(document).off("keyup", "#precio").on("keyup", "#precio", function (){
	event.preventDefault();	
	var prods = $(this).data("idVerd");
	var precio = $(this).val();
	var tots = $(this).val()*$(".dude"+prods).val();
	$(".total"+prods).val(tots);
	var values = {"id_verdura":prods,"precio":precio};
	var ora = 0;
	changeprice(values).done(function(resp){
		var cuenta = $("input[id*='total'").length;
		for (var i = 1; i < cuenta; i++) {
			ora += parseFloat($(".total"+i).val());
		}
		setTimeout(function(){
			$("#stotal").val(formatMoney(ora,2))
		},500)
	});

	
})

function changeprice(values){
    return $.ajax({
        url: site_url+"Verduras/changeprice",
        type: "POST",
        dataType: "JSON",
        data: {
            values : values
        },
    });
}

$(document).off("keyup", "#preci2").on("keyup", "#preci2", function (){
	event.preventDefault();	
	var prods = $(this).data("idVerd");
	var precio = $(this).val();
	var tots = $(this).val()*$(".dud2"+prods).val();
	$(".tota2"+prods).val(tots);
	var values = {"id_verdura":prods,"precio":precio};
	changeprice(values).done(function(resp){
		
	});
	
})

function changeprice(values){
    return $.ajax({
        url: site_url+"Verduras/changeprice",
        type: "POST",
        dataType: "JSON",
        data: {
            values : values
        },
    });
}


$(document).off("change", "#file_precios").on("change", "#file_precios", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_precios")[0]);
	uploadPrecios(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
			}else{
				unblockPage();
				setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});
function uploadPrecios(formData) {
	return $.ajax({
		url: site_url+"Verduras/upload_precios/",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}